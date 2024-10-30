<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Barangay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLocationsCommand extends Command
{
    protected $signature = 'import:locations {file : Path to the CSV file}';
    protected $description = 'Import cities and barangays from CSV file';

    private $currentCity = null;
    private $currentCityType = null;
    private $cities = [];
    private $barangays = [];

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info('Starting import...');

        DB::beginTransaction();

        try {
            // Set proper encoding
            DB::statement('SET NAMES utf8mb4');
            DB::statement('SET CHARACTER SET utf8mb4');
            DB::statement('SET SESSION collation_connection = utf8mb4_unicode_ci');

            // Read CSV file with UTF-8 encoding
            $handle = fopen($file, 'r');
            stream_filter_append($handle, 'convert.iconv.windows-1252/utf-8');

            if ($handle !== false) {
                $rowCount = 0;

                while (($row = fgetcsv($handle)) !== false) {
                    $rowCount++;
                    if (!$this->validateRow($row)) {
                        $this->warn("Skipping invalid row {$rowCount}: " . implode(',', $row));
                        continue;
                    }
                    $this->processRow($row);
                }

                fclose($handle);

                // Bulk insert cities
                if (!empty($this->cities)) {
                    foreach (array_chunk($this->cities, 50) as $chunk) {
                        City::insert($chunk);
                    }
                    $this->info('Cities/Municipalities imported: ' . count($this->cities));
                }

                // Get city IDs for relationships
                $cityMap = City::pluck('id', 'name')->toArray();

                // Add city_id to barangays
                foreach ($this->barangays as &$barangay) {
                    if (isset($cityMap[$barangay['city_name']])) {
                        $barangay['city_id'] = $cityMap[$barangay['city_name']];
                        unset($barangay['city_name']);
                    } else {
                        $this->warn("City not found for barangay: {$barangay['name']}");
                    }
                }

                // Remove any barangays without a valid city_id
                $this->barangays = array_filter($this->barangays, function ($barangay) {
                    return isset($barangay['city_id']);
                });

                // Insert barangays in smaller chunks
                foreach (array_chunk($this->barangays, 50) as $chunk) {
                    foreach ($chunk as &$barangay) {
                        $barangay['name'] = mb_convert_encoding($barangay['name'], 'UTF-8', 'auto');
                    }
                    Barangay::insert($chunk);
                }

                $this->info('Barangays imported: ' . count($this->barangays));

                DB::commit();
                $this->info('Import completed successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    private function validateRow($row): bool
    {
        if (count($row) < 2) {
            return false;
        }

        $type = trim($row[1]);

        if (!in_array($type, ['City', 'Mun', 'Bgy'])) {
            return false;
        }

        if (empty(trim($row[0]))) {
            return false;
        }

        return true;
    }

    private function processRow($row)
    {
        $name = trim(mb_convert_encoding($row[0], 'UTF-8', 'auto'));
        $type = trim($row[1]);

        $timestamp = now()->toDateTimeString();

        if ($type === 'City' || $type === 'Mun') {
            $this->currentCity = $name;
            $this->currentCityType = $type;
            $this->cities[] = [
                'name' => $name,
                'type' => $type,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        } elseif ($type === 'Bgy' && $this->currentCity) {
            $this->barangays[] = [
                'city_name' => $this->currentCity,
                'name' => $name,
                'contact_person' => 'TBD',
                'phone' => 'TBD',
                'email' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }
    }
}

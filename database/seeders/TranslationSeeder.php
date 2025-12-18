<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalRecords = 100000;
        $chunkSize = 2000; // Optimal for Postgres memory/packet size

        $this->command->getOutput()->progressStart($totalRecords);

        // Use a LazyCollection to generate data without hitting memory limits
        LazyCollection::range(1, $totalRecords)->chunk($chunkSize)->each(function ($chunk) {
            $data = [];

            foreach ($chunk as $i) {
                // Manually calling factory definition for speed
                $row = Translation::factory()->make()->toArray();

                // Format PHP array to Postgres text[] string: {"tag1","tag2"}
                $tags = $row['context_tag'];
                $row['context_tag'] = '{' . implode(',', array_map(fn($t) => "\"$t\"", $tags)) . '}';

                $data[] = $row;
            }

            // Bulk Insert
            DB::table('translations')->insert($data);

            $this->command->getOutput()->progressAdvance(count($data));
        });

        $this->command->getOutput()->progressFinish();
    }
}

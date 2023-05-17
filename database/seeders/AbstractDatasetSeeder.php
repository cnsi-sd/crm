<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

abstract class AbstractDatasetSeeder extends Seeder
{
    const INSERT_CHUNK_SIZE = 1000;

    protected abstract function getDatasetName(): string;

    protected abstract function getModelClass(): string;

    public function run(): void
    {
        $this->preProcess();

        $dataset_name = $this->getDatasetName();

        $filepath = database_path(sprintf('seeders/datasets/%s.csv', $dataset_name));
        if (!file_exists($filepath) || !is_readable($filepath))
            throw new Exception(sprintf("Unable to read file '%s'", $filepath));

        $rows = array_map('str_getcsv', file($filepath));

        // replace "NULL" to null
        array_walk($rows, function (&$row) {
            array_walk($row, function (&$field) {
               $field = $field === "NULL" ? NULL : $field;
            });
        });

        $header = array_shift($rows);

        $objects = [];
        foreach ($rows as $row)
            $objects[] = array_combine($header, $row);

        $chunks = array_chunk($objects, self::INSERT_CHUNK_SIZE);
        foreach ($chunks as $objects) {
            $class = $this->getModelClass();
            $class::upsert($objects, $header);
        }

        $this->postProcess();
    }

    protected function preProcess()
    {

    }

    protected function postProcess()
    {

    }
}

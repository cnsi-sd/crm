<?php

namespace Database\Seeders\dev;

use App\Models\Tags\Tag;
use Database\Seeders\AbstractDatasetSeeder;

class TagDatasetSeeder extends AbstractDatasetSeeder
{
    protected function getDatasetName(): string
    {
        return 'tags';
    }

    protected function getModelClass(): string
    {
        return Tag::class;
    }
}

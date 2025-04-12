<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cms;
use App\Enums\CmsType;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CmsType::cases() as $type) {
            Cms::updateOrInsert(
                [
                    'type' => $type->value
                ],
                [
                    'title' => $type->title()
                ]
            );
        }
    }
}

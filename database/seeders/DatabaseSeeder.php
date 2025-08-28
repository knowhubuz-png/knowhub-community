<?php

// file: database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Level;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Dasturlash','AI','Cybersecurity','Open Source','DevOps'
        ] as $name) {
            Category::firstOrCreate(['name'=>$name], ['slug'=>\Str::slug($name)]);
        }

        foreach (['Laravel','Livewire','MySQL','PostgreSQL','Redis','Next.js','Tailwind','Python','PHP','JavaScript'] as $t) {
            Tag::firstOrCreate(['name'=>$t], ['slug'=>\Str::slug($t)]);
        }

        $levels = [
            ['name'=>'Novice','min_xp'=>0,'icon'=>'spark'],
            ['name'=>'Apprentice','min_xp'=>200,'icon'=>'feather'],
            ['name'=>'Wizard','min_xp'=>1000,'icon'=>'wand'],
            ['name'=>'Mentor','min_xp'=>2500,'icon'=>'shield'],
        ];
        foreach ($levels as $lv) { Level::firstOrCreate(['name'=>$lv['name']], $lv); }
    }
}


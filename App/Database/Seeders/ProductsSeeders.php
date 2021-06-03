<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeders
extends Seeder
{
    protected $table = 'products';

    public function run()
    {
        DB::table($this->table)
            ->insert([
                'name' => 'Litrão Itaipava',
                'price' => 10.00
            ]);
    }
}
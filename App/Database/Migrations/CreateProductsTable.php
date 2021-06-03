<?php

namespace App\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable
extends Migration
{
    protected $table = 'products';

    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->decimal('price', 5, 2)->default(0.00);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
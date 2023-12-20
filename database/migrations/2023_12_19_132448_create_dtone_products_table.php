<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtoneProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['products_gb', 'products_us', 'products_it'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('network_code');
                $table->string('network')->nullable();
                $table->string('country_iso')->nullable();
                $table->string('sku_code');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('value', 19, 2);
                $table->string('value_currency');
                $table->decimal('price', 19, 2);
                $table->string('price_currency');
                $table->decimal('supplier_charge', 19, 2);
                $table->string('supplier_charge_currency');
                $table->text('benefits');
                $table->json('data');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['products_gb', 'products_us', 'products_it'] as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
}

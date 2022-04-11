<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTailorProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tailor_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('garment_name', 100)->nullable();
            $table->longText('description')->nullable();
            $table->string('gender', 100)->nullable()->comment('Male, Female');
            $table->float('stiching_charges')->nullable()->default(0.00);
            $table->string('category_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->json('process_name')->nullable();
            $table->json('measurement_name')->nullable();
            $table->json('gallery')->nullable();
            $table->json('fabric_consumption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tailor_products');
    }
}

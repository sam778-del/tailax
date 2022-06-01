<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement_fields', function (Blueprint $table) {
            $table->id();
            $table->string('measurement_name', 100)->unique()->nullable();
            $table->string('value_type', 100)->nullable()->default('Single Line');
            $table->json('options')->default('[]');
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('measurement_fields');
    }
}

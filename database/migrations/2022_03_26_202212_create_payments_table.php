<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->string('reference', 100)->nullable();
            $table->float('amount')->nullable()->default(0.00);
            $table->string('branch_id')->default("0");
            $table->string('customer_id')->default("0");
            $table->boolean('is_received')->nullable()->default(false);
            $table->boolean('alert_customer')->nullable()->default(false);
            $table->string('gateway_id')->nullable()->default("0");
            $table->string('created_by')->default(User::SUPERADMIN);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('payments');
    }
}

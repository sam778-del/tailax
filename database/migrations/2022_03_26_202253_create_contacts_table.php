<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable()->default('Lores Pium');
            $table->integer('phone_number')->unsigned()->nullable()->default(12);
            $table->string('email')->unique();
            $table->string('branch_id')->default("0");
            $table->longText('address')->nullable();
            $table->longText('description')->nullable();
            $table->string('created_by')->default(User::SUPERADMIN);
            $table->string('imgae')->nullable()->default("0");
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
        Schema::dropIfExists('contacts');
    }
}

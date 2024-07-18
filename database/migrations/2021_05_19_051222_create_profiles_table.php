onUpdate('cascade')-><?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('document_type_id');
            $table->bigInteger('vat');
            $table->string('addres');
            $table->foreignId('phone_prefix_id');
            $table->bigInteger('phone');
            $table->mediumInteger('zip_code');
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
        Schema::dropIfExists('profiles');
    }
}

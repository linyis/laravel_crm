<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryCrm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_crm', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("crm_id");
            $table->foreign('crm_id')->references('id')->on('crms');
            $table->unsignedInteger("category_id");
            $table->foreign('category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('category_crm');
    }
}

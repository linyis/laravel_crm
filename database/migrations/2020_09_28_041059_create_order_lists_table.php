<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(null)->comment("訂單id");
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('goods_id')->default(null)->comment("商品id");
//            $table->foreign('goods_id')->references('id')->on('goods');

            $table->integer('quantity')->default(null)->comment("數量");
            $table->decimal('total_price')->default(null)->comment("總價");
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
        Schema::dropIfExists('order_lists');
    }
}

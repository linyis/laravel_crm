<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
//            $table->integer('user_id')->default(null)->comment("用戶id");
            $table->string("email")->default(null)->comment("email");
            $table->string("mobile")->default(null)->comment("手機");
            $table->bigInteger('order_no')->default(null)->comment("訂單號");
            $table->decimal('payment')->default(null)->comment("實付金額");
            $table->integer('payment_type')->default(1)->comment("支付类型，1-綠界支付");
            $table->integer('status')->default(10)->comment("訂單狀態：0-已取消-10-未付款-5-已付款");
            $table->timestamp('platform_time')->nullable()->default(null)->comment("支付時間");
            $table->timestamp('end_time')->nullable()->default(null)->comment("交易完成時間");
            $table->timestamp('close_time')->nullable()->default(null)->comment("交易關閉時間");
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
        Schema::dropIfExists('orders');
    }
}

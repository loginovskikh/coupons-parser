<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('storeId')
                ->references('storeId')
                ->on('stores')
                ->onDelete('cascade');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->foreign('categoryId')
                ->references('categoryId')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['storeId']);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['categoryId']);
        });
    }
}

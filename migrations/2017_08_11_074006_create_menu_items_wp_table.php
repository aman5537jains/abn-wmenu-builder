<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsWpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( config('menu.table_prefix') . config('menu.table_name_items') , function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('link')->nullable();
            $table->string('link_type')->enum(["CUSTOM","ROUTE_NAME","CMS_PAGES"])->default('CUSTOM');
            $table->unsignedBigInteger('parent')->default(0);
            $table->integer('sort')->default(0);
            $table->string('class')->nullable();
            $table->unsignedBigInteger('menu');
            $table->integer('depth')->default(0);
            $table->text('permissions')->nullable();
            $table->string('menu_handler')->nullable();
            $table->timestamps();

            $table->foreign('menu')->references('id')->on(config('menu.table_prefix') . config('menu.table_name_menus'))
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( config('menu.table_prefix') . config('menu.table_name_items'));
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientKpiItemsExclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_kpi_items_exclusions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('kpi_item_id');
            $table->unsignedBigInteger('global_kpi_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('kpi_item_id')->references('id')->on('kpi_items')->onDelete('cascade');
            $table->foreign('global_kpi_id')->references('id')->on('global_kpis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_kpi_items_exclusions');
    }
}

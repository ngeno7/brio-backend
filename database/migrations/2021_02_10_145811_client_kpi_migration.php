<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientKpiMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_kpis', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('global_kpi_id')->nullable();
            $table->unsignedBigInteger('kpi_item_id')->nullable();
            $table->timestamps();

            $table->foreign('global_kpi_id')->references('id')->on('global_kpis')->onDelete('set null');
            $table->foreign('kpi_item_id')->references('id')->on('kpi_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_kpis');
    }
}

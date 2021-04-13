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
            $table->unsignedBigInteger('global_client_kpi_id')->nullable();
            $table->unsignedBigInteger('client_kpi_item_id')->nullable();
            $table->timestamps();

            $table->foreign('global_client_kpi_id')->references('id')
                ->on('global_client_kpis')->onDelete('cascade');
            $table->foreign('client_kpi_item_id')->references('id')
                ->on('client_kpi_items')->onDelete('cascade');
            $table->foreign('client_id')->references('id')
                ->on('clients')->onDelete('cascade');
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

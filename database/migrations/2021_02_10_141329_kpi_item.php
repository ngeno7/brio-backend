<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KpiItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_items', function(Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('global_kpi_id');
            $table->timestamps();

            $table->foreign('global_kpi_id')->references('id')
                ->on('global_kpis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_items');
    }
}

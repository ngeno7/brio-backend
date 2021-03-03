<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalKpiMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_kpis', function(Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('system')->default(false);
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('global_kpis');
    }
}

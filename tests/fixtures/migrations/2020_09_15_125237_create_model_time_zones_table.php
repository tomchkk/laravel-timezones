<?php
// phpcs:ignoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelTimeZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_time_zones', function (Blueprint $table) {
            $table->id();
            $table->timestamp('utc')->nullable();
            $table->timestamp('default')->nullable();
            $table->timestamp('europe_london')->nullable();
            $table->timestamp('europe_paris')->nullable();
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
        Schema::dropIfExists('model_time_zones');
    }
}

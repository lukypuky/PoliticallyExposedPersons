<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('osoba_meno');
            $table->string('osoba_priezvisko');
            $table->date('osoba_datum_narodenia');
            $table->unsignedInteger('id_pep_category')->nullable();
            $table->foreign('id_pep_category')->references('id')->on('politically_exposed_people');
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
        Schema::dropIfExists('people');
    }
};

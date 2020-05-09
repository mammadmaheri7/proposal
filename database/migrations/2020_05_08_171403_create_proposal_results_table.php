<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->enum('judge1_response',['accept','decline','miner','major']);
            $table->enum('judge2_response',['accept','decline','miner','major']);
            $table->enum('supervisor_response',['accept','decline','miner','major']);
            $table->enum('status',['accepted','decline','first_accept','waiting']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_results');
    }
}

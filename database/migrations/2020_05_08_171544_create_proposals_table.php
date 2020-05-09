<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('field_id')->nullable();
            $table->foreign('field_id')->references('id')->on('fields');

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('Students');

            $table->unsignedBigInteger('professor_id')->nullable();
            $table->foreign('professor_id')->references('id')->on('professors');

            $table->string('persian_title');
            $table->string('persian_keywords');
            $table->string('english_title');
            $table->string('english_keywords');
            $table->enum('type',['bonyadi','nazari','karbordi','tose']);
            $table->string('filename');

            $table->unsignedBigInteger('judge1_id')->nullable();
            $table->foreign('judge1_id')->references('id')->on('professors');

            $table->unsignedBigInteger('judge2_id')->nullable();
            $table->foreign('judge2_id')->references('id')->on('professors');

            $table->unsignedBigInteger('proposal_result_id')->nullable();
            $table->foreign('proposal_result_id')->references('id')->on('proposal_results');

            $table->integer('year');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
}

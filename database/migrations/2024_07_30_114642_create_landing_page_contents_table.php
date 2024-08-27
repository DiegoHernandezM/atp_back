<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->text('principal_text');
            $table->string('footer_title');
            $table->string('link_video');
            $table->string('subscribe_button');
            $table->string('compatible_text');
            $table->string('login_link_text');
            $table->string('footer_text_1');
            $table->string('footer_text_2');
            $table->string('footer_text_3');
            $table->string('footer_text_4');
            $table->string('ws_number');
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
        Schema::dropIfExists('landing_page_contents');
    }
}

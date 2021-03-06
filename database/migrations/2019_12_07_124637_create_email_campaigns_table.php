<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('mailing_list_id')->nullable();
            $table->foreign('mailing_list_id')->references('id')->on('mailing_lists');
            $table->boolean('conditional')->default(false);
            $table->unsignedBigInteger('email_campaign_id')->nullable();
            $table->foreign('email_campaign_id')->references('id')->on('email_campaigns');
            $table->enum('event', ['Đã mở', 'Đã click', 'Đã nhận', 'Không mở', 'Không click'])->nullable();
            $table->integer('after')->nullable();
            $table->enum('time_mode', ['h', 'd', 'w', 'm'])->nullable();
            $table->tinyInteger('order')->nullable();
            $table->unsignedBigInteger('email_automation_id');
            $table->foreign('email_automation_id')->references('id')->on('email_automations');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('email_campaigns');
    }
}

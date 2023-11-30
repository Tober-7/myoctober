<?php namespace TobiasFasanok\Arrivals\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateTobiasfasanokArrivals extends Migration
{
    public function up()
    {
        Schema::create('tobiasfasanok_arrivals', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->dateTime('date');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tobiasfasanok_arrivals');
    }
}

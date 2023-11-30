<?php namespace TobiasFasanok\Users\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateTobiasfasanokUsers extends Migration
{
    public function up()
    {
        Schema::create('tobiasfasanok_users', function($table)
        {
            $table->increments('id');
            $table->text('name');
            $table->text('email');
            $table->text('password');
            $table->text('token')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tobiasfasanok_users');
    }
}

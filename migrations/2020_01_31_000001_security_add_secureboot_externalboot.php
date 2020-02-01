<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddSecurebootExternalboot extends Migration
{
    private $tableName = 'security';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->string('t2_secureboot')->default('')->nullable();
          $table->string('t2_externalboot')->default('')->nullable();
          $table->index('t2_secureboot');
          $table->index('t2_externalboot');
          
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('t2_secureboot');
            $table->dropColumn('t2_externalboot');
        });
    }
}

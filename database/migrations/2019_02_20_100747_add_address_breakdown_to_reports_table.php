<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressBreakdownToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->string("country")->nullable();
            $table->string("region")->nullable();
            $table->string("province")->nullable();
            $table->string("city")->nullable();
            $table->string("longitude")->nullable();
            $table->string("latitude")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->dropColumn("country");
            $table->dropColumn("region");
            $table->dropColumn("province");
            $table->dropColumn("city");
            $table->dropColumn("longitude");
            $table->dropColumn("latitude");

        });
    }
}

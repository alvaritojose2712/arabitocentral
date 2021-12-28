<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalsVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locals_versions', function (Blueprint $table) {
            $table->increments("id");
            $table->string("version")->unique();
            $table->timestamps();
        });
        DB::table("locals_versions")->insert([
            ["version"=>2]
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locals_versions');
    }
}

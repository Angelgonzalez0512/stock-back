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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("company_name")->default("");
            $table->string("tax_number")->default("");
            $table->string("dni",8)->default("");
            $table->string("email")->default("");
            $table->string("phone")->default("");
            $table->string("address")->default("");
            $table->text("notes")->nullable();
            $table->foreignId("created_by")->constrained("users");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};

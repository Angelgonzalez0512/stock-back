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
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("transfer_id")->constrained("transfers");
            $table->foreignId("product_id")->constrained("products");
            $table->decimal("quantity", 10, 2)->default(0);
            $table->decimal("price", 10, 2)->default(0);
            $table->decimal("total", 10, 2)->default(0);
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
        Schema::dropIfExists('transfer_details');
    }
};

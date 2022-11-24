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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("barcode");
            $table->decimal("price", 10, 2)->default(0);
            $table->decimal("presentation_quantity",10,2);
            $table->string("presentation")->nullable();
            $table->decimal("stock", 10, 2)->default(0);
            $table->decimal("min_stock", 10, 2)->default(0);
            $table->decimal("max_stock", 10, 2)->default(0);
            $table->string("brand")->nullable();
            $table->foreignId("category_id")->constrained("categories");
            $table->string("unit")->nullable();
            $table->foreignId("created_by")->constrained("users");
            $table->text("description")->nullable();
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
        Schema::dropIfExists('products');
    }
};

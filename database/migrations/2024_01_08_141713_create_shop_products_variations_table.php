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
        Schema::create('shop_products_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_product_id')->constrained('shop_products')->onDelete('cascade');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('weight_unit')->default('kg');
            $table->decimal('weight_value', 10, 2)->nullable()->default(0.00)->unsigned();
            $table->string('height_unit')->default('cm');
            $table->decimal('height_value', 10, 2)->nullable()->default(0.00)->unsigned();
            $table->string('width_unit')->default('cm');
            $table->decimal('width_value', 10, 2)->nullable()->default(0.00)->unsigned();
            $table->string('depth_unit')->default('cm');
            $table->decimal('depth_value', 10, 2)->nullable()->default(0.00)->unsigned();
            $table->string('volume_unit')->default('l');
            $table->decimal('volume_value', 10, 2)->nullable()->default(0.00)->unsigned();
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
        Schema::dropIfExists('shop_products_variations');
    }
};

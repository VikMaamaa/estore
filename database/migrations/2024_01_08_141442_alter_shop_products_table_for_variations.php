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
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn(['requires_shipping', 'weight_unit', 'height_unit', 'width_unit', 'depth_unit', 'volume_unit']);
            $table->boolean('has_variations')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->boolean('requires_shipping')->default(false);
            $table->string('weight_unit')->default('kg');
            $table->string('height_unit')->default('cm');
            $table->string('width_unit')->default('cm');
            $table->string('depth_unit')->default('cm');
            $table->string('volume_unit')->default('l');
            $table->dropColumn('has_variations');
        });
    }
};

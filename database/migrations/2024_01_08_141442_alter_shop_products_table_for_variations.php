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
            $table->dropColumn([
                'weight_unit',
                'weight_value',
                'height_unit',
                'height_value',
                'width_unit',
                'width_value',
                'depth_unit',
                'depth_value',
                'volume_unit',
                'volume_value',

            ]);

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
            $table->dropColumn('has_variations');
        });
    }
};

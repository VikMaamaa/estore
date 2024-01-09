<?php

namespace App\Services;

use App\Models\Shop\Product;
use App\Models\Shop\ProductVariation;
use Illuminate\Database\QueryException;

class ProductVariationService
{

/**
     * Get variations by product
     *
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVariationsByProduct(Product $product)
    {
        return ProductVariation::where('shop_product_id', $product->id)->get();
    }

    /**
     * Create product variation
     */
    public function create(Product $product, array $data): ProductVariation
    {
        return $product->variations()->create($data);
    }

    /**
     * Update product variation data
     */
    public function update(ProductVariation $productVariation, array $data): ProductVariation
    {
        $productVariation->update($data);
        return $productVariation->refresh();
    }


    /**
     * Delete product variation
     */
    public function delete(ProductVariation $productVariation): void
    {
        $productVariation->delete();
    }

    public function getVariationById(Product $product, int $variationId, array $searchParams = []): ProductVariation
{
    // Find the product variation by ID within the specified product
    $variation = $product->variations()->findOrFail($variationId);

    foreach ($searchParams as $field => $value) {
        // Check if the field is valid and searchable
        if (in_array($field, [
            'size',
            'color',
            'requires_shipping',
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
        ])) {
            $variation = $variation->where($field, 'like', '%' . $value . '%');
        }
    }


    $variation = $variation->with(['product']);

    return $variation;
}
}

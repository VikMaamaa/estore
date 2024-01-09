<?php

// App/Services/ProductService.php

namespace App\Services;

use App\Models\Shop\Product;
use Illuminate\Database\QueryException;

class ProductService
{
    /**
     * Find Product by Id
     */
    public function findProductById(int $productId): Product
    {
        return Product::findOrFail($productId);
    }

    /**
     * List and search products
     */
    public function search(array $filters, $perPage, $page)
{
    $query = Product::query();

    foreach ($filters as $field => $value) {
        // Exclude 'id' from search
        if ($field !== 'id') {
            $query->where($field, 'like', '%' . $value . '%');
        }
    }


    $query->with('brand');

    // Paginate the results with the specified page and per page values
    return $query->paginate($perPage, ['*'], 'page', $page);
}

    /**
     * create product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * update product data
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->refresh();
    }

    /**
     * soft delete product
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }

    /**
     * Search product
     */
    public function searchProduct(Product $product, array $searchParams): Product
    {
        try {
            foreach ($searchParams as $field => $value) {
                // Skip invalid or non-searchable fields
                if (!in_array($field, [
                    'name',
                    'shop_brand_id',
                    'slug',
                    'sku',
                    'barcode',
                    'description',
                    'qty',
                    'security_stock',
                    'featured',
                    'is_visible',
                    'has_variations',
                    'old_price',
                    'price',
                    'cost',
                    'published_at',
                    'seo_title',
                    'seo_description',
                ])) {
                    continue;
                }

                // Apply the search condition for each field
                $product = $product->where($field, 'like', '%' . $value . '%');
            }

            $product = $product->with('brand');

            return $product->firstOrFail();
        }  catch (QueryException $e) {
            // Check if it's a soft-deleted record (error code 1054 indicates column not found)
            if ($e->getCode() === 1054 && stripos($e->getMessage(), 'deleted_at') !== false) {
                // Return a custom message for soft-deleted records
                throw new ModelNotFoundException('Product not found. The record may have been deleted.');
            }

            // If it's not a soft-deleted record, rethrow the exception
            throw $e;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Shop\Brand;
use Illuminate\Database\QueryException;

class BrandService
{
    /**
     * List and search brands
     */
    public function search(array $filters, $perPage, $page)
    {
        $query = Brand::query();

        foreach ($filters as $field => $value) {
            // Exclude 'id' from search
            if ($field !== 'id') {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        // Paginate the results with the specified page and per page values
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * create brand
     */
    public function create(array $data): Brand
    {
        return Brand::create($data);
    }

    /**
     * update brand data
     */
    public function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);
        return $brand->refresh();
    }

    /**
     * soft delete brand
     */
    public function delete(Brand $brand): void
    {
        $brand->delete();
    }

    /**
     * Search brand
     */
    public function searchBrand(Brand $brand, array $searchParams): Brand
    {
        try {
            foreach ($searchParams as $field => $value) {
                // Skip invalid or non-searchable fields
                if (!in_array($field, ['name', 'slug', 'website', 'description', 'position', 'is_visible', 'seo_title', 'seo_description', 'sort'])) {
                    continue;
                }

                // Apply the search condition for each field
                $brand = $brand->where($field, 'like', '%' . $value . '%');
            }

            return $brand->firstOrFail();
        } catch (QueryException $e) {
            // Check if it's a soft-deleted record (error code 1054 indicates column not found)
            if ($e->getCode() === 1054 && stripos($e->getMessage(), 'deleted_at') !== false) {
                // Return a custom message for soft-deleted records
                throw new ModelNotFoundException('Brand not found. The record may have been deleted.');
            }

            // If it's not a soft-deleted record, rethrow the exception
            throw $e;
        }
    }
}

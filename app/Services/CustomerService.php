<?php

namespace App\Services;
use App\Models\Shop\Customer;
use Illuminate\Database\QueryException;

class CustomerService
{
    /**
     * List and search customers
     */
    public function search(array $filters, $perPage, $page)
    {
        $query = Customer::query();

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
     * create customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * update customer data
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);
        return $customer->refresh();
    }

    /**
     * soft delete customer
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    /**
     * Search customer
     */
    public function searchCustomer(Customer $customer, array $searchParams): Customer
    {
        try {
            foreach ($searchParams as $field => $value) {
                // Skip invalid or non-searchable fields
                if (!in_array($field, ['name', 'email', 'photo', 'gender', 'phone', 'birthday'])) {
                    continue;
                }

                // Apply the search condition for each field
                $customer = $customer->where($field, 'like', '%' . $value . '%');
            }

            return $customer->firstOrFail();
        } catch (QueryException $e) {
            // Check if it's a soft-deleted record (error code 1054 indicates column not found)
            if ($e->getCode() === 1054 && stripos($e->getMessage(), 'deleted_at') !== false) {
                // Return a custom message for soft-deleted records
                throw new ModelNotFoundException('Customer not found. The record may have been deleted.');
            }

            // If it's not a soft-deleted record, rethrow the exception
            throw $e;
        }
    }
}

<?php

namespace App\Services\Api\Customer;

use App\Repositories\Api\Customer\CustomerRepository;
use App\Models\Customer;

class CustomerService
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all customers
     */
    public function getAll(
        int $perPage = 15,
        ?string $loyaltyLevel = null,
        ?bool $isActive = null,
        ?string $search = null
    ): array {
        $customers = $this->customerRepository->getAll($perPage, $loyaltyLevel, $isActive, $search);

        return [
            'success' => true,
            'data' => $customers,
        ];
    }

    /**
     * Get customer by ID
     */
    public function getById(int $id): array
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $customer,
        ];
    }

    /**
     * Create new customer
     */
    public function create(array $data): array
    {
        // Check if phone already exists
        if ($this->customerRepository->findByPhone($data['phone'])) {
            return [
                'success' => false,
                'message' => __('customers.phone_already_exists'),
            ];
        }

        // Check if email already exists
        if (!empty($data['email']) && $this->customerRepository->findByEmail($data['email'])) {
            return [
                'success' => false,
                'message' => __('customers.email_already_exists'),
            ];
        }

        try {
            $customer = $this->customerRepository->create($data);

            return [
                'success' => true,
                'data' => $customer,
                'message' => __('customers.customer_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.customer_creation_failed'),
            ];
        }
    }

    /**
     * Update customer
     */
    public function update(int $id, array $data): array
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        // Check if phone already exists for another customer
        if (!empty($data['phone'])) {
            $existingCustomer = $this->customerRepository->findByPhone($data['phone']);
            if ($existingCustomer && $existingCustomer->id !== $customer->id) {
                return [
                    'success' => false,
                    'message' => __('customers.phone_already_exists'),
                ];
            }
        }

        // Check if email already exists for another customer
        if (!empty($data['email'])) {
            $existingCustomer = $this->customerRepository->findByEmail($data['email']);
            if ($existingCustomer && $existingCustomer->id !== $customer->id) {
                return [
                    'success' => false,
                    'message' => __('customers.email_already_exists'),
                ];
            }
        }

        try {
            $this->customerRepository->update($customer, $data);

            return [
                'success' => true,
                'data' => $customer->fresh(),
                'message' => __('customers.customer_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.customer_update_failed'),
            ];
        }
    }

    /**
     * Delete customer
     */
    public function delete(int $id): array
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        try {
            $this->customerRepository->delete($customer);

            return [
                'success' => true,
                'message' => __('customers.customer_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.customer_deletion_failed'),
            ];
        }
    }

    /**
     * Search customers by phone
     */
    public function searchByPhone(string $phone): array
    {
        $customers = $this->customerRepository->searchByPhone($phone);

        return [
            'success' => true,
            'data' => $customers,
        ];
    }

    /**
     * Get customer sales history
     */
    public function getSalesHistory(int $customerId, int $perPage = 15): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        $sales = $this->customerRepository->getSalesHistory($customer, $perPage);

        return [
            'success' => true,
            'data' => $sales,
        ];
    }

    /**
     * Get customer preferences
     */
    public function getPreferences(int $customerId): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        $preferences = $this->customerRepository->getPreferences($customer);

        return [
            'success' => true,
            'data' => $preferences,
        ];
    }

    /**
     * Update customer preferences
     */
    public function updatePreferences(int $customerId, array $preferences): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        try {
            $this->customerRepository->updatePreferences($customer, $preferences);

            return [
                'success' => true,
                'data' => $this->customerRepository->getPreferences($customer),
                'message' => __('customers.preferences_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.preferences_update_failed'),
            ];
        }
    }

    /**
     * Get loyalty points balance
     */
    public function getLoyaltyBalance(int $customerId): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        $balance = $this->customerRepository->getLoyaltyBalance($customer);

        return [
            'success' => true,
            'data' => $balance,
        ];
    }

    /**
     * Get loyalty points history
     */
    public function getLoyaltyHistory(int $customerId, int $perPage = 15): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        $history = $this->customerRepository->getLoyaltyHistory($customer, $perPage);

        return [
            'success' => true,
            'data' => $history,
        ];
    }

    /**
     * Earn loyalty points
     */
    public function earnPoints(int $customerId, float $points, ?string $notes = null): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        if ($points <= 0) {
            return [
                'success' => false,
                'message' => __('customers.invalid_points_amount'),
            ];
        }

        try {
            $loyaltyPoint = $this->customerRepository->earnPoints($customer, $points, null, null, $notes);

            return [
                'success' => true,
                'data' => [
                    'transaction' => $loyaltyPoint,
                    'new_balance' => $customer->fresh()->loyalty_points,
                ],
                'message' => __('customers.points_earned_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.points_earning_failed'),
            ];
        }
    }

    /**
     * Redeem loyalty points
     */
    public function redeemPoints(int $customerId, float $points, ?string $notes = null): array
    {
        $customer = $this->customerRepository->findById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => __('customers.customer_not_found'),
            ];
        }

        if ($points <= 0) {
            return [
                'success' => false,
                'message' => __('customers.invalid_points_amount'),
            ];
        }

        if ($customer->loyalty_points < $points) {
            return [
                'success' => false,
                'message' => __('customers.insufficient_points'),
            ];
        }

        try {
            $loyaltyPoint = $this->customerRepository->redeemPoints($customer, $points, $notes);

            return [
                'success' => true,
                'data' => [
                    'transaction' => $loyaltyPoint,
                    'new_balance' => $customer->fresh()->loyalty_points,
                ],
                'message' => __('customers.points_redeemed_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('customers.points_redemption_failed'),
            ];
        }
    }
}

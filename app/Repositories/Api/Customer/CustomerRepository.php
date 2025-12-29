<?php

namespace App\Repositories\Api\Customer;

use App\Models\Customer;
use App\Models\LoyaltyPoint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{
    /**
     * Get all customers with pagination
     */
    public function getAll(
        int $perPage = 15,
        ?string $loyaltyLevel = null,
        ?bool $isActive = null,
        ?string $search = null
    ): LengthAwarePaginator {
        $query = Customer::orderBy('name', 'asc');

        if ($loyaltyLevel) {
            $query->where('loyalty_level', $loyaltyLevel);
        }

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Find customer by ID
     */
    public function findById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Find customer by phone
     */
    public function findByPhone(string $phone): ?Customer
    {
        return Customer::where('phone', $phone)->first();
    }

    /**
     * Find customer by email
     */
    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    /**
     * Create new customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Update customer
     */
    public function update(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    /**
     * Delete customer
     */
    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    /**
     * Get customer sales history
     */
    public function getSalesHistory(Customer $customer, int $perPage = 15): LengthAwarePaginator
    {
        return $customer->sales()
            ->with(['employee', 'items.product', 'items.composition'])
            ->orderBy('sale_date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get customer preferences
     */
    public function getPreferences(Customer $customer): array
    {
        return [
            'preferred_scents' => $customer->preferred_scents ?? [],
            'favorite_products' => $customer->favorite_products ?? [],
        ];
    }

    /**
     * Update customer preferences
     */
    public function updatePreferences(Customer $customer, array $preferences): bool
    {
        return $customer->update([
            'preferred_scents' => $preferences['preferred_scents'] ?? $customer->preferred_scents,
            'favorite_products' => $preferences['favorite_products'] ?? $customer->favorite_products,
        ]);
    }

    /**
     * Get loyalty points balance
     */
    public function getLoyaltyBalance(Customer $customer): array
    {
        return [
            'points' => (float) $customer->loyalty_points,
            'level' => $customer->loyalty_level,
            'total_purchases' => (float) $customer->total_purchases,
        ];
    }

    /**
     * Get loyalty points history
     */
    public function getLoyaltyHistory(Customer $customer, int $perPage = 15): LengthAwarePaginator
    {
        return $customer->loyaltyPoints()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Earn loyalty points
     */
    public function earnPoints(Customer $customer, float $points, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): LoyaltyPoint
    {
        return DB::transaction(function () use ($customer, $points, $referenceType, $referenceId, $notes) {
            $customer->loyalty_points += $points;
            $customer->save();

            // Update loyalty level based on total purchases
            $this->updateLoyaltyLevel($customer);

            return LoyaltyPoint::create([
                'customer_id' => $customer->id,
                'points' => $points,
                'type' => 'earned',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'balance_after' => $customer->loyalty_points,
                'expires_at' => now()->addYear(), // Points expire in 1 year
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Redeem loyalty points
     */
    public function redeemPoints(Customer $customer, float $points, ?string $notes = null): LoyaltyPoint
    {
        return DB::transaction(function () use ($customer, $points, $notes) {
            $customer->loyalty_points -= $points;
            $customer->save();

            return LoyaltyPoint::create([
                'customer_id' => $customer->id,
                'points' => -$points,
                'type' => 'redeemed',
                'balance_after' => $customer->loyalty_points,
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Update customer total purchases
     */
    public function updateTotalPurchases(Customer $customer, float $amount): void
    {
        $customer->total_purchases += $amount;
        $customer->last_purchase_date = now();
        $customer->save();

        $this->updateLoyaltyLevel($customer);
    }

    /**
     * Update loyalty level based on total purchases
     */
    private function updateLoyaltyLevel(Customer $customer): void
    {
        $totalPurchases = $customer->total_purchases;

        if ($totalPurchases >= 10000) {
            $customer->loyalty_level = 'platinum';
        } elseif ($totalPurchases >= 5000) {
            $customer->loyalty_level = 'gold';
        } elseif ($totalPurchases >= 2000) {
            $customer->loyalty_level = 'silver';
        } else {
            $customer->loyalty_level = 'bronze';
        }

        $customer->save();
    }

    /**
     * Search customers by phone
     */
    public function searchByPhone(string $phone): Collection
    {
        return Customer::where('phone', 'like', "%{$phone}%")
            ->where('is_active', true)
            ->limit(10)
            ->get();
    }
}

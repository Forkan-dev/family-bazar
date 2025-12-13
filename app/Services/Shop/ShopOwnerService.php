<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopOwner;
use Illuminate\Support\Collection;

class ShopOwnerService
{
    /**
     * Search shop owners by user name or email
     */
    public function searchShopOwners(string $query, int $limit = 20): Collection
    {
        return ShopOwner::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get()
            ->map(function ($owner) {
                return [
                    'value' => $owner->id,
                    'label' => "{$owner->user->name} ({$owner->user->email})",
                ];
            });
    }

    /**
     * Get formatted shop owners for select dropdown
     */
    public function getFormattedShopOwners(?string $query = null, int $limit = 20): Collection
    {
        if (empty($query)) {
            return collect([]);
        }

        return $this->searchShopOwners($query, $limit);
    }

    /**
     * Get initial shop owner data for edit form
     */
    public function getInitialShopOwner(?ShopOwner $shopOwner): ?array
    {
        if (! $shopOwner ||
        ! $shopOwner->user) {
            return null;
        }

        return [

            'value' => $shopOwner->id,
            'label' => "{$shopOwner->user->name} ({$shopOwner->user->email})",
        ];
    }

    /**
     * Get initial shop owners alphabetically for create form
     */
    public function getInitialShopOwners(int $limit = 10): Collection
    {
        return ShopOwner::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(function ($owner) {
                return $owner->user->name;
            })
            ->take($limit)
            ->map(function ($owner) {
                return [
                    'value' => $owner->id,
                    'label' => "{$owner->user->name} ({$owner->user->email})",
                ];
            })
            ->values();
    }
}

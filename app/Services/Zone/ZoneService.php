<?php

namespace App\Services\Zone;

use App\Models\Zone\Zone;
use Illuminate\Support\Collection;

class ZoneService
{
    /**
     * Search zones by name or address
     */
    public function searchZones(string $query, int $limit = 20): Collection
    {
        return Zone::where('status', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'address')
            ->limit($limit)
            ->get()
            ->map(function ($zone) {
                return [
                    'value' => $zone->id,
                    'label' => $zone->address ? "{$zone->name} - {$zone->address}" : $zone->name,
                ];
            });
    }

    /**
     * Get formatted zones for select dropdown
     */
    public function getFormattedZones(?string $query = null, int $limit = 20): Collection
    {
        if (empty($query)) {
            return $this->getInitialZones();
        }

        return $this->searchZones($query, $limit);
    }

    /**
     * Get initial zones alphabetically for dropdown
     */
    public function getInitialZones(int $limit = 10): Collection
    {
        return Zone::where('status', true)
            ->select('id', 'name', 'address')
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(function ($zone) {
                return [
                    'value' => $zone->id,
                    'label' => $zone->address ? "{$zone->name} - {$zone->address}" : $zone->name,
                ];
            });
    }

    /**
     * Get initial zone data for edit form
     */
    public function getInitialZone(?Zone $zone): ?array
    {
        if (!$zone) {
            return null;
        }

        return [
            'value' => $zone->id,
            'label' => $zone->address ? "{$zone->name} - {$zone->address}" : $zone->name,
        ];
    }
}

<?php

namespace App\Actions\Offer;

use App\Models\Offer;

class UpdateOffer
{
    public function 
    handle(Offer $offer, array $data): Offer
    {
        $offer->name = $data['name'];
        $offer->name_bn = $data['name_bn'] ?? null;
        $offer->start_at = $data['start_at'] ? \Carbon\Carbon::parse($data['start_at']) : null;
        $offer->end_at = $data['end_at'] ? \Carbon\Carbon::parse($data['end_at']) : null;
        $offer->discount_type = $data['discount_type'];

        if ($data['discount_type'] === 'flat') {
            $offer->value = $data['flat_amount'] ?? 0;
        } else {
            $offer->value = $data['percentage'] ?? 0;
        }

        $offer->save();

        // Handle offer targets if provided
        if (isset($data['targets'])) {
            // Sync offer targets
            $offer->offerTargets()->delete(); // Remove existing targets
            foreach ($data['targets'] as $target) {
                $offer->offerTargets()->create([
                    'target_id' => $target['id'],
                    'target_type' => $target['type'],
                ]);
            }
        }

        return $offer;
    }
}

<?php

namespace App\Actions\Offer;

use App\Models\Offer;

class CreateOffer
{
    public function handle(array $data): Offer  
    {
        $start_date_time = datetime_parse($data['start_at'], 'Y-m-d H:i:s');
        $end_date_time = datetime_parse($data['end_at'], 'Y-m-d H:i:s');
        $offer = new Offer();
        $offer->name = $data['name'];
        $offer->name_bn = $data['name_bn'] ?? null;
        $offer->start_at = $start_date_time;
        $offer->end_at = $end_date_time;
        $offer->discount_type = $data['discount_type'];

        if ($data['discount_type'] === 'flat') {
            $offer->value = $data['flat_amount'] ?? 0;
        } else {
            $offer->value = $data['percentage'] ?? 0;
        }

        $offer->save();

        // Handle offer targets if provided
        if (!empty($data['targets'])) {
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

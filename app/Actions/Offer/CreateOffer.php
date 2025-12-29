<?php

namespace App\Actions\Offer;

use App\Models\Offer;
use Carbon\Carbon;

class CreateOffer
{
    public function handle(array $data): Offer
    {
        $start_date_utc = datetime_parse_local_to_utc($data['start_at'], 'Y-m-d\TH:i', 'Y-m-d H:i:s');
        $end_date_utc = datetime_parse_local_to_utc($data['end_at'], 'Y-m-d\TH:i', 'Y-m-d H:i:s');
        $offer = new Offer();
        $offer->name = $data['name'];
        $offer->name_bn = $data['name_bn'] ?? null;
        $offer->start_at = $start_date_utc;
        $offer->end_at = $end_date_utc;
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

<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;

class CreateBanner
{
    public function handle(StoreBannerRequest $request): Banner
    {
        $validatedData = $request->validated();

        return DB::transaction(function () use ($validatedData, $request) {
            $banner = Banner::create($validatedData);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/banner'), $filename);
                $banner->update(['image' => 'images/banner/' . $filename]);
            }

            return $banner;
        });
    }
}
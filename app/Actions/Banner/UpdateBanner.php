<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;

class UpdateBanner
{
    public function handle(UpdateBannerRequest $request, Banner $banner): Banner
    {
        $validatedData = $request->validated();

        return DB::transaction(function () use ($validatedData, $request, $banner) {
            $banner->update($validatedData);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                if ($banner->image && file_exists(public_path($banner->image))) {
                    unlink(public_path($banner->image));
                }

                $file->move(public_path('images/banner'), $filename);
                $banner->update(['image' => 'images/banner/' . $filename]);
            }

            return $banner;
        });
    }
}

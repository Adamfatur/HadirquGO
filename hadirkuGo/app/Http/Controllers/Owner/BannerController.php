<?php

namespace App\Http\Controllers\Owner;

use App\Models\Banner;
use App\Models\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BannerController extends Controller
{
    /**
     * Display a listing of banners for a specific business.
     *
     * @param string $business_unique_id
     * @return View
     */
    public function index(string $business_unique_id): View
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $banners = $business->banners;
        return view('owner.banners.index', compact('banners', 'business')); // Pastikan mengirimkan $business
    }

    /**
     * Display the form for adding a new banner for a specific business.
     *
     * @param string $business_unique_id
     * @return View
     */
    public function createBanner(string $business_unique_id): View
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        // Tidak perlu lagi mengirimkan semua bisnis, karena sudah dalam konteks bisnis tertentu
        return view('owner.banners.create', ['business' => $business]);
    }

    /**
     * Store a new banner in the database for a specific business.
     *
     * @param Request $request
     * @param string $business_unique_id
     * @return RedirectResponse
     */
    public function storeBanner(Request $request, string $business_unique_id): RedirectResponse
    {
        $request->validate([
            'banner_url' => 'required|url',
            // 'business_id' tidak lagi diperlukan dari request, kita akan menggunakan business_id dari business_unique_id
        ]);

        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        Banner::create([
            'banner_url' => $request->banner_url,
            'business_id' => $business->id, // Menggunakan business_id dari bisnis yang ditemukan
        ]);

        // Perbarui route untuk menyertakan business_unique_id
        return redirect()->route('banners.index', ['business_unique_id' => $business_unique_id])->with('success', 'Banner successfully added.');
    }

    /**
     * Display the form for editing a banner for a specific business.
     *
     * @param string $business_unique_id
     * @param Banner $banner
     * @return View
     */
    public function editBanner(string $business_unique_id, Banner $banner): View
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        // Tidak perlu lagi mengirimkan semua bisnis
        return view('owner.banners.edit', compact('banner', 'business')); // Perhatikan path view yang diperbarui
    }

    /**
     * Update banner data in the database untuk bisnis tertentu.
     *
     * @param Request $request
     * @param string $business_unique_id
     * @param Banner $banner
     * @return RedirectResponse
     */
    public function updateBanner(Request $request, string $business_unique_id, Banner $banner): RedirectResponse
    {
        $request->validate([
            'banner_url' => 'required|url',
            // business_id tidak lagi diubah dari form, kita tetap menggunakan business yang sama
        ]);

        $banner->update([
            'banner_url' => $request->banner_url,
            // business_id tetap sama
        ]);

        // Perbarui route untuk menyertakan business_unique_id
        return redirect()->route('banners.index', ['business_unique_id' => $business_unique_id])->with('success', 'Banner successfully updated.');
    }

    /**
     * Delete a banner from the database untuk bisnis tertentu.
     *
     * @param string $business_unique_id
     * @param Banner $banner
     * @return RedirectResponse
     */
    public function destroyBanner(string $business_unique_id, Banner $banner): RedirectResponse
    {
        $banner->delete();
        // Perbarui route untuk menyertakan business_unique_id
        return redirect()->route('banners.index', ['business_unique_id' => $business_unique_id])->with('success', 'Banner successfully deleted.');
    }

    /**
     * Toggle the active/inactive status of a banner untuk bisnis tertentu.
     *
     * @param string $business_unique_id
     * @param Banner $banner
     * @return RedirectResponse
     */
    public function toggleBannerStatus(string $business_unique_id, Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => !$banner->is_active]);
        // Perbarui route untuk menyertakan business_unique_id
        return redirect()->route('banners.index', ['business_unique_id' => $business_unique_id])->with('success', 'Banner status successfully changed.');
    }
}
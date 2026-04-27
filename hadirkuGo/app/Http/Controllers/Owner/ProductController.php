<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RedemptionRequest;
use App\Models\UserPointSummary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Business;
use Illuminate\Support\Facades\Log;
use App\Mail\RedemptionRequestNotification;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    /**
     * Display a list of products owned by the owner.
     */
    public function index(Request $request)
    {
        $owner = Auth::user();

        // Get the search query from the request
        $search = $request->input('search');

        // Fetch paginated products owned by the owner
        $products = Product::where('owner_id', $owner->id)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('owner.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('owner.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $owner = Auth::user();

        // Get the business associated with the owner
        $business = $owner->businesses()->first();

        if (!$business) {
            return redirect()->back()->withErrors('You do not have a registered business. Please create one first.');
        }

        // Validate input
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048', // Max 2MB
            'stock_quantity'  => 'required|integer|min:0',
            'points_required' => 'required|integer|min:1',
            'status'          => 'required|in:ready,waiting_list',
        ]);

        // Create a new product
        $product = new Product();
        $product->owner_id = $owner->id;
        $product->business_id = $business->id; // Automatically assign the business_id
        $product->product_code = 'HG-' . strtoupper(Str::random(10)); // Generate unique product code

        // Handle image upload
        if ($request->hasFile('image')) {
            $product->image = $this->handleImageUpload($request->file('image'));
        }

        // Save product details
        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock_quantity = $request->stock_quantity;
        $product->points_required = $request->points_required;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('owner.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($productCode)
    {
        $owner = Auth::user();

        // Find the product by product_code owned by the owner
        $product = Product::where('owner_id', $owner->id)
            ->where('product_code', $productCode)
            ->firstOrFail();

        return view('owner.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $productCode)
    {
        $owner = Auth::user();

        // Find the product by product_code owned by the owner
        $product = Product::where('owner_id', $owner->id)
            ->where('product_code', $productCode)
            ->firstOrFail();

        // Validate input
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048', // Max 2MB
            'stock_quantity'  => 'required|integer|min:0',
            'points_required' => 'required|integer|min:1',
            'status'          => 'required|in:ready,waiting_list',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $product->image = $this->handleImageUpload($request->file('image'));
        }

        // Update product details
        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock_quantity = $request->stock_quantity;
        $product->points_required = $request->points_required;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('owner.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product from the database.
     */
    public function destroy($productCode)
    {
        $owner = Auth::user();

        // Find the product by product_code owned by the owner
        $product = Product::where('owner_id', $owner->id)
            ->where('product_code', $productCode)
            ->firstOrFail();

        // Delete image if it exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete the product
        $product->delete();

        return redirect()->route('owner.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Handle image upload logic.
     */
    private function handleImageUpload($image)
    {
        // Generate a unique filename
        $imageName = time() . '_' . uniqid() . '.' . $image->extension();

        // Define the storage path
        $destinationPath = public_path('images/products');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Move the uploaded file to the destination
        $image->move($destinationPath, $imageName);

        return 'images/products/' . $imageName;
    }

    /**
     * Display the waiting list, approved, and rejected redemption requests for a product.
     */
    public function showWaitingList($productCode)
    {
        $owner = Auth::user();

        // Find the product by product_code owned by the owner
        $product = Product::where('owner_id', $owner->id)
            ->where('product_code', $productCode)
            ->firstOrFail();

        // Fetch redemption requests with 'waiting_list' or 'pending' status
        $waitingList = RedemptionRequest::where('product_id', $product->id)
            ->whereIn('status', [RedemptionRequest::STATUS_WAITING_LIST, RedemptionRequest::STATUS_PENDING])
            ->orderBy('requested_at')
            ->paginate(10, ['*'], 'waiting');

        // Fetch approved requests
        $approvedList = RedemptionRequest::where('product_id', $product->id)
            ->where('status', RedemptionRequest::STATUS_APPROVED)
            ->orderBy('requested_at')
            ->paginate(10, ['*'], 'approved');

        // Fetch rejected requests
        $rejectedList = RedemptionRequest::where('product_id', $product->id)
            ->where('status', RedemptionRequest::STATUS_REJECTED)
            ->orderBy('requested_at')
            ->paginate(10, ['*'], 'rejected');

        return view('owner.products.waiting_list', compact('product', 'waitingList', 'approvedList', 'rejectedList'));
    }

    /**
     * Bulk approve redemption requests.
     */
    public function bulkApprove(Request $request, $productCode)
    {
        $owner = Auth::user();

        try {
            $request->validate([
                'request_ids' => 'required|array',
                'request_ids.*' => 'exists:redemption_requests,id',
            ]);

            $product = Product::where('owner_id', $owner->id)
                ->where('product_code', $productCode)
                ->firstOrFail();

            DB::beginTransaction();

            // Ambil semua redemptionRequests yang masih pending/waiting_list
            $redemptionRequests = RedemptionRequest::whereIn('id', $request->request_ids)
                ->where('product_id', $product->id)
                ->whereIn('status', [
                    RedemptionRequest::STATUS_PENDING,
                    RedemptionRequest::STATUS_WAITING_LIST
                ])
                ->get();

            foreach ($redemptionRequests as $redemptionRequest) {
                $user = $redemptionRequest->user;

                // 1) Tandai request -> APPROVED
                $redemptionRequest->update([
                    'status'     => RedemptionRequest::STATUS_APPROVED,
                    'updated_at' => now(),
                ]);

                // 2) Kirim notifikasi email
                $notificationMessage = "Your redemption request has been approved. Enjoy your reward!";
                Mail::to($user->email)->send(new RedemptionRequestNotification(
                    $user,
                    $product,
                    'approved',
                    $notificationMessage,
                    now()
                ));
            }

            DB::commit();

            return redirect()
                ->route('owner.products.waiting_list', $productCode)
                ->with('success', 'Selected requests have been approved.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error approving redemption requests: ' . $e->getMessage(), [
                'product_code' => $productCode,
                'request_ids'  => $request->request_ids,
                'user_id'      => $owner->id,
            ]);

            return redirect()
                ->route('owner.products.waiting_list', $productCode)
                ->withErrors('An error occurred while approving the requests. Please try again.');
        }
    }
    /**
     * Bulk reject redemption requests.
     */
    public function bulkReject(Request $request, $productCode)
    {
        $owner = Auth::user();

        try {
            $request->validate([
                'request_ids' => 'required|array',
                'request_ids.*' => 'exists:redemption_requests,id',
            ]);

            $product = Product::where('owner_id', $owner->id)
                ->where('product_code', $productCode)
                ->firstOrFail();

            DB::beginTransaction();

            // Ambil semua redemptionRequests yang masih pending/waiting_list
            $redemptionRequests = RedemptionRequest::whereIn('id', $request->request_ids)
                ->where('product_id', $product->id)
                ->whereIn('status', [
                    RedemptionRequest::STATUS_PENDING,
                    RedemptionRequest::STATUS_WAITING_LIST
                ])
                ->get();

            foreach ($redemptionRequests as $redemptionRequest) {
                $user = $redemptionRequest->user;
                $pointsRequired = $redemptionRequest->product->points_required;

                // 1) Pastikan baris UserPointSummary ada
                $userPointSummary = UserPointSummary::firstOrCreate(
                    ['user_id' => $user->id],
                    ['total_points' => 0, 'current_points' => 0]
                );

                // 2) Kembalikan poin user (current_points)
                $userPointSummary->increment('current_points', $pointsRequired);

                // 2a) Tambahkan log ke user_points (riwayat poin)
                $user->userPoints()->create([
                    'points'      => +$pointsRequired,
                    'description' => "Refund from rejected redemption of product: {$product->name}",
                ]);

                // 3) Kembalikan stok jika sebelumnya dikurangi (hanya jika status request tadinya PENDING dan produk READY)
                if ($redemptionRequest->status === RedemptionRequest::STATUS_PENDING && $product->status === 'ready') {
                    $product->increment('stock_quantity');
                }

                // 4) Tandai request -> REJECTED
                $redemptionRequest->update([
                    'status'     => RedemptionRequest::STATUS_REJECTED,
                    'updated_at' => now(),
                ]);

                // 5) Kirim notifikasi email
                $notificationMessage = "Your redemption request has been rejected. Points have been refunded to your account.";
                Mail::to($user->email)->send(new RedemptionRequestNotification(
                    $user,
                    $product,
                    'rejected',
                    $notificationMessage,
                    now()
                ));
            }

            DB::commit();

            return redirect()
                ->route('owner.products.waiting_list', $productCode)
                ->with('success', 'Selected requests have been rejected, and points have been refunded.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting redemption requests: ' . $e->getMessage(), [
                'product_code' => $productCode,
                'request_ids'  => $request->request_ids,
                'user_id'      => $owner->id,
            ]);

            return redirect()
                ->route('owner.products.waiting_list', $productCode)
                ->withErrors('An error occurred while rejecting the requests. Please try again.');
        }
    }
}

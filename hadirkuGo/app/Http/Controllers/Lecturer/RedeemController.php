<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RedemptionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\UserPointSummary;
use App\Models\UserPoint;
use Illuminate\Support\Facades\DB;

class RedeemController extends Controller
{
    /**
     * Display a list of available products for redemption and redemption history.
     */
    public function index()
    {
        $student = Auth::user();

        // Fetch the businesses the student is associated with
        $teamIds = $student->teams()->pluck('id')->toArray();
        $businessIds = Team::whereIn('id', $teamIds)->pluck('business_id')->toArray();

        // Fetch paginated products and eager load business relation
        $products = Product::with('business')
            ->whereIn('status', ['ready', 'waiting_list'])
            ->where('stock_quantity', '>', 0)
            ->whereIn('business_id', $businessIds)
            ->paginate(12); // 12 products per page

        // Fetch all redemption requests made by the student related to these products
        $redemptionRequests = RedemptionRequest::where('user_id', $student->id)
            ->whereIn('product_id', $products->pluck('id')->toArray())
            ->get()
            ->keyBy('product_id');

        // Fetch categorized redemption requests made by the student
        $waitingList = RedemptionRequest::where('user_id', $student->id)
            ->where('status', RedemptionRequest::STATUS_WAITING_LIST)
            ->orderBy('requested_at', 'desc')
            ->paginate(10, ['*'], 'waiting');

        $pendingList = RedemptionRequest::where('user_id', $student->id)
            ->where('status', RedemptionRequest::STATUS_PENDING)
            ->orderBy('requested_at', 'desc')
            ->paginate(10, ['*'], 'pending');

        $approvedList = RedemptionRequest::where('user_id', $student->id)
            ->where('status', RedemptionRequest::STATUS_APPROVED)
            ->orderBy('requested_at', 'desc')
            ->paginate(10, ['*'], 'approved');

        $rejectedList = RedemptionRequest::where('user_id', $student->id)
            ->where('status', RedemptionRequest::STATUS_REJECTED)
            ->orderBy('requested_at', 'desc')
            ->paginate(10, ['*'], 'rejected');

        // Fetch total points
        $totalPoints = UserPointSummary::where('user_id', $student->id)->value('total_points') ?? 0;

        // Fetch the student's current points
        $currentPoints = UserPointSummary::where('user_id', $student->id)->value('current_points') ?? 0;

        // Fetch the history of points used for redemption
        $pointHistory = UserPoint::where('user_id', $student->id)
            ->where(function ($query) {
                $query->where('description', 'like', '%Redeemed product:%')
                    ->orWhere('description', 'like', '%Request approved%')
                    ->orWhere('description', 'like', '%Request rejected%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch all redemption requests made by the student
        $allRedemptionRequests = RedemptionRequest::with('product')
            ->where('user_id', $student->id)
            ->orderBy('requested_at', 'desc')
            ->get();

        // Send all variables to the view
        return view('lecturer.redeem.index', compact(
            'products',
            'redemptionRequests',
            'waitingList',
            'pendingList',
            'approvedList',
            'rejectedList',
            'currentPoints',
            'pointHistory',
            'allRedemptionRequests',
            'totalPoints'

        ));
    }

    /**
     * Handle the redemption request from a student.
     */
    public function store(Request $request, $productCode)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($user, $productCode) {
            // Fetch the product with lock
            $product = Product::with('business.teams.members')
                ->where('product_code', $productCode)
                ->lockForUpdate()
                ->firstOrFail();

            // Check if stock is available for ready products
            if ($product->status === 'ready' && $product->stock_quantity <= 0) {
                return redirect()->back()->withErrors(['error' => 'Product is out of stock.']);
            }

            // Check if the user is authorized to redeem the product
            $isAuthorized = $product->business->teams()->whereHas('members', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->exists();

            // Additional authorization for leaders, staff, and lecturers
            $isLeaderOrStaffOrLecturer = $product->business->staff()->where('user_id', $user->id)->exists() ||
                $product->business->lecturers()->where('users.id', $user->id)->exists();

            if (!$isAuthorized && !$isLeaderOrStaffOrLecturer) {
                return redirect()->back()->withErrors(['error' => 'You are not authorized to redeem this product.']);
            }

            // Check if the user has enough current points (with lock)
            $summary = UserPointSummary::where('user_id', $user->id)->lockForUpdate()->first();
            $currentPoints = $summary ? $summary->current_points : 0;

            if ($currentPoints < $product->points_required) {
                return redirect()->back()->withErrors(['error' => 'You do not have enough points to redeem this product.']);
            }

            // Check if the user has already requested this product
            $existingRequest = RedemptionRequest::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->latest('requested_at')
                ->first();

            if ($existingRequest) {
                if (in_array($existingRequest->status, [RedemptionRequest::STATUS_PENDING, RedemptionRequest::STATUS_WAITING_LIST])) {
                    return redirect()->route('lecturer.redeem.index')
                        ->with('info', 'You have already requested this product and it is being processed.');
                }
            }

            // Handle waiting list
            $status = $product->status === 'waiting_list' ? RedemptionRequest::STATUS_WAITING_LIST : RedemptionRequest::STATUS_PENDING;

            // Create a new redemption request
            RedemptionRequest::create([
                'user_id'      => $user->id,
                'product_id'   => $product->id,
                'status'       => $status,
                'requested_at' => now(),
            ]);

            // Deduct points from the user's current points
            $user->userPoints()->create([
                'points'      => -$product->points_required,
                'description' => "Redeemed product: {$product->name}",
            ]);

            // Update current points in the summary
            if ($summary) {
                $summary->decrement('current_points', $product->points_required);
            }

            // Update stock only if the product is ready
            if ($product->status === 'ready') {
                $product->decrement('stock_quantity');
            }

            return redirect()->route('lecturer.redeem.index')->with('success', 'Redemption request submitted successfully!');
        });
    }
}

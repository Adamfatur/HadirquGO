<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\QrScannerController;
use App\Http\Controllers\Public\PublicViewboardController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\Lecturer\DailyCheckinController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\Lecturer\RewardController;
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\TestingDbController;
use App\Http\Controllers\TestQuestionController;
use App\Http\Controllers\Lecturer\StudentEvaluationController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route untuk memberikan hadiah secara acak (menggunakan GET)
Route::get('/users/{userId}/give-reward', [RewardController::class, 'giveRandomReward']);

// Route untuk melihat daftar hadiah yang diterima oleh user
Route::get('/users/{userId}/rewards', [RewardController::class, 'getUserRewards']);

Route::get('/public-viewboard', [PublicViewboardController::class, 'index']);
Route::get('/public-viewboard/api', [PublicViewboardController::class, 'api']);
Route::get('/check-user-levels', [UserLevelController::class, 'checkUserLevels']);
// Route GET untuk memeriksa dan memberikan poin
Route::get('/check-all-users', [DailyCheckinController::class, 'checkAllUsers']);
Route::get('/sync-all-user-points', [UserPointController::class, 'syncAllUserPoints']);

Route::post('scanner/{location_id}/process', [QrScannerController::class, 'processAttendance'])
    ->name('qr_scanner.process');

Route::post('scanner/{location_id}/processCheckoutCheckin', [QrScannerController::class, 'processCheckoutCheckin'])
    ->name('api.scanner.checkout_checkin');

Route::get('/businesses/{business_unique_id}/member', [StaffController::class, 'index'])->name('owner.staff.index');

Route::get('/user/{userId}/points', [TestingDbController::class, 'getUserPointSummary']);

Route::get('/lecturer/evaluate/by-member-id/{member_id}', [StudentEvaluationController::class, 'evaluateByMemberId'])
    ->name('lecturer.evaluate.by-member-id');


// Route untuk mendapatkan 20 pertanyaan acak
// Untuk format teks
//Route::get('/test-questions/text', [TestQuestionController::class, 'getRandomQuestionsText']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Owner\BusinessManagementController;
use App\Http\Controllers\Lecturer\TeamController;
use App\Http\Controllers\Lecturer\AttendanceController;
use App\Http\Controllers\Lecturer\QrCodeController as LecturerQrCodeController;
//lecturer dashboard
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\AttendanceHistoryController as LecturerAttendanceHistoryController;
use App\Http\Controllers\Lecturer\ProfileController as LecturerProfileController;
use App\Http\Controllers\Lecturer\CalendarController as LecturerCalendarController;
use App\Http\Controllers\Lecturer\LeaderboardController as LecturerLeaderboardController;
use App\Http\Controllers\Lecturer\PointController as LecturerPointController;
use App\Http\Controllers\Student\TeamController as StudentTeamController;
use App\Http\Controllers\Lecturer\ViewboardController as LecturerViewboardController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ViewboardController;
use App\Http\Controllers\Lecturer\StudentEvaluationController;


//owner attendance
use App\Http\Controllers\Owner\AttendanceLocationController;
use App\Http\Controllers\Owner\QrScannerController;
use App\Http\Controllers\Student\QrCodeController;
use App\Http\Controllers\Student\AttendanceHistoryController;
use App\Http\Controllers\Student\PointController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\LeaderboardController as StudentLeaderboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Student\CalendarController;
use App\Http\Controllers\Student\UserLevelController;
use App\Http\Controllers\Student\StatisticsController;
use App\Http\Controllers\Student\AchievementController;
use App\Http\Controllers\Lecturer\AchievementController as LecturerAchievementController;
use App\Http\Controllers\Lecturer\StatisticsController as  LecturerStatisticsController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Student\RedeemController;
use App\Http\Controllers\Lecturer\RedeemController as LecturerRedeemController;
use App\Http\Controllers\Student\ChallengeController;
use App\Http\Controllers\Lecturer\UserController;
use App\Http\Controllers\Lecturer\TeamMemberRankingController;
use App\Http\Controllers\Lecturer\TestimonyController; // Sesuaikan path controller
use App\Http\Controllers\Lecturer\RewardController;
use App\Http\Controllers\Student\RewardController as StudentRewardController;
use App\Http\Controllers\Student\TestimonyController as StudentTestimonyController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Lecturer\LecturerAttendanceController as LecturerAttendanceController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Owner\QuizController as OwnerQuizController;
use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;
use App\Http\Controllers\Owner\QuestionController;
//staffcontroller
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\Owner\BannerController;
use App\Http\Controllers\Owner\SuperQuizController;
use App\Http\Controllers\Lecturer\SuperQuizController as LecturerSuperQuizController;
use App\Http\Controllers\Student\SuperQuizController as StudentSuperQuizController;
use App\Http\Controllers\PublicPages\TeamAttendanceRecapController;


//RankingController
use App\Http\Controllers\RankingController;
use App\Http\Controllers\Student\RankingController as StudentRankingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//route to layout.test
Route::get('/layout', function () {
    return view('layout.test');
});

// Route Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Route untuk mengambil statistik attendance berdasarkan member_id
Route::get('/share/journey/{memberId}', [JourneyController::class, 'getAttendanceStatsByMemberId'])->name('journey.stats');
Route::get('/leaderboard-2025', [LeaderboardController::class, 'index2025'])->name('leaderboard.2025');
Route::get('/rankings/snapshot/daily', [RankingController::class, 'storeDailySnapshot']);

// Public team attendance recap
Route::get('/public/teams/{team_unique_id}/attendance', [TeamAttendanceRecapController::class, 'show'])
    ->name('public.team.attendance.recap');

// Social Authentication Routes
Route::get('auth/google', [SocialController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
Route::post('/logout', [SocialController::class, 'logout'])->name('logout');

// Form login (GET)
Route::get('/qr-scanner-login', [QrScannerController::class, 'showLoginForm'])->name('qr_scanner.login_form');

// Proses login (POST)
Route::post('/qr-scanner-login', [QrScannerController::class, 'loginViaUniqueId'])->name('qr_scanner.login_process');

// Halaman scanner (GET) khusus untuk user yang sudah 'login'
Route::get('/qr-scanner/{unique_id}', [QrScannerController::class, 'showScannerByUniqueId'])
    ->name('qr_scanner.show_by_unique_id');

// Role-based dashboard routes with middleware for role restriction
Route::middleware('auth')->group(function () {

    // Feedback Routes
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/feedback/{feedback}/like', [App\Http\Controllers\FeedbackController::class, 'toggleLike'])->name('feedback.like');
    Route::patch('/feedback/{feedback}/status', [App\Http\Controllers\FeedbackController::class, 'updateStatus'])->name('feedback.status.update');
    Route::put('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('feedback.destroy');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Admin dashboard and user management routes
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin');
        })->name('admin.dashboard');

        // User management routes for admin
        Route::get('users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('users/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Owner dashboard and business management routes
    Route::prefix('owner')->middleware(['auth', 'role:Owner'])->group(function () {

        // Dashboard Route
        Route::get('/dashboard', function () {
            return view('dashboard.owner');
        })->name('owner.dashboard');

        // Business Management Routes
        Route::get('/businesses', [BusinessManagementController::class, 'index'])->name('owner.businesses.index');
        Route::get('/businesses/create', [BusinessManagementController::class, 'create'])->name('owner.businesses.create');
        Route::post('/businesses', [BusinessManagementController::class, 'store'])->name('owner.businesses.store');
        Route::get('/businesses/{id}', [BusinessManagementController::class, 'show'])->name('owner.businesses.show');
        Route::get('/businesses/{id}/edit', [BusinessManagementController::class, 'edit'])->name('owner.businesses.edit');
        Route::put('/businesses/{id}', [BusinessManagementController::class, 'update'])->name('owner.businesses.update');
        Route::delete('/businesses/{id}', [BusinessManagementController::class, 'destroy'])->name('owner.businesses.destroy');
        Route::get('/businesses/{business_unique_id}/manage', [BusinessManagementController::class, 'manage'])->name('owner.businesses.manage');
        Route::get('/businesses/go/search-lecturers/', [BusinessManagementController::class, 'searchLecturers'])->name('owner.businesses.searchLecturers');
        Route::post('/businesses/{business_unique_id}/add-staff', [BusinessManagementController::class, 'addStaff'])->name('owner.businesses.addStaff');
        Route::delete('/businesses/{business_unique_id}/remove-staff/{staff_id}', [BusinessManagementController::class, 'removeStaff'])->name('owner.businesses.removeStaff');
        Route::post('/businesses/{business_unique_id}/addTeam', [BusinessManagementController::class, 'addTeam'])->name('owner.businesses.addTeam');
        Route::delete('/businesses/{business_unique_id}/teams/{team_id}', [BusinessManagementController::class, 'deleteTeam'])->name('owner.businesses.deleteTeam');


        // Attendance Location Management Routes
        Route::get('businesses/{business_unique_id}/attendance_locations', [AttendanceLocationController::class, 'index'])->name('owner.attendance_locations.index');
        Route::get('businesses/{business_unique_id}/attendance_locations/create', [AttendanceLocationController::class, 'create'])->name('owner.attendance_locations.create');
        Route::post('businesses/{business_unique_id}/attendance_locations', [AttendanceLocationController::class, 'store'])->name('owner.attendance_locations.store');
        Route::get('businesses/{business_unique_id}/attendance_locations/{location}/edit', [AttendanceLocationController::class, 'edit'])->name('owner.attendance_locations.edit');
        Route::put('businesses/{business_unique_id}/attendance_locations/{location}', [AttendanceLocationController::class, 'update'])->name('owner.attendance_locations.update');
        Route::delete('businesses/{business_unique_id}/attendance_locations/{location}', [AttendanceLocationController::class, 'destroy'])->name('owner.attendance_locations.destroy');

        Route::get('{business_unique_id}/qr-scanner/{slug}/{unique_id}', [QrScannerController::class, 'showScanner'])->name('owner.qr_scanner.show');
        Route::get('scanner/{business_unique_id}/{slug}/{unique_id}', [QrScannerController::class, 'showScanner'])->name('qr_scanner.show');
        Route::post('scanner/{location_id}/process', [QrScannerController::class, 'processAttendance'])->name('qr_scanner.process');

        Route::get('/qr-scanner/{business_unique_id}/{slug}/{unique_id}', [QrScannerController::class, 'showOutIn'])
            ->name('qr_scanner.showoutin');


        // Product Routes
        Route::get('products', [ProductController::class, 'index'])->name('owner.products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('owner.products.create');
        Route::post('products', [ProductController::class, 'store'])->name('owner.products.store');
        Route::get('products/{product_code}/edit', [ProductController::class, 'edit'])->name('owner.products.edit');
        Route::put('products/{product_code}', [ProductController::class, 'update'])->name('owner.products.update');
        Route::delete('products/{product_code}', [ProductController::class, 'destroy'])->name('owner.products.destroy');

        // Waiting List Routes
        Route::get('products/{product_code}/waiting-list', [ProductController::class, 'showWaitingList'])->name('owner.products.waiting_list');
        Route::post('products/{product_code}/approve-requests', [ProductController::class, 'approveRequests'])->name('owner.products.approve_requests');
        Route::post('products/{product_code}/reject-requests', [ProductController::class, 'rejectRequests'])->name('owner.products.reject_requests');
        Route::post('products/{product_code}/bulk-approve', [ProductController::class, 'bulkApprove'])->name('owner.products.bulk_approve');
        Route::post('products/{product_code}/bulk-reject', [ProductController::class, 'bulkReject'])->name('owner.products.bulk_reject');

        // Menambahkan manager
        Route::post('/businesses/{business_unique_id}/teams/{team_id}/add-manager',
            [BusinessManagementController::class, 'addManager']
        )->name('owner.businesses.addManager');

// Menghapus manager
        Route::delete('/businesses/{business_unique_id}/teams/{team_id}/remove-manager/{user_id}',
            [BusinessManagementController::class, 'removeManager']
        )->name('owner.businesses.removeManager');


        // -------------------------
// QUIS
// -------------------------
        Route::get('/businesses/{business_unique_id}/quizzes', [OwnerQuizController::class, 'index'])
            ->name('owner.quizzes.index');

        Route::get('/businesses/{business_unique_id}/quizzes/create', [OwnerQuizController::class, 'create'])
            ->name('owner.quizzes.create');

        Route::post('/businesses/{business_unique_id}/quizzes', [OwnerQuizController::class, 'store'])
            ->name('owner.quizzes.store');

        Route::get('/businesses/{business_unique_id}/quizzes/{quiz}', [OwnerQuizController::class, 'show'])
            ->name('owner.quizzes.show');

        Route::get('/businesses/{business_unique_id}/quizzes/{quiz}/edit', [OwnerQuizController::class, 'edit'])
            ->name('owner.quizzes.edit');

        Route::put('/businesses/{business_unique_id}/quizzes/{quiz}', [OwnerQuizController::class, 'update'])
            ->name('owner.quizzes.update');

        Route::delete('/businesses/{business_unique_id}/quizzes/{quiz}', [OwnerQuizController::class, 'destroy'])
            ->name('owner.quizzes.destroy');

// -------------------------
// QUESTION
// -------------------------
        Route::get('/businesses/{business_unique_id}/quizzes/{quiz}/questions/create',
            [QuestionController::class, 'create'])->name('owner.questions.create');

        Route::post('/businesses/{business_unique_id}/quizzes/{quiz}/questions',
            [QuestionController::class, 'store'])->name('owner.questions.store');

        Route::get('/businesses/{business_unique_id}/quizzes/{quiz}/questions/{question}/edit',
            [QuestionController::class, 'edit'])->name('owner.questions.edit');

        Route::put('/businesses/{business_unique_id}/quizzes/{quiz}/questions/{question}',
            [QuestionController::class, 'update'])->name('owner.questions.update');

        Route::delete('/businesses/{business_unique_id}/quizzes/{quiz}/questions/{question}',
            [QuestionController::class, 'destroy'])->name('owner.pertanyaan.destroy');

        Route::get('/businesses/{business_unique_id}/members', [StaffController::class, 'index'])->name('owner.member.index');

        // List all SuperQuizzes for a specific business
        Route::get('business/{business_unique_id}/superquizzes', [SuperQuizController::class, 'index'])
            ->name('superquizzes.index');

        // Show the form to create a new SuperQuiz
        Route::get('business/{business_unique_id}/superquizzes/create', [SuperQuizController::class, 'create'])
            ->name('superquizzes.create');

        // Store the newly created SuperQuiz
        Route::post('business/{business_unique_id}/superquizzes', [SuperQuizController::class, 'store'])
            ->name('superquizzes.store');

        // Show the details of a specific SuperQuiz
        Route::get('business/{business_unique_id}/superquizzes/{superQuiz}', [SuperQuizController::class, 'show'])
            ->name('superquizzes.show');

        // Show the form to edit a specific SuperQuiz
        Route::get('business/{business_unique_id}/superquizzes/{superQuiz}/edit', [SuperQuizController::class, 'edit'])
            ->name('superquizzes.edit');

        // Update a specific SuperQuiz
        Route::put('business/{business_unique_id}/superquizzes/{superQuiz}', [SuperQuizController::class, 'update'])
            ->name('superquizzes.update');

        // Delete a specific SuperQuiz
        Route::delete('business/{business_unique_id}/superquizzes/{superQuiz}', [SuperQuizController::class, 'destroy'])
            ->name('superquizzes.destroy');

        // Routes for Managing Questions
        Route::get('business/{business_unique_id}/superquizzes/{unique_id}/questions/create', [SuperQuizController::class, 'createQuestion'])->name('questions.create');
        Route::post('business/{business_unique_id}/superquizzes/{unique_id}/questions', [SuperQuizController::class, 'storeQuestion'])->name('questions.store');
        Route::get('business/{business_unique_id}/superquizzes/{unique_id}/questions/{question_id}/edit', [SuperQuizController::class, 'editQuestion'])->name('questions.edit');
        Route::put('business/{business_unique_id}/superquizzes/{unique_id}/questions/{question_id}', [SuperQuizController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('business/{business_unique_id}/superquizzes/{unique_id}/questions/{question_id}', [SuperQuizController::class, 'destroyQuestion'])->name('questions.destroy');

        Route::group(['prefix' => 'businesses/{business_unique_id}'], function () {
            Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
            Route::get('/banners/create', [BannerController::class, 'createBanner'])->name('banners.create');
            Route::post('/banners', [BannerController::class, 'storeBanner'])->name('banners.store');
            Route::get('/banners/{banner}/edit', [BannerController::class, 'editBanner'])->name('banners.edit');
            Route::put('/banners/{banner}', [BannerController::class, 'updateBanner'])->name('banners.update');
            Route::delete('/banners/{banner}', [BannerController::class, 'destroyBanner'])->name('banners.destroy');
            Route::post('/banners/{banner}/toggle-status', [BannerController::class, 'toggleBannerStatus'])->name('banners.toggleStatus');
        });

    });



    Route::middleware(['auth', 'role:Lecturer'])->prefix('lecturer')->group(function () {
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('lecturer.dashboard');
        Route::post('/dashboard/update-rival', [LecturerDashboardController::class, 'updateRival'])->name('lecturer.dashboard.updateRival');
        Route::get("/dashboard/search-rivals", [LecturerDashboardController::class, "searchRivals"])->name("lecturer.dashboard.searchRivals");

        Route::get('/teams', [TeamController::class, 'index'])->name('lecturer.teams.index');
        Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember'])->name('lecturer.teams.addMember');
        Route::patch('/teams/{team}/update-name', [TeamController::class, 'updateTeamName'])->name('lecturer.teams.updateName');
        Route::delete('/teams/{team}/remove-member/{user}', [TeamController::class, 'removeMember'])->name('lecturer.teams.removeMember');
        Route::get('/teams/search-members', [TeamController::class, 'searchMembers'])->name('lecturer.teams.searchMembers');
        Route::patch('/teams/{team}/transfer-leader', [TeamController::class, 'transferLeader'])->name('lecturer.teams.transferLeader');
        Route::delete('/teams/{team}/dissolve', [TeamController::class, 'dissolveTeam'])->name('lecturer.teams.dissolve');


        // Attendance Routes
        Route::get('teams/{team_unique_id}/attendance/custom', [AttendanceController::class, 'custom'])->name('lecturer.attendance.custom');
        Route::get('teams/{team_unique_id}/attendance', [AttendanceController::class, 'index'])->name('lecturer.attendance.index');
        Route::get('app/teams/{team_unique_id}/attendance', [AttendanceController::class, 'mobileindex'])->name('lecturer.attendance.mobileindex');
        Route::get('/attendance/details/{memberId}/{date}', [AttendanceController::class, 'getAttendanceDetails'])->name('lecturer.attendance.details');
        Route::get('teams/{team_unique_id}/attendance/pdf', [AttendanceController::class, 'downloadPdf'])->name('lecturer.attendance.pdf');
        Route::get('/teams/{teamUniqueId}/attendance/csv', [AttendanceController::class, 'downloadCsv'])->name('lecturer.attendance.csv');
        Route::get('/attendance/{teamUniqueId}/date/{date}', [AttendanceController::class, 'getAttendanceByDate'])->name('lecturer.attendance.by-date');

        // Jika Check-In tidak lagi butuh POST karena tidak ada form input
        Route::get('/attendance/check-in', [LecturerQrCodeController::class, 'checkIn'])->name('lecturer.qrcode.checkin');
        // Check-Out tetap GET seperti sebelumnya
        Route::get('/attendance/check-out', [LecturerQrCodeController::class, 'checkOut'])->name('lecturer.qrcode.checkout');
        Route::get('/attendance/qrcode/check-active-checkin-token', [LecturerQrCodeController::class, 'checkActiveCheckinToken'])->name('lecturer.qrcode.checkActiveCheckinToken');
        Route::get('/attendance/qrcode/check-active-checkout-token', [LecturerQrCodeController::class, 'checkActiveCheckoutToken'])->name('lecturer.qrcode.checkActiveCheckoutToken');
//        Route::get('/attendance/success', [LecturerQrCodeController::class, 'success'])->name('lecturer.attendance.success');
        // Route untuk halaman success attendance
        Route::get('/attendance/success', [TestimonyController::class, 'success'])->name('lecturer.attendance.success');

        Route::get('/dashboard/team-activities', [LecturerDashboardController::class, 'fetchTeamActivities'])
            ->name('lecturer.team.activities');

        // Attendance History
        Route::get('/attendance-history', [LecturerAttendanceHistoryController::class, 'index'])
            ->name('lecturer.attendance.history');

        Route::get('profile', [LecturerProfileController::class, 'show'])->name('lecturer.profile.show');
        Route::get('profile/edit', [LecturerProfileController::class, 'edit'])->name('lecturer.profile.edit');
        Route::post('profile', [LecturerProfileController::class, 'update'])->name('lecturer.profile.update');
        // Rute untuk memperbarui tanggal lahir
        Route::post('/profile/update-birthdate', [LecturerProfileController::class, 'updateBirthDate'])->name('lecturer.profile.updateBirthDate');
        Route::post('/profile/update-name', [LecturerProfileController::class, 'updateName'])->name('lecturer.profile.updateName');

        Route::get('/calendar', [LecturerCalendarController::class, 'showCalendar'])->name('lecturer.calendar');
        Route::get('/attendance/details/{date}', [LecturerCalendarController::class, 'getAttendanceDetails']);

        Route::get('/leaderboard', [LecturerLeaderboardController::class, 'index'])->name('lecturer.leaderboard.index');
        Route::get('/leaderboard/team', [LecturerLeaderboardController::class, 'teamRanking'])->name('lecturer.leaderboard.team');

        Route::get('/points', [LecturerPointController::class, 'index'])->name('lecturer.points.index');

        Route::get('/achievements', [LecturerAchievementController::class, 'index'])->name('lecturer.achievements.index');

        // Route untuk mendapatkan detail pencapaian
        Route::get('/achievements/{achievementId}/details', [LecturerAchievementController::class, 'showDetails'])->name('lecturer.achievements.details');

        Route::get('/statistics', [LecturerStatisticsController::class, 'index'])->name('lecturer.statistics.index');

        Route::get('/viewboard', [LecturerViewboardController::class, 'index'])->name('lecturer.viewboard.index');
        Route::get('/top-sessions', [LecturerViewboardController::class, 'topSessions'])->name('lecturer.viewboard.top-sessions');
        Route::get('/top-duration', [LecturerViewboardController::class, 'topDuration'])->name('lecturer.viewboard.top-duration');
        Route::get('/top-locations', [LecturerViewboardController::class, 'topLocations'])->name('lecturer.viewboard.top-locations');
        Route::get('/top-points', [LecturerViewboardController::class, 'topPoints'])->name('lecturer.viewboard.top-points');
        Route::get('/top-levels', [LecturerViewboardController::class, 'topLevels'])->name('lecturer.viewboard.top-levels');
        Route::get('/top-teams', [LecturerViewboardController::class, 'topTeams'])->name('lecturer.viewboard.top-teams');
        Route::get('/viewboard/search', [LecturerViewboardController::class, 'searchRanking'])->name('lecturer.viewboard.search');

        Route::get('/redeem', [LecturerRedeemController::class, 'index'])->name('lecturer.redeem.index');
        Route::post('/redeem/{productId}', [LecturerRedeemController::class, 'store'])->name('lecturer.redeem.store');

        Route::get('/notifications', [LecturerDashboardController::class, 'fetchNotifications'])->name('lecturer.notifications.fetch');

        // Route to display the list of users and their teams
        Route::get('/users', [UserController::class, 'index'])->name('lecturer.users.index');

        Route::get('/teams-rank', [TeamMemberRankingController::class, 'index'])->name('lecturer.teamsrank.index');
        // Route untuk halaman details ranking member dalam tim
        Route::get('/teams-rank/{team_unique_id}/member-rankings', [TeamMemberRankingController::class, 'show'])->name('lecturer.teamsrank.member-rankings');

        //TESTIMONI
        // Route untuk menampilkan daftar testimoni
        Route::get('/testimonies', [TestimonyController::class, 'index'])->name('lecturer.testimonies.index');
        // Route untuk menampilkan form tambah testimoni
        Route::get('/testimonies/create', [TestimonyController::class, 'create'])->name('lecturer.testimonies.create');
        // Route untuk menyimpan testimoni baru
        Route::post('/testimonies', [TestimonyController::class, 'store'])->name('lecturer.testimonies.store');
        // Route untuk menampilkan detail testimoni
        Route::get('/testimonies/{id}', [TestimonyController::class, 'show'])->name('lecturer.testimonies.show');
        // Route untuk menampilkan form edit testimoni
        Route::get('/testimonies/{id}/edit', [TestimonyController::class, 'edit'])->name('lecturer.testimonies.edit');
        // Route untuk mengupdate testimoni
        Route::put('/testimonies/{id}', [TestimonyController::class, 'update'])->name('lecturer.testimonies.update');
        // Route untuk menghapus testimoni
        Route::delete('/testimonies/{id}', [TestimonyController::class, 'destroy'])->name('lecturer.testimonies.destroy');
        // Route untuk menampilkan testimoni berdasarkan user tertentu
        Route::get('/users/{userId}/testimonies', [TestimonyController::class, 'getUserTestimonies'])->name('lecturer.users.testimonies');

        // Route untuk halaman testing reward
        Route::get('/reward/testing/{userId}', [RewardController::class, 'testing'])
            ->name('lecturer.reward.testing');

        // Route untuk menampilkan hasil pemberian hadiah
        Route::get('/reward/show/{userId}', [RewardController::class, 'showRewardResult'])
            ->name('lecturer.reward.show');

        // Route untuk menyimpan testimoni baru
        Route::post('/testimonies/store', [RewardController::class, 'storeTestimony'])
            ->name('lecturer.testimonies.store');

        // Route untuk mengupdate testimoni yang sudah ada
        Route::put('/testimonies/update/{id}', [RewardController::class, 'updateTestimony'])
            ->name('lecturer.testimonies.update');

        Route::get('/reward/spin/{userId}', [RewardController::class, 'showSpinPage'])->name('lecturer.reward.spin');
        Route::post('/reward/process-spin/{userId}', [RewardController::class, 'processSpin'])->name('lecturer.reward.processSpin');

        // Route untuk mengambil statistik attendance berdasarkan member_id
        Route::get('/journey/{memberId}', [LecturerAttendanceController::class, 'getAttendanceStatsByMemberId'])->name('lecturer.attendance.stats');

        Route::get('/quizzes', [LecturerQuizController::class, 'index'])->name('lecturer.quizzes.index');
        Route::get('/quizzes/{uniqueId}', [LecturerQuizController::class, 'show'])->name('lecturer.quizzes.show'); // Perubahan di sini
        Route::post('/quizzes/{uniqueId}', [LecturerQuizController::class, 'store'])->name('lecturer.quizzes.store'); // Perubahan di sini
        Route::get('/quizzes/result/{uniqueId}', [LecturerQuizController::class, 'result'])->name('lecturer.quizzes.result');



        // Route untuk menampilkan daftar Super Quiz yang aktif
        Route::get('/superquizzes', [LecturerSuperQuizController::class, 'index'])->name('lecturer.superquiz.index');

        // Route untuk menampilkan detail Super Quiz dan memulai quiz (halaman sebelum soal pertama)
        Route::get('/superquizzes/{superQuiz}', [LecturerSuperQuizController::class, 'show'])->name('lecturer.superquiz.show');

        // Route untuk menampilkan soal quiz tertentu (berdasarkan nomor soal)
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}', [LecturerSuperQuizController::class, 'showQuestion'])->name('lecturer.superquiz.question');

        // Route untuk mengirimkan jawaban soal
        Route::post('/superquizzes/{superQuiz}/question/{questionNumber}/submit', [LecturerSuperQuizController::class, 'submitAnswer'])->name('lecturer.superquiz.submitAnswer');

        // Pastikan ini berada di file routes/web.php
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/confirmation', [LecturerSuperQuizController::class, 'showConfirmation'])->name('lecturer.superquiz.confirmation');

        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/failed', [LecturerSuperQuizController::class, 'showFailed'])->name('lecturer.superquiz.failed');

        Route::get('/superquizzes/{superQuiz}/result', [LecturerSuperQuizController::class, 'viewResult'])->name('lecturer.superquiz.viewResult');

        // Route untuk menangani timeout dan mengubah status attempt menjadi gagal
        Route::post('/superquiz/{superQuiz}/timeoutAttempt', [LecturerSuperQuizController::class, 'timeoutAttempt'])
            ->name('lecturer.superquiz.timeoutAttempt');

        // Route untuk menyerah dari quiz
        Route::post('/superquizzes/{superQuiz}/surrender', [LecturerSuperQuizController::class, 'surrenderQuiz'])->name('lecturer.superquiz.surrenderQuiz');

        // Route untuk pengecekan timeout jawaban (AJAX)
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/check-timeout', [LecturerSuperQuizController::class, 'checkAnswerTimeout'])->name('lecturer.superquiz.checkAnswerTimeout');

        Route::get('/lecturer/evaluate/by-member-id/{member_id}', [StudentEvaluationController::class, 'evaluateByMemberId'])
            ->name('lecturer.evaluate.by-member-id');

        Route::get('/evaluation/{member_id}', [StudentEvaluationController::class, 'evaluateByMemberId'])
            ->name('lecturer.evaluation.show'); // Memberi nama route adalah praktik yang baik


    });



    // Parent dashboard
    Route::get('/parent/dashboard', function () {
        return view('dashboard.parent');
    })->name('parent.dashboard')->middleware('role:Parent');

    Route::prefix('student')->middleware(['auth', 'role:Student'])->group(function () {
        // Student dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::get("/dashboard/search-rivals", [DashboardController::class, "searchRivals"])->name("student.dashboard.searchRivals");
        Route::post('/dashboard/update-rival', [DashboardController::class, 'updateRival'])->name('student.dashboard.updateRival');

        // Student teams
        Route::get('/teams', [StudentTeamController::class, 'index'])->name('student.teams.index');
        Route::get('/teams/{teamId}/members', [StudentTeamController::class, 'paginateMembers'])->name('student.teams.paginateMembers');
        Route::get('/teams/{team_unique_id}/attendance', [AttendanceController::class, 'index'])->name('student.attendance.index');
        Route::get('/attendance/details/{memberId}/{date}', [AttendanceController::class, 'getAttendanceDetails'])->name('student.attendance.details');
        Route::get('/attendance/{teamUniqueId}/date/{date}', [AttendanceController::class, 'getAttendanceByDate'])->name('student.attendance.by-date');
        Route::get('/teams/{team_unique_id}/attendance/pdf', [AttendanceController::class, 'downloadPdf'])->name('student.attendance.pdf');
        Route::get('/teams/{teamUniqueId}/attendance/csv', [AttendanceController::class, 'downloadCsv'])->name('student.attendance.csv');
        Route::get('/teams/{team_unique_id}/attendance/custom', [AttendanceController::class, 'custom'])->name('student.attendance.custom');

        Route::get('/teams/{teamUniqueId}', [StudentTeamController::class, 'show'])->name('student.teams.show');

        // Evaluation
        Route::get('/evaluation/{member_id}', [StudentEvaluationController::class, 'evaluateByMemberId'])
            ->name('student.evaluation.show');

        // Check-in and Check-out with team selection
        Route::get('/checkin', [QrCodeController::class, 'checkIn'])->name('qrcode.checkIn');
        Route::get('/checkout', [QrCodeController::class, 'checkOut'])->name('qrcode.checkout');

        // Attendance history
        Route::get('/attendance-history', [AttendanceHistoryController::class, 'index'])
            ->name('student.attendance.history');

        // Daily attendance summary
        Route::get('/attendance-summary', [AttendanceHistoryController::class, 'dailySummary'])
            ->name('student.attendance.dailySummary');

        Route::get('/points', [PointController::class, 'index'])->name('student.points.index');

        Route::get('profile', [ProfileController::class, 'show'])->name('student.profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('student.profile.edit');
        Route::post('profile', [ProfileController::class, 'update'])->name('student.profile.update');

        Route::get('/leaderboard', [StudentLeaderboardController::class, 'index'])->name('student.leaderboard.index');
        Route::get('/leaderboard/team', [StudentLeaderboardController::class, 'teamRanking'])->name('student.leaderboard.team');

        Route::get('/calendar', [CalendarController::class, 'showCalendar'])->name('student.calendar');
        Route::get('/attendance/details/{date}', [CalendarController::class, 'getAttendanceDetails']);

        Route::get('/user/level', [UserLevelController::class, 'show'])->name('user.level.show');
        Route::post('/user/level/update-points', [UserLevelController::class, 'updatePoints'])->name('user.level.update-points');
        Route::post('/user/level/reset', [UserLevelController::class, 'resetPoints'])->name('user.level.reset');

        Route::get('/statistics', [StatisticsController::class, 'index'])->name('student.statistics.index');

        Route::post('/profile/update-birthdate', [ProfileController::class, 'updateBirthDate'])->name('student.profile.updateBirthDate');
        Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->name('student.profile.updateName');

        Route::get('/achievements', [AchievementController::class, 'index'])->name('student.achievements.index');

        // Route untuk mendapatkan detail pencapaian
        Route::get('/achievements/{achievementId}/details', [AchievementController::class, 'showDetails'])->name('student.achievements.details');

        Route::get('/redeem', [RedeemController::class, 'index'])->name('student.redeem.index');
        Route::post('/redeem/{productId}', [RedeemController::class, 'store'])->name('student.redeem.store');

        Route::get('/dashboard/team-activities', [DashboardController::class, 'fetchTeamActivities'])
            ->name('student.team.activities');

        Route::get('/qrcode/check-active-checkin-token', [QrCodeController::class, 'checkActiveCheckinToken'])->name('student.qrcode.checkActiveCheckinToken');
        Route::get('/qrcode/check-active-checkout-token', [QrCodeController::class, 'checkActiveCheckoutToken'])->name('student.qrcode.checkActiveCheckoutToken');
//        Route::get('/attendance/success', [QrCodeController::class, 'success'])->name('student.attendance.success');

        Route::get('/viewboard', [ViewboardController::class, 'index'])->name('student.viewboard.index');
        Route::get('/top-sessions', [ViewboardController::class, 'topSessions'])->name('student.viewboard.top-sessions');
        Route::get('/top-duration', [ViewboardController::class, 'topDuration'])->name('student.viewboard.top-duration');
        Route::get('/top-locations', [ViewboardController::class, 'topLocations'])->name('student.viewboard.top-locations');
        Route::get('/top-points', [ViewboardController::class, 'topPoints'])->name('student.viewboard.top-points');
        Route::get('/top-levels', [ViewboardController::class, 'topLevels'])->name('student.viewboard.top-levels');
        Route::get('/viewboard/search', [ViewboardController::class, 'searchRanking'])->name('student.viewboard.search');

        Route::get('/levels/all', [UserLevelController::class, 'showAllLevels'])->name('student.levels.all');

        Route::get('/notifications', [DashboardController::class, 'fetchNotifications'])->name('notifications.fetch');

        Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
        Route::post('/challenges', [ChallengeController::class, 'createChallenge'])->name('challenges.create');
        Route::delete('/challenges/{challengeId}', [ChallengeController::class, 'deleteChallenge'])
            ->name('challenges.delete');

        // Route untuk halaman testing reward
        Route::get('/reward/testing/{userId}', [StudentRewardController::class, 'testing'])
            ->name('student.reward.testing');
        // Route untuk menampilkan hasil pemberian hadiah
        Route::get('/reward/show/{userId}', [StudentRewardController::class, 'showRewardResult'])
            ->name('student.reward.show');

// Route untuk menampilkan success
        Route::get('/attendance/success', [StudentTestimonyController::class, 'success'])
            ->name('student.attendance.success');
        // Route untuk menampilkan form tambah testimoni
        Route::get('/testimonies/create', [StudentTestimonyController::class, 'create'])->name('student.testimonies.create');
        // Route untuk menyimpan testimoni baru
        Route::post('/testimonies', [StudentTestimonyController::class, 'store'])->name('student.testimonies.store');
        // Route untuk menampilkan detail testimoni
        Route::get('/testimonies/{id}', [StudentTestimonyController::class, 'show'])->name('student.testimonies.show');
        // Route untuk menampilkan form edit testimoni
        Route::get('/testimonies/{id}/edit', [StudentTestimonyController::class, 'edit'])->name('student.testimonies.edit');
        // Route untuk mengupdate testimoni
        Route::put('/testimonies/{id}', [StudentTestimonyController::class, 'update'])->name('student.testimonies.update');

        // Route untuk mengambil statistik attendance berdasarkan member_id
        Route::get('/journey/{memberId}', [StudentAttendanceController::class, 'getAttendanceStatsByMemberId'])->name('student.attendance.stats');

        Route::get('/quizzes', [QuizController::class, 'index'])->name('student.quizzes.index');
        Route::get('/quizzes/{uniqueId}', [QuizController::class, 'show'])->name('student.quizzes.show'); // Perubahan di sini
        Route::post('/quizzes/{uniqueId}', [QuizController::class, 'store'])->name('student.quizzes.store'); // Perubahan di sini
        Route::get('/quizzes/result/{uniqueId}', [QuizController::class, 'result'])->name('student.quizzes.result');

        Route::get('/ranking/daily', [StudentRankingController::class, 'viewDailyRanking']);

        // Route untuk menampilkan daftar Super Quiz yang aktif
        Route::get('/superquizzes', [StudentSuperQuizController::class, 'index'])->name('student.superquiz.index');

        // Route untuk menampilkan detail Super Quiz dan memulai quiz (halaman sebelum soal pertama)
        Route::get('/superquizzes/{superQuiz}', [StudentSuperQuizController::class, 'show'])->name('student.superquiz.show');

        // Route untuk menampilkan soal quiz tertentu (berdasarkan nomor soal)
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}', [StudentSuperQuizController::class, 'showQuestion'])->name('student.superquiz.question');

        // Route untuk mengirimkan jawaban soal
        Route::post('/superquizzes/{superQuiz}/question/{questionNumber}/submit', [StudentSuperQuizController::class, 'submitAnswer'])->name('student.superquiz.submitAnswer');

        // Pastikan ini berada di file routes/web.php
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/confirmation', [StudentSuperQuizController::class, 'showConfirmation'])->name('student.superquiz.confirmation');

        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/failed', [StudentSuperQuizController::class, 'showFailed'])->name('student.superquiz.failed');

        Route::get('/superquizzes/{superQuiz}/result', [StudentSuperQuizController::class, 'viewResult'])->name('student.superquiz.viewResult');

        // Route untuk menangani timeout dan mengubah status attempt menjadi gagal
        Route::post('/superquiz/{superQuiz}/timeoutAttempt', [StudentSuperQuizController::class, 'timeoutAttempt'])
            ->name('student.superquiz.timeoutAttempt');

        // Route untuk menyerah dari quiz
        Route::post('/superquizzes/{superQuiz}/surrender', [StudentSuperQuizController::class, 'surrenderQuiz'])->name('student.superquiz.surrenderQuiz');

        // Route untuk pengecekan timeout jawaban (AJAX)
        Route::get('/superquizzes/{superQuiz}/question/{questionNumber}/check-timeout', [StudentSuperQuizController::class, 'checkAnswerTimeout'])->name('student.superquiz.checkAnswerTimeout');

    });
});

// Home Route
Route::get('/', function () {
    $stats = Illuminate\Support\Facades\Cache::remember('landing_stats', 86400, function () {
        $userCount = \App\Models\User::count();
        $attendanceCount = \App\Models\Attendance::count();
        $teamCount = \App\Models\Team::count();
        $businessCount = \App\Models\Business::count();

        $format = function ($number) {
            if ($number >= 1000) {
                return floor($number / 1000) . 'K+';
            }
            if ($number >= 100) {
                return floor($number / 100) * 100 . '+';
            }
            return $number;
        };

        return [
            'users' => $format($userCount),
            'attendances' => $format($attendanceCount),
            'teams' => $format($teamCount),
        ];
    });

    return view('welcome', compact('stats'));
});

// Legal Routes
Route::get('/privacy-policy', function () {
    return view('legal.privacy');
});

Route::get('/terms-conditions', function () {
    return view('legal.terms');
});

// =============================================
// SaiQu AI Agent Routes
// =============================================
Route::middleware('auth')->prefix('saiqu')->group(function () {
    Route::post('/chat', [\App\Http\Controllers\SaiQuController::class, 'chat'])->name('saiqu.chat');
    Route::post('/clear', [\App\Http\Controllers\SaiQuController::class, 'clearHistory'])->name('saiqu.clear');
});

<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AskExpertsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[ UserController::class, 'login']);
Route::post('register',[UserController::class, 'register']);
Route::get('appsetting', [ API\DashboardController::class, 'appsetting'] );
Route::get('language-table-list',[API\LanguageTableController::class, 'getList']);
Route::post('forget-password', [API\UserController::class, 'forgetPassword']);
Route::get('get-pregnancy-week', [API\PregnancyDateController::class, 'getPregnancy']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('notification-list', [ API\NotificationController::class, 'getList'] );
    Route::get('notification-detail', [ API\NotificationController::class, 'getNotificationDetail'] );
    Route::get('user-detail',[API\UserController::class, 'userDetail']);
    Route::get('doctor-detail',[API\UserController::class, 'doctorDetail']);
    Route::get('doctor-dashboard',[API\DashboardController::class, 'doctorDashboard']);
    Route::get('health-expert-list',[API\HealthExpertController::class, 'getList']);
    Route::post('update-profile', [ API\UserController::class, 'updateProfile']);
    Route::post('update-doctor-profile', [ API\UserController::class, 'updateHealthExpertProfile']);
    Route::post('change-password',[ API\UserController::class, 'changePassword']);
    Route::post('update-user-status', [ API\UserController::class, 'updateUserStatus']);
    Route::post('delete-user-account', [ API\UserController::class, 'deleteUserAccount']);
    Route::get('logout',[ API\UserController::class, 'logout']);
    Route::post('update-goal-type', [ API\UserController::class, 'updateGoalType']);
    Route::post('create-pairing', [ API\UserController::class, 'createPairing']);
    Route::post('verify-pairing', [ API\UserController::class, 'verifyPairing']);
    Route::post('cancel-invitation', [ API\UserController::class, 'cancelInvitation']);
    Route::post('remove-linking', [ API\UserController::class, 'removeLinking']);
    Route::post('delete-user-data', [ API\UserController::class, 'deleteUserData']);
    Route::post('category-list', [ API\CategoryController::class, 'getList' ]);
    Route::get('get-category-data', [ API\CategoryController::class, 'getCategoryData' ]);
    Route::get('tags-list',[API\TagsController::class, 'getList']);
    Route::get('article-list',[API\ArticleController::class, 'getList']);
    Route::post('article-detail',[API\ArticleController::class, 'getDetail']);
    Route::post('dashboard-article-list',[API\ArticleController::class, 'articleList']);
    Route::post('tag-article-list',[API\ArticleController::class, 'tagArticleList']);
    Route::post('article-create', [ App\Http\Controllers\ArticleController::class, 'store']);
    Route::post('article-update/{id}', [ App\Http\Controllers\ArticleController::class, 'update']);
    Route::post('article-delete/{id}', [ App\Http\Controllers\ArticleController::class, 'destroy']);
    Route::get('symptoms-list', [ API\SymptomsController::class, 'getList' ]);
    Route::post('symptoms-detail',[API\SymptomsController::class, 'getDetail']);
    Route::get('insights-list', [ API\InsightsController::class, 'getList' ]);
    Route::get('get-insights-data', [ API\InsightsController::class, 'getInsightsData' ]);
    Route::post('insights-detail',[API\InsightsController::class, 'getDetail']);
    Route::get('pregnancy-date-list', [ API\PregnancyDateController::class, 'getList' ]);
    Route::get('calculator-tool-list', [ API\CalculatorToolController::class, 'getList' ]);
    Route::get('default-log-category-list', [ API\DefaultLogCategoryController::class, 'getList' ]);
    Route::get('sub-symptoms-list', [ API\SubSymptomsController::class, 'getList' ]);
    Route::get('user-symptom-list', [ API\UserSymptomController::class, 'getList']);
    Route::post('user-symptom-create', [ API\UserSymptomController::class, 'create']);
    Route::post('dashboard-list', [ API\DashboardController::class, 'dashboard' ]);
    Route::get('get-setting',[ API\DashboardController::class, 'getSetting']);
    Route::post('log-period-create', [ API\LogPeriodController::class, 'create']);
    Route::get('log-period-list', [ API\LogPeriodController::class, 'getList']);
    Route::post('session-registration', [ API\SessionRegistrationController::class, 'registerSession']);
    Route::post('cancel-session-registration', [ API\SessionRegistrationController::class, 'cancelRegistration']);
    Route::get('days-list', [ API\HealthExpertSessionController::class, 'index']);
    Route::post('store-health-expert-session', [ API\HealthExpertSessionController::class, 'create']);
    Route::get('health-expert-session-list', [ API\HealthExpertSessionController::class, 'healthExpertSessionList']);
    Route::get('doctor-session-list', [ API\HealthExpertSessionController::class, 'doctorSessionList']);
    Route::post('doctor-session-delete', [ API\HealthExpertSessionController::class, 'delete']);
    Route::get('get-bookmark-insights-list', [ API\InsightsController::class, 'getBookmarkList' ]);
    Route::post('bookmark-insights', [ API\InsightsController::class, 'bookmarkInsights' ]);
    Route::get('image-section-list', [ API\ImageSectionController::class, 'getList' ]);
    Route::get('review-list', [ API\ReviewController::class, 'getList' ]);
    Route::post('store-review', [ API\ReviewController::class, 'create']);
    Route::post('send-code', [ API\UserController::class, 'sendCode']);
    Route::get('faq-list', [ API\FaqsController::class, 'getList' ]);
    //Based insights
    Route::post('get-based-insight',[API\DashboardController::class, 'basedInsight']);
    Route::get('get-bookmark-articles-list', [ API\ArticleController::class, 'getBookmarkActicleList' ]);
    Route::post('bookmark-articles', [ API\ArticleController::class, 'bookmarkActicle' ]);
    //Askexpert
    Route::post('askexpert-save',[AskExpertsController::class, 'store']);
    Route::get('askexpert-list', [ API\AskExpertsController::class, 'getList' ]);
    Route::get('assigndoctor-list', [ API\AskExpertsController::class, 'doctorAssignList' ]);
    Route::post('askexpert-delete/{id}',[AskExpertsController::class, 'destroy']);
    Route::post('askexpert-update/{id}',[AskExpertsController::class, 'update']);
    //Restore and Backup User Data
    Route::get('restore-data',[UserController::class, 'restoreData']);
    Route::post('backup-data',[UserController::class, 'backupData']);
    Route::post('manual-backup',[UserController::class, 'manualBackup']);
    Route::get('paid-userlist',[Api\UserController::class,'paidUserList']);
});

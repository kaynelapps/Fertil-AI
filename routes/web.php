<?php

use App\DataTables\SectionDataMainDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\{
    ArticleExport, 
    CategoryExport, 
    infoSectionExport, 
    SelfCareExport
};
use Illuminate\Support\Facades\{
    Artisan,
    Route
};
use App\Http\Controllers\{
    HomeController,
    RoleController,
    PermissionController,
    SettingController,
    LanguageController,
    ArticleController,
    AskExpertsController,
    CalculatorToolController,
    CategoryController,
    CommonQuestionController,
    CustomTopicController,
    CycleDateDataController,
    CycleDatesController,
    DailyInsightsController,
    DefaultkeywordController,
    DefaultLogCategoryController,
    FAQsController,
    HealthExpertController,
    InsightsController,
    LanguageListController,
    LanguageWithKeywordListController,
    NotificationController,
    PersonalisedInsightsController,
    SectionDataController,
    SectionDataMainController,
    SectionsController,
    SymptomsController,
    SubSymptomsController,
    PregnancyDateController,
    PregnancyWeekController,
    PushNotificationController,
    ScreenController,
    SubAdminController,
    SubscriptionsController,
    VideosUploadController,
    UserController,
    AnonymousController,
    EducationalSessionController,
    HealthExpertSessionController,
    TagsController,
    ImageSectionController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('db-migrate', function () {
    Artisan::call('migrate');
    return 'Database Migrations have been run successfully.';
});
Route::get('db-seeder', function () {
    Artisan::call('db:seed', ['--class' => 'CalculatorToolSeeder']);
    return 'Calculator Tool Seeder has been run successfully.';
});

require __DIR__.'/auth.php';

//Auth pages Routs
Route::group(['prefix' => 'auth'], function() {
    Route::get('login', [HomeController::class, 'authLogin'])->name('frontend.auth.login');
    Route::post('login', [HomeController::class, 'login'])->name('frontend.login');
    Route::get('otp-form', [HomeController::class, 'showOtpForm'])->name('auth.otp-form');
    Route::post('verify-otp', [HomeController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::get('register', [HomeController::class, 'authRegister'])->name('auth.register');
    Route::get('recover-password', [HomeController::class, 'authRecoverPassword'])->name('auth.recover-password');
    Route::get('confirm-email', [HomeController::class, 'authConfirmEmail'])->name('auth.confirm-email');
    Route::get('lock-screen', [HomeController::class, 'authlockScreen'])->name('auth.lock-screen');
});
Route::get('logs/{date}', function ($date) { $logPath = storage_path('logs/laravel-' . $date . '.log'); return response()->file($logPath); });

Route::get('language/{locale}', [ HomeController::class, 'changeLanguage'])->name('change.language');
Route::group(['middleware' => ['auth', 'verified', 'useractive']], function()
{
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/charts', [HomeController::class, 'dashboardCharts'])->name('charts');

    Route::group(['namespace' => '' ], function () {
        Route::resource('permission', PermissionController::class);
        Route::get('permission/add/{type}',[ PermissionController::class,'addPermission' ])->name('permission.add');
        Route::post('permission/save',[ PermissionController::class,'savePermission' ])->name('permission.save');
	});

    Route::get('notification-list',[ NotificationController::class ,'notificationList'])->name('notification.list');
    Route::get('notification-counts',[ NotificationController::class ,'notificationCounts'])->name('notification.counts');
    Route::get('notification',[ NotificationController::class ,'index'])->name('notification.index');

	Route::resource('role', RoleController::class);

	Route::get('changeStatus', [ HomeController::class, 'changeStatus'])->name('changeStatus');

	Route::get('setting/{page?}',[ SettingController::class, 'settings'])->name('setting.index');
    Route::post('/layout-page',[ SettingController::class, 'layoutPage'])->name('layout_page');
    Route::post('settings/save',[ SettingController::class , 'settingsUpdates'])->name('settingsUpdates');
    Route::post('mobile-config-save',[ SettingController::class , 'settingUpdate'])->name('settingUpdate');
    Route::post('payment-settings/save',[ SettingController::class , 'paymentSettingsUpdate'])->name('paymentSettingsUpdate');
    // Route::post('subscription-settings/save',[ SettingController::class , 'subscriptionSettingsUpdate'])->name('subscriptionSettingsUpdate');

    Route::post('get-lang-file', [ LanguageController::class, 'getFile' ] )->name('getLanguageFile');
    Route::post('save-lang-file', [ LanguageController::class, 'saveFileContent' ] )->name('saveLangContent');

    Route::get('pages/term-condition',[ SettingController::class, 'termAndCondition'])->name('term-condition');
    Route::post('term-condition-save',[ SettingController::class, 'saveTermAndCondition'])->name('term-condition-save');

    Route::get('pages/privacy-policy',[ SettingController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::post('privacy-policy-save',[ SettingController::class, 'savePrivacyPolicy'])->name('privacy-policy-save');

	Route::post('env-setting', [ SettingController::class , 'envChanges'])->name('envSetting');
    Route::post('update-profile', [ SettingController::class , 'updateProfile'])->name('updateProfile');
    Route::post('health-expert-update-profile', [ SettingController::class , 'updateHealthExpertProfile'])->name('updateHealthExpertProfile');
    Route::post('change-password', [ SettingController::class , 'changePassword'])->name('changePassword');

    Route::post('remove-file',[ HomeController::class, 'removeFile' ])->name('remove.file');

    Route::delete('datatble/destroySelected', [HomeController::class,'destroySelected'])->name('datatble.destroySelected');
    // Route::get('download-users-list', [ UserController::class, 'downloadUsersList'])->name('download.users.list');
    Route::resource('sub-admin', SubAdminController::class);
    Route::resource('users', UserController::class)->except(['create']);
    Route::get('user/list/{status?}', [UserController::class, 'index'])->name('user.status');
    Route::get('user-view/{id?}', [UserController::class, 'show'])->name('user-view.show');
    Route::get('user-filter',[UserController::class,'filter'])->name('user.filter');
    
    Route::resource('anonymoususer', AnonymousController::class)->except(['create']);
    Route::get('anonymoususer/list/{status?}', [AnonymousController::class, 'index'])->name('anonymoususer.status');
    Route::get('anonymoususer-view/{id?}', [AnonymousController::class, 'show'])->name('anonymoususer-view.show');
    Route::get('anonymous-filter',[AnonymousController::class,'filter'])->name('anonymous.filter');

    Route::resource('category', CategoryController::class);
    Route::get('category-filter',[CategoryController::class,'filter'])->name('category.filter');
    Route::resource('tags', TagsController::class);
    Route::resource('symptoms', SymptomsController::class);
    Route::resource('sub-symptoms', SubSymptomsController::class);
    Route::get('subsymptoms-filter',[SubSymptomsController::class,'filter'])->name('subsymptoms.filter');
    Route::resource('default-log-category', DefaultLogCategoryController::class);
    Route::get('category-need-help', [CategoryController::class, 'needHelp'])->name('category.needhelp');
    Route::get('image-need-help', [CategoryController::class, 'needHelp'])->name('image.needhelp');
    Route::get('selfcare-need-help', [CategoryController::class, 'selfcareNeedHhelp'])->name('selfcare.needhelp');


    Route::resource('article', ArticleController::class);
    Route::get('article-filter',[ArticleController::class,'filter'])->name('article.filter');
    Route::post('article-reference-delete', [ ArticleController::class , 'articleReferenceDelete'])->name('article.reference.delete');

    Route::resource('insights', InsightsController::class);
    Route::get('insights-filter',[InsightsController::class,'filter'])->name('insights.filter');
    Route::resource('sections', SectionsController::class);
    Route::get('sections-filter',[SectionsController::class,'filter'])->name('sections.filter');
    Route::resource('common-question', CommonQuestionController::class);
    Route::get('common-question-filter',[CommonQuestionController::class,'filter'])->name('common-question.filter');
    Route::resource('section-data-main', SectionDataMainController::class);
    Route::get('topic',[SectionDataMainController::class,'topicIndex'])->name('topic.index');
    Route::get('topic-filter',[SectionDataMainController::class,'filter'])->name('topic.filter');
    Route::get('topic-view-filter{id}',[SectionDataMainController::class,'topicViewFilter'])->name('topic.view.filter');
    Route::get('section-data-main-filter{id}',[SectionDataMainController::class,'sectionFilter'])->name('section-data-main.filter');
    Route::get('selfcare-filter', [SectionDataMainController::class, 'filterSection'])->name('slefcare.filter');
    Route::post('region-save-reorder', [SectionDataMainController::class, 'saveDragondrop'])->name('saveDragondrop');
    Route::get('insights-need-help', [InsightsController::class, 'needHelp'])->name('insights.needhelp');

    Route::resource('section-data', SectionDataController::class);

    Route::resource('health-experts', HealthExpertController::class);

    Route::get('healthexperts', [ HealthExpertController::class,'index' ])->name('health.experts.access');
    Route::get('access-password-form', [ HealthExpertController::class,'accessPasswordForm' ])->name('access.password.form');
    Route::post('access-password-store', [ HealthExpertController::class,'accessPasswordStore' ])->name('access.password.store');

    Route::resource('cycle-dates', CycleDatesController::class);
    Route::resource('cycle-dates-data', CycleDateDataController::class);
    Route::resource('pregnancy-date', PregnancyDateController::class);
    Route::resource('image-section', ImageSectionController::class);
    Route::get('image-section-filter',[ImageSectionController::class,'filter'])->name('imagesection.filter');

    // Route::get("need-help", function(){
    //     return View::make("need-help");
    //  })->name('need-help');

    Route::resource('upload-videos', VideosUploadController::class);
    Route::resource('calculator-tool', CalculatorToolController::class);
    Route::resource('faqs', FAQsController::class);
    Route::get('faq-filter',[FAQsController::class,'filter'])->name('faqs.filter');
    Route::get('cycledays-filter',[CycleDatesController::class,'filter'])->name('cycledays.filter');

    Route::resource('pushnotification', PushNotificationController::class);
    Route::resource('healthexpert-session', HealthExpertSessionController::class);

    // Daily Insight
    Route::resource('dailyInsight',DailyInsightsController::class);
    Route::get('dailyinsights-filter',[DailyInsightsController::class,'filter'])->name('dailyinsights.filter');

    // Language Setting Route
    Route::resource('screen', ScreenController::class);
    Route::resource('defaultkeyword', DefaultkeywordController::class);
    Route::resource('languagelist', LanguageListController::class);
    Route::resource('languagewithkeyword', LanguageWithKeywordListController::class);
    Route::get('download-language-with-keyword-list', [ LanguageWithKeywordListController::class, 'downloadLanguageWithKeywordList'])->name('download.language.with,keyword.list');

    Route::post('import-language-keyword', [ LanguageWithKeywordListController::class,'importlanguagewithkeyword' ])->name('import.languagewithkeyword');
    Route::get('bulklanguagedata', [ LanguageWithKeywordListController::class,'bulklanguagedata' ])->name('bulk.language.data');
    Route::get('help', [ LanguageWithKeywordListController::class,'help' ])->name('help');
    Route::get('download-template', [ LanguageWithKeywordListController::class,'downloadtemplate' ])->name('download.template');

    Route::resource('personalinsights',PersonalisedInsightsController::class);
    Route::get('personalinsights-filter',[PersonalisedInsightsController::class,'filter'])->name('personalinsights.filter');

    Route::get('activity-history', [SettingController::class, 'activity'])->name('activity.history');
    Route::get('view-changes/{id}', [SettingController::class, 'viewChanges'])->name('viewChanges');

    //Restore Data
    Route::get('users-restore/{id?}', [UserController::class, 'action'])->name('users.restore');
    Route::delete('users-force-delete/{id?}', [UserController::class, 'action'])->name('users.force.delete');

    Route::get('anonymoususer-restore/{id?}', [AnonymousController::class, 'action'])->name('anonymoususer.restore');
    Route::delete('anonymoususer-force-delete/{id?}', [AnonymousController::class, 'action'])->name('anonymoususer.force.delete');

    Route::get('subadmiin-restore/{id?}', [SubAdminController::class, 'action'])->name('subadmin.restore');
    Route::delete('subadmiin-force-delete/{id?}', [SubAdminController::class, 'action'])->name('subadmin.force.delete');

    Route::get('category-restore/{id?}', [CategoryController::class, 'action'])->name('category.restore');
    Route::delete('category-force-delete/{id?}', [CategoryController::class, 'action'])->name('category.force.delete');

    Route::get('imagesection-restore/{id?}', [ImageSectionController::class, 'action'])->name('imagesection.restore');
    Route::delete('imagesection-force-delete/{id?}', [ImageSectionController::class, 'action'])->name('imagesection.force.delete');

    Route::get('sections-restore/{id?}', [SectionsController::class, 'action'])->name('sections.restore');
    Route::delete('sections-force-delete/{id?}', [SectionsController::class, 'action'])->name('sections.force.delete');

    Route::get('commonquestion-restore/{id?}', [CommonQuestionController::class, 'action'])->name('commonquestion.restore');
    Route::delete('commonquestion-force-delete/{id?}', [CommonQuestionController::class, 'action'])->name('commonquestion.force.delete');

    Route::get('section-data-main-restore/{id?}',[ SectionDataMainController::class ,'action'])->name('section-data-main.restore');
    Route::delete('section-data-main-delete/{id?}',[ SectionDataMainController::class ,'action'])->name('section-data-main.force.delete');

    Route::get('symptoms-restore/{id?}',[ SymptomsController::class ,'action'])->name('symptoms.restore');
    Route::delete('symptoms-force-delete/{id?}',[ SymptomsController::class ,'action'])->name('symptoms.force.delete');

    Route::get('subsymptoms-restore/{id?}',[ SubSymptomsController::class ,'action'])->name('subsymptoms.restore');
    Route::delete('subsymptoms-force-delete/{id?}',[ SubSymptomsController::class ,'action'])->name('subsymptoms.force.delete');

    Route::get('pregnancydate-restore/{id?}',[ PregnancyDateController::class ,'action'])->name('pregnancydate.restore');
    Route::delete('pregnancydate-force-delete/{id?}',[ PregnancyDateController::class ,'action'])->name('pregnancydate.force.delete');

    Route::get('sectiondata-restore/{id?}',[ SectionDataController::class ,'action'])->name('sectiondata.restore');
    Route::delete('sectiondata-force-delete/{id?}',[ SectionDataController::class ,'action'])->name('sectiondata.force.delete');

    Route::get('cycledates-restore/{id?}',[ CycleDatesController::class ,'action'])->name('cycledates.restore');
    Route::delete('cycledates-force-delete/{id?}',[ CycleDatesController::class ,'action'])->name('cycledates.force.delete');

    Route::get('article-restore/{id?}', [ArticleController::class, 'action'])->name('article.restore');
    Route::delete('article-force-delete/{id?}', [ArticleController::class, 'action'])->name('article.force.delete');

    Route::get('videosupload-restore/{id?}',[ VideosUploadController::class ,'action'])->name('videosupload.restore');
    Route::delete('videosupload-force-delete/{id?}',[ VideosUploadController::class ,'action'])->name('videosupload.force.delete');

    Route::get('faqs-restore/{id?}',[ FAQsController::class ,'action'])->name('faqs.restore');
    Route::delete('faqs-force-delete/{id?}',[ FAQsController::class ,'action'])->name('faqs.force.delete');

    Route::get('helthsession-restore/{id?}',[ HealthExpertSessionController::class ,'action'])->name('helthsession.restore');
    Route::delete('helthsession-force-delete/{id?}',[ HealthExpertSessionController::class ,'action'])->name('helthsession.force.delete');

    Route::get('healthexpert-restore/{id?}',[ HealthExpertController::class ,'action'])->name('healthexpert.restore');
    Route::delete('healthexpert-force-delete/{id?}',[ HealthExpertController::class ,'action'])->name('healthexpert.force.delete');

    Route::get('tags-restore/{id?}',[ TagsController::class ,'action'])->name('tags.restore');
    Route::delete('tags-force-delete/{id?}',[ TagsController::class ,'action'])->name('tags.force.delete');

    // Personal Insight
    Route::get('personalinsights-restore/{id?}',[ PersonalisedInsightsController::class ,'action'])->name('personalinsights.restore');
    Route::delete('personalinsights-force-delete/{id?}',[ PersonalisedInsightsController::class ,'action'])->name('personalinsights.force.delete');

    // Custom Topic
    Route::get('customtopic-restore/{id?}',[ CustomTopicController::class ,'action'])->name('customtopic.restore');
    Route::delete('customtopic-force-delete/{id?}',[ CustomTopicController::class ,'action'])->name('customtopic.force.delete');
    //Insight
    Route::get('insights-restore/{id?}',[ InsightsController::class ,'action'])->name('insights.restore');
    Route::delete('insights-force-delete/{id?}',[ InsightsController::class ,'action'])->name('insights.force.delete');
    // Daiy Insghts
     Route::get('daiyinsight-restore/{id?}',[ DailyInsightsController::class ,'action'])->name('daiyinsight.restore');
    Route::delete('daiyinsight-force-delete/{id?}',[ DailyInsightsController::class ,'action'])->name('daiyinsight.force.delete');

    //Ask Expert
    Route::resource('ask-expert',AskExpertsController::class);
    Route::get('ask-expert-restore/{id?}', [AskExpertsController::class, 'action'])->name('askexpert.restore');
    Route::delete('ask-expert-force-delete/{id?}', [AskExpertsController::class, 'action'])->name('askexpert.force.delete');

    //Pregnancy Week
    Route::resource('pregnancy-week',PregnancyWeekController::class);
    Route::get('pregnancyweek.filter-filter',[PregnancyWeekController::class,'filter'])->name('pregnancyweek.filter');
    Route::get('pregnancy-week-restore/{id?}', [PregnancyWeekController::class, 'action'])->name('pregnancy-week.restore');
    Route::delete('pregnancy-week-force-delete/{id?}', [PregnancyWeekController::class, 'action'])->name('pregnancy-week.force.delete');

    //Import
     Route::post('import-data', [CategoryController::class, 'import'])->name('import.categorydata');
     Route::get('importdata', [CategoryController::class, 'importFile'])->name('bulk.data');
     Route::get('template.excel', [CategoryController::class, 'templateExcel'])->name('template.excel');
     Route::get('category-help', [CategoryController::class, 'help'])->name('category-help');


     Route::post('import-selfcare-data', [SectionDataMainController::class, 'import'])->name('import.selfcaredata');
     Route::get('importdata-selfcare', [SectionDataMainController::class, 'importFile'])->name('bulk.selfcaredata');
     Route::get('templateselfcare.excel', [SectionDataMainController::class, 'templateExcel'])->name('templateselfcare.excel');
     Route::get('selfcare-help', [SectionDataMainController::class, 'help'])->name('selfcare-help');


     Route::get('importdata-infosection', [SectionsController::class, 'importFile'])->name('bulk.infosection');
     Route::post('import-infosection-data', [SectionsController::class, 'import'])->name('import.infosection');
     Route::get('templateinfosection.excel', [SectionsController::class, 'templateExcel'])->name('templateinfosection.excel');
     Route::get('section-help', [SectionsController::class, 'help'])->name('section-help');


     Route::get('importdata-article', [ArticleController::class, 'importFile'])->name('bulk.article');
     Route::post('import-article-data', [ArticleController::class, 'import'])->name('import.article');
     Route::get('templatearticle.excel', [ArticleController::class, 'templateExcel'])->name('templatearticle.excel');
     Route::get('article-help', [ArticleController::class, 'help'])->name('article-help');

     //Custom Topic
     Route::resource('customtopic',CustomTopicController::class);
     Route::get('customtopic-filter',[CustomTopicController::class,'filter'])->name('customtopic.filter');
     Route::get('customtopic/search/{id}', [CustomTopicController::class, 'search'])->name('customtopic.search');
    Route::get('customtopic-data-filter{id}',[CustomTopicController::class,'customtopicFilter'])->name('customtopic-data.filter');
    Route::get('customtopic-search-filter{id}',[CustomTopicController::class,'customtopicSearchFilter'])->name('customtopic-search.filter');
     Route::post('storeIds', [CustomTopicController::class, 'storeIds'])->name('storeIds');
     Route::post('topiciddestroy/{id}', [CustomTopicController::class, 'topiciddestroy'])->name('topiciddestroy');


    //Export
    Route::get('/export-categories', function () {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    });
    Route::get('/export-selfcare', function () {
        return Excel::download(new SelfCareExport, 'selfcare.xlsx');
    });
    Route::get('/export-sections', function () {
        return Excel::download(new infoSectionExport, 'infosection.xlsx');
    });
    Route::get('/export-article', function () {
        return Excel::download(new ArticleExport, 'article.xlsx');
    });
});

Route::get('/ajax-list',[ HomeController::class, 'getAjaxList' ])->name('ajax-list');

Route::get('termofservice', [HomeController::class, 'termofservice'])->name('termofservice');
Route::get('privacypolicy', [HomeController::class, 'privacypolicySetting'])->name('privacypolicy');

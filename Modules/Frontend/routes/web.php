<?php

use Illuminate\Support\Facades\Route;
use Modules\Frontend\Http\Controllers\FrontendController;
use Modules\Frontend\Http\Controllers\MailSubscribeController;
use Modules\Frontend\Http\Controllers\SubscribersController;
use Modules\Frontend\Http\Controllers\WebSiteSectionController;

Route::group(['middleware' => ['auth']], function () {

    Route::get('website-section/{type}', [ FrontendController::class, 'websiteSettingForm' ] )->name('frontend.website.form');
    Route::post('update-website-information/{type}', [ FrontendController::class, 'websiteSettingUpdate' ] )->name('frontend.website.information.update');

    Route::post('store-frontend-data', [ FrontendController::class, 'storeFrontendData' ] )->name('store.frontend.data');
    Route::get('frontend-data-list',[ FrontendController::class, 'getFrontendDatatList'])->name('get.frontend.data');
    Route::post('frontend-data-delete',[ FrontendController::class, 'frontendDataDestroy' ])->name('delete.frontend.data');

});

Route::get('browse', [FrontendController::class, 'index'])->name('browse');
Route::get('article-list', [FrontendController::class, 'articleList'])->name('article.list');
Route::get('article-detail/{slug}', [FrontendController::class, 'articleDetail'])->name('article.detail');
Route::get('articles-tag/{slug}', [FrontendController::class, 'articlesByTag'])->name('articles.by.tag');

Route::match(['get', 'post'], 'tools/{slug}', [FrontendController::class, 'calculator'])->name('calculator');

Route::post('subscribe', [MailSubscribeController::class, 'subscribe'])->name('subscribe');
Route::get('unsubscribe/success', [MailSubscribeController::class, 'unsubscribeSuccess'])->name('unsubscribe.success');
Route::get('/unsubscribe/{email}', [MailSubscribeController::class, 'showUnsubscribeForm'])->name('unsubscribe');
Route::post('/unsubscribe', [MailSubscribeController::class, 'unsubscribe'])->name('unsubscribe.submit');
Route::get('resubscribe', [MailSubscribeController::class, 'resubscribe'])->name('resubscribe');

Route::resource('subscribers', SubscribersController::class);
Route::resource('app-overview',WebSiteSectionController::class);
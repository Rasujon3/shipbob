<?php

use App\Http\Controllers\BonusController;
use App\Http\Controllers\BonusReasonController;
use App\Http\Controllers\CreditAssignController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrozenAmountController;
use App\Http\Controllers\GiftBoxController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\InvitationCodeController;
use App\Http\Controllers\LevelAssignController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageAssignController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RTTAssignTaskController;
use App\Http\Controllers\RTTProductController;
use App\Http\Controllers\RTTTaskController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TaskAssignController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrialTaskAssignController;
use App\Http\Controllers\TrialTaskController;
use App\Http\Controllers\UpdateUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeBonusController;
use Illuminate\Support\Facades\Route;

// Author: Sujon

Route::middleware('admin_auth')->group(function () {
    // products
    Route::resource('products', ProductController::class);

    // Package
    Route::resource('packages', PackageController::class);

    // Settings
    Route::get('/settings', [SettingController::class, 'settings'])->name('settings');
    Route::post('settings-app', [SettingController::class, 'settingApp']);
    // Login Page Content
    Route::get('/login-page-content', [SettingController::class, 'loginPageContent'])->name('login-page-content');
    Route::post('update-login-page-content', [SettingController::class, 'updateLoginPageContent'])->name('update-login-page-content');

    // about-us
    Route::get('about-us', [SettingController::class, 'aboutUs'])->name('about-us');
    Route::post('about-us', [SettingController::class, 'storeAboutUs'])->name('store-about-us');

    // Reserved Amount
    Route::resource('frozen-amounts', FrozenAmountController::class);

    // Event
    Route::resource('events', EventController::class);

    // Level
    Route::resource('levels', LevelController::class);

    // Package Assign
    Route::resource('package-assign', PackageAssignController::class);

    // Level Assign
    Route::resource('level-assign', LevelAssignController::class);

    // tasks
    Route::resource('tasks', TaskController::class);

    // Task Assign
    Route::resource('task-assign', TaskAssignController::class);

    // Trial Task
    Route::get('trial-task', [TrialTaskController::class, 'trialTask'])->name('trial-task');
    Route::post('update-trial-task', [TrialTaskController::class, 'updateTrialTask'])->name('update-trial-task');

    // Trial Task Assign
    Route::resource('trial-task-assign', TrialTaskAssignController::class);

    // Home Page Content
    Route::prefix('home-page')->group(function () {
        // 1. Hero section
        Route::get('/hero-section-content', [HomePageController::class, 'heroSectionContent'])->name('hero-section-content');
        Route::post('update-hero-section-content', [HomePageController::class, 'updateHeroSectionContent'])->name('update-hero-section-content');

        // 2. Video section
        Route::get('/video-section-content', [HomePageController::class, 'videoSectionContent'])->name('video-section-content');
        Route::post('update-video-section-content', [HomePageController::class, 'updateVideoSectionContent'])->name('update-video-section-content');

        // 3. Growth section
        Route::get('/growth-section-content', [HomePageController::class, 'growthSectionContent'])->name('growth-section-content');
        Route::post('update-growth-section-content', [HomePageController::class, 'updateGrowthSectionContent'])->name('update-growth-section-content');

        // 4. Shipping section
        Route::get('/shipping-section-content', [HomePageController::class, 'shippingSectionContent'])->name('shipping-section-content');
        Route::post('update-shipping-section-content', [HomePageController::class, 'updateShippingSectionContent'])->name('update-shipping-section-content');

        // 5. Courier section
        Route::get('/courier-section-content', [HomePageController::class, 'courierSectionContent'])->name('courier-section-content');
        Route::post('update-courier-section-content', [HomePageController::class, 'updateCourierSectionContent'])->name('update-courier-section-content');

        // 6. Delivery section
        Route::get('/delivery-section-content', [HomePageController::class, 'deliverySectionContent'])->name('delivery-section-content');
        Route::post('update-delivery-section-content', [HomePageController::class, 'updateDeliverySectionContent'])->name('update-delivery-section-content');

        // 7. Contact section
        Route::get('/contact-section-content', [HomePageController::class, 'contactSectionContent'])->name('contact-section-content');
        Route::post('update-contact-section-content', [HomePageController::class, 'updateContactSectionContent'])->name('update-contact-section-content');// 4. Shipping section

        // 8. Global section
        Route::get('/global-section-content', [HomePageController::class, 'globalSectionContent'])->name('global-section-content');
        Route::post('update-global-section-content', [HomePageController::class, 'updateGlobalSectionContent'])->name('update-global-section-content');

    });

    // Set Off Page Content

    // 1. Video section
    Route::get('/set-off-video-section-content', [HomePageController::class, 'setOffVideoSectionContent'])->name('set-off-video-section-content');
    Route::post('update-set-off-video-section-content', [HomePageController::class, 'updateSetOffVideoSectionContent'])->name('update-set-off-video-section-content');

    // 2. Sliders
    Route::resource('sliders', SliderController::class);

    // Credit Page Content

    // 1. Rules section
    Route::get('/rule-section-content', [HomePageController::class, 'ruleSectionContent'])->name('rule-section-content');
    Route::post('update-rule-section-content', [HomePageController::class, 'updateRuleSectionContent'])->name('update-rule-section-content');

    // Cash In Image Change
    Route::get('/cashin-img-changes', [HomePageController::class, 'cashinImgChange'])->name('cashin-img-changes');
    Route::post('update-cashin-img-changes', [HomePageController::class, 'updateCashinImgChange'])->name('update-cashin-img-changes');

    // users
    Route::resource('updateUser', UpdateUserController::class);
    Route::post('user-status-update', [UpdateUserController::class, 'userStatusUpdate']);
    Route::get('/updateUser/{updateUser}/withdraw-password-edit', [UpdateUserController::class, 'withdrawPasswordEdit'])->name('updateUser.withdraw-password-edit');
    Route::patch('/updateUser/{updateUser}/withdraw-password-update', [UpdateUserController::class, 'withdrawPasswordUpdate'])
        ->name('updateUser.withdraw-password-update');


    // Help Center
    Route::resource('helpCenter', HelpCenterController::class);

    // Sign Up section
    Route::get('/sign-up-content', [HomePageController::class, 'signUpContent'])->name('sign-up-content');
    Route::post('update-sign-up-content', [HomePageController::class, 'updateSignUpContent'])->name('update-sign-up-content');

    Route::get('password-change', [UserController::class, 'passwordChange'])->name('password-change');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('change-password');

    // Invitation Code
    Route::resource('invitation-codes', InvitationCodeController::class);
    Route::get('/generate-code', [InvitationCodeController::class, 'generateCode']);

    // Sign Up section
    Route::get('gift-box-content', [HomePageController::class, 'giftBoxContent'])->name('gift-box-content');
    Route::post('update-gift-box-content', [HomePageController::class, 'updateGiftBoxContent'])->name('update-gift-box-content');

    // Gifts
    Route::resource('gifts', GiftBoxController::class);

    // Bonus
    Route::resource('bonus-reasons', BonusReasonController::class);
    Route::resource('bonus', BonusController::class);

    // Credit
    Route::resource('credits', CreditController::class);

    // Credit Assign
    Route::resource('credit-assign', CreditAssignController::class);

    // Welcome Bonus
    Route::resource('welcome-bonuses', WelcomeBonusController::class);

    Route::prefix('rtt')->group(function () {
        Route::resource('rtt-products', RTTProductController::class);
        Route::resource('rtt-tasks', RTTTaskController::class);
        Route::resource('rtt-assign-tasks', RTTAssignTaskController::class);
    });
});

// user part
Route::get('/user/login', [UserController::class, 'userLogin'])->name('login-user');
Route::post('/user/login', [UserController::class, 'loginUser'])->name('user-login');
Route::post('/user/sign-up', [UserController::class, 'signUp'])->name('user-sign-up');

Route::prefix('user')->middleware('user_auth')->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('user-index');

    Route::get('/profile', [UserController::class, 'profile'])->name('user-profile');

    Route::get('/setoff', [UserController::class, 'setoff'])->name('user-setoff');

    Route::get('/event', [UserController::class, 'event'])->name('user-event');

    Route::get('/credit-score', [UserController::class, 'creditScore'])->name('user-credit-score');

    Route::post('/product-order', [UserController::class, 'productOrder'])->name('product-order');

    Route::post('/rtt-product-order', [UserController::class, 'rttProductOrder'])->name('rtt-product-order');

    Route::post('/order-product', [OrderController::class, 'store'])->name('order.product');

    Route::post('/order-rtt-product', [OrderController::class, 'rttStore'])->name('order-rtt-product');

    Route::get('/cash-in', [UserController::class, 'cashIn'])->name('user-cash-in');

    Route::get('/account-details', [UserController::class, 'accountDetails'])->name('user-account-details');

    Route::get('/level', [UserController::class, 'level'])->name('user-level');
    Route::get('/level-details', [UserController::class, 'levelDetails'])->name('user-level-details');

    Route::get('/sign-in-details', [UserController::class, 'signInDetails'])->name('user-sign-in-details');

    Route::get('/technical-support', [UserController::class, 'technicalSupport'])->name('user-technical-support');

    Route::get('/help-center', [UserController::class, 'helpCenter'])->name('user-help-center');

    Route::get('/problem-help', [UserController::class, 'problemHelp'])->name('user-problem-help');

    Route::get('/about-us', [UserController::class, 'aboutUs'])->name('user-about-us');

    Route::get('/settings', [UserController::class, 'settings'])->name('user-settings');

    Route::get('/user-agreement', [UserController::class, 'userAgreement'])->name('user-agreement');

    Route::get('/user-privacy', [UserController::class, 'userPrivacy'])->name('user-privacy');

    Route::get('/modify-withdraw-password', [UserController::class, 'modifyWithdrawPassword'])->name('modify-withdraw-password');

    Route::post('/update-withdraw-password', [UserController::class, 'updateWithdrawPassword'])->name('update-withdraw-password');

    Route::get('/modify-login-password', [UserController::class, 'modifyLoginPassword'])->name('modify-login-password');

    Route::post('/update-login-password', [UserController::class, 'updateLoginPassword'])->name('update-login-password');

    Route::get('gift-box', [UserController::class, 'giftBox'])->name('gift-box');

    Route::get('/get-gift-box-data', [UserController::class, 'getGiftBoxData'])->name('get-gift-box-data');
    Route::post('/select-gift-box', [UserController::class, 'selectGiftBox'])->name('select-gift-box');


});

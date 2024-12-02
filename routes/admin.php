<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\FatherController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ChildrenController;
use App\Http\Controllers\Admin\DriverCarController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Client\TestPaymentController;
use App\Http\Controllers\Admin\SchoolClassesController;
use App\Http\Controllers\Admin\SchoolSemsterController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\TripController;
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile-update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('change-password', [ProfileController::class, 'password'])->name('password.index');
    Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::resource('users', UserController::class);
    Route::get('user-ban-unban/{id}/{status}', [UserController::class, 'banUnban'])->name('user.banUnban');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // schools
    Route::resource('schools', SchoolController::class);
    Route::get('school-ban-unban/{id}/{status}', [SchoolController::class, 'banUnban'])->name('school.banUnban');
    // Route::get('school-create-group/{id}',    [GroupController::class, 'create'])->name('school.create.group');
    Route::post('school-store-group',    [GroupController::class, 'store'])->name('school.store.group');
    Route::get('/Schools/{id}/groups', [SchoolController::class, 'getGroups'])->name('school.get.groups');
    Route::post('children/{child}/store-price', [ChildrenController::class, 'storePrice'])->name('children.store-price');
    // school holiday
    Route::get('/school-holidays-all/{id}', [HolidayController::class, 'schoolHolidays'])->name('school.holiday.index');
    Route::get('/school-holiday-create/{id}', [HolidayController::class, 'create'])->name('school.holiday.create');
    Route::post('/school-holidays', [HolidayController::class, 'store'])->name('school.holiday.store');
    Route::get('/school-holiday-edit/{id}', [HolidayController::class, 'edit'])->name('school.holiday.edit');
    Route::post('/school-holiday/{id}', [HolidayController::class, 'update'])->name('school.holiday.update');
    Route::get('/school-holidays-destroy/{id}', [HolidayController::class, 'destroy'])->name('school.holiday.destroy');

    // Official holidays
    Route::get('/official-holidays-all', [HolidayController::class, 'officialHolidays'])->name('official.holiday.index');
    Route::get('/official-holiday-create', [HolidayController::class, 'officialCreate'])->name('official.holiday.create');
    Route::post('/official-holidays', [HolidayController::class, 'officialStore'])->name('official.holiday.store');
    Route::get('/official-holiday-edit/{id}', [HolidayController::class, 'officialEdit'])->name('official.holiday.edit');
    Route::post('/official-holiday/{id}', [HolidayController::class, 'officialUpdate'])->name('official.holiday.update');
    Route::get('/official-holidays-destroy/{id}', [HolidayController::class, 'officialDestroy'])->name('official.holiday.destroy');

    // school calsses
    Route::get('add-class/{id}', [SchoolClassesController::class, 'create'])->name('school.classes.create');
    Route::post('store-class', [SchoolClassesController::class, 'store'])->name('school.classes.store');
    Route::get('edit-class/{id}', [SchoolClassesController::class, 'edit'])->name('school.classes.edit');
    Route::post('update-class', [SchoolClassesController::class, 'update'])->name('school.classes.update');

    // groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/group/{id}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/create', [GroupController::class, 'createGroup'])->name('groups.create');
    Route::get('groups/{groupId}/details', [GroupController::class, 'getGroupDetails'])->name('get.group.details');
    Route::get('/all/groups', [GroupController::class, 'getGroups'])->name('get.all.group');
    Route::get('/get-schools', [GroupController::class, 'getSchools'])->name('getSchools');
    Route::get('/get-children', [GroupController::class, 'getChildren'])->name('getChildren');
    Route::get('/get-classes', [GroupController::class, 'getClasses'])->name('getClasses');
    Route::get('groups-ban-unban/{id}/{status}', [GroupController::class, 'banUnban'])->name('groups.banUnban');
    Route::post('groups-add-child/{group_id}', [GroupController::class, 'addChild'])->name('groups.add.child');
    Route::delete('groups-delete-child-from', [GroupController::class, 'deleteChildFromGroup'])->name('groups.delete.child');

    // drivers
    Route::resource('drivers', DriverController::class);
    Route::get('drivers-ban-unban/{id}/{status}', [DriverController::class, 'banUnban'])->name('driver.banUnban');
    Route::get('driver-add-car/{id}', [DriverController::class, 'addCar'])->name('driver.add.car');
    Route::resource('cars', DriverCarController::class);

    // fathers
    Route::resource('fathers', FatherController::class);
    Route::get('fathers-ban-unban/{id}/{status}', [FatherController::class, 'banUnban'])->name('father.banUnban');
    Route::get('father-add-child/{id}', [FatherController::class, 'addChild'])->name('father.add.child');
    Route::resource('children', ChildrenController::class);

    // Semesters
    Route::resource('semesters', SchoolSemsterController::class);
    Route::get('school-create-semester/{id}', [SchoolSemsterController::class, 'createSemester'])->name('school.create.semester');
    Route::get('school-destroy/{id}', [SchoolSemsterController::class, 'destroySemester'])->name('school.destroy.semester');

    Route::prefix('subscription')->group(function () {
        Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans');
        Route::get('/create-plan', [SubscriptionController::class, 'createPlan'])->name('subscription.create.plan');
        Route::post('/store-plan', [SubscriptionController::class, 'storePlan'])->name('subscription.store.plan');

        Route::get('/edit-plan/{id}', [SubscriptionController::class, 'editPlan'])->name('subscription.edit.plan');
        Route::post('/update-plan/{id}', [SubscriptionController::class, 'updatePlan'])->name('subscription.update.plan');

        Route::get('/getPlans', [SubscriptionController::class, 'getPlans'])->name('subscription.getPlans');
        Route::get('/plans-details/{id}', [SubscriptionController::class, 'getPlansDetails'])->name('subscription.plans.details');
        Route::get('/add-plan-feature/{id}', [SubscriptionController::class, 'addFeaturetoPlan'])->name('subscription.create.feature');
        Route::get('/edit-plan-feature/{id}', [SubscriptionController::class, 'editFeature'])->name('subscription.edit.feature');
        Route::post('/update-plan-feature/{id}', [SubscriptionController::class, 'updateFeature'])->name('subscription.update.feature');
        Route::post('/store-feature/{id}', [SubscriptionController::class, 'storeFeature'])->name('subscription.store.feature');
        Route::get('/getPlanFeature/{id}', [SubscriptionController::class, 'getPlanFeature'])->name('subscription.getPlansFeature');
        Route::get('/getPlanSubscriptions/{id}', [SubscriptionController::class, 'getPlanSubscriptions'])->name('subscription.getPlanSubscriptions');

        Route::get('/cancelSubscriptions/{id}', [SubscriptionController::class, 'cancelSubscriptions'])->name('subscription.cancel');
        Route::get('/activeSubscriptions/{id}', [SubscriptionController::class, 'activeSubscriptions'])->name('subscription.active');

        // invoices
        Route::get('/all-invoices',       [InvoicesController::class, 'index'])->name('subscription.invoices');
        Route::get('/getInvoicesData',    [InvoicesController::class, 'getInvoicesData'])->name('subscription.getInvoicesData');
        Route::get('invoice/create',      [InvoicesController::class, 'create'])->name('subscription.invoice.create');
        Route::post('invoice/store',     [InvoicesController::class, 'store'])->name('subscription.invoice.store');

        Route::get('invoice/{id}/edit',   [InvoicesController::class, 'edit'])->name('subscription.invoice.edit');
        Route::post('invoice/update',     [InvoicesController::class, 'update'])->name('subscription.invoice.update');

    });

    Route::get('/admin/get-children-by-father/{fatherId}', [InvoicesController::class, 'getChildrenByFather'])->name('father.get.childrens');

    // settings
    Route::prefix('setting')->group(function () {
        // main website settings
        Route::get('/main-settings',  [SettingController::class, 'index'])->name('main.setting');
        Route::post('/main-settings-update',  [SettingController::class, 'update'])->name('main.setting.update');

        // taxs
        // Route::get('/taxts',         [TaxController::class, 'index'])->name('taxs');
        // Route::get('/gettaxts',      [TaxController::class, 'getTaxTypes'])->name('get.taxs');
        // Route::post('/taxtsUpdate',  [TaxController::class, 'TaxUpdate'])->name('tax.update');
        // Route::post('/taxtsStore',   [TaxController::class, 'TaxCreate'])->name('tax.store');

        // Route::get('/Terms-Privacy',  [PrivacyTermsController::class, 'TermsPrivacyUpdate'])->name('privacy.terms');
        // Route::post('/privacyUpdate', [PrivacyTermsController::class, 'privacyUpdate'])->name('setting.privacy.update');
        // Route::post('/termsUpdate',   [PrivacyTermsController::class, 'termsUpdate'])->name('setting.terms.update');

        // // Q&A
        // Route::prefix('QA')->group(function () {

        //     Route::get('questions',  [QuestionsController::class, 'questions'])->name('questions');
        //     Route::get('categorys',  [QuestionsController::class, 'categorys'])->name('questions.categorys');
        //     Route::get('/getQAcategorys', [QuestionsController::class, 'getQAcategorys'])->name('get.QAcategorys');
        //     Route::post('/QAcategorysStore',   [QuestionsController::class, 'QAcategorysStore'])->name('QAcategorys.store');
        //     Route::post('/QAcategorysUpdate',  [QuestionsController::class, 'QAcategorysUpdate'])->name('QAcategorys.update');

        //     Route::get('/getQAquestions', [QuestionsController::class, 'getQAquestions'])->name('get.QAquestions');
        //     Route::post('/QAquestionsStore',   [QuestionsController::class, 'QAquestionsStore'])->name('QAquestions.store');
        //     Route::get('/QAquestionsUpdate/{id}',  [QuestionsController::class, 'QAquestionsUpdate'])->name('QAquestions.update');
        //     Route::post('/QAquestionsChange/{id}',  [QuestionsController::class, 'QAquestionsChange'])->name('QAquestions.change');

        // });
    });

    // trips
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/get-trips', [TripController::class, 'getTrips'])->name('trips.get');


    // for test
    Route::get('/payment', [TestPaymentController::class, 'showPaymentForm'])->name('payment.form'); // for test
    Route::post('/payment/initiate', [TestPaymentController::class, 'initiatePayment'])->name('payment.initiate'); // for test
    Route::get('/callback', [TestPaymentController::class, 'handlePaymentCallback'])->name('callback'); // for test
});


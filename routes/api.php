<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Driver\GroupController;
use App\Http\Controllers\Api\Father\FatherController;
use App\Http\Controllers\Api\Father\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TripApiController;
use App\Http\Controllers\Api\SchoolApiController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\Driver\TripController as DriverTripController;
use App\Http\Controllers\Api\Father\TripController as FatherTripController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function () {
    // Auth Routes

    Route::middleware('auth:sanctum')->group(function () {
        // User Routes
        // Route::prefix('user')->group(function () {
        //     Route::get('profile', [UserController::class, 'profile']);
        //     Route::put('profile', [UserController::class, 'updateProfile']);
        // });

        // Groups Routes
        // Route::apiResource('groups', GroupController::class);
        // Route::get('groups/{group}/children', [GroupController::class, 'children']);

        // Trips Routes
        // Route::prefix('trips')->group(function () {
        //     Route::get('/', [TripController::class, 'index']);
        //     Route::post('start', [TripController::class, 'startTrip']);
        //     Route::post('end', [TripController::class, 'endTrip']);
        //     Route::get('current', [TripController::class, 'currentTrip']);
        //     Route::get('history', [TripController::class, 'history']);
        // });

        // Live Tracking Routes
        // Route::prefix('tracking')->group(function () {
        //     Route::post('update-location', [TrackingController::class, 'updateLocation']);
        //     Route::get('driver/{driver}', [TrackingController::class, 'driverLocation']);
        //     Route::get('trip/{trip}', [TrackingController::class, 'tripPath']);
        // });

        // Driver Routes
        // Route::prefix('drivers')->group(function () {
        //     Route::get('/', [DriverController::class, 'index']);
        //     Route::get('{driver}', [DriverController::class, 'show']);
        //     Route::get('{driver}/trips', [DriverController::class, 'trips']);
        //     Route::put('{driver}/status', [DriverController::class, 'updateStatus']);
        // });

        // Notifications Routes
        // Route::prefix('notifications')->group(function () {
        //     Route::get('/', [NotificationController::class, 'index']);
        //     Route::put('{notification}/read', [NotificationController::class, 'markAsRead']);
        // });
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// fathers
Route::prefix('father')->group(function () {

    // Register father
    Route::post('register', [AuthController::class, 'registerFather']);

    // Register children
    Route::post('register-children', [AuthController::class, 'registerFatherChildren']);

    // Login father
    Route::post('login', [AuthController::class, 'fatherLogin']);

    Route::middleware('auth:father')->group(function () {

        Route::get('me', [AuthController::class, 'meFather']);
        Route::post('logout', [AuthController::class, 'logoutFather']);

        // update profile
        Route::put('update-profile', [AuthController::class, 'updateFather']);
        // change password
        Route::post('change-password', [AuthController::class, 'changePassword']);

        // subscription
        Route::get('subscription/overview', [SubscriptionController::class, 'subscriptionOverview']);

        // invoice
        Route::get('subscription/invoice', [SubscriptionController::class, 'subscriptionInvoices']);
        Route::get('subscription/plans', [SubscriptionController::class, 'subscriptionPlans']);
        Route::post('subscription/{plan_id}', [SubscriptionController::class, 'setSubscription']);

        // payment
        Route::post('/initiate-payment', [PaymentController::class, 'initiatePayment']);
        Route::post('/handle-response', [PaymentController::class, 'handleResponse']);

        // get childre
        Route::get('/children', [FatherController::class, 'getFatherChildren']);

        // get groups
        Route::get('/groups', [FatherController::class, 'getFatherGroups']);

        // get group details
        Route::get('/group/{id}', [FatherController::class, 'getGroupDetails']);

        // get father trips
        Route::get('/trips', [FatherTripController::class, 'getFatherTrips']);

        // get notifications
        Route::get('/notifications', [NotificationController::class, 'getNotifications']);
        // mark notification as read
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });

});

// drivers
Route::prefix('driver')->group(function () {
    Route::post('register', [AuthController::class, 'registerDriver']);
    Route::post('login', [AuthController::class, 'driverLogin']);
    Route::middleware('auth:driver')->group(function () {

        Route::get('me', [AuthController::class, 'meDriver']);
        Route::post('logout', [AuthController::class, 'logoutDriver']);

        Route::put('update-profile', [AuthController::class, 'updateDriver']);
        Route::post('change-password', [AuthController::class, 'changePasswordDriver']);

        // get all groups
        Route::get('/groups', [GroupController::class, 'getAllGroups']);
        // get group data
        Route::get('/group/{id}', [GroupController::class, 'getGroupDetails']);
        // trips
        Route::get('/trips', [DriverTripController::class, 'getDriverTrips']);

        Route::post('/trip/{group_id}/start', [DriverTripController::class, 'startTrip']);
        Route::post('/trip/{group_id}/end', [DriverTripController::class, 'endTrip']);
        Route::post('/child/{child_id}/got-in-car', [DriverTripController::class, 'childGotInCar']);

    });

});

// get Active Classes
Route::get('/schools/get-active-schools', [SchoolApiController::class, 'getActiveSchools']);
// get Classes By School
Route::get('/schools/{school_id}/classes', [SchoolApiController::class, 'getClassesBySchool']);

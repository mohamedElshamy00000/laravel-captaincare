<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FatherController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TripApiController;
use App\Http\Controllers\Api\SchoolApiController;
use App\Http\Controllers\Api\SubscriptionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// fathers
Route::prefix('father')->group(function () {

    // Register father
    Route::post('register', [AuthController::class, 'registerFather']);

    // Register children
    Route::post('register-children', [AuthController::class, 'registerFatherChildren']);

    Route::post('login', [AuthController::class, 'fatherLogin']);

    Route::middleware('auth:father')->group(function () {
        Route::get('me', [AuthController::class, 'meFather']);
        Route::post('logout', [AuthController::class, 'logoutFather']);

        // subscription
        Route::get('subscription/overview', [SubscriptionController::class, 'subscriptionOverview']);
        Route::get('subscription/plans', [SubscriptionController::class, 'subscriptionPlans']);
        Route::post('subscription/{plan_id}', [SubscriptionController::class, 'setSubscription']);
        
        // payment
        Route::post('/initiate-payment', [PaymentController::class, 'initiatePayment']);
        Route::post('/handle-response', [PaymentController::class, 'handleResponse']);

        // get childre
        Route::get('/children', [FatherController::class, 'getFatherChildren']);

        // get groups
        Route::get('/groups', [FatherController::class, 'getFatherGroups']);

        // trips
        Route::get('/trips', [TripApiController::class, 'getFatherTrips']);

    });

});

// drivers
Route::prefix('driver')->group(function () {
    Route::post('register', [AuthController::class, 'registerDriver']);
    Route::post('login', [AuthController::class, 'driverLogin']);
    Route::middleware('auth:driver')->group(function () {
        Route::get('me', [AuthController::class, 'meDriver']);
        Route::post('logout', [AuthController::class, 'logoutDriver']);
        
        // get group data
        Route::get('/group/{id}', [TripApiController::class, 'getGroupsForDriver']);

        // trips
        Route::get('/{driverId}/trips', [TripApiController::class, 'getDriverTrips']);

    });

});

// get Active Classes
Route::get('/schools/get-active-schools', [SchoolApiController::class, 'getActiveSchools']);
// get Classes By School
Route::get('/schools/{school_id}/classes', [SchoolApiController::class, 'getClassesBySchool']);

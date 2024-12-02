<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Father;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::privateChannel('started_trip_father.{id}', function (Father $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::privateChannel('ended_trip_father.{id}', function (Father $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::privateChannel('child_got_in_car_father.{id}', function (Father $user, $id) {
    return (int) $user->id === (int) $id;
});

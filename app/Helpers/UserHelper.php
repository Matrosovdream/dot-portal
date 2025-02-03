<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use Illuminate\Support\Str;


class UserHelper {

    public static function sendUserCreatedEmail($user) {
        $password = self::generateRandomPassword();
        $user->password = bcrypt($password);
        $user->save();
        
        Mail::to($user->email)->queue(new UserCreated($user, $password));
    }

    public static function generateRandomPassword() {
        return Str::random(8);
    }

}
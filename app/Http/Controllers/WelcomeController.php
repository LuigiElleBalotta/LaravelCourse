<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function greet($name = '', $lastname = '', $age = 0, Request $req) {
        $lang = $req->input('lang', 'en');

        switch($lang) {
            case 'it':
                $message = "Ciao $name $lastname, hai $age anni";
                break;
            case 'en':
                $message = "Welcome $name $lastname, you are $age years old";
                break;
            case 'es':
                $message = "Bienvenido $name $lastname, tienes $age años";
                break;
            default:
                $message = "Welcome $name $lastname, you are $age years old";
                break;
        }

        return $message;
    }
}

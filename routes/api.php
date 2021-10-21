<?php

use Illuminate\Support\Facades\Route;


//LOGIN

Route::group(["middleware" => "apikey.validate"], function () {

    // Crear un usuario cliente
    Route::post('/createClient', 'ClientsController@create');

    // Logear un usuario cliente
    Route::post('/loginClient', 'ClientsController@login');

    // Crear cuenta bancaria


    //Registro
    Route::post('register', 'UserController@register');

    //Login
    Route::post('login', 'UserController@authenticate');

    Route::group(['middleware' => ['jwt.verify']], function() {

        Route::post('user','UserController@getAuthenticatedUser');

        Route::post('changeUserPasswd','UserController@changeUserPasswd');

        // Gestionar dinero de la cuenta
        Route::post('/showAccount', 'EventsAccountController@show');

        Route::post('/depositAccount', 'EventsAccountController@deposit');

        Route::post('/withdrawAccount', 'EventsAccountController@withdraw');
        
        Route::post('/paymentAccount', 'EventsAccountController@payment');

        //Crear Cuenta bancaria
        Route::post('/createAccount', 'BankAccountController@createAccount');

        // Crear prestamo
        Route::post('/eventLoan', 'LoanController@create');

        // Retornar historial de prestamos
        Route::get('/eventLoanHistory', 'EventsLoanController@manager');

        // Retornar historial de pagos
        Route::get('/eventPaymentHistory', 'EventsPaymentHistoryController@manager');


    });
});
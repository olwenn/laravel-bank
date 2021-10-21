<?php

use Illuminate\Support\Facades\Route;


//LOGIN

Route::group(["middleware" => "apikey.validate"], function () {

   

    // Crear cuenta bancaria


    //Registro
    Route::post('registerUser', 'UserController@register');

    //Login
    Route::post('loginUser', 'UserController@authenticate');

    Route::group(['middleware' => ['jwt.verify']], function() {

        Route::post('user','UserController@getAuthenticatedUser');

        //Cambiar la contraseña del usuario
        Route::post('changeUserPasswd','UserController@changeUserPasswd');
        
        //Crear Cuenta bancaria
        Route::post('/createAccount', 'BankAccountController@createAccount');

        //Mostrar las cuentas bancarias de un usuario
        Route::get('/showAccount', 'EventsAccountController@show');

        //Deposito
        Route::post('/depositAccount', 'EventsAccountController@deposit');

        //Retiro
        Route::post('/withdrawAccount', 'EventsAccountController@withdraw');
        
        //Pago
        Route::post('/paymentAccount', 'EventsAccountController@payment');

        
        // Crear prestamo
        Route::post('/createLoan', 'LoanController@create');

        // Retornar historial de prestamos
        Route::get('/showLoanHistory', 'LoanController@showLoanHistory');

        // Retornar historial de pagos
        Route::get('/showPaymentHistory', 'PaymentHistoryController@showPaymentHistory');


    });
});
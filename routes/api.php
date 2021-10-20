<?php

use Illuminate\Support\Facades\Route;


//LOGIN


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

    // Gestionar dinero de la cuenta
    Route::post('/eventAccount', 'EventsAccountController@manager');

    //Crear Cuenta bancaria
    Route::post('/createAccount', 'BankAccountController@create');

    // Crear prestamo
    Route::post('/eventLoan', 'LoanController@create');

    // Retornar historial de prestamos
    Route::get('/eventLoanHistory', 'EventsLoanController@manager');

    // Retornar historial de pagos
    Route::get('/eventPaymentHistory', 'EventsPaymentHistoryController@manager');


});
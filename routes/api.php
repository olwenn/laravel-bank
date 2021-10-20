<?php

use Illuminate\Support\Facades\Route;


//LOGIN


// Crear un usuario cliente
Route::post('/createClient', 'ClientsController@create');

// Logear un usuario cliente
Route::post('/loginClient', 'ClientsController@login');

// Crear cuenta bancaria

// Gestionar dinero de la cuenta
Route::post('/eventAccount', 'EventsAccountController@manager');

// Crear prestamo
Route::post('/eventLoan', 'LoanController@create');

// Realizar pago
Route::post('/eventLoan', 'LoanController@create');

// Retornar historial de prestamos
Route::get('/eventLoanHistory', 'EventsLoanController@manager');

// Retornar historial de pagos
Route::get('/eventPaymentHistory', 'EventsPaymentHistoryController@manager');


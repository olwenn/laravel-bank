<?php

use Illuminate\Support\Facades\Route;


//LOGIN


// Crear un usuario cliente

// Crear cuenta bancaria

// Gestionar dinero de la cuenta
Route::post('/eventAccount', 'EventsAccountController@manager');

// Gestionar prestamo
Route::post('/eventLoan', 'EventsLoanController@manager');

// Retornar historial de prestamos
Route::post('/eventLoanHistory', 'EventsLoanController@manager');

// Retornar historial de pagos
Route::post('/eventPaymentHistory', 'EventsPaymentHistoryController@manager');


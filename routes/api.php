<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\test_api;
use App\Models\pre_reg;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test_api', [test_api::class, 'index'])->name('test_index');

//Clientes que no han pagado y estan en estado de suspension y clientes desactivados
Route::get('/test_api/get_sus', [test_api::class, 'axios_get_sus'])->name('axios_get_sus');
Route::get('/test_api/get_desc', [test_api::class, 'axios_get_desc'])->name('axios_get_des');

//traer clientes deudores entre 2 fechas
Route::get('/test_api/get_users/{inicio}/{fin}', [test_api::class, 'axios_get_date'])->name('axios_get');

//traer tasa del BCV registrada en la BD
Route::get('/test_api/get_bcv/', [test_api::class, 'axios_get_bcv'])->name('axios_get_bcv');

//Cambiar tasa del BCV
Route::post('/test_api/change_bcv/{bcv_change}', [test_api::class, 'axios_change_bcv'])->name('axios_change_bcv');

//Opciones del bot
//opcion 1 obtener fecha de vencimiendo
Route::get('/test_api/get_id/{id_user}', [test_api::class, 'get_id_user'])->name('axios_get_id');

//Envio de mensajes a los clientes por servidor

//Mensaje de difusion por servidores
Route::get('/test_api/get_23_enero', [test_api::class, 'get_23_enero'])->name('axios_get_23_enero');

//23 de enero (Mensaje !auto-report)
Route::get('/test_api/auto_report', [test_api::class, 'auto_report'])->name('axios_auto_report'); //dia de corte, Restan 2 y 1 dia

//Confirmar si se envio el mensaje !auto-report
Route::get('/test_api/send_confirm', [test_api::class, 'send_confirm'])->name('axios_send_confirm');

Route::get('/test_api/change_confirm_0', [test_api::class, 'change_confirm_0'])->name('axios_change_confirm_0');//apagar api
Route::get('/test_api/change_confirm_1', [test_api::class, 'change_confirm_1'])->name('axios_change_confirm_1');//encender api

//Lista de fibreros por Whatsapp
Route::get('/test_api/lista_fibreros', [test_api::class, 'lista_fibreros'])->name('axios_lista_fibreros');

//Creacion de soporte
Route::post('/test_api/soporte/{id_user}', [test_api::class, 'soporte'])->name('axios_soporte');
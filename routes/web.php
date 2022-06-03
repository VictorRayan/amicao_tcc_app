<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return view('home');
});

Route::get('/institucional', function(){
    return view('institucional');
});

Route::get('/institucional/pets', [PetsController::class, 'listPets']);

Route::get('/institucional/requisicoes', function(){
    return view('requisicoes');
});

Route::get('/institucional/pets/excluir/{id}',[PetsController::class, 'deletePet']);

Route::post('/institucional/pets/alterar/{id}', [PetsController::class, 'updatePet']);

Route::get('/institucional/pets/cadastrar', function(){
    return view('cadastrar_pet');
});


Route::post('/institucional/pets/cadastrar/add', [PetsController::class, 'insertPet']);





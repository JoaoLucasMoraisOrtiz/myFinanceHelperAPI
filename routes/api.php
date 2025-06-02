<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancasController;

// Rota de autenticação (não protegida)
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas por autenticação
Route::middleware('auth.api')->group(function () {
    // Rotas para dados financeiros completos
    Route::get('/financas', [FinancasController::class, 'getAll']);
    Route::post('/financas', [FinancasController::class, 'updateAll']);
    
    // Rotas específicas para receitas
    Route::get('/receitas', [FinancasController::class, 'getReceitas']);
    Route::post('/receitas', [FinancasController::class, 'addReceita']);
    Route::put('/receitas/{id}', [FinancasController::class, 'updateReceita']);
    Route::delete('/receitas/{id}', [FinancasController::class, 'deleteReceita']);
    
    // Rotas específicas para despesas
    Route::get('/despesas', [FinancasController::class, 'getDespesas']);
    Route::post('/despesas', [FinancasController::class, 'addDespesa']);
    Route::put('/despesas/{id}', [FinancasController::class, 'updateDespesa']);
    Route::delete('/despesas/{id}', [FinancasController::class, 'deleteDespesa']);
    
    // Rotas para planejamento
    Route::get('/planejamento', [FinancasController::class, 'getPlanejamento']);
    Route::post('/planejamento/receitas', [FinancasController::class, 'addReceitaPlanejamento']);
    Route::post('/planejamento/despesas', [FinancasController::class, 'addDespesaPlanejamento']);
    Route::put('/planejamento/receitas/{id}', [FinancasController::class, 'updateReceitaPlanejamento']);
    Route::put('/planejamento/despesas/{id}', [FinancasController::class, 'updateDespesaPlanejamento']);
    Route::delete('/planejamento/receitas/{id}', [FinancasController::class, 'deleteReceitaPlanejamento']);
    Route::delete('/planejamento/despesas/{id}', [FinancasController::class, 'deleteDespesaPlanejamento']);
    
    // Rota para validar token
    Route::get('/validate-token', [AuthController::class, 'validateToken']);
});

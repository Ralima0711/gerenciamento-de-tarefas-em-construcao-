<?php

use App\Mail\MensagemTesteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;

Route::get('/', function () {
    return view('bem-vindo');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\TarefaController::class, 'index'])->name('home')->middleware('verified');
Route::resource('/tarefa', TarefaController::class)->middleware('verified');

Route::get('/mensagem-teste', function() {
    return new MensagemTesteMail();
    //Mail::to('robertasaiph@yahoo.com.br')->send(new MensagemTesteMail());
    //return 'E-mail enviado com sucesso!';
});

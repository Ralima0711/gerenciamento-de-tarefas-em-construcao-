<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Mail\NovaTarefaMail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\TarefasExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;




class TarefaController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->get();
        return view('tarefa.index', ['tarefas' => $tarefas]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;
        
        $tarefa = Tarefa::create($dados);

        $destinario = auth()->user()->email; //e-mail do usuário logado (autenticado)
        Mail::to($destinario)->send(new NovaTarefaMail($tarefa));

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;

        if($tarefa->user_id == $user_id) {
            return view('tarefa.edit', ['tarefa' => $tarefa]);
        }

        return view('acesso-negado');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        if(!$tarefa->user_id == auth()->user()->id) {
            return view('acesso-negado');
        }

        $tarefa->update($request->all());
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        if(!$tarefa->user_id == auth()->user()->id) {
            return view('acesso-negado');
        }
        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }

    public function exportacao() {

        return Excel::download(new TarefasExport, 'tarefa.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use Illuminate\Http\Request;

class EditoraController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Editora::class);
        $editoras = Editora::all();
        return view('publishers.index', compact('editoras'));
    }

    public function create()
    {
        $this->authorize('create', Editora::class);
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Editora::class);
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:editoras|max:255',
        ]);
        Editora::create($request->all());
        return redirect()->route('publishers.index')->with('success', 'Publisher added successfully.');
    }

    public function show(Editora $publisher)
    {
        $this->authorize('view', $publisher);
        return view('publishers.show', compact('publisher'));
    }

    public function edit(Editora $publisher)
    {
        $this->authorize('update', $publisher);
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Editora $publisher)
    {
        $this->authorize('update', $publisher);
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:editoras,cnpj,' . $publisher->id . '|max:255',
        ]);
        $publisher->update($request->all());
        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully.');
    }

    public function destroy(Editora $publisher)
    {
        $this->authorize('delete', $publisher);
        $publisher->delete();
        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully.');
    }
}
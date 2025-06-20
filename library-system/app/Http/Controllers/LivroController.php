<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Editora;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Livro::class);

        $query = Livro::query()->with('editora');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
        }

        $livros = $query->paginate(10); // Paginate results
        return view('books.index', compact('livros'));
    }

    public function create()
    {
        $this->authorize('create', Livro::class);
        $editoras = Editora::all();
        return view('books.create', compact('editoras'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Livro::class);

        $request->validate([
            'editora_id' => 'required|exists:editoras,id',
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:255',
            'isbn' => 'required|string|unique:livros|max:255',
            'ano_publicacao' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'qtd_exemplares' => 'required|integer|min:1',
        ]);

        Livro::create($request->all());

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    public function show(Livro $book)
    {
        $this->authorize('view', $book);
        return view('books.show', compact('book'));
    }

    public function edit(Livro $book)
    {
        $this->authorize('update', $book);
        $editoras = Editora::all();
        return view('books.edit', compact('book', 'editoras'));
    }

    public function update(Request $request, Livro $book)
    {
        $this->authorize('update', $book);

        $request->validate([
            'editora_id' => 'required|exists:editoras,id',
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:255',
            'isbn' => 'required|string|unique:livros,isbn,' . $book->id . '|max:255',
            'ano_publicacao' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'qtd_exemplares' => 'required|integer|min:0', // Can be 0 if all are out on loan
            'status' => 'required|string|in:disponivel,emprestado,reservado',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Livro $book)
    {
        $this->authorize('delete', $book);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    // Search functionality can use the index method with a search parameter.
    // public function search(Request $request)
    // {
    //     // This functionality is already integrated into the index method.
    //     // You could create a dedicated method if needed for more complex search.
    // }
}
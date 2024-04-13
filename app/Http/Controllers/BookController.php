<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        $books = Book::when(    // when is a codetional method that accepts a function to handel somthing for further querying, so if the first parameter ($title) is not empty or not null then it will run the function and filter books by title, otherwis it dosn't and it gets all the books.
            $title,
            fn($query, $title) => $query->searchTitle($title)
        );

        $books = match ($filter) { // match is a statement like swich ( if key => do something) and default.
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest() // sort by new books first
        };
        $books = $books->get();

        return view('books.index', ['books' => $books] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view(
            'books.show',
            [
                    // we will get all the reviews from the reviews relation and do somthing more on them..
                    // in this case we will use local quary scope latest() to sort the results of reviews..
                    // by chaining the latest() with $query, we will be working on reviews relation. 
                
                'book' => $book->load([ // model method that allow to load certain relations.
                    'reviews' => fn($query) => $query->latest()
                ])
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

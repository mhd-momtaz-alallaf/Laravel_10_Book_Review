<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;


// add laravel debugBar to the app => λ composer require barryvdh/laravel-debugbar --dev


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

        $cacheKey = 'books:' . $filter . ':' .$title; // dynamic cache key to store alote of quary states.
        $books = cache()->remember($cacheKey , 3600 , fn() => $books->get() ); // to store somthing into cache we need a key and time to how long we will keep the data in cache (here its 1 hour) and the data we want to store.

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
    public function show(Book $book) // if we want cache somthing and not querying it from tha database, we dont have to use the route model binding, we have to pass an $id and find the book by its id then deeling with cache function. 
    {
        $cacheKey = 'book:' . $book->id;

        $book = cache()->remember($cacheKey, 3600, 
            fn() => $book->load([ // "load" its a model method that allow to load certain relations.
            'reviews' => fn($query) => $query->latest()

            // we will get all the reviews from the reviews relation and do somthing more on them..
            // in this case we will use local quary scope latest() to sort the results of reviews..
            // by chaining the latest() with $query, we will be working on reviews relation.
        ]) );
            // we just cached the reviews sorted by latest.
            
        return view('books.show',['book' => $book]);
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

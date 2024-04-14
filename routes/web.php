<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',function (){
    return redirect()->route('books.index');
});

Route::resource('books',BookController::class) // the "Resource Controller" creates the http verbs and all routes of the resource ind its names as standerd way.
    ->only(['index','show']); 

Route::resource('books.reviews',ReviewController::class)
    ->scoped(['review' => 'book']) // scoped means that the review it not exist by itself, it related to the book.( no book => no review)
    ->only(['create','store']);
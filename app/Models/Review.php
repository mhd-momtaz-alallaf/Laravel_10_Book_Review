<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable=[ 'review' , 'rating' ]; // mass assignment to be able to use Create() or Update() method for the models, but if we used query builder with with the updete like (Review::where('id',10)->update(['rating' => 3])) this will not fetch the model first, it will directly preform the changes to the database.

    public function book() // the reverse side of the relation between books and reviews in Book model.
    {
        return $this->belongsTo(Book::class); // one to one relation ( each review -> one book ).
    }

    protected static function booted() // to perform opperations to event handler.
    {
        // when updated-deleted event (after any review is updated-deleted), we have to delete the old cached reviews. 
        
        static::updated( fn(Review $review) => cache()->forget('book:'.$review->book_id) ); // or $review->book->id but this will couse lazy louding because this will fetch the book model with the id and all its properties, so we simply have the colomn book_id in the reviews table and we can use it efficiently.
        static::deleted( fn(Review $review) => cache()->forget('book:'.$review->book_id) );
        
        // there are 3 setuations the event handler will not be triggered and its functions will not be called..
        //      1- when we edit the data directly from the database.
        //      2- when we use mass assignment(using update method with Query Biulder will not fetch the model, it will perform the query directly) like (Review::where('id',10)->update(['rating' => 3]) ) . the case of using model direct update like($review->update(['rating' => 3])) is fine and the cache will delete correctly because the event handler will normally called.
        //      3- when use raw SQL Queries inside laravel or when use SQL Transactions if the Transactions Rooled Back. 
    
    }
}

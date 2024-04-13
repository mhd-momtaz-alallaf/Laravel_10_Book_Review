<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class); // one to many relation (one book -> many reviews).
    }

    // Nameing convintion is important so we start with "scope" word then the actual name like (scopeTitle).
    public function scopeSearchTitle(Builder $query, string $title) :Builder  // "Local Query Scope" is a function that we writes querys (Query Builder querys) inside it then we use it when needed. 
    {
        return $query->where('title','like','%'. $title .'%'); // quarying the titles that contains string "$title"
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder // | QueryBuilder
    {
        return $query->withCount([ //this will add a "reviews_count" colomn in the table 
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc'); // sort by "reviews_count"
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder//| QueryBuilder
    {
        return $query->withAvg([ //this will add a "reviews_avg_rating" colomn in the table 
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc'); // sort by "reviews_avg_rating"
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder //|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews); // to have atleast x number of reviews
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null) // we dont have to return anything because the "$query" of (Builder $query) is passed by reference (that means we are editing the actual instence of $query) 
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query): Builder //|QueryBuilder
    {
        return $query->popular( now()->subMonth(), now() ) // pupular query priority of 1 => sorted by pupular 
            ->highestRated( now()->subMonth(), now() )    // highestRated query priority of 2 
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder //|QueryBuilder
    {
        return $query->popular( now()->subMonths(6), now() )
            ->highestRated( now()->subMonths(6), now() )
            ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder //|QueryBuilder
    {
        return $query->highestRated( now()->subMonth(), now() ) // highestRated query priority of 1 => sorted by haghestRatted 
            ->popular( now()->subMonth(), now() )                // pupular query priority of 2
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder //|QueryBuilder
    {
        return $query->highestRated( now()->subMonths(6), now() )
            ->popular( now()->subMonths(6), now() )
            ->minReviews(5);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function books() // the reverse side of the relation between books and reviews in Book model.
    {
        return $this->belongsTo(Book::class); // one to one relation ( each review -> one book ).
    }
}

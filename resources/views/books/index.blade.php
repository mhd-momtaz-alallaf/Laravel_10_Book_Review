@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    <form method="GET" action="{{route('books.index')}}" class="mb-4 flex items-center space-x-2 h-10">
        <input type="text" name="title" placeholder="Search by Title"
        value="{{request('title')}}" class="input h-10"/>
        <button type="submit" class="btn">Search</button>
        <a href="{{route('books.index')}}" class="btn h-10" >Clear</a>
    </form>

    <div class="filter-container mb-4 flex">
        @php
        $filters = [
            '' => 'Latest',
            'popular_last_month' => 'Popular Last Month',
            'popular_last_6months' => 'Popular Last 6 Months',
            'highest_rated_last_month' => 'Highest Rated Last Month',
            'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
        ];
        @endphp

        @foreach ($filters as $key => $label)
            <a href="{{ route('books.index', ['filter' => $key]) }}" 
            class="{{ request('filter') === $key ? 'filter-item-active' : 'filter-item' }}">
                {{$label}}
            </a>    
        @endforeach
        
    </div>

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                <div
                    class="flex flex-wrap items-center justify-between">
                    <div class="w-full flex-grow sm:w-auto">
                    <a href="{{route('books.show',$book)}}" class="book-title">{{$book->title}}</a>
                    <span class="book-author">by {{$book->author}}</span>
                    </div>
                    <div>
                    <div class="book-rating">
                        {{number_format($book->review_avg_rating, 1)}} <!-- this only will rendered if the attribute exist--> <!-- (, 1) means just 1 number after comma -->
                    </div>
                    <div class="book-review-count">
                        out of {{ $book->reviews_count }} {{ Str::plural('review',$book->review_count) }} <!-- 1 review - 2 reviews - 10 reviews -->
                    </div>
                    </div>
                </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                <p class="empty-text">No books found</p>
                <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
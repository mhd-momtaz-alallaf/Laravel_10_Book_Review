@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    <form method="GET" action="{{route('books.index')}}" class="mb-4 flex items-center space-x-2 h-10">
        <input type="text" name="title" placeholder="Search by Title"
        value="{{request('title')}}" class="input h-10"/>
        <input type="hidden" name="filter" value="{{ request('filter') }}"> <!-- to stay at the wanted active tab after refreshing the page -->
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
            <!-- request()->query() its gets all the querys that request have and returns the result as array--> <!-- this for get the resoults of searsh form and stick with it with every active tab -->
            <!-- ...request()->query() "separate operator" we add "..." before an array to extract its data and add it to the parent array ( we dont want array anside array so we extract the sub array and add its data to the main array) -->
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"   
            class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}"> <!-- to active the tab wanted -->  <!-- (request('filter') === null || $key === '') to active the latest tab -->
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
                        {{ number_format($book->reviews_avg_rating, 1) }} <!-- this only will rendered if the attribute exist--> <!-- (, 1) means just 1 number after comma -->
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
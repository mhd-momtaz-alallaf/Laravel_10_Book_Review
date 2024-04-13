@extends('layouts.app')

@section('content')
  <div class="mb-4">
    <h1 class="mb-2 text-2xl">{{ $book->title }}</h1>

    <div class="book-info">
      <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
      <div class="book-rating flex items-center">
        <div class="flex space-x-2 mr-2 text-sm font-medium text-slate-700">
          <div>{{ number_format($book->reviews_avg_rating, 1) }}</div>
            <div><x-star-rating :rating="$book->reviews_avg_rating"/></div> <!-- Component -->
        </div>
        <span class="book-review-count text-sm text-gray-500 ">
          {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
        </span>
      </div>
    </div>
  </div>

  <div>
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
    <ul>
        @forelse ($book->reviews as $review) <!-- if the reviews relation not passed from the model, this will be lazy loading ( makeing a db quary with each review ) and that will couse a huge number of queries -->
            <li class="book-item mb-4">        <!-- in this case the relation is fetched with the book from the model => just 1 more query -->
            <div>
                <div class="mb-2 flex items-center justify-between">
                <div class="font-semibold">
                  <x-star-rating :rating="$review->rating"/> <!-- Component -->
                </div>
                <div class="book-review-count">
                    {{ $review->created_at->format('M j, Y') }}</div>
                </div>
                <p class="text-gray-700">{{ $review->review }}</p>
            </div>
            </li>
        @empty
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
          </div>
        </li>
        @endforelse

        @if($book->reviews->count())
            <nav class="mb-4">
                {{ $book->reviews->links() }}
            </nav>
        @endif
    </ul>
  </div>
@endsection
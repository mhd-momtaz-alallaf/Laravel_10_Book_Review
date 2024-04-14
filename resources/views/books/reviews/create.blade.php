@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Add review for {{$book->title}}</h1>

    <form  method="POST" action="{{route('books.reviews.store',$book)}}">
        @csrf
        <div class="mb-2">
            <label for="review">Review</label>
            <textarea name="review" id="review" class="input mb-4"
            @class(['border-red-500' => $errors->has('review')])
            >{{$book->review ?? old('review')}}</textarea> <!-- id have to match "for" in label --> <!-- error'review' matchs the name attr-->
            
            @error('review')
                <p class="error mb-4">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-2">
            <label for="rating">Rating</label>
            <select name="rating" id="rating" class="input mb-4" 
                @class(['border-red-500' => $errors->has('rating')]) >
                <option value="">Select a Rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
                
            </select>
            
            @error('rating')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn">Add Review</button>
    </form>


@endsection
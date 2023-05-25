@php
    use Illuminate\Support\Str;
@endphp

@extends('layout')

@section('content')
    <form method="GET" action="/">
      @csrf
      <div class="mb-3">
        <input type="search" class="form-control" id="q" name="q" value={{ request()->get('q', '') }}>
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
    @forelse ($posts as $post)
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">{{ $post["title"] }}</h5>
          <p class="card-text">
            {{ Illuminate\Support\Str::limit($post["content"], $limit = 500, $end = '...') }}
          </p>
        </div>
      </div>
    @empty
      <div class="card mt-3">
        <div class="card-body">
          There are no records available
        </div>
      </div>
    @endforelse
@endsection
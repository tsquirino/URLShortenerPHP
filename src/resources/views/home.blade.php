@extends('template')
@section('title', 'Home')
@section('header', 'URLShortener')

@section('content')

<p class="pull-right">
    <a href="{{ route('logout') }}">Logout</a>
</p>

<!-- List of URLs -->
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Shortened URL</th>
            <th scope="col">Original URL</th>
            <th scope="col">Expiration date</th>
        </tr>
    </thead>
    <tbody>
    @if (isset($urls))
    @foreach ($urls as $url)
    <tr>
      <th scope="row">{{ $url['id'] }}</th>
      <td><a href="{{ $url['shortened_url'] }}" target="_blank">{{ $url['shortened_url'] }}</a></td>
      <td><a href="{{ $url['original_url'] }}" target="_blank">{{ $url['original_url'] }}</a></td>
      <td>{{ $url['expiration_date'] }}</td>
    </tr>
    @endforeach
    @else
    <tr>
      <th scope="row" colspan="0">No URLs found.</th>
    </tr>
    @endif
    </tbody>
</table>

<!-- Pagination links -->
@if (isset($pagination))
<div class="text-center">
  <ul class="pagination">
    @if ($pagination['current'] > 1)
    <li class="page-item">
      <a class="page-link" href="{{ $pagination['_links']['first'] }}">&lt;&lt;</a>
    </li>
    @else
    <li class="page-item disabled">
      <a class="page-link" href="#">&lt;&lt;</a>
    </li>
    @endif
    @if ($pagination['current'] > 1)
    <li class="page-item">
        <a class="page-link" href="{{ $pagination['_links']['prev'] }}">&lt;</a>
    </li>
    @else
    <li class="page-item disabled">
        <a class="page-link" href="#">&lt;</a>
    </li>
    @endif
    <li class="page-item active">
      <a class="page-link" href="#">{{ $pagination['current'] }} <span class="sr-only">/{{ $pagination['total'] }}</span></a>
    </li>
    @if ($pagination['current'] < $pagination['total'])
    <li class="page-item">
        <a class="page-link" href="{{ $pagination['_links']['next'] }}">&gt;</a>
    </li>
    @else
    <li class="page-item disabled">
        <a class="page-link" href="#">&gt;</a>
    </li>
    @endif
    @if ($pagination['current'] < $pagination['total'])
    <li class="page-item">
      <a class="page-link" href="{{ $pagination['_links']['last'] }}">&gt;&gt;</a>
    </li>
    @else
    <li class="page-item disabled">
        <a class="page-link" href="#">&gt;&gt;</a>
    </li>
    @endif
  </ul>
</div>
@endif

@stop
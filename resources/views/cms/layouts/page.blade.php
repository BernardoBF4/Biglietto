@extends('cms.layouts.app')
@section('body')

<header class="header">
  <p>
    {{ auth()->user()->name }}
  </p>
</header>
<aside class="menu">
  <ul>
    @foreach($groups as $group)
    <li>{{ $group->name }}</li>
    @endforeach
  </ul>
</aside>

@yield('content')

@endsection
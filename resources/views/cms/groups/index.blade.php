@extends('cms.layouts.app')
@section('content')

<ul>
  @foreach($groups as $group)
  <li>{{ $group->name }}</li>
  @endforeach
</ul>

@endsection
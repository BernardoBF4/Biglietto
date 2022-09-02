@extends('cms.layouts.app')
@section('content')

<form action="/cms/auth/login" method="post">
  @csrf
  <input type="text" name="email">
  <input type="password" name="password">
  <input type="submit">
</form>

@endsection
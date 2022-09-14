@extends('cms.layouts.app')
@section('content')

<section class="login">
  <form action="{{ route('auth.log_user') }}" class="login__form" method="post">
    <div>
      <label for="name">E-mail</label>
      <input id="name" name="email" type="email">
    </div>
    <div>
      <label for="password">Senha</label>
      <input id="password" name="password" type="password">
    </div>
    <button class="ui-button" type="submit">Logar</button>
    @if (session()->has('message'))
    <p>{{ session()->get('message') }}</p>
    @endif
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </form>
</section>

@endsection
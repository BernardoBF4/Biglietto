<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Biglietto</title>
  <link href="/css/cms/index.css" rel="stylesheet">
</head>

<body>
  <main>
    @yield('body')
  </main>
</body>

</html>
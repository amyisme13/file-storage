<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
</head>

<body>
  <div id="app"></div>

  @auth
  <script>
    localStorage.setItem('authenticated', '1')
  </script>
  @endauth

  <script src="{{ asset(mix('js/app.js')) }}"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Video Player</title>

  <link rel="stylesheet" href="{{ asset(mix('css/player.css')) }}">
</head>

<body>
  <main class="h-screen">
    <video controls crossorigin playsinline id="player" data-poster="{{ $file->thumbnail_url }}"></video>
  </main>

  <script>
    const source = '{{ $file->video_url }}';
  </script>
  <script src="{{ asset(mix('js/player.js')) }}"></script>
</body>

</html>
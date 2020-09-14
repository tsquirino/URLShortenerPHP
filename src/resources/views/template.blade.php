<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>URLShortener - @yield('title')</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <!-- Page container-->
    <div class="container">

        <!-- Header -->
        <div class="page-header">
            <h1>@yield('header')</h1>
        </div>

        <!-- Display flashed messages-->
        @if (isset($messages) && count($messages) > 0)
        <ul class="list-unstyled">
        @foreach ($messages as $message)
            <li class="alert alert-success">{{ $message }}</li>
        @endforeach
        </ul>
        @endif

        <!-- Page contents -->
        @yield('content')

    </div>
</body>
</html>
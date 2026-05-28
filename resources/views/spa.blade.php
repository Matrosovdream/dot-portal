<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DOT Portal</title>
    @vite(['resources/spa/main.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>

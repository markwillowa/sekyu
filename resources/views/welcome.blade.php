<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Sekyu</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body class="bg-slate-50 text-slate-900">

@include('public.navbar')

@include('public.hero')

@include('public.featured-jobs')

@include('public.how-it-works')

@include('public.stats')

@include('public.footer')

</body>
</html>

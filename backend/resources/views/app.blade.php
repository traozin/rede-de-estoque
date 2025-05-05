<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
    @viteReactRefresh
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>
    @inertia
    <div id="app" data-page="{{ json_encode($page) }}"></div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
    @vite('resources/js/app.js') <!-- Vite -->
</head>

<body>
    <div id="app" data-page="{{ json_encode($page) }}"></div> <!-- React será montado aqui -->
</body>

</html>
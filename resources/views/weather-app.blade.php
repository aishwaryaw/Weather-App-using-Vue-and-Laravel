<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Weather-vue-app</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://rawgithub.com/darkskyapp/skycons/master/skycons.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1" defer></script>
       
        <link href="/css/main.css" rel="stylesheet">
        <script src="/js/app.js" defer></script>
       
      
        </head>


<body class="bg-blue-300">

<div id="app">

<weather-component></weather-component>

</div>

</body>
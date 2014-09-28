<?php
use DebugBar\StandardDebugBar;

$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();
echo $debugbarRenderer->render();
?>
<html lang="en">
    <head>

    {{ HTML::style('css/main.css'); }}
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" ></script>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
    </head>
    <body>
    {{--Служебные сообщения от контролеров пользователю.
    Если ничего нет - ничего и не выводим--}}
    @if (isset($message))
       <div class="message">{{$message}}</div>
    @endif

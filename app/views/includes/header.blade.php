<html lang="en">
    <head>
        {{ HTML::script('js/jquery-2.1.1.js'); }}
        {{ HTML::style('css/main.css'); }}
        <meta charset="UTF-8">
        <title>@yield('title')</title>
    </head>
    <body>
    {{--Служебные сообщения от контролеров пользователю.
    Если ничего нет - ничего и не выводим--}}
    @if (isset($message))
       <div class="message">{{$message}}</div>
    @endif

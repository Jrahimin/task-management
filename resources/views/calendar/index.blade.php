@extends('layouts.master')
        <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id="token" content="{{csrf_token()}}">
</head>
<body>

<div id="app2">
    <calendar-header></calendar-header>
    <div class="container">
        <router-view></router-view>
    </div>
</div>

<script src="{{asset('js/calendar.js')}}"></script>
</body>
</html>

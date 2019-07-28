@extends('layouts.master')
<!doctype html>
<head>
    <style>

    </style>
</head>
<body>
<div id="allWorkOrder">

</div>

<div class="content">
    <div id="addWorkOrder">
        <div class="container">
            <work-order-add></work-order-add>
        </div>
    </div>
</div>

<script src="{{asset('js/work-order.js')}}"></script>
</body>
</html>

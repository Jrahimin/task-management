@extends('layouts.master')
        <!doctype html>
<head>
    <style>
        .form-control{
            height: 34px;
        }
    </style>
</head>
<body>

<div class="content">
    <div id="addCustomer">
            <customer></customer>
    </div>
</div>

<script src="{{asset('js/customer.js')}}"></script>
</body>
</html>

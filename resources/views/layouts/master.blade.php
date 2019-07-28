<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


    <!-- Jquery Ui -->
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" />

    <!-- Bootstrap Datepicker css -->
    <link href={{ asset('css/bootstrap-datepicker.css')}} rel="stylesheet"/>

    <!-- Select2 -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
    
    <style>
        body{
            background-color: #eceeef;
        }
        .table td, th{
            text-align: center;
        }
        ul li{
            list-style: none;
        }
        a{
            color: whitesmoke;
        }
        #customerDiv{
            background-color: #E1F5FE;
            padding: 2%;
            border-radius: 2%;
            margin-left: -2%;
        }
        #workOrderLeft{
            background-color: #E1F5FE;
            padding: 2%;
            margin-right: 3%;
            border-radius: 2%;
        }
        #workOrderRight{
            background-color: #CFD8DC;
            padding: 3%;
            border-radius: 2%;
        }
    </style>


</head>

<body>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @yield('pageTitle')
        </h1>

        @yield('breadcrumbs')
    </section>

    <!-- Main content -->
    <section class="content">
        @yield('content')
    </section>

    {{--<footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 0.3.0
        </div>

        <strong>Copyright &copy; 2017-{{date('Y')}} <a href="#">Fire Solution</a>.</strong> All rights reserved.
    </footer>--}}
</div>


<!-- Scripts -->
<!-- JS Scripts -->

<!--   Core JS Files   -->
<script src="{{ asset('js/jquery.min.js')  }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset("js/tag-it.js")}}" type="text/javascript" charset="utf-8"></script>
<script src={{ asset('js/bootstrap.min.js')}} type="text/javascript"></script>


<!-- Data table -->
<script type="text/javascript" src="{{ asset('DataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset("DataTables/mark.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("js/datatables.mark.js") }}"></script>
<!-- Select 2 -->
<script src="{{ asset('js/select2.min.js') }}"></script>

<!-- Bootstrap DatePicker JS -->
<script src={{ asset('js/bootstrap-datepicker.js')}} type="text/javascript"></script>




@yield('additionalJS')

</body>
</html>
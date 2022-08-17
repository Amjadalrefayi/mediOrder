

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	 <link rel="icon" type="image/png" href= {{ asset('dashboard/img/favicon.ico') }}>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Support DashBoard</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href= {{ asset('dashboard/css/bootstrap.min.css') }} rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href={{ asset('dashboard/css/animate.min.css') }} rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href={{ asset('dashboard/css/light-bootstrap-dashboard.css?v=1.4.0') }} rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href={{ asset('dashboard/css/demo.css') }} rel="stylesheet" />

    <link rel="stylesheet" href={{ asset('https://fonts.googleapis.com/icon?family=Material+Icons') }}>
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href={{ asset('dashboard/css/pe-icon-7-stroke.css') }} rel="stylesheet" />


     {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bootstrap CRUD Data Table for Database with Modal Form</title>
     <link rel="stylesheet" href={{ asset('https://fonts.googleapis.com/css?family=Roboto|Varela+Round') }}>
     <link rel="stylesheet" href={{ asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css') }}>

      <link rel="stylesheet" href={{ asset('https://fonts.googleapis.com/icon?family=Material+Icons') }}>

    <link rel="stylesheet" href={{asset('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css') }}>

     <script src={{ asset('https://code.jquery.com/jquery-3.5.1.min.js') }}></script>
    <script src={{ asset('https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js') }}></script>
    <script src={{ asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js') }}></script>  --}}
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }
        .table-responsive {
            margin: 30px 0;
        }
        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            min-width: 1000px;
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }
        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            min-width: 100%;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }
        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }
        .table-title .btn-group {
            float: right;
        }
        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }
        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }
        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }
        table.table tr th, table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }
        table.table tr th:first-child {
            width: 60px;
        }
        table.table tr th:last-child {
            width: 100px;
        }
        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }
        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }
        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }
        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }
        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }
        table.table td a:hover {
            color: #2196F3;
        }
        table.table td a.edit {
            color: #FFC107;
        }
        table.table td a.delete {
            color: #F44336;
        }
        table.table td i {
            font-size: 19px;
        }
        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }
        .pagination {
            float: right;
            margin: 0 0 5px;
        }
        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }
        .pagination li a:hover {
            color: #666;
        }
        .pagination li.active a, .pagination li.active a.page-link {
            background: #03A9F4;
        }
        .pagination li.active a:hover {
            background: #0397d6;
        }
        .pagination li.disabled i {
            color: #ccc;
        }
        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }
        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }
        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }
        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }
        .custom-checkbox label:before{
            width: 18px;
            height: 18px;
        }
        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }
        .custom-checkbox input[type="checkbox"]:checked + label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }
        .custom-checkbox input[type="checkbox"]:checked + label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }
        .custom-checkbox input[type="checkbox"]:checked + label:after {
            border-color: #fff;
        }
        .custom-checkbox input[type="checkbox"]:disabled + label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }
        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }
        .modal .modal-header, .modal .modal-body, .modal .modal-footer {
            padding: 20px 30px;
        }
        .modal .modal-content {
            border-radius: 3px;
            font-size: 14px;
        }
        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }
        .modal .modal-title {
            display: inline-block;
        }
        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }
        .modal textarea.form-control {
            resize: vertical;
        }
        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }
        .modal form label {
            font-weight: normal;
        }
        </style>


</head>

<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image={{asset('dashboard/img/sidebar-5.jpg') }}>

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="" class="simple-text">
                    Medi Order
                </a>
            </div>

            <ul class="nav">
                <li >
                    <a href={{route('complaintstable')}}>
                        <i class="pe-7s-graph"></i>
                        <p>Complaints table</p>
                    </a>
                </li>
                <li>
                    <a href={{route('allordertable')}}>
                        <i class="pe-7s-user"></i>
                        <p>Orders table</p>
                    </a>
                </li>
                <li>
                    <a href={{route('allcustomers')}} >
                        <i class="pe-7s-user"></i>
                        <p>Customers table</p>
                    </a>
                </li>
                <li>
                    <a href={{route('alldrivers')}} >
                        <i class="pe-7s-user"></i>
                        <p>Drivers table</p>
                    </a>
                </li>
                <li class="active">
                    <a href={{route('allpharmacies')}} >
                        <i class="pe-7s-user"></i>
                        <p>Pharmacies table</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Supporter Panel </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href={{route('logout')}}>
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-xl">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2><b>Pharmacies</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href={{ route('blockedpharmacy') }} class="btn btn-success" data-toggle="modal"><i class="material-icons">block</i> <span>Banned Pharmacies</span></a>
                                <form class="btn btn-success" style="display: flex; justify-content :end!important" action="{{ route('searchCustomer') }}" method="POST">
                                    <input type="text" placeholder="Search.." name="searchWord" style="color: black" required/>
                                    <button style=" background-color: white;
                                    color: black; type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>

                     @foreach ($pharmacies as $pharmacy)
                            <tr>

                                <td>{{$pharmacy->id}}</td>
                                <td>{{$pharmacy->name}}</td>
                                <td>{{$pharmacy->email}}</td>
                                <td>{{$pharmacy->phone}}</td>
                                <td>{{$pharmacy->location}}</td>
                                <td style="display:flex">

                                     {{--<form action="{{route('editdriverpage',$driver)}}" method="get">
                                        @csrf

                                        <button type="submit"> <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></button>
                                        </form>

                                     {{-- <a href="{{route('editpharmacypage',$pharmacy)}}" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>--}}

                                    <form action="{{ route('destroypharmacy',$pharmacy->id)}}" method="POST">
                                    @csrf
                                     @method('DELETE')
                                         <button type="submit"><i class="material-icons" style="font-size:20px;color:red">block</i></button>
                                    </form>
                                </td>
                            </tr>

            @endforeach


                        </tbody>




                    </table>
                     <div class="clearfix">

                        @if ($pharmacies->hasPages())
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                @if ($pharmacies->onFirstPage())
                                <li class="page-item disabled">
                                    <a class="page-link" href="#"
                                       tabindex="-1">Previous</a>
                                </li>
                                @else
                                <li class="page-item"><a class="page-link"
                                    href="{{ $pharmacies->previousPageUrl() }}">
                                          Previous</a>
                                  </li>
                                @endif

                                @foreach ($pharmacies as $element)
                                @if (is_string($element))
                                <li class="page-item disabled">{{ $element }}</li>
                                @endif

                                @if (is_array($element))
                                @foreach ($pharmacies as $page => $url)
                                @if ($page == $pharmacies->currentPage())
                                <li class="page-item active">
                                    <a class="page-link">{{ $page }}</a>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link"
                                       href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endif
                                @endforeach
                                @endif
                                @endforeach

                                @if ($pharmacies->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link"
                                       href="{{ $pharmacies->nextPageUrl() }}"
                                       rel="next">Next</a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                                @endif
                            </ul>
                          </nav>
                            @endif
                    </div>
                </div>
            </div>
        </div>




    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script type="text/javascript">
    	$(document).ready(function(){

        	demo.initChartist();

        	$.notify({
            	icon: 'pe-7s-gift',
            	message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."

            },{
                type: 'info',
                timer: 4000
            });

    	});
	</script>

</html>

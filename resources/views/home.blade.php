@extends('layouts.app')
@section('content')
    <div>
        <div class="d-flex" id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <div class="sidebar-heading">
                    <img src="{{ asset('/images/trashguard.png') }}" class="img-responsive" alt="">
                </div>
                <div class="list-group list-group-flush tg-menu">
                    <a href="/" data-name="Reports" class="list-group-item list-group-item-action bg-light"><i class="ti-folder"></i>&nbsp; Reports</a>
                    <a href="/p/ongoing" data-name="On-going" class="list-group-item list-group-item-action bg-light"><i class="ti-reload"></i>&nbsp; ON-GOING</a>
                    <a href="/p/solved" data-name="Solved" class="list-group-item list-group-item-action bg-light"><i class="ti-check"></i>&nbsp; Solved</a>
                    <a href="/p/declined" data-name="Declined" class="list-group-item list-group-item-action bg-light"><i class="ti-close"></i>&nbsp; Declined</a>
                    <a href="/p/archive" data-name="Archive" class="list-group-item list-group-item-action bg-light"><i class="ti-trash"></i>&nbsp; Archive</a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <button class="btn btn-primary btn-menu" id="menu-toggle"><i class="ti-menu"></i></button>
                    <span class="navbar-text hidden navbar-page-name" >
                        <strong>@{{ current_page }}</strong>
                    </span>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            
                            <li class="nav-item">
                                <button type="button" @click="$router.push('/addReport')" class="btn btn-outline-primary navbar-btn"><i class="ti-plus"></i>&nbsp; Add Report</button>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
                <router-view :key="$route.fullPath"></router-view>
            </div><!-- /#page-content-wrapper -->
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
@endsection
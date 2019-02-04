@extends('layouts.app')
@section('content')
<style type="text/css">
    .nav-link {
        display: block;
        padding: .5rem 1rem;
        color: #fff;
    }
</style>
<div class="container">
    <div class="container-fluid px-0">
        <div class="row collapse show no-gutters d-flex h-100 position-relative">
            <div class="col-3 p-0 h-100 w-sidebar navbar-collapse collapse d-none d-md-flex sidebar">
                <!-- fixed sidebar -->
                <div class="navbar-dark text-white position-fixed h-100 align-self-start w-sidebar" style="width: 15%; background: #f1c40f">
                    <h6 class="px-3 pt-3">
                        <img src="{{asset('images/trashguard.png')}}" style="width: 100%; padding: 20px;">
                    </h6>

                    <ul class="nav flex-column flex-nowrap text-truncate" style="padding-left: 20px; font-size: 20px;">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">REPORTS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">ON-GOING</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">SOLVED</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">DECLINED</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col p-3">
                <h3>Content..</h3>
                <div>
                    McfloatMcfloatMcfloatMcfloatMcfloatMcfloatMcfloatMcfloatMcfloatMcfloat
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

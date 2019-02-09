@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/nav-style.css') }}">
<div class="container">
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <nav id="spy">
                <ul class="sidebar-nav nav">
                    <li class="sidebar-brand">
                        <img src="{{asset('images/trashguard.png')}}" style="width: 80%; padding: 30px;">
                    </li>
                    <li class="li-nav">REPORTS</li>
                    <li class="li-nav">ON-GOING</li>
                    <li class="li-nav">SOLVED</li>
                    <li class="li-nav">DECLINED</li>
                </ul>
            </nav>
        </div>

        <!-- Page content -->
        <div id="page-content-wrapper">
            <div class="tab-pane active" id="report_tab" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="row">
                    <div class="col-md-4" style="background-color: #f1f2f6; padding: 30px; position: fixed; height: 100%; z-index: 100; width: 25%;">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="w-100" style="background: #a0a0a0; font-weight: 500; border: none;">
                                    <option>Barangay</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="w-100" style="background: #a0a0a0; font-weight: 500; border: none;">
                                    <option>Sitio</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12" style="overflow-y: auto; height: 82%;">
                            <ul>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                                <li>asdasdasdasd</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8" style="background-color: #fff; position: fixed; height: 100%; padding-left: 26%; width: 100%">
                        <div class="row">
                            <div class="col-md-6" style="padding: 20px 0px 0px; padding-bottom: 10px;">
                                <i class="lnr lnr-checkmark-circle" aria-hidden="true" style="font-size: 20px; font-weight: bold; color: grey; padding: 20px"></i>  
                                <i class="lnr lnr-cross-circle" aria-hidden="true" style="font-size: 20px; font-weight: bold; color: grey; padding: 20px"></i>   
                                <i class="lnr lnr-trash" aria-hidden="true" style="font-size: 20px; font-weight: bold; color: grey; padding: 20px"></i>
                            </div>
                            <div class="col-md-6" style="padding: 20px 0px 0px;">
                                <div style="float: right; margin-right: 5%;">
                                    <input type="text" name="" placeholder="Search...">
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            {{-- <div class="tab-content">
                <div class="tab-pane active" id="report_tab" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="col-md-4" style="background-color: #f1f2f6; padding: 30px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <select>
                                        <option>Barangay</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select>
                                        <option>Sitio</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-8" style="background-color: #fff">
                            activeactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactiveactive
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="ongoing_tab" role="tabpanel" aria-labelledby="nav-home-tab">asdasdasdas</div>
                <div class="tab-pane fade show" id="solved_tab" role="tabpanel" aria-labelledby="nav-home-tab">asdasdasdasdasdasdddddddddddddddddddddddas</div>
                <div class="tab-pane fade show" id="declined_tab" role="tabpanel" aria-labelledby="nav-home-tab">asdasdasdasdasdasdddddddddddddddddd123333333333dddddas</div>
            </div> --}}
        </div>
    </div>       
</div>
@endsection

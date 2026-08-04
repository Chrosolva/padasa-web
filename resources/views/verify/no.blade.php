@extends('verify.app')

@section('header-title')
    Dokumen Terverifikasi
@endsection

@section('header-content')
    <style>
        .main-banner:after,
        .main-banner::before {
            top: 0;
        }
    </style>
@endsection

@section('main-header')
    <div class="row">
        <div class="col-lg-12" style="display: flex; justify-content: center;">
            <div class="row" style="text-align: center">
                <div class="left-content header-text wow">
                    <img src="{{ url('assets/verify/images/icons8-cancel-160.png') }}"
                        style="width:10%; min-width:200px; padding-bottom:20px"> <br>
                    <span style="font-size: 30px"> Document Tidak Terverifikasi </span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
@endsection

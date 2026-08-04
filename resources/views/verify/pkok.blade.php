@extends('verify.app')

@section('header-title')
    Dokumen Terverifikasi
@endsection

@section('header-content')
    <style>
        #about {
            margin: 0;
        }

        .about-us {
            padding: 0;
        }

        .about-us .services .item {
            margin: 0;
        }

        .about-us .services .item h3 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .about-us p {
            font-size: 18px;
        }

        .main-banner .left-content h6 {
            color: #8cc44b;
        }

        .main-banner .left-content h2 {
            font-size: 32px;
            word-break: break-all;
        }

        .main-banner p {
            margin-top: 20px !important;
            margin-bottom: 30px !important;
        }

        .section-heading p, ul {
            margin-top: 15px !important;
            margin-bottom: 45px !important;
        }

        .section-heading h4:first {
            margin-top: 50px !important;
        }

        .main-banner:after,
        .main-banner::before {
            top: 0;
        }

        .main-banner {
            padding: 80px 0 0 0;
        }

        .label {
            padding: 0.5em 0.8em;
            font-weight: 400;
        }

        .label-success {
            background-color: #198754;
        }
        .left-content p {
            font-size: 20px;
             text-transform: uppercase;
        }
        .section-heading h3 {
            text-decoration: underline;
        }
        .section-heading p {
            text-align: justify;
        }
        .header-text h4 {
            font-variant: small-caps;
        }

        @media (max-width: 992px) {
            .main-banner .left-content {
                margin-bottom: 0!important;
            }
            ul {
                margin: 0!important;
                padding: 0!important;
            }
            li {
                list-style-type: none!important;
                text-align: center!important;
                padding: 2px;
            }
        }
        .main-banner .left-content h6 {
            font-size: 28px;
        }
    </style>
@endsection

@section('main-header')
<div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="left-content header-text wow ">
                    <h6>
                        <img src="{{ url('assets/verify/images/icons8-approval-96.png') }}"
                            style="width:40px; margin-right: 10px;">
                        Dokumen Terverifikasi
                    </h6> 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
    <hr>
    <div class="row" style="margin: 50px 0;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12 align-self-top">
                    <div class="left-content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                        <h4>MASTER POKOK TANAMAN</h4>
                        <ul>
                            <li>{{ $data->NamaPT }}</li>
                            <li>{{ $data->Lokasi }}</li>
                            
                            <li>{{ $data->kode }}</li>
                            <li>{{ $data->info }}</li>
                            <li>{{ $data->koordinatlb.' , '.$data->koordinatls }} </li>
                            <li>{{ $data->jenis_bibit }}</li>
                            <!-- <li>{{ $data->keterangan }}</li> -->
                        </ul>
 
                        
                    </div>
                    <!-- <div class="left-content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                        <h4>PENYAKIT</h4>
                        <ul>
                            <li>{{$data->penyakit}}</li>
                        </ul>                        
                    </div>-->
                    <br>
                    <div class="left-content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s"  >
                        <h4>APLIKASI TINDAKAN</h4>
                        <ul style="text-align:left">
                        @foreach ($rincian as $rinci)
                            <li> {{ substr($rinci->TglTindakan,0,10) }}: [{{ $rinci->NoTindakan }}] {{ $rinci->Tindakan }} </li>
                        @endforeach
                        </ul>                        
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <hr>
@endsection

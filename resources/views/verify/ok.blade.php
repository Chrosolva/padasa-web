@extends('verify.app')
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
            padding: 80px 15px 0 15px;
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
                    <h2>
                        {{ $data->ca }} </h2>
                    <h4>
                        Contract Agreement <br>
                        {{ $data->p1_PT }} </h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
    <hr>
    <div class="row" style="margin-top: 50px;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 align-self-top">
                    <div class="left-content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                        <h4>Pihak I</h4>
                        <p>{{ $data->p1_nama }} <br>{{ $data->p1_jabatan }} {{ $data->p1_PT }} </p>
                        <br>
                        <h4>Pihak II</h4>
                        <p>{{ $data->p2_nama }} <br>{{ $data->p2_jabatan }} {{ $data->p2_PT }} </p>
                        <span class="badge bg-success" style="margin-bottom:38px">Ditandatangani pada  {{ $ca_date }} </span>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                        <div class="section-heading">
                            <h3>Rincian Perjanjian</h3>
                            <h4 style="padding-top: 50px;">Jenis Pekerjaan</h4>
                            <p> {{ $data->jenis_pekerjaan }} </p>
                            <h4>Dasar Pelaksanaan Pekerjaan</h4>
                            <p> {{ $data->dasar_pekerjaan }} </p>
                            <h4>Lokasi Pekerjaan</h4>
                            <p> {{ $data->lokasi_pekerjaan }} </p>
                            <h4>Masa Pelaksanaan Pekerjaan</h4>
                            <p>{{$diff}} hari kerja dari tanggal {{ $start_date }} sampai dengan tanggal
                                {{ $end_date }} terhitung mulai dari tanggal Berita Acara Serah Terima.
                            </p>
                            <h4>Teknis Pekerjaan</h4>
                            @if (count($detail) > 1)
                                <ul>
                                    @foreach ($detail as $item)
                                        <li>{{ $item->uraian }}</li>
                                    @endforeach
                                </ul>
                            @elseif (count($detail) == 1)
                                {{ $detail[0]->uraian }}
                            @else
                                -
                            @endif
                            <h4>Jaminan Pekerjaan</h4>
                            <p> {{ $data->jaminan_pekerjaan }} </p>
                            <h4>Jangka Waktu Pembayaran</h4>
                            <p> {{ $data->jangka_waktu_pembayaran }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
@endsection

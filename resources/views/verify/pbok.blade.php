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
                        {{ $data->KodeDepartemen }}/PB/{{substr($data->TglPB,2,2)}}/{{$month}}/{{ substr($data->NoPB,6,4) }} </h2>
                        <h3>{{ substr($data->TglPB,0,10)  }} </h3>
                    <h4>
                        Permintaan Bayar <br>
                        {{ $data->NamaPT }} </h4>
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
                <div class="col-lg-4 align-self-top">
                    <div class="left-content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                        <h4>Dibayarkan kepada</h4>
                        <p>{{ $data->VendorCode }} - {{ $data->VendorName }}<br>{{ $data->Address }} </p>
                        <br>
                        <h4>Untuk Pembayaran</h4>
                        <p>{{ $data->Pekerjaan }} </p>
                        <br>
                        <h4>Uang Sejumlah</h4>
                        <p>Rp {{ number_format($data->Jumlah) }} </p>
                        <!-- <span class="badge bg-success" style="margin-bottom:38px">Disetujui oleh  {{ $data->Disetujui }} / {{ $data->JabatanDisetujui }} </span> -->
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                        <div class="section-heading">
                            <h3>Rincian Pembayaran</h3>
                            @if (count($rincian_pembayaran) > 1)
                                <ol style="padding-top:10px">
                                    @foreach ($rincian_pembayaran as $item)
                                        <li>{{ $item->Uraian }} <br> &nbsp; <b>{{ number_format($item->Harga) }}</b> </li>
                                    @endforeach
                                </ol>
                            @elseif (count($detail) == 1)
                                {{ $item[0]->Uraian }} <br>{{ number_format($item[0]->Harga) }}
                            @else
                                -
                            @endif
                           
                           @if (count($rincian) > 1)
                           <table class="table table-striped" style="margin-top:50px">
                            <thead>
                                <tr>
                                <th scope="col">Dokumen</th>
                                <th scope="col">Uraian</th>
                                <th scope="col">Tgl</th>
                                <th scope="col">SDTgl</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rincian as $item)
                                <tr>
                                    <td>{{ $item->Dokumen}} </td>
                                    <td>{{ $item->Uraian}}</td>
                                    <td>{{ substr ($item->Tgl,0,10)}}</td>
                                    <td>{{ substr ($item->SDTgl,0,10)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                            @endif
                            <h4 style="margin-top:30px">Catatan</h4>
                            <p>{{ $data->Catatan }}</p>
                            <div>
                                <small>Dibuat: {{ substr($data->TglBuat,0,20) }}</small> <br>
                                <small>Diubah: {{ strlen($data->TglUbah)>0? substr($data->TglUbah,0,20):"-" }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
@endsection

@extends('dashboard.app')

@section('header-title')
    Produksi Gabungan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Jumlah TBS olah, hasil produksi CPO dan Inti per hari
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/timbangan/produksi-gabungan') }}">
                    <div class="form-group">
                        <label for="dari_tanggal">Dari Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampai_tanggal">Sampai Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"  value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @for ($i = 0; $i < count($kebun); $i++)
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $kebun[$i]->nama_lengkap }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart_{{ $i }}" style="height:400px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

        @for ($i = 0; $i < count($kebun); $i++)
            generateBarChartJS('barChart_{{ $i }}',
                [
                    @foreach ($produksi_gabungan[$i] as $row)
                        '{{ date('d-m-Y', strtotime($row->TglProduksi)) }}' ,
                    @endforeach
                ], 
                [
                    {
                        label : "Jumlah TBS Olah",
                        data : [
                            @foreach ($produksi_gabungan[$i] as $row)
                                {{ $row->JumlahTBSOlah }} ,
                            @endforeach
                        ]
                    },
                    {
                        label : "Produksi CPO",
                        data : [
                            @foreach ($produksi_gabungan[$i] as $row)
                                {{ $row->ProduksiCPO }} ,
                            @endforeach
                        ]
                    },
                    {
                        label : "Produksi Inti",
                        data : [
                            @foreach ($produksi_gabungan[$i] as $row)
                                {{ $row->ProduksiInti }} ,
                            @endforeach
                        ]
                    }
                ]
            );
        @endfor
    </script>
@endsection
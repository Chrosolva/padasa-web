@extends('dashboard.app')

@section('header-title')
    Kontrak Penjualan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Total kontrak perjualan untuk setiap produk per hari
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/kontrak-penjualan') }}">
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
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $kebun[$i]->nama_lengkap }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="lineChart_{{ $i }}" style="height:400px"></canvas>
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
            <?php  $total_row = count($kontrak_penjualan[$i]); ?>
            generateLineChartJS('lineChart_{{ $i }}',
                [
                    @for ($j = 0; $j < $total_row; $j++)
                        <?php $status = true; ?>
                        @for ($k = 0; $k < $j && $status == true; $k++)
                            @if ($kontrak_penjualan[$i][$j]->TglKontrak == $kontrak_penjualan[$i][$k]->TglKontrak)
                                <?php $status = false; ?>
                            @endif
                        @endfor

                        @if ($status == true)
                            '{{ date('d-m-Y', strtotime($kontrak_penjualan[$i][$j]->TglKontrak)) }}' ,
                        @endif
                    @endfor
                ],
                [
                    @foreach ($list_produk[$i] as $produk)
                        {
                            label : "{{ $produk->Produk }}",
                            data : [
                                @foreach ($kontrak_penjualan[$i] as $row)
                                    @if ($row->Produk == $produk->Produk)
                                        {{ $row->TotalKontrak }} ,
                                    @endif
                                @endforeach
                            ]
                        },
                    @endforeach
                ]
            );
        @endfor
    </script>
@endsection

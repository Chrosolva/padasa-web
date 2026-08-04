@extends('dashboard.app')

@section('header-title')
    Harga Ideal
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Harga Ideal
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/harga-idealNew') }}">
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
            {{-- TELDA --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">TELDA</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_TD" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- KALSA --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">KALSA</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_K1" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- KALDA --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">KALDA</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_K2" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- KOKAR --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">KOKAR</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_KK" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RICKO --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">RICKO</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_RK" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- PASER --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">PASER</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_PS" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        // Telda
        generateLineChartJS('lineChart_TD',
            [
                @foreach ($harga_idealTD as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealTD as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealTD as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealTD as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );

        // KALSA
        generateLineChartJS('lineChart_K1',
            [
                @foreach ($harga_idealK1 as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealK1 as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealK1 as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealK1 as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );

        // KALDA
        generateLineChartJS('lineChart_K2',
            [
                @foreach ($harga_idealK2 as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealK2 as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealK2 as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealK2 as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );

        // KOKAR
        generateLineChartJS('lineChart_KK',
            [
                @foreach ($harga_idealKK as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealKK as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealKK as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealKK as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );

        // RICKO
        generateLineChartJS('lineChart_RK',
            [
                @foreach ($harga_idealRK as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealRK as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealRK as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealRK as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );

        // PASER
        generateLineChartJS('lineChart_PS',
            [
                @foreach ($harga_idealPS as $row)
                    '{{ date('d-m-Y', strtotime($row->TGLPEMBELIAN)) }}' ,
                @endforeach
            ],
            [
                {
                    label : "Harga Rata-Rata Pembelian",
                    data : [
                        @foreach ($harga_idealPS as $row)
                            {{ $row->HARGA_RATA_RATA_PEMBELIAN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price",
                    data : [
                        @foreach ($harga_idealPS as $row)
                            {{ $row->IDEALPRICE }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                },
                {
                    label : "Ideal Price Zero Margin",
                    data : [
                        @foreach ($harga_idealPS as $row)
                            {{ $row->IDEALPRICEZEROMARGIN }} ,
                        @endforeach
                    ],
                    ticks: {
                        stepSize: 1000
                    }
                }
            ]
        );
    </script>
@endsection

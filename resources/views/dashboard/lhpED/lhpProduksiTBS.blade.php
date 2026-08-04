@extends('dashboard.app')

@section('header-title')
    Produksi TBS [Kg]
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Produksi TBS [Kg]
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpProduksiAngkutTBS') }}">
                    <div class="row">
                        <div class="form-group">
                            <label for="dari_tanggal">Dari Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sampai_tanggal">Sampai Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"  value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selectkebun">Kebun : </label>
                            <select class="form-control" id="selectkebun" name="selectkebun">
                                <option value="2200">TELDA</option>
                                <option value="2300">KALSA</option>
                                <option value="2400">KALDA</option>
                                <option value="2500">KOKAR</option>
                                <option value="2600">MITRA KOKAR</option>
                                <option value="3200">RICKO</option>
                                <option value="4200">MUARA</option>
                                <option value="5200">PASER</option>
                                <option value="6200">LANGGAI</option>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        {{-- <div class="form-group form-inline">
                            <a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenExport') }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="chart">
                        <canvas id="lineChart_1" style="height:300px"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <?php
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200';
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">TGL</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">BERAT BERSIH TBS (KG)</th>
                                        <th style="font-size: 12px;">BERAT BERSIH BRONDOLAN (KG)</th>
                                        <th style="font-size: 12px;">BERAT BERSIH TOTAL (KG)</th>
                                        <th style="font-size: 12px;">JLH TRIP</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProduksiTBS as $row)
                                        <tr>
                                            <td>{{$row->TGL}}</td>
                                            <td>{{$namakebun}}</td>
                                            <td style="text-align: right;">{{number_format($row->BERAT_BERSIH_TBS,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->BERAT_BERSIH_BRONDOLAN,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->BERATBERSIH,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->JLH_TRIP,0,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        makeDataTableResponsive('table-data2', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";

        generateLineChartJSCustom('lineChart_1',
                [
                    @foreach ($lhp_ProduksiTBS as $row)
                        '{{ date('d-m-Y', strtotime($row->TGL)) }}' ,
                    @endforeach
                ],
                [
                    {
                        label : "Produksi [KG]",
                        data : [
                            @foreach ($lhp_ProduksiTBS as $row)
                                {{ $row->BERATBERSIH }} ,
                            @endforeach
                        ],
                        ticks: {
                            stepSize: 100000
                        }
                    }
                ],
                [
                    {
                        legend: {
                            position : "right"
                        },
                        elements : {
                            line : {
                                cubicInterpolationMode: 'monotone'
                            }
                        },
                        scales : {
                            xAxes : [{
                                display: true,
                                text: 'TGL'
                                ,ticks : {}
                            }],
                            yAxes : [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Produksi'
                                },
                                ticks : {
                                    beginAtZero : true,
                                    userCallback : function(value, index, values) {
                                        return formatNumberWithFormat(value, (labels.length == 0 ? 1 : -1));
                                    }
                                }
                            }]
                        }
                    }
                ]
            );
    </script>
@endsection

@extends('dashboard.app')

@section('header-title')
    Produksi TBS Per 2 Jam
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Produksi TBS Per 2 Jam
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/lhpProduksiTBS2Jam') }}">
                    <div class="row">
                        <div class="form-group">
                            <label for="per_tanggal">Per Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selectkebun">Kebun : </label>
                            <select class="form-control" id="selectkebun" name="selectkebun">
                                <option value="2200">TELDA</option>
                                <option value="2300">KALSA</option>
                                <option value="2400">KALDA</option>
                                <option value="2500">KOKAR</option>
                                <option value="3200">RICKO</option>
                                <option value="5200">PASER</option>
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

            {{-- <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="chart">
                        <canvas id="lineChart_1" style="height:300px"></canvas>
                    </div>
                </div>
            </div> --}}

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
                                        <th style="display: none;"></th>
                                        <th style="font-size: 12px;">AFDELING</th>
                                        <th style="font-size: 12px;">TANGGAL</th>
                                        <th style="font-size: 12px;">JAM 5-7</th>
                                        <th style="font-size: 12px;">JAM 7-9</th>
                                        <th style="font-size: 12px;">JAM 9-11</th>
                                        <th style="font-size: 12px;">JAM 11-13</th>
                                        <th style="font-size: 12px;">JAM 13-15</th>
                                        <th style="font-size: 12px;">JAM 15-17</th>
                                        <th style="font-size: 12px;">JAM 17-19</th>
                                        <th style="font-size: 12px;">JAM 19-21</th>
                                        <th style="font-size: 12px;">JAM 21-23</th>
                                        <th style="font-size: 12px;">JAM 23-01</th>
                                        <th style="font-size: 12px;">TOTAL</th>
                                        <th style="font-size: 12px;">DAILY BUDGET</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_Produksi2Jam as $row)
                                        <tr>
                                            <?php
                                                $date = date_create($row->TANGGAL);
                                            ?>
                                            <td style="display: none;">{{$row->BARIS}}</td>
                                            <td>{{$row->AFDELING}}</td>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J05_07,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J07_09,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J09_11,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J11_13,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J13_15,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J15_17,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J17_19,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J19_21,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J21_23,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->J23_01,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->DAILYBUDGET,0,',','.')}}</td>
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
        setValidationRangeDatePicker('per_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        makeDataTableResponsive('table-data2', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>
@endsection

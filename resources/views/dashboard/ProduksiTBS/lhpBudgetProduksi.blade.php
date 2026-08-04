@extends('dashboard.app')

@section('header-title')
    Budget Produksi
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Budget Produksi
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpBudgetProduksi') }}">
                    <div class="row">
                        <div class="form-group">
                            <label for="select_tahun"> Tahun : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="number" min="1900" max="2099" step="1" value="2022" class="form-control" id="sampai_tanggal" name="sampai_tanggal" />
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
                                        <th style="font-size: 12px;">TGL MASUK</th>
                                        <th style="font-size: 12px;">{{$namakebun}}</th>
                                        <th style="font-size: 12px;">P3_{{$namakebun}}</th>
                                        @if($selectkebun == '5200')
                                            <th style="font-size: 12px;">MUARA</th>
                                            <th style="font-size: 12px;">LANGGAI</th>
                                        @endif
                                        @if($selectkebun == '2500' || $selectkebun == '3200' || $selectkebun == '5200')
                                            <th style="font-size: 12px;">MITRA {{$namakebun}}</th>
                                        @endif
                                        @if($selectkebun == '5200')
                                            <th style="font-size: 12px;">MITRA MUARA</th>
                                            <th style="font-size: 12px;">MITRA LANGGAI</th>
                                        @endif

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProduksiTBS as $row)
                                        <tr>
                                            <?php
                                                $date = date_create($row->TGLMASUK);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            @if($selectkebun == '2200')
                                                <td style="text-align: right;">{{number_format($row->TELDA,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->P3_TELDA,0,',','.')}}</td>
                                            @elseif($selectkebun == '2300')
                                                <td style="text-align: right;">{{number_format($row->KALSA,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->P3_KALSA,0,',','.')}}</td>
                                            @elseif($selectkebun == '2400')
                                                <td style="text-align: right;">{{number_format($row->KALDA,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->KALDA,0,',','.')}}</td>
                                            @elseif($selectkebun == '2500')
                                                <td style="text-align: right;">{{number_format($row->KOKAR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->P3_KOKAR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MITRA_KOKAR,0,',','.')}}</td>
                                            @elseif($selectkebun == '3200')
                                                <td style="text-align: right;">{{number_format($row->RICKO,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->P3_RICKO,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MITRA_RICKO,0,',','.')}}</td>
                                            @elseif($selectkebun == '5200')
                                                <td style="text-align: right;">{{number_format($row->PASER,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->P3_PASER,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MUARA,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->LANGGAI,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MITRA_PASER,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MITRA_MUARA,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->MITRA_LANGGAI,0,',','.')}}</td>
                                            @endif
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
    </script>
@endsection

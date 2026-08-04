@extends('dashboard.app')

@section('header-title')
    Pencapaian Produksi TBS Kebun Sendiri
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Pencapaian Produksi TBS Kebun Sendiri
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/lhpPencapaianProduksiTBS') }}">
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
                                <option value="SEMUA">SEMUA</option>
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

            <div class="col-md-8">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <?php
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    // $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200';
                                    // echo $selectkebun;
                                    // echo $selecttype;

                                    $totalbudget = 0; 
                                    $totalrealisasi = 0;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">TGL MASUK</th>
                                        <th style="font-size: 12px;">ESTATE</th>
                                        <th style="font-size: 12px;">BUDGET</th>
                                        <th style="font-size: 12px;">REALISASI</th>
                                        <th style="font-size: 12px;">PENCAPAIAN [%]</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_PencapaianProduksi as $row)
                                        <tr>
                                            <?php
                                                $date = date_create($row->TGLMASUK);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            <td>{{$row->ESTATE}}</td>
                                            <td style="text-align: right;">{{number_format($row->BUDGET,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->REALISASI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->PENCAPAIAN,0,',','.')}}</td>
                                        </tr>
                                        <?php 
                                            $totalbudget += $row->BUDGET; 
                                            $totalrealisasi += $row->REALISASI; 
                                        ?>
                                    @endforeach
                                    <tr>
                                        <td><strong>TOTAL</strong></td>
                                        <td><strong>-</strong></td>
                                        <td style="text-align:right;"><strong>{{number_format($totalbudget)}}</strong></td>
                                        <td style="text-align:right;"><strong>{{number_format($totalrealisasi)}}</strong></td>
                                        <td style="text-align:right;"><strong>-</strong></td>
                                    </tr>
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

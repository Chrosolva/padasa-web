@extends('dashboard.app')

@section('header-title')
    TBS Tersedia
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            TBS Tersedia
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/lhpTBSTersedia') }}">
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
                                        <th style="font-size: 12px;display:none;" rowspan="2">BARIS</th>
                                        <th style="font-size: 12px;" rowspan="2">KEBUN</th>
                                        <th style="font-size: 12px;" rowspan="2">TGL</th>
                                        <th style="font-size: 12px; text-align: center;" colspan="5">RESTAN TBS (KG)</th>
                                        <th style="font-size: 12px; text-align: center;" colspan="5">TBS MASUK (KG)</th>
                                        <th style="font-size: 12px;" rowspan="2">TOTAL TBS (KG)</th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">Inti</th>
                                        <th style="font-size: 12px;">Mitra</th>
                                        <th style="font-size: 12px;">Pihak 3</th>
                                        <th style="font-size: 12px;">Pihak 3 Seinduk</th>
                                        <th style="font-size: 12px;">Pihak 3 Seinduk Mitra</th>
                                        <th style="font-size: 12px;">Inti</th>
                                        <th style="font-size: 12px;">Mitra</th>
                                        <th style="font-size: 12px;">Pihak 3</th>
                                        <th style="font-size: 12px;">Pihak 3 Seinduk</th>
                                        <th style="font-size: 12px;">Pihak 3 Seinduk Mitra</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_TBSTersedia as $row)
                                        <tr>
                                            <?php
                                                $date = date_create($row->TANGGAL);
                                            ?>
                                            <td style="display: none;" rowspan="2">{{$row->BARIS}}</td>
                                            <td>{{$row->KEBUN}}</td>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RESTAN_INTI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RESTAN_MITRA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RESTAN_PIHAK3,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RESTAN_PIHAK3_SEINDUK,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RESTAN_PIHAK3_SEINDUK_MITRA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_MASUK_INTI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_MASUK_MITRA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_MASUK_PIHAK3,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_MASUK_PIHAK3_SEINDUK,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_MASUK_PIHAK3_SEINDUK_MITRA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_TERSEDIA,0,',','.')}}</td>
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
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
    </script>
@endsection

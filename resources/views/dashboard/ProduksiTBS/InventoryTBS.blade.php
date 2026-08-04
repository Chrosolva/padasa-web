@extends('dashboard.app')

@section('header-title')
    Inventory TBS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Inventroy TBS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/inventoryTBS') }}">
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
                                <option value="3200">RICKO</option>
                                <option value="5200">PASER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Jenis : </label>
                            <select class="form-control" id="type" name="type">
                                <option class="form-control" value ="0" > Harian</option>
                                <option class="form-control" value ="1" > Bulanan</option>
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
                                        @if($selecttype = '0')
                                        <th style="font-size: 12px;">TANGGAL</th>
                                        <th style="font-size: 12px;">NAMA KEBUN</th>
                                        <th style="font-size: 12px;">STATUS</th>
                                        <th style="font-size: 12px;">SALDO AWAL TBS</th>
                                        <th style="font-size: 12px;">TBS MASUK</th>
                                        <th style="font-size: 12px;">TBS OLAH</th>
                                        <th style="font-size: 12px;">PENYESUAIAN</th>
                                        <th style="font-size: 12px;">SALDO AKHIR TBS</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProdukSampingan as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->TGL);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            @if($row->SITE_ID == '2200') 
                                                <td>TELDA</td>
                                            @elseif($row->SITE_ID == '2300') 
                                                <td>KALSA</td>
                                            @elseif($row->SITE_ID == '2400') 
                                                <td>KALDA</td>
                                            @elseif($row->SITE_ID == '2500') 
                                                <td>KOKAR</td>
                                            @elseif($row->SITE_ID == '3200') 
                                                <td>RICKO</td>
                                            @elseif($row->SITE_ID == '5200') 
                                                <td>PASER</td>
                                            @endif
                                            <td>{{$row->STATUS}}</td>
                                            <td>{{number_format($row->SALDOAWALTBS,0,',','.')}}</td>
                                            <td>{{number_format($row->TBSMASUK,0,',','.')}}</td>
                                            <td>{{number_format($row->TBSOLAH,0,',','.')}}</td>
                                            <td>{{number_format($row->PENYESUAIAN,0,',','.')}}</td>
                                            <td>{{number_format($row->SALDOAKHIRTBS,0,',','.')}}</td>
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
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";

    </script>
@endsection

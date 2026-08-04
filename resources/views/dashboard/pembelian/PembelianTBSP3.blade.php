@extends('dashboard.app')

@section('header-title')
    Pembelian TBS Pihak 3 
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Pembelian TBS Pihak 3
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/PembelianTBSP3') }}">
                    
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

                    <div class="form-group">
                        <label for="type">Jenis : </label>
                        <select class="form-control" id="type" name="type">
                            <option class="form-control" value ="0" > Harian</option>
                            <option class="form-control" value ="1" > Bulanan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
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
                                    $selecttype = isset($_REQUEST['type']) ? $_REQUEST['type'] :  '0';
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;display:none;">DETAIL</th>
                                        <th style="font-size: 12px;">SITE</th>
                                        @if($selecttype == '0')
                                            <th style="font-size: 12px;">TGL MASUK</th>
                                        @elseif($selecttype =='1')
                                            <th style="font-size: 12px;">TAHUN</th>
                                            <th style="font-size: 12px;">BULAN</th>
                                        @endif
                                        <th style="font-size: 12px;">SUPPLIERCODE</th>
                                        <th style="font-size: 12px;">TBS TERIMA [KG]</th>
                                        @if($selecttype == '0')
                                            <th style="font-size: 12px;">HARGA TBS REALISASI [Rp.]</th>
                                        @elseif($selecttype =='1')
                                            <th style="font-size: 12px;">HARGA BELI AVERAGE [Rp.]</th>
                                        @endif
                                        <th style="font-size: 12px;">TOTAL [RP.]</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($Pembelian_TBSP3 as $row)
                                        <tr>
                                            <td style= "display:none;">{{$row->DETAIL}}</td>
                                            @if($row->SITE_ID == '2200') 
                                                <td>TELDA</td>
                                            @elseif($row->SITE_ID == '2300')
                                                <td>KALSA</td>
                                            @elseif($row->SITE_ID == '2400')
                                                <td>KALDA</td>
                                            @elseif ($row->SITE_ID == '2500')
                                                <td>KOKAR</td>
                                            @elseif ($row->SITE_ID == '3200')
                                                <td>RICKO</td>
                                            @elseif ($row->SITE_ID == '5200')
                                                <td>PASER</td>
                                            @elseif($row->DETAIL == 'T') 
                                                <td>TOTAL</td>
                                            @endif
                                            @if ($selecttype == '0')
                                                <?php 
                                                    $date = date_create($row->TGLMASUK);
                                                ?>
                                                @if($row->DETAIL == 'T') 
                                                    <td>-</td>
                                                @else
                                                    <td>{{date_format($date, 'd/m/y')}}</td> 
                                                @endif
                                            @elseif($selecttype == '1') 
                                                <td style="text-align: right;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td style="text-align: right;">{{number_format($row->BULAN,0,'.','.')}}</td>
                                            @endif
                                            @if($row->DETAIL == 'T') 
                                                <td>-</td>
                                            @else
                                                <td>{{$row->SUPPLIERCODE}}</td>
                                            @endif
                                            <td style="text-align: right;">{{number_format($row->TBSTERIMA,0,',','.')}}</td>
                                            @if($selecttype == '0')
                                                <td style="text-align: right;">{{number_format($row->HARGA_BELI_TBS_REALISASI,0,',','.')}}</td>
                                            @elseif($selecttype =='1')
                                                <td style="text-align: right;">{{number_format($row->HARGA_BELI_AVERAGE,0,',','.')}}</td>
                                            @endif
                                            <td style="text-align: right;">{{number_format($row->TOTAL,0,',','.')}}</td>
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
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        
    </script>
@endsection

@extends('dashboard.app')

@section('header-title')
    Persediaan Produk Sampingan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Persediaan Produk Sampingan
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpByProduct') }}">
                    <div class="row">
                        {{-- <div class="form-group">
                            <label for="pilih_tanggal">Pilih Tanggal : </label>
                            <div class="input-group date input-inline">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="pilih_tanggal" name="pilih_tanggal" value="{{ Request::get('pilih_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div> --}}
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
                        <div class="form-group">
                            <label for="selectproduct">Produk : </label>
                            <select class="form-control" id="selectproduct" name="selectproduct">
                                <option value="SEMUA">SEMUA</option>
                                <option value="PALM ACID OIL">PALM ACID OIL</option>
                                <option value="Crude Palm Oil (CP1)">Crude Palm Oil (CP1)</option>
                                <option value="Cangkang">Cangkang</option>
                                <option value="Fiber">Fiber</option>
                                <option value="Janjangan Kosong">Janjangan Kosong</option>
                                <option value="Abu">Abu</option>
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
                                        <th style="font-size: 12px;" rowspan="2">TGL</th>
                                        @if($selectkebun == 'SEMUA')
                                            <th style="font-size: 12px;" rowspan="2">NAMA_KEBUN</th>
                                        @endif
                                        <th style="font-size: 12px;" rowspan="2">PRODUK</th>
                                        <th style="font-size: 12px;" rowspan="2">SALDO AWAL (KG)</th>   
                                        <th style="font-size: 12px;" rowspan="2">MASUK / DITERIMA (KG)</th>
                                        <th style="font-size: 12px;text-align:center;" colspan="4">KELUAR </th>
                                        <th style="font-size: 12px;" rowspan="2">SALDO AKHIR (KG)</th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">DIPAKAI (KG)</th>
                                        <th style="font-size: 12px;">KIRIM JUAL (KG)</th>
                                        <th style="font-size: 12px;">TRANSFER OUT (KG)</th>
                                        <th style="font-size: 12px;">TOTAL (KG)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProdukSampingan as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->TGL);
                                            ?>
                                            @if($row->KETERANGAN == 'RINCI')
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                @if($selectkebun == 'SEMUA')
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
                                                @endif
                                                <td>{{$row->PRODUK}}</td>
                                                @if($row->PRODUK == 'PALM ACID OIL' && $row->SALDOAKHIR > $row->TONASE_MAX) 
                                                    <td class="bg-red">{{number_format($row->SALDOAWAL,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->MASUK,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->PAKAI,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->KIRIM_JUAL,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->TRANSFER_OUT,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->TOTAL,0,',','.')}}</td>
                                                    <td class="bg-red">{{number_format($row->SALDOAKHIR,0,',','.')}}</td>
                                                @else 
                                                    <td>{{number_format($row->SALDOAWAL,0,',','.')}}</td>
                                                    <td>{{number_format($row->MASUK,0,',','.')}}</td>
                                                    <td>{{number_format($row->PAKAI,0,',','.')}}</td>
                                                    <td>{{number_format($row->KIRIM_JUAL,0,',','.')}}</td>
                                                    <td>{{number_format($row->TRANSFER_OUT,0,',','.')}}</td>
                                                    <td>{{number_format($row->TOTAL,0,',','.')}}</td>
                                                    <td>{{number_format($row->SALDOAKHIR,0,',','.')}}</td>
                                                @endif
                                            @elseif($row->KETERANGAN == 'TOTAL')
                                                <td><strong>TOTAL</strong></td>
                                                @if($selectkebun == 'SEMUA')
                                                    @if($row->SITE_ID == '2200') 
                                                        <td><strong>TELDA</strong></td>
                                                    @elseif($row->SITE_ID == '2300') 
                                                        <td><strong>KALSA</strong></td>
                                                    @elseif($row->SITE_ID == '2400') 
                                                        <td><strong>KALDA</strong></td>
                                                    @elseif($row->SITE_ID == '2500') 
                                                        <td><strong>KOKAR</strong></td>
                                                    @elseif($row->SITE_ID == '3200') 
                                                        <td><strong>RICKO</strong></td>
                                                    @elseif($row->SITE_ID == '5200') 
                                                        <td><strong>PASER</strong></td>
                                                    @endif
                                                @endif
                                                <td><strong>{{$row->PRODUK}}</strong></td>
                                                <td><strong></strong></td> 
                                                <td><strong>{{number_format($row->MASUK,0,',','.')}}</strong></td>
                                                <td><strong>{{number_format($row->PAKAI,0,',','.')}}</strong></td>
                                                <td><strong>{{number_format($row->KIRIM_JUAL,0,',','.')}}</strong></td>
                                                <td><strong>{{number_format($row->TRANSFER_OUT,0,',','.')}}</strong></td>
                                                <td><strong>{{number_format($row->TOTAL,0,',','.')}}</strong></td>
                                                <td><strong></strong></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h5>untuk Palm Acid Oil, Jumlah PAO Max didalam kolam limbah (JKT-PEU/SE/00/II/2022) : </h5>
                        <ul>
                            <li> <h5> TELDA 30 TON </h5> </li>
                            <li> <h5> KALSA 30 TON </h5> </li> 
                            <li> <h5> KALDA 50 TON </h5> </li>
                            <li> <h5> KOKAR 50 TON </h5> </li>
                            <li> <h5> RICKO 30 TON </h5> </li> 
                            <li> <h5> PASER 50 TON </h5> </li>
                        </ul>
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
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'PALM ACID OIL'; ?>";
    </script>
@endsection
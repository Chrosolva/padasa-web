@extends('dashboard.app')

@section('header-title')
    Mutasi Persediaan CPO / Inti
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Mutasi Persediaan CPO / Inti
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/MutasiCI') }}">
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
                        <div class="form-group">
                            <label for="type">Jenis : </label>
                            <select class="form-control" id="type" name="type">
                                <option class="form-control" value ="0" > Harian</option>
                                <option class="form-control" value ="1" > Bulanan</option>
                            </select>
                        </div> 

                        <div class="form-group">
                            <label for="selectproduct">Produk : </label>
                            <select class="form-control" id="selectproduct" name="selectproduct">
                                <option class="form-control" value ="SEMUA" > SEMUA</option>
                                <option class="form-control" value ="CPO" > CPO</option>
                                <option class="form-control" value ="PK" > Inti</option>
                            </select>
                        </div>

                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        
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
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'SEMUA';
                                    $selecttype = isset($_REQUEST['type']) ? $_REQUEST['type'] :  '0';
                                    $selectproduk = isset($_REQUEST['selectproduct']) ? $_REQUEST['selectproduct'] :  'SEMUA';

                                    $total_saldo = 0;
                                    $total_produksi = 0;
                                    $total_pakai = 0;
                                    $total_pengiriman = 0;
                                    $total_Total = 0;
                                    $total_TIO = 0; 
                                    $total_penyesuaian = 0;
                                    $total_sisaakhir = 0;
                                    
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="display:none;font-size: 12px;" rowspan="2">SITE_ID</th>
                                        @if ($selecttype == '0') 
                                            <th style="font-size: 12px;" rowspan="2">TGL LHP</th>
                                        @else
                                            <th style="font-size: 12px;" rowspan="2">TAHUN</th>
                                            <th style="font-size: 12px;" rowspan="2">BULAN</th>
                                        @endif
                                        <th style="font-size: 12px;" rowspan="2">PRODUK</th>
                                        <th style="font-size: 12px;" rowspan="2">STATUS</th>
                                        <th style="font-size: 12px;" rowspan="2">SALDO AWAL [KG]</th>
                                        <th style="font-size: 12px;" rowspan="2">PRODUKSI [KG]</th>
                                        <th style="font-size: 12px; text-align:center;" colspan="3">KELUAR</th>
                                        <th style="font-size: 12px; text-align:center;" colspan="2">+ / -</th>
                                        <th style="font-size: 12px;" rowspan="2">SISA AKHIR [KG]</th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">PAKAI [KG]</th>
                                        <th style="font-size: 12px;">PENGIRIMAN [KG]</th>
                                        <th style="font-size: 12px;">TOTAL [KG]</th>
                                        <th style="font-size: 12px;">TRANSFER IN OUT [KG]</th>
                                        <th style="font-size: 12px;">PENYESUAIAN [KG]</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_mutasiCI as $row)
                                        <tr>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            @if ($selecttype == '0') 
                                                <?php 
                                                    $date = date_create($row->TGLLHP);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                            @else
                                                <td>{{$row->TAHUN}}</td>
                                                <td>{{$row->BULAN}}</td>
                                            @endif
                                            
                                            <td>{{$row->GROUPNAME}}</td>
                                            <td>{{$row->STATUS}}</td>
                                            <td>{{number_format($row->SALDO_AWAL,0,',','.')}}</td>
                                            <td>{{number_format($row->PRODUKSI,0,',','.')}}</td>
                                            <td>{{number_format($row->PAKAI,0,',','.')}}</td>
                                            <td>{{number_format($row->PENGIRIMAN,0,',','.')}}</td>
                                            <td>{{number_format($row->TOTAL,0,',','.')}}</td>
                                            <td>{{number_format($row->TRANSFER_IN_OUT,0,',','.')}}</td>
                                            <td>{{number_format($row->PENYESUAIAN,0,',','.')}}</td>
                                            <!-- <td>{{number_format($row->SISA,0,',','.')}}</td> -->
                                            @if($row->SISA > 100000 && $row->GROUPNAME == 'PK')
                                                <td class ="bg-red">{{number_format($row->SISA,0,',','.')}}</td>
                                            @else
                                                <td>{{number_format($row->SISA,0,',','.')}}</td>
                                            @endif
                                        </tr>

                                        <?php 
                                            if($selecttype == '1' && $selectproduk != 'SEMUA' && $selectkebun != 'SEMUA')  {
                                                $total_saldo += $row->SALDO_AWAL;
                                                $total_produksi += $row->PRODUKSI;
                                                $total_pakai += $row->PAKAI;
                                                $total_pengiriman += $row->PENGIRIMAN;
                                                $total_Total += $row->TOTAL;
                                                $total_TIO += $row->TRANSFER_IN_OUT;
                                                $total_penyesuaian += $row->PENYESUAIAN;
                                                $total_sisaakhir += $row->SISA;
                                            }
                                        ?>
                                    @endforeach
                                    @if ($selecttype == '1' && $selectproduk != 'SEMUA' && $selectkebun != 'SEMUA')
                                        <tr>
                                            <td style="display:none;">TOTAL</td>
                                            <td>TOTAL</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>{{number_format($total_saldo,0,',','.')}}</td>
                                            <td>{{number_format($total_produksi,0,',','.')}}</td>
                                            <td>{{number_format($total_pakai,0,',','.')}}</td>
                                            <td>{{number_format($total_pengiriman,0,',','.')}}</td>
                                            <td>{{number_format($total_Total,0,',','.')}}</td>
                                            <td>{{number_format($total_TIO,0,',','.')}}</td>
                                            <td>{{number_format($total_penyesuaian,0,',','.')}}</td>
                                            <!-- <td>{{number_format($total_sisaakhir,0,',','.')}}</td> -->
                                            @if($total_sisaakhir > 100000 && $selectproduk == 'PK')
                                                <td class ="bg-red">{{number_format($total_sisaakhir,0,',','.')}}</td>
                                            @else
                                                <td>{{number_format($total_sisaakhir,0,',','.')}}</td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <h5>NB: Batas Toleransi Saldo Sisa Akhir Maksimal Adalah 100.000 KG</h5>
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
        // makeDataTableResponsive('table-data2', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'SEMUA'; ?>";

    </script>
@endsection

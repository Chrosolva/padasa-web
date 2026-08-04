@extends('dashboard.app')

@section('header-title')
    Mutasi Persediaan TBS di PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Mutasi Persediaan TBS di PMKS 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/MutasiTBS') }}">
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
                                    
                                    // echo $selecttype;

                                    $total_saldoawal = 0;
                                    $total_produksi = 0;
                                    $total_olah = 0;
                                    $total_penyesuaian = 0;
                                    $total_sisaakhir = 0;

                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="display:none;font-size: 12px;">SITE_ID</th>
                                        @if ($selecttype == '0') 
                                            <th style="font-size: 12px;">TGL LHP</th>
                                        @else
                                            <th style="font-size: 12px;">TAHUN</th>
                                            <th style="font-size: 12px;">BULAN</th>
                                        @endif
                                        <th style="font-size: 12px;">STATUS</th>
                                        <th style="font-size: 12px;">SALDO AWAL</th>
                                        <th style="font-size: 12px;">PRODUKSI</th>
                                        <th style="font-size: 12px;">OLAH</th>
                                        <th style="font-size: 12px;">PENYESUAIAN</th>
                                        <th style="font-size: 12px;">SISA AKHIR</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_mutasiTBS as $row)
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
                                            
                                            <td>{{$row->STATUS}}</td>
                                            @if ($selecttype == '0') 
                                                <td>{{number_format($row->SALDOAWAL,0,',','.')}}</td>
                                            @else
                                                <td>{{number_format($row->SALDO_AWAL,0,',','.')}}</td>
                                            @endif
                                            <td>{{number_format($row->PRODUKSI,0,',','.')}}</td>
                                            <td>{{number_format($row->OLAH,0,',','.')}}</td>
                                            <td>{{number_format($row->PENYESUAIAN,0,',','.')}}</td>
                                            @if ($selecttype == '0') 
                                                <td>{{number_format($row->SISAAKHIR,0,',','.')}}</td>
                                            @else
                                                <td>{{number_format($row->SISA_TBS,0,',','.')}}</td>
                                            @endif
                                        </tr>
                                        <?php 
                                            if( $selectkebun != 'SEMUA')  {
                                                if($selecttype == '0') {
                                                    $total_saldoawal += $row->SALDOAWAL;
                                                }
                                                else {
                                                    $total_saldoawal += $row->SALDO_AWAL;
                                                }
                                                $total_produksi += $row->PRODUKSI;
                                                $total_olah += $row->OLAH;
                                                $total_penyesuaian += $row->PENYESUAIAN;

                                                if($selecttype == '0') {
                                                    $total_sisaakhir += $row->SISAAKHIR;
                                                }
                                                else {
                                                    $total_saldoawal += $row->SISA_TBS;
                                                }
                                            }
                                        ?>
                                    @endforeach
                                    @if ($selectkebun != 'SEMUA')
                                        <tr>
                                            <td style="display:none;">TOTAL</td>
                                            @if ($selecttype == '0') 
                                                <td>-</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                            <td>TOTAL</td>
                                            <td>{{number_format($total_saldoawal,0,',','.')}}</td>
                                            <td>{{number_format($total_produksi,0,',','.')}}</td>
                                            <td>{{number_format($total_olah,0,',','.')}}</td>
                                            <td>{{number_format($total_penyesuaian,0,',','.')}}</td>
                                            <td>{{number_format($total_sisaakhir,0,',','.')}}</td>
                                        </tr>
                                    @endif
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
        // makeDataTableResponsive('table-data2', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";

    </script>
@endsection

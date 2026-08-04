@extends('dashboard.app')

@section('header-title')
    Realisasi Rendemen Minyak Sawit Vs Target Hitungan P3
@endsection

@section('main-content')
<style>
    /* ====== TABLE ALIGNMENT FIX ====== */

    /* Header alignment */
    table.dataTable thead th {
        text-align: center !important;
        vertical-align: middle !important;
        /* white-space: nowrap; */
    }

    /* Date column */
    .col-date {
        text-align: center !important;
        width: 90px;
    }

    /* Text column */
    .col-text {
        text-align: left !important;
        white-space: nowrap;
    }

    /* Numeric column */
    .col-num {
        text-align: right !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    /* Total row highlight */
    .row-total {
        background: #eef5ff !important;
        font-weight: bold;
    }
</style>

    <section class="content-header">
        <h1>
            Realisasi Rendemen Minyak Sawit Vs Target Hitungan P3
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpHitunganP3') }}">
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
                                @for ($i = 0;$i < count($kebun); $i++)
                                    <option class="form-control" value ="{{ $kebun[$i]->nama_DB }}"> {{$kebun[$i]->nama_lengkap}}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- <div class="Row">
                            <br>
                        </div> --}}

                        <div class="form-group">
                            <label for="type">Jenis : </label>
                            <select class="form-control" id="type" name="type">
                                {{-- <option class="form-control" value ="0" > Harian</option> --}}
                                <option class="form-control" value ="1" > Bulanan</option>
                            </select>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='0.35' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="selectharga">Harga : </label>
                            <input type='number' step='100' value='5800' placeholder='5800' id = 'harga' name = 'harga' disabled/>
                        </div>

                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        {{-- <div class="form-group form-inline">
                            <a href="{{ url('/dashboard/lhpexecutive/lhpRealisasiVsTargetExport') }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                    $dom = new DOMDocument();
                    $dom->loadHtml("Index.php");
                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'DBTimbPMKSTD';
                    $selecttype = isset($_REQUEST['type']) ? $_REQUEST['type'] :  '1';
                    $total_realisasitbsolah = 0;
                    $total_cpoproduksitarget = 0;
                    $total_rendementarget = 0;
                    $total_produksirealisasi = 0;
                    $total_rendemenrealisasi = 0;
                    $total_selisihcpo = 0;
                    $total_kerugian = 0;
                    $total_sanksi = 0;
                    $total_restanpabrik = 0;

                    // echo $selectkebun;
                    // echo $selecttype;
                ?>

                @if($selecttype === '1')
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title"><b>Detail Produksi P3 (Bulanan)</b></h3>
                        </div>

                        <div class="box-body table-responsive">
                            <table id="table-data2" class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">BULAN</th>  
                                        <th class="text-center">TAHUN</th>
                                        <th>PMKS</th>
                                        <th>NAMA GRUP</th>
                                        <th class="text-right">TBS OLAH PROPORSI</th>
                                        <th class="text-right">CPO TARGET</th>
                                        <th class="text-right">RENDEMEN KONTRAK (%)</th>
                                        <th class="text-right">CPO REALISASI</th>
                                        <th class="text-right">RENDEMEN REALISASI (%)</th>
                                        {{-- <th class="text-right">SELISIH (KG)</th> --}}
                                        {{-- <th class="text-right">SELISIH RENDEMEN (%)</th> --}}
                                        <th class="text-right">TOTAL (RP)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($lhp_P3Detail as $row)
                                        <tr @if($row->NAMAGRUP === 'TOTAL') class="bg-info" style="font-weight:bold;" @endif>
                                            <td class="text-center">{{ $row->BULAN }}</td>
                                            <td class="text-center">{{ $row->TAHUN }}</td>
                                            <td>{{ $row->PMKS }}</td>
                                            <td>{{ $row->NAMAGRUP }}</td>
                                            <td class="text-right">{{ number_format($row->TBSOLAHPROPORSI,0,',','.') }}</td>
                                            <td class="text-right">{{ number_format($row->CPOTARGET,0,',','.') }}</td>
                                            <td class="text-right">{{ number_format($row->RENDTARGET,2,',','.') }}</td>
                                            <td class="text-right">{{ number_format($row->TBSOLAHREALISASI,0,',','.') }}</td>
                                            <td class="text-right">{{ number_format($row->RENDREALISASI,2,',','.') }}</td>
                                            {{-- <td class="text-right">{{ number_format($row->SELISIHKG,0,',','.') }}</td> --}}
                                            {{-- <td class="text-right">{{ number_format($row->SELISIHRENDEMEN,2,',','.') }}</td> --}}
                                            <td class="text-right">{{ number_format($row->TOTAL,0,',','.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <strong><p style="font-size: 14px;">Perhitungan menggunakan angka dari rendemen kontrak Pihak 3.</p></strong>
                    </div>
                @endif
            </div>
        </div>

        
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        makeDataTableResponsive('table-data1', 0, 'asc', -1);
        makeDataTableResponsive('table-data2', 0, 'asc', -1);   
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '1'; ?>";
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD'; ?>";
        document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35'; ?>";
        document.getElementById('harga').value = "<?php echo isset($_GET['harga']) ? $_GET['harga'] : '5800'; ?>";
        var lhpRvst = <?php echo json_encode($lhp_RvsT); ?>;
        console.log(lhpRvst);
    </script>
@endsection

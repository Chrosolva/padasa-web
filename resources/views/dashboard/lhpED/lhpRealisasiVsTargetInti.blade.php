@extends('dashboard.app')

@section('header-title')
    Realisasi Rendemen Inti Sawit Vs Target
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Realisasi Rendemen Inti Sawit Vs Target
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpRealisasiVsTargetInti') }}">
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
                                <option class="form-control" value ="0" > Harian</option>
                                <option class="form-control" value ="1" > Bulanan</option>
                            </select>
                        </div>

                        {{-- <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='0.35' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="selectharga">Harga : </label>
                            <input type='number' step='100' value='5800' placeholder='5800' id = 'harga' name = 'harga' disabled/>
                        </div> --}}

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
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                                <?php
                                        $dom = new DOMDocument();
                                        $dom->loadHtml("Index.php");
                                        $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'DBTimbPMKSTD';
                                        $selecttype = isset($_REQUEST['type']) ? $_REQUEST['type'] :  '0';
                                        $total_realisasitbsolah = 0;
                                        $total_cpoproduksitarget = 0;
                                        $total_rendementarget = 0;
                                        $total_produksirealisasi = 0;
                                        $total_rendemenrealisasi = 0;
                                        $total_selisihinti = 0;
                                        $total_restanpabrik = 0;

                                        // echo $selectkebun;
                                        // echo $selecttype;
                                ?>
                                <script></script>
                                @if ($selecttype === '0')
                                    <thead>
                                        <tr>
                                            <td style="display: none;">BARIS</td>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;">NAMA GRUP</th>
                                            <th style="font-size: 12px;">REALISASI TBS OLAH (KG)</th>
                                            <th style="font-size: 12px;">PRODUKSI INTI TARGET (KG)</th>
                                            <th style="font-size: 12px;">RENDEMEN INTI TARGET (%)</th>
                                            <th style="font-size: 12px;">PRODUKSI INTI REALISASI (KG)</th>
                                            <th style="font-size: 12px;">RENDEMEN INTI REALISASI (%)</th>
                                            <th style="font-size: 12px;">SELISIH INTI (KG)</th>
                                            <th style="font-size: 12px;">SELISIH RENDEMEN (%)</th>
                                            <th style="font-size: 12px;">RESTAN TBS PABRIK (KG.)</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lhp_RvsT as $row)
                                            <tr>
                                                <td style="display: none;">{{$row->BARIS}}</td>
                                                <?php
                                                    $date = date_create($row->TGL);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td>{{$row->NAMA_GRUP}}</td>
                                                <td style="text-align: right;">{{number_format($row->REALISASI_TBS_OLAH,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->PRODUKSI_INTI_TARGET,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->RENDEMEN_INTI_TARGET,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->PRODUKSI_INTI_REALISASI,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->RENDEMEN_INTI_REALISASI,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->SELISIH_INTI,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->SELISIH_RENDEMEN,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->RESTAN_TBS_PABRIK,0,',','.')}}</td>
                                                <?php
                                                    $total_realisasitbsolah += $row->REALISASI_TBS_OLAH;
                                                    $total_cpoproduksitarget += $row->PRODUKSI_INTI_TARGET;
                                                    $total_rendementarget += $row->RENDEMEN_INTI_TARGET;
                                                    $total_produksirealisasi += $row->PRODUKSI_INTI_REALISASI;
                                                    $total_rendemenrealisasi += $row->RENDEMEN_INTI_REALISASI;
                                                    $total_selisihinti += $row->SELISIH_INTI;
                                                    $total_restanpabrik += $row->RESTAN_TBS_PABRIK;
                                                ?>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @elseif ($selecttype === '1')
                                    <thead>
                                        <tr>
                                            <td style="display: none;">BARIS</td>
                                            <th>BULAN</th>
                                            <th>NAMA GRUP</th>
                                            <th>REALISASI TBS OLAH (KG)</th>
                                            <th>PRODUKSI IS TARGET (KG)</th>
                                            <th>RENDEMEN IS TARGET (%)</th>
                                            <th>PRODUKSI IS REALISASI (KG)</th>
                                            <th>RENDEMEN IS REALISASI (%)</th>
                                            <th>SELISIH IS (KG)</th>
                                            <th>SELISIH RENDEMEN (%)</th>
                                            <th>RESTAN TBS PABRIK (KG.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lhp_RvsT as $row)
                                            @if(str_contains($row->NAMA_GRUP, 'TOTAL'))
                                            <tr>
                                                <td style="display: none;">{{$row->BARIS}}</td>
                                                <td><strong>{{$row->BULAN}}</td></strong>
                                                <td><strong>{{$row->NAMA_GRUP}}</td></strong>
                                                <td><strong>{{number_format($row->REALISASI_TBS_OLAH,0,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->PRODUKSI_INTI_TARGET,0,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->RENDEMEN_INTI_TARGET,2,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->PRODUKSI_INTI_REALISASI,0,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->RENDEMEN_INTI_REALISASI,2,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->SELISIH_INTI,2,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->SELISIH_RENDEMEN,2,',','.')}}</td></strong>
                                                <td><strong>{{number_format($row->RESTAN_TBS_PABRIK,0,',','.')}}</td></strong>
                                                <?php
                                                    $total_realisasitbsolah += $row->REALISASI_TBS_OLAH;
                                                    $total_cpoproduksitarget += $row->PRODUKSI_INTI_TARGET;
                                                    $total_rendementarget += $row->RENDEMEN_INTI_TARGET;
                                                    $total_produksirealisasi += $row->PRODUKSI_INTI_REALISASI;
                                                    $total_rendemenrealisasi += $row->RENDEMEN_INTI_REALISASI;
                                                    $total_selisihinti += $row->SELISIH_INTI;
                                                    $total_restanpabrik += $row->RESTAN_TBS_PABRIK;
                                                ?>
                                            </tr>
                                            @else
                                                <tr>
                                                    <td style="display: none;">{{$row->BARIS}}</td>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td>{{$row->NAMA_GRUP}}</td>
                                                    <td>{{number_format($row->REALISASI_TBS_OLAH,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_INTI_TARGET,0,',','.')}}</td>
                                                    <td>{{number_format($row->RENDEMEN_INTI_TARGET,2,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_INTI_REALISASI,0,',','.')}}</td>
                                                    <td>{{number_format($row->RENDEMEN_INTI_REALISASI,2,',','.')}}</td>
                                                    <td>{{number_format($row->SELISIH_INTI,2,',','.')}}</td>
                                                    <td>{{number_format($row->SELISIH_RENDEMEN,2,',','.')}}</td>
                                                    <td>{{number_format($row->RESTAN_TBS_PABRIK,0,',','.')}}</td>
                                                    <?php
                                                        $total_realisasitbsolah += $row->REALISASI_TBS_OLAH;
                                                        $total_cpoproduksitarget += $row->PRODUKSI_INTI_TARGET;
                                                        $total_rendementarget += $row->RENDEMEN_INTI_TARGET;
                                                        $total_produksirealisasi += $row->PRODUKSI_INTI_REALISASI;
                                                        $total_rendemenrealisasi += $row->RENDEMEN_INTI_REALISASI;
                                                        $total_selisihinti += $row->SELISIH_INTI;
                                                        $total_restanpabrik += $row->RESTAN_TBS_PABRIK;
                                                    ?>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>

                            @if($selecttype === '0')
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead>
                                        <tr>
                                            <td style="display: none;">BARIS</td>
                                            <th colspan="2" style="width:10cm;">Total Keseluruhan</th>
                                            <th>REALISASI TBS OLAH (KG)</th>
                                            <th>PRODUKSI INTI TARGET (KG)</th>
                                            <th>RENDEMEN INTI TARGET (%)</th>
                                            <th>PRODUKSI INTI REALISASI (KG)</th>
                                            <th>RENDEMEN INTI REALISASI (%)</th>
                                            <th>SELISIH INTI (KG)</th>
                                            <th>SELISIH RENDEMEN (%)</th>
                                            <th>TOTAL KERUGIAN (RP.)</th>
                                            <th>TOTAL SANKSI (RP.)</th>
                                            {{-- @if ($selecttype == '0')
                                                <th>RESTAN TBS PABRIK (KG.)</th>
                                            @endif --}}
                                            <th>RESTAN TBS PABRIK (KG.)</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="display: none;">baris</td>
                                            <td colspan="2">Total : </td>
                                            <td>{{number_format($total_realisasitbsolah,0,',','.')}}</td>
                                            <td>{{number_format($total_cpoproduksitarget,0,',','.')}}</td>
                                            <?php
                                                $total_rendementarget = ($total_cpoproduksitarget * 100) / $total_realisasitbsolah;
                                                $total_rendemenrealisasi = ($total_produksirealisasi * 100) / $total_realisasitbsolah;
                                                $total_selisihrendemen = $total_rendemenrealisasi - $total_rendementarget;
                                            ?>
                                            <td>{{number_format($total_rendementarget,2,',','.')}}</td>
                                            <td>{{number_format($total_produksirealisasi,0,',','.')}}</td>
                                            <td>{{number_format($total_rendemenrealisasi,2,',','.')}}</td>
                                            <td>{{number_format($total_selisihinti,2,',','.')}}</td>
                                            <td>{{number_format($total_selisihrendemen,2,',','.')}}</td>
                                            
                                            {{-- @if ($selecttype == '0')
                                                <td>{{number_format($total_restanpabrik,2,',','.')}}</td>
                                            @endif --}}
                                            <td>{{number_format($total_restanpabrik,2,',','.')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <strong><p style="font-size: 14px;">Harga Patokan : 5800 </p></strong> --}}
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        makeDataTableResponsive('table-data1', 0, 'asc', -1);
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD'; ?>";
        // document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35'; ?>";
        // document.getElementById('harga').value = "<?php echo isset($_GET['harga']) ? $_GET['harga'] : '5800'; ?>";
        var lhpRvst = <?php echo json_encode($lhp_RvsT); ?>;
        console.log(lhpRvst);
    </script>
@endsection

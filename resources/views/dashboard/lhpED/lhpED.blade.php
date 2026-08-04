@extends('dashboard.app')

@section('header-title')
    Rendemen MS Per PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Rendemen MS Per PMKS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpEDDetail') }}">
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

                        <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='0.35' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
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

                    {{-- <div class="box-header with-border">
                        
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                                <?php 
                                        $dom = new DOMDocument();
                                        $dom->loadHtml("Index.php");
                                        $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'DBTimbPMKSTD';
                                        $selecttype = isset($_REQUEST['type']) ? $_REQUEST['type'] :  '0';  
                                        // echo $selectkebun;  
                                        // echo $selecttype;
                                ?>
                                <script></script>
                                @if ($selecttype === '0')
                                    @if ($selectkebun === 'DBTimbPMKSTD')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="font-size: 12px;">REND TELDA</th>
                                                <th style="font-size: 12px;">REND TARGET TELDA</th>
                                                <th style="font-size: 12px;">REND P3 TELDA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 TELDA</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td> 
                                                    @if ( round($row->REND_TELDA - $row->REND_TARGET_TELDA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_TELDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_TELDA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_TELDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_TELDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_P3_TELDA - $row->REND_TARGET_P3_TELDA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_TELDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_TELDA,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_TELDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_TELDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSK1')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="display: none; font-size:12px;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND KALSA</th>
                                                <th style="font-size: 12px;">REND TARGET KALSA</th>
                                                <th style="font-size: 12px;">REND P3 KALSA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 KALSA</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td> 
                                                    <td style="display: none;">{{number_format($row->TOLERANSI,2,'.',',')}}</td>
                                                    @if ( round($row->REND_KALSA - $row->REND_TARGET_KALSA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_KALSA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALSA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_KALSA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALSA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_P3_KALSA - $row->REND_TARGET_P3_KALSA ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_KALSA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_KALSA,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_KALSA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_KALSA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif($selectkebun === 'DBTimbPMKSK2')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="display: none; font-size: 12px;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND KALDA</th>
                                                <th style="font-size: 12px;">REND TARGET KALDA</th>
                                                <th style="font-size: 12px;">REND P3 KALDA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 KALDA</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td>
                                                    <td style="display: none;">{{number_format($row->TOLERANSI,2,'.',',')}}</td>
                                                    @if ( round($row->REND_KALDA - $row->REND_TARGET_KALDA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_KALDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALDA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_KALDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_P3_KALDA - $row->REND_TARGET_P3_KALDA  ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_KALDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_KALDA,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_KALDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_KALDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif($selectkebun === 'DBTimbPMKSKK')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="font-size: 12px; display:none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND KOKAR</th>
                                                <th style="font-size: 12px;">REND TARGET KOKAR</th>
                                                <th style="font-size: 12px;">REND P3 KOKAR</th>
                                                <th style="font-size: 12px;">REND TARGET P3 KOKAR</th>
                                                <th style="font-size: 12px;">REND MITRA KOKAR</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA KOKAR</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td>
                                                    <td style="display: none;">{{number_format($row->TOLERANSI,2,'.',',')}}</td>
                                                    @if ( round($row->REND_KOKAR - $row->REND_TARGET_KOKAR,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_KOKAR,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KOKAR,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_KOKAR,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KOKAR,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_P3_KOKAR - $row->REND_TARGET_P3_KOKAR ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_KOKAR,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_KOKAR,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_KOKAR,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_KOKAR,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_MITRA_KOKAR - $row->REND_TARGET_MITRA_KOKAR ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MITRA_KOKAR,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_KOKAR,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MITRA_KOKAR,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_KOKAR,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif($selectkebun === 'DBTimbPMKSRK')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="font-size: 12px; display:none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND RICKO</th>
                                                <th style="font-size: 12px;">REND TARGET RICKO</th>
                                                <th style="font-size: 12px;">REND P3 RICKO</th>
                                                <th style="font-size: 12px;">REND TARGET P3 RICKO</th>
                                                <th style="font-size: 12px;">REND MITRA RICKO</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA RICKO</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td>
                                                    <td style="display: none;">{{number_format($row->TOLERANSI,2,'.',',')}}</td>
                                                    @if ( round($row->REND_RICKO - $row->REND_TARGET_RICKO,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_RICKO,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_RICKO,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_RICKO,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_RICKO,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_P3_RICKO - $row->REND_TARGET_P3_RICKO ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_RICKO,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_RICKO,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_RICKO,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_RICKO,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_MITRA_RICKO - $row->REND_TARGET_MITRA_RICKO  ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MITRA_RICKO,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_RICKO,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MITRA_RICKO,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_RICKO,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round( $row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif($selectkebun === 'DBTimbPMKSPS')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">TGL</th>
                                                <th style="font-size: 12px; display:none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND PASER</th>
                                                <th style="font-size: 12px;">REND TARGET PASER</th>
                                                <th style="font-size: 12px;">REND MUARA</th>
                                                <th style="font-size: 12px;">REND TARGET MUARA</th>
                                                <th style="font-size: 12px;">REND LANGGAI</th>
                                                <th style="font-size: 12px;">REND TARGET LANGGAI</th>
                                                <th style="font-size: 12px;">REND MITRA PASER</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA PASER</th>
                                                <th style="font-size: 12px;">REND MITRA MUARA</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA MUARA</th>
                                                <th style="font-size: 12px;">REND MITRA LANGGAI</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA LNAGGAI</th>
                                                <th style="font-size: 12px;">REND P3 PASER</th>
                                                <th style="font-size: 12px;">REND TARGET P3 PASER</th>
                                                <th style="font-size: 12px;">REND GABUNGAN REALISASI</th>
                                                <th style="font-size: 12px;">REND GABUNGAN TARGET</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <?php 
                                                        $date = date_create($row->TGL_LHP);
                                                    ?>
                                                    <td>{{date_format($date, 'd/m/y')}}</td>
                                                    <td style="display: none;">{{number_format($row->TOLERANSI,2,'.',',')}}</td>
                                                    @if ( round($row->REND_PASER - $row->REND_TARGET_PASER,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_PASER,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_PASER,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_PASER,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_PASER,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_MUARA - $row->REND_TARGET_MUARA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MUARA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MUARA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MUARA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MUARA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_LANGGAI - $row->REND_TARGET_LANGGAI,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_LANGGAI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_LANGGAI,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_LANGGAI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_LANGGAI,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_MITRA_PASER - $row->REND_TARGET_MITRA_PASER,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MITRA_PASER,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_PASER,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MITRA_PASER,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_PASER,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_MITRA_MUARA - $row->REND_TARGET_MITRA_MUARA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MITRA_MUARA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_MUARA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MITRA_MUARA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_MUARA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_MITRA_LANGGAI - $row->REND_TARGET_MITRA_LANGGAI,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_MITRA_LANGGAI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_LANGGAI,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_MITRA_LANGGAI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_LANGGAI,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_P3_PASER - $row->REND_TARGET_P3_PASER,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_P3_PASER,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3_PASER,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_P3_PASER,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3_PASER,2,'.',',')}}</td>
                                                    @endif
                                                    
                                                    @if ( round( $row->REND_GABUNGAN_REALISASI - $row->REND_GABUNGAN_TARGET ,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_GABUNGAN_REALISASI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_GABUNGAN_TARGET,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                @elseif ($selecttype === '1')
                                    @if ($selectkebun === 'DBTimbPMKSTD')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="font-size: 12px; display:none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN TELDA</th>
                                                <th style="font-size: 12px;">REND TARGET TELDA</th>
                                                <th style="font-size: 12px;">REND BULANAN P3 TELDA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 TELDA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_TELDA - $row->REND_TARGET_TELDA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_TELDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_TELDA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_TELDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_TELDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_TELDA_P3 - $row->REND_TARGET_TELDA_P3,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_TELDA_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_TELDA_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_TELDA_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_TELDA_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSK1')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="display: none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN KALSA</th>
                                                <th style="font-size: 12px;">REND TARGET KALSA</th>
                                                <th style="font-size: 12px;">REND BULANAN P3 KALSA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 KALSA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_KALSA - $row->REND_TARGET_KALSA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KALSA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALSA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KALSA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALSA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_KALSA_P3 - $row->REND_TARGET_KALSA_P3,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KALSA_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALSA_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KALSA_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALSA_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSK2')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="display: none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN KALDA</th>
                                                <th style="font-size: 12px;">REND TARGET KALDA</th>
                                                <th style="font-size: 12px;">REND BULANAN P3 KALDA</th>
                                                <th style="font-size: 12px;">REND TARGET P3 KALDA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_KALDA - $row->REND_TARGET_KALDA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KALDA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALDA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KALDA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALDA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_KALDA_P3 - $row->REND_TARGET_KALDA_P3,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KALDA_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KALDA_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KALDA_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KALDA_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSKK')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="display: none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN KOKAR</th>
                                                <th style="font-size: 12px;">REND TARGET KOKAR</th>
                                                <th style="font-size: 12px;">REND BULANAN MITRA KOKAR</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA KOKAR</th>
                                                <th style="font-size: 12px;">REND BULANAN KOKAR P3</th>
                                                <th style="font-size: 12px;">REND TARGET KOKAR P3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_KOKAR - $row->REND_TARGET_KOKAR,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KOKAR,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KOKAR,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KOKAR,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KOKAR,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MITRA_KOKAR - $row->REND_TARGET_MITRA_KOKAR,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MITRA_KOKAR,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_KOKAR,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MITRA_KOKAR,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_KOKAR,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_KOKAR_P3 - $row->REND_TARGET_KOKAR_P3,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_KOKAR_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_KOKAR_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_KOKAR_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_KOKAR_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSRK')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="display: none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN RICKO</th>
                                                <th style="font-size: 12px;">REND TARGET RICKO</th>
                                                <th style="font-size: 12px;">REND BULANAN MITRA RICKO</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA RICKO</th>
                                                <th style="font-size: 12px;">REND BULANAN RICKO P3</th>
                                                <th style="font-size: 12px;">REND TARGET RICKO P3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_RICKO - $row->REND_TARGET_RICKO,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_RICKO,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_RICKO,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_RICKO,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_RICKO,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MITRA_RICKO - $row->REND_TARGET_MITRA_RICKO,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MITRA_RICKO,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_RICKO,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MITRA_RICKO,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_RICKO,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_RICKO_P3 - $row->REND_TARGET_P3,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_RICKO_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_RICKO_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @elseif ($selectkebun === 'DBTimbPMKSPS')
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px;">BULAN</th>
                                                <th style="display: none;">TOLERANSI</th>
                                                <th style="font-size: 12px;">REND BULANAN PASER</th>
                                                <th style="font-size: 12px;">REND TARGET PASER</th>
                                                <th style="font-size: 12px;">REND BULANAN MUARA</th>
                                                <th style="font-size: 12px;">REND TARGET MUARA</th>
                                                <th style="font-size: 12px;">REND BULANAN LANGGAI</th>
                                                <th style="font-size: 12px;">REND TARGET LANGGAI</th>
                                                <th style="font-size: 12px;">REND BULANAN MITRA PASER</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA PASER</th>
                                                <th style="font-size: 12px;">REND BULANAN MITRA MUARA</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA MUARA</th>
                                                <th style="font-size: 12px;">REND BULANAN MITRA LANGGAI</th>
                                                <th style="font-size: 12px;">REND TARGET MITRA LANGGAI</th>
                                                <th style="font-size: 12px;">REND BULANAN PASER P3</th>
                                                <th style="font-size: 12px;">REND TARGET PASER P3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lhp_ED as $row)
                                                <tr>
                                                    <td>{{$row->BULAN}}</td>
                                                    <td style="display: none;">{{$row->TOLERANSI}}</td>
                                                    @if ( round($row->REND_BULANAN_PASER - $row->REND_TARGET_PASER,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_PASER,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_PASER,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_PASER,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_PASER,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MUARA - $row->REND_TARGET_MUARA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MUARA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MUARA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MUARA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MUARA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_LANGGAI - $row->REND_TARGET_LANGGAI,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_LANGGAI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_LANGGAI,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_LANGGAI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_LANGGAI,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MITRA_PASER - $row->REND_TARGET_MITRA_PASER,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MITRA_PASER,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_PASER,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MITRA_PASER,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_PASER,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MITRA_MUARA - $row->REND_TARGET_MITRA_MUARA,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MITRA_MUARA,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_MUARA,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MITRA_MUARA,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_MUARA,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_MITRA_LANGGAI - $row->REND_TARGET_MITRA_LANGGAI,2) < -0.35 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_MITRA_LANGGAI,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_MITRA_LANGGAI,2,'.',',') }}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_MITRA_LANGGAI,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_MITRA_LANGGAI,2,'.',',')}}</td>
                                                    @endif
                                                    @if ( round($row->REND_BULANAN_PASER_P3 - $row->REND_TARGET_PASER_P3,2) < -0.20 )
                                                        <td class ="bg-red">{{number_format($row->REND_BULANAN_PASER_P3,2,'.',',')}}</td>
                                                        <td class ="bg-red">{{number_format($row->REND_TARGET_PASER_P3,2,'.',',')}}</td>
                                                    @else
                                                        <td>{{number_format($row->REND_BULANAN_PASER_P3,2,'.',',')}}</td>
                                                        <td>{{number_format($row->REND_TARGET_PASER_P3,2,'.',',')}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <strong><p style="font-size: 15px;">Toleransi : 0.35 </p></strong>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('type').value = "<?php echo isset($_GET['type']) ? $_GET['type'] : '0'; ?>";
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD'; ?>";
        document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35'; ?>";
    </script>
@endsection
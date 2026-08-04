@extends('dashboard.app')

@section('header-title')
    Curah Hujan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Curah Hujan
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpCurahHujanV2') }}">
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
                                <option value="2200">TELDA</option>
                                <option value="2300">KALSA</option>
                                <option value="2400">KALDA</option>
                                <option value="2500">KOKAR</option>
                                <option value="3200">RICKO</option>
                                <option value="4200">MUARA</option>
                                <option value="5200">PASER</option>
                                <option value="6200">LANGGAI</option>
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
                        <?php
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200';

                                    
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                        <div class="box-body table-responsive">
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                @if($selectkebun == '2200')
                                <?php 
                                    $totalAFD1 = 0;
                                    $totalAFD2 = 0;
                                    $totalAFD3 = 0;
                                    $totalAFD4 = 0;
                                    $totalAFD5 = 0;
                                    $totalAFD6 = 0;
                                    $total = 0;
                                ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>

                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach

                                        <tr>
                                            <td>TELDA</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody> 
                                @elseif($selectkebun == '2300')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                                <?php 
                                                    $totalAFD1 += $row->AFD_01;
                                                    $totalAFD2 += $row->AFD_02;
                                                    $totalAFD3 += $row->AFD_03;
                                                    $totalAFD4 += $row->AFD_04;
                                                    $totalAFD5 += $row->AFD_05;
                                                    $total += $row->Total;
                                                ?>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td>KALSA</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody> 
                                @elseif($selectkebun == '2400')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $totalAFD6 = 0;
                                        $totalAFD7 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">AFD VII</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_07 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_07,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>

                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $totalAFD7 += $row->AFD_07;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach

                                        <tr>
                                            <td>KALDA</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD7,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody> 
                                @elseif($selectkebun == '2500')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $totalAFD6 = 0;
                                        $totalAFD7 = 0;
                                        $totalAFD8 = 0;
                                        $totalAFD9 = 0;
                                        $totalAFD10 = 0;
                                        $totalAFD11 = 0;
                                        $totalAFD12 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">AFD VII</th>
                                            <th style="font-size: 12px;">AFD VIII</th>
                                            <th style="font-size: 12px;">AFD IX</th>
                                            <th style="font-size: 12px;">AFD X</th>
                                            <th style="font-size: 12px;">AFD XI</th>
                                            <th style="font-size: 12px;">AFD XII</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_07 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_07,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_08 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_08,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_09 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_09,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_10 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_10,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_11 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_11,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_12 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_12,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>
                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $totalAFD7 += $row->AFD_07;
                                                $totalAFD8 += $row->AFD_08;
                                                $totalAFD9 += $row->AFD_09;
                                                $totalAFD10 += $row->AFD_10;
                                                $totalAFD11 += $row->AFD_11;
                                                $totalAFD12 += $row->AFD_12;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach

                                        <tr>
                                            <td>KOKAR</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD7,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD8,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD9,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD10,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD11,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD12,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody> 
                                @elseif($selectkebun == '3200')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $totalAFD6 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>
                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>RICKO</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody>     
                                @elseif($selectkebun == '4200')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $totalAFD6 = 0;
                                        $totalAFD7 = 0;
                                        $totalAFD8 = 0;
                                        $totalAFD9 = 0;
                                        $totalAFD10 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">AFD VII</th>
                                            <th style="font-size: 12px;">AFD VIII</th>
                                            <th style="font-size: 12px;">AFD IX</th>
                                            <th style="font-size: 12px;">AFD X</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_07 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_07,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_08 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_08,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_09 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_09,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_10 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_10,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>
                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $totalAFD7 += $row->AFD_07;
                                                $totalAFD8 += $row->AFD_08;
                                                $totalAFD9 += $row->AFD_09;
                                                $totalAFD10 += $row->AFD_10;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>MUARA</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD7,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD8,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD9,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD10,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody> 
                                @elseif($selectkebun == '5200')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $totalAFD3 = 0;
                                        $totalAFD4 = 0;
                                        $totalAFD5 = 0;
                                        $totalAFD6 = 0;
                                        $totalAFD7 = 0;
                                        $totalAFD8 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">AFD III</th>
                                            <th style="font-size: 12px;">AFD IV</th>
                                            <th style="font-size: 12px;">AFD V</th>
                                            <th style="font-size: 12px;">AFD VI</th>
                                            <th style="font-size: 12px;">AFD VII</th>
                                            <th style="font-size: 12px;">AFD VIII</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_03 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_03,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_04 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_04,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_05 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_05,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_06 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_06,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_07 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_07,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_08 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_08,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>
                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $totalAFD3 += $row->AFD_03;
                                                $totalAFD4 += $row->AFD_04;
                                                $totalAFD5 += $row->AFD_05;
                                                $totalAFD6 += $row->AFD_06;
                                                $totalAFD7 += $row->AFD_07;
                                                $totalAFD8 += $row->AFD_08;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>PASER</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD3,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD4,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD5,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD6,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD7,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD8,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody>
                                @elseif($selectkebun == '6200')
                                    <?php 
                                        $totalAFD1 = 0;
                                        $totalAFD2 = 0;
                                        $total = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">TGL</th>
                                            <th style="font-size: 12px;display:none;">SITE_ID</th>
                                            <th style="font-size: 12px;">AFD I</th>
                                            <th style="font-size: 12px;">AFD II</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lhp_WeatherStation as $row)
                                            <tr>
                                                <td>{{$row->KEBUN}}</td>
                                                <?php 
                                                    $date = date_create($row->Tanggal);
                                                ?>
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                                <td style="display:none;">{{$row->SITE_ID}}</td>
                                                @if($row->AFD_01 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_01,1,',','.')}}</td>
                                                @endif
                                                @if($row->AFD_02 == null)
                                                    @if($row->selisih > 4)
                                                        <td class = "bg-red"></td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td>{{number_format($row->AFD_02,1,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->Total,1,',','.')}}</td>
                                            </tr>
                                            <?php 
                                                $totalAFD1 += $row->AFD_01;
                                                $totalAFD2 += $row->AFD_02;
                                                $total += $row->Total;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>LANGGAI</td>
                                            <td>TOTAL</td>
                                            <td style="display:none;">{{$row->SITE_ID}}</td>
                                            <td>{{number_format($totalAFD1,1,',','.')}}</td>
                                            <td>{{number_format($totalAFD2,1,',','.')}}</td>
                                            <td>{{number_format($total,1,',','.')}}</td>
                                        </tr>
                                    </tbody>      
                                @endif    
                            </table>
                        </div>
                    </div>
                </div>
                <p><strong>Catatan : * Jika Kosong berarti belum di tarik dari mesin</strong></p>
                <p><strong>Catatan : * Jika Merah berarti belum di tarik dari mesin lebih dari 4 hari </strong></p>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>
@endsection
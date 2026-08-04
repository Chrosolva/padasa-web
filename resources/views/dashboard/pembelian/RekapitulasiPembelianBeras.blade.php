@extends('dashboard.app')

@section('header-title')
    Rekapitulasi Penerimaan Beras [GRN]
@endsection

<!-- <style>
    .fixedcol {
        overflow-x: auto;
    }

    .tfixedcol {
        position: sticky;
        left: 0;
        background: #FFF;
    }
</style> -->


@section('main-content')
    <section class="content-header">
        <h1>
            Rekapitulasi Penerimaan Beras [GRN]
            <!-- Rekapitulasi Penerimaan Solar dan Beras [GRN] -->
            <!-- <small>( dalam Ribuan Rupiah )</small> -->
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/RekapitulasiPembelianBeras') }}">
                    
                    <div class="form-group">
                        <label for="tahun">Tahun : </label>
                        <div class="input-group date input-inline" style="width: 175px;"> 
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="{{ Request::get('tahun') ?: date('Y', strtotime('-7 days')) }}">
                            {{-- <input type="number" class="form-control" id="tahun" name="tahun" value="2022"> --}}
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
                            <option value="4200">MUARA</option>
                            <option value="5200">PASER</option>
                            <option value="6200">LANGGAI</option>
                        </select>
                    </div>

                    <div class="form-group" style="display:none;">
                        <label for="selectjenis">Jenis : </label>
                        <select class="form-control" id="selectjenis" name="selectjenis">
                            <!-- <option value="00.000.0001">SOLAR</option> -->
                            <option value="13.000.0001">BERAS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">

            <?php 
                $dom = new DOMDocument();
                $dom->loadHtml("Index.php");
                $kebun = Request::get('selectkebun') ?: 'SEMUA';
                $jenis = isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '00.000.0001';
                $satuan = ''; 
                if($jenis == '13.000.0001') {   
                    $satuan = 'KG';
                }
                else {
                    $satuan = 'KG';
                }
            ?>

            @if($kebun == 'SEMUA') 
                <div class="col-md-12">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive fixedcol">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalTD = 0;
                                        $totalK1 = 0; 
                                        $totalK2 = 0;
                                        $totalKK = 0;
                                        $totalRK = 0;   
                                        $totalMR = 0;   
                                        $totalPS = 0;   
                                        $totalLG = 0;
                                        
                                        $totalTD_QTY = 0;
                                        $totalK1_QTY = 0; 
                                        $totalK2_QTY = 0;
                                        $totalKK_QTY = 0;
                                        $totalRK_QTY = 0;   
                                        $totalMR_QTY = 0;   
                                        $totalPS_QTY = 0;   
                                        $totalLG_QTY = 0;

                                        $totalJMLTD = 0;
                                        $totalJMLK1 = 0;
                                        $totalJMLK2 = 0;
                                        $totalJMLKK = 0;
                                        $totalJMLRK = 0;
                                        $totalJMLMR = 0;
                                        $totalJMLPS = 0;
                                        $totalJMLLG = 0;
                                        
                                        // echo $selectkebun;
                                        // echo $selecttype;
                                        // echo $Jlh_PBeras;
                                        
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th class= "tfixedcol" style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>TD</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>K1</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>K2</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>KK</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>RK</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>MR</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>PS</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>LG</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>  
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                            $index = 1;
                                        ?>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->TD_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->TD_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->TD,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->TD/$row->TD_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][0]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][0]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                                @if(is_null($row->K1_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->K1_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->K1,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->K1/$row->K1_QTY),0,',','.')}}</td>                                                  
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][1]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][1]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                                @if(is_null($row->K2_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->K2_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->K2,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->K2/$row->K2_QTY),0,',','.')}}</td>                    
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][2]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][2]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif                               
                                                @if(is_null($row->KK_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->KK_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->KK,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->KK/$row->KK_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][3]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][3]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif                                                   
                                                @if(is_null($row->RK_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->RK_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->RK,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->RK/$row->RK_QTY),0,',','.')}}</td>
                                                                                                       
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][4]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][4]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                                @if(is_null($row->MR_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->MR_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->MR,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->MR/$row->MR_QTY),0,',','.')}}</td>                                                  
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][5]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][5]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                                @if(is_null($row->PS_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->PS_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->PS,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->PS/$row->PS_QTY),0,',','.')}}</td>                                                   
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][6]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][6]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                                @if(is_null($row->LG_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->LG_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->LG,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->LG/$row->LG_QTY),0,',','.')}}</td>                                                  
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][7]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][7]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalTD += $row->TD; 
                                                $totalK1 += $row->K1; 
                                                $totalK2 += $row->K2; 
                                                $totalKK += $row->KK; 
                                                $totalRK += $row->RK; 
                                                $totalMR += $row->MR; 
                                                $totalPS += $row->PS; 
                                                $totalLG += $row->LG; 

                                                $totalTD_QTY += $row->TD_QTY; 
                                                $totalK1_QTY += $row->K1_QTY; 
                                                $totalK2_QTY += $row->K2_QTY; 
                                                $totalKK_QTY += $row->KK_QTY; 
                                                $totalRK_QTY += $row->RK_QTY; 
                                                $totalMR_QTY += $row->MR_QTY; 
                                                $totalPS_QTY += $row->PS_QTY; 
                                                $totalLG_QTY += $row->LG_QTY; 

                                                if(!empty($Jlh_PBeras[$index][0]['JML']) && $Jlh_PBeras[$index][0]['SITE_ID'] == '2200') {
                                                    $totalJMLTD += $Jlh_PBeras[$index][0]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][1]['JML']) && $Jlh_PBeras[$index][1]['SITE_ID'] == '2300') {
                                                    $totalJMLK1 += $Jlh_PBeras[$index][1]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][2]['JML']) && $Jlh_PBeras[$index][2]['SITE_ID'] == '2400') {
                                                    $totalJMLK2 += $Jlh_PBeras[$index][2]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][3]['JML']) && $Jlh_PBeras[$index][3]['SITE_ID'] == '2500') {
                                                    $totalJMLKK += $Jlh_PBeras[$index][3]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][4]['JML']) && $Jlh_PBeras[$index][4]['SITE_ID'] == '3200') {
                                                    $totalJMLRK += $Jlh_PBeras[$index][4]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][5]['JML']) && $Jlh_PBeras[$index][5]['SITE_ID'] == '4200') {
                                                    $totalJMLMR += $Jlh_PBeras[$index][5]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][6]['JML']) && $Jlh_PBeras[$index][6]['SITE_ID'] == '5200') {
                                                    $totalJMLPS += $Jlh_PBeras[$index][6]['JML'];
                                                }
                                                if(!empty($Jlh_PBeras[$index][7]['JML']) && $Jlh_PBeras[$index][7]['SITE_ID'] == '6200') {
                                                    $totalJMLLG += $Jlh_PBeras[$index][7]['JML'];
                                                }

                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td class = "tfixedcol" style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalTD <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalTD_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalTD,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLTD > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLTD),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalK1 <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalK1_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalK1,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLK1 > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLK1),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalK2 <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalK2_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalK2,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLK2 > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLK2),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalKK <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalKK_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalKK,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLKK > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLKK),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalRK <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalRK_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalRK,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLRK > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLRK),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalMR <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalMR_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalMR,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLMR > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLMR),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalPS <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalPS_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalPS,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLPS > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLPS),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                            @if($totalLG <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalLG_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalLG,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLLG > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLLG),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '2200')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalTD = 0;
                                        $totalTD_QTY = 0;
                                        $index = 1;
                                        $totalJMLTD = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>TD</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->TD_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->TD_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->TD,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->TD/$row->TD_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][0]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][0]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif

                                            </tr>

                                            <?php 
                                                $totalTD += $row->TD; 
                                                $totalTD_QTY += $row->TD_QTY; 
                                                if(!empty($Jlh_PBeras[$index][0]['JML'])) {
                                                    $totalJMLTD += $Jlh_PBeras[$index][0]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalTD <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalTD_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalTD,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLTD > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLTD),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '2300')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalK1 = 0;
                                        $totalK1_QTY = 0;
                                        $index = 1;
                                        $totalJMLK1 = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>K1</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->K1_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->K1_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->K1,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->K1/$row->K1_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][1]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][1]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalK1 += $row->K1; 
                                                $totalK1_QTY += $row->K1_QTY; 
                                                if(!empty($Jlh_PBeras[$index][1]['JML'])) {
                                                    $totalJMLK1 += $Jlh_PBeras[$index][1]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalK1 <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalK1_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalK1,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLK1 > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLK1),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '2400')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalK2 = 0;
                                        $totalK2_QTY = 0;
                                        $index = 1;
                                        $totalJMLK2 = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>K2</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->K2_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->K2_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->K2,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->K2/$row->K2_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][2]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][2]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif

                                            </tr>

                                            <?php 
                                                $totalK2 += $row->K2; 
                                                $totalK2_QTY += $row->K2_QTY; 
                                                if(!empty($Jlh_PBeras[$index][2]['JML'])) {
                                                    $totalJMLK2 += $Jlh_PBeras[$index][2]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalK2 <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalK2_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalK2,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLK2 > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLK2),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '2500')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalKK = 0;
                                        $totalKK_QTY = 0;
                                        $index = 1;
                                        $totalJMLKK = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>KK</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->KK_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->KK_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->KK,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->KK/$row->KK_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][3]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][3]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalKK += $row->KK; 
                                                $totalKK_QTY += $row->KK_QTY; 
                                                if(!empty($Jlh_PBeras[$index][3]['JML'])) {
                                                    $totalJMLKK += $Jlh_PBeras[$index][3]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalKK <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalKK_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalKK,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLKK > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLKK),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '3200')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalRK = 0;
                                        $totalRK_QTY = 0;
                                        $index = 1;
                                        $totalJMLRK = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>RK</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->RK_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->RK_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->RK,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->RK/$row->RK_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][4]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][4]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalRK += $row->RK; 
                                                $totalRK_QTY += $row->RK_QTY; 
                                                if(!empty($Jlh_PBeras[$index][4]['JML'])) {
                                                    $totalJMLRK += $Jlh_PBeras[$index][4]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalRK <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalRK_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalRK,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLRK > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLRK),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '4200')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalMR = 0;
                                        $totalMR_QTY = 0;
                                        $index = 1;
                                        $totalJMLMR = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>MR</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->MR_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->MR_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->MR,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->MR/$row->MR_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][5]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][5]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalMR += $row->MR; 
                                                $totalMR_QTY += $row->MR_QTY; 
                                                if(!empty($Jlh_PBeras[$index][5]['JML'])) {
                                                    $totalJMLMR += $Jlh_PBeras[$index][5]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalMR <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalMR_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalMR,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLMR > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLMR),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '5200')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalPS = 0;
                                        $totalPS_QTY = 0;
                                        $index = 1;
                                        $totalJMLPS = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>PS</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->PS_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->PS_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->PS,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->PS/$row->PS_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][6]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][6]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif

                                            </tr>

                                            <?php 
                                                $totalPS += $row->PS; 
                                                $totalPS_QTY += $row->PS_QTY; 
                                                if(!empty($Jlh_PBeras[$index][6]['JML'])) {
                                                    $totalJMLPS += $Jlh_PBeras[$index][6]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalPS <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalPS_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalPS,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @endif
                                            @if($totalJMLPS > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLPS),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($kebun == '6200')
                <div class="col-md-6">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                        </div> --}}
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <?php
                                        
                                        $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                        
                                        $totalLG = 0;
                                        $totalLG_QTY = 0;
                                        $index = 1;
                                        $totalJMLLG = 0;
                                ?>

                                <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;display:none;" rowspan = 2>TAHUN</th>
                                            <th style="font-size: 12px;text-align: center;" rowspan = 2>BULAN</th>
                                            <th style="font-size: 12px;text-align: center;" colspan = 4>LG</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 12px;text-align: center;">Qty [{{$satuan}}]</th>
                                            <th style="font-size: 12px;text-align: center;">Total Harga [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">Harga Rata [Rp]</th>
                                            <th style="font-size: 12px;text-align: center;">JLH</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($Rekapitulasi_PBeras as $row)
                                            <tr>
                                                <td style="text-align: center;display:none;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td class = "tfixedcol" style="text-align: center;">{{$row->BULAN}}</td>
                                                @if(is_null($row->LG_QTY))
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                    <td style="text-align: center;">0</td>
                                                @else
                                                    <td style="text-align: center;">{{number_format($row->LG_QTY,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format($row->LG,0,',','.')}}</td>
                                                    <td style="text-align: center;">{{number_format(round($row->LG/$row->LG_QTY),0,',','.')}}</td>
                                                @endif
                                                @if(!empty($Jlh_PBeras[$index][7]['JML']))
                                                    <td style="text-align: center;">{{number_format(round($Jlh_PBeras[$index][7]['JML']),0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: center;">-</td>
                                                @endif
                                            </tr>

                                            <?php 
                                                $totalLG += $row->LG; 
                                                $totalLG_QTY += $row->LG_QTY; 
                                                if(!empty($Jlh_PBeras[$index][7]['JML'])) {
                                                    $totalJMLLG += $Jlh_PBeras[$index][7]['JML'];
                                                }
                                                $index++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: center;display:none;"><strong>{{$tahun}}</strong></td>
                                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                                            
                                            @if($totalLG <= 0)
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong>0</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                            @else
                                                <td style="text-align: center;"><strong>{{number_format($totalLG_QTY,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($totalLG,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong></strong></td>
                                                
                                            @endif
                                            @if($totalJMLLG > 0)
                                                <td style="text-align: center;">{{number_format(round($totalJMLLG),0,',','.')}}</td>
                                            @else
                                                <td style="text-align: center;">0</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Catatan: Qty diperoleh dari GRN dan harga diperoleh dari PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif 
            
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        document.getElementById('selectjenis').value = "<?php echo isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '13.000.0001'; ?>";

        const Jlh_PBeras = <?php echo json_encode($Jlh_PBeras, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?>;
        console.log(Jlh_PBeras);
    </script>
@endsection

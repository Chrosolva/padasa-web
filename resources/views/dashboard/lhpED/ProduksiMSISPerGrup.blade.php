@extends('dashboard.app')

@section('header-title')
    Produksi MS IS Per Grup
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Produksi MS IS Per Grup
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/MSISPerGrup') }}">
                    <div class="row">
                    <div class="form-group">
                        <label for="tahun">Tahun : </label>
                        <div class="input-group date input-inline" style="width: 175px;">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="{{ Request::get('tahun') ?: date('Y', strtotime('-7 days')) }}">
                        </div>
                    </div>

                        <div class="form-group">
                            <label for="selectkebun">PMKS : </label>
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
                            <label for="selectjenis">Jenis : </label>
                            <select class="form-control" id="selectjenis" name="selectjenis">
                                <option value="1">MS Per Grup</option>
                                <option value="2">IS Per Grup</option>
                                <option value="3">MS IS Per Grup</option>
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
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <?php 
                                        $dom = new DOMDocument();
                                        $dom->loadHtml("Index.php");
                                        $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200';
                                        $selectjenis = isset($_REQUEST['selectjenis']) ? $_REQUEST['selectjenis'] :  '1';  
                                        // echo $selectkebun;  
                                        // echo $selecttype;
                                ?>
                                @if ($selectjenis === '1') 
                                    <?php 
                                        $totalMSKS = 0; 
                                        $totalMSP3 = 0;
                                        $totalMSMITRA = 0;
                                        $totalMSMITRAK2 = 0;
                                        $totalMS = 0;
                                        $totalMSAPMR = 0;
                                        $totalMSBMML = 0;
                                        $totalMSSANR = 0;
                                        $totalMSBMMLMITRA = 0;
                                        $totalMSSANRMITRA = 0;
                                        $totaltitipolah = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">TAHUN</th>
                                            <th style="font-size: 12px;">BULAN</th>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">PRODUKSI MS KS [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI MS P3 [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI MS MITRA [KG]</th>
                                            
                                            @if($selectkebun === '2500')
                                                <th>PENGELUARAN MS MITRA KALDA [KG]</th>
                                            @endif

                                            @if($selectkebun === '5200')
                                                <th>PRODUKSI MS APMR [KG]</th>
                                                <th>PRODUKSI MS BMML [KG]</th>
                                                <th>PRODUKSI MS SANR [KG]</th>
                                                <th>PRODUKSI MS BMML MITRA [KG]</th>
                                                <th>PRODUKSI MS SANR MITRA [KG]</th>
                                            @endif

                                            @if($selectkebun === '2400' && $selectjenis === '1')
                                                <th style="font-size: 12px;">TITIP OLAH MS K1 [KG]</th>
                                            @endif
                                            <th style="font-size: 12px;">PRODUKSI TOTAL MS [KG]</th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        @foreach ($lhp_MSISPerGrup as $row)
                                            <tr>
                                                <td>{{$row->TAHUN}}</td>
                                                <td>{{number_format($row->BULAN,0,',','.')}}</td>
                                                <td>{{$row->KEBUN}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_KEBUNSENDIRI,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_P3,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_MITRA,0,',','.')}}</td>

                                                @if($selectkebun === '2500')
                                                    <td>{{number_format($row->PRODUKSI_MS_MITRA_KALDA,0,',','.')}}</td>
                                                @endif
                                                
                                                @if($selectkebun === '5200')
                                                    <td>{{number_format($row->PRODUKSI_MS_APMR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_BMML,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_SANR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_BMML_MITRA,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_SANR_MITRA,0,',','.')}}</td>
                                                @endif
                                                @if($selectkebun === '2400' && $selectjenis === '1')
                                                    <td>{{number_format($row->PRODUKSI_MS_KALSA,0,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->PRODUKSI_TOTAL_MS,0,',','.')}}</td>
                                                <?php 
                                                    $totalMSKS += $row->PRODUKSI_MS_KEBUNSENDIRI; 
                                                    $totalMSP3 += $row->PRODUKSI_MS_P3;
                                                    $totalMSMITRA += $row->PRODUKSI_MS_MITRA;
                                                    $totalMS += $row->PRODUKSI_TOTAL_MS;

                                                    if($selectkebun === '2500') {
                                                        $totalMSMITRAK2 += $row->PRODUKSI_MS_MITRA_KALDA;
                                                    }

                                                    if ($selectkebun === '5200') {
                                                        $totalMSAPMR += $row->PRODUKSI_MS_APMR;
                                                        $totalMSBMML += $row->PRODUKSI_MS_BMML;
                                                        $totalMSSANR += $row->PRODUKSI_MS_SANR;
                                                        $totalMSBMMLMITRA += $row->PRODUKSI_MS_BMML_MITRA;
                                                        $totalMSSANRMITRA += $row->PRODUKSI_MS_SANR_MITRA;
                                                    }

                                                    if($selectkebun === '2400') {
                                                        $totaltitipolah += $row->PRODUKSI_MS_KALSA;
                                                    }
                                                ?>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>TOTAL </strong></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><strong>{{number_format($totalMSKS,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalMSP3,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalMSMITRA,0,',','.')}}</strong></td>

                                            @if($selectkebun === '2500')
                                                <td><strong>{{number_format($totalMSMITRAK2,0,',','.')}}</strong></td>
                                            @endif
                                            
                                            @if($selectkebun === '5200')
                                                <td><strong>{{number_format($totalMSAPMR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSBMML,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSSANR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSBMMLMITRA,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSSANRMITRA,0,',','.')}} </strong></td>
                                            @endif

                                            @if($selectkebun === '2400' && $selectjenis === '1')
                                                <td>{{number_format($totaltitipolah,0,',','.')}}</td>
                                            @endif
                                            <td><strong>{{number_format($totalMS,0,',','.')}} </strong></td>
                                        </tr>
                                    </tbody>
                                @elseif ($selectjenis === '2')
                                    <?php 
                                        $totalISKS = 0; 
                                        $totalISP3 = 0;
                                        $totalISMITRA = 0;
                                        $totalISMITRAK2 = 0;
                                        $totalIS = 0;
                                        $totalISAPMR = 0;
                                        $totalISBMML = 0;
                                        $totalISSANR = 0;
                                        $totalISBMMLMITRA = 0;
                                        $totalISSANRMITRA = 0;
                                        $totaltitipolah = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">TAHUN</th>
                                            <th style="font-size: 12px;">BULAN</th>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">PRODUKSI IS KS [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI IS P3 [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI IS MITRA [KG]</th>

                                            @if($selectkebun === '2500')
                                                <th>PRODUKSI IS MITRA KALDA [KG]</th>
                                            @endif
                                            
                                            @if($selectkebun === '5200')
                                                <th>PRODUKSI IS APMR [KG]</th>
                                                <th>PRODUKSI IS BMML [KG]</th>
                                                <th>PRODUKSI IS SANR [KG]</th>
                                                <th>PRODUKSI IS BMML MITRA [KG]</th>
                                                <th>PRODUKSI IS SANR MITRA [KG]</th>
                                            @endif

                                            @if($selectkebun === '2400' && $selectjenis === '2')
                                                <th style="font-size: 12px;">TITIP OLAH IS K1 [KG]</th>
                                            @endif
                                            <th style="font-size: 12px;">PRODUKSI TOTAL IS [KG]</th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        @foreach ($lhp_MSISPerGrup as $row)
                                            <tr>
                                                <td>{{$row->TAHUN}}</td>
                                                <td>{{number_format($row->BULAN,0,',','.')}}</td>
                                                <td>{{$row->KEBUN}}</td>
                                                <td>{{number_format($row->PRODUKSI_IS_KEBUNSENDIRI,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_IS_P3,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_IS_MITRA,0,',','.')}}</td>

                                                @if($selectkebun === '2500')
                                                    <td><strong>{{number_format($row->PRODUKSI_IS_MITRA_KALDA,0,',','.')}}</strong></td>
                                                @endif
                                                
                                                @if($selectkebun === '5200')
                                                    <td>{{number_format($row->PRODUKSI_IS_APMR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_IS_BMML,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_IS_SANR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_IS_BMML_MITRA,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_IS_SANR_MITRA,0,',','.')}}</td>
                                                @endif
                                                @if($selectkebun === '2400' && $selectjenis === '2')
                                                    <td>{{number_format($row->PRODUKSI_IS_KALSA,0,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->PRODUKSI_TOTAL_IS,0,',','.')}}</td>
                                                <?php 
                                                    $totalISKS += $row->PRODUKSI_IS_KEBUNSENDIRI; 
                                                    $totalISP3 += $row->PRODUKSI_IS_P3;
                                                    $totalISMITRA += $row->PRODUKSI_IS_MITRA;
                                                    $totalIS += $row->PRODUKSI_TOTAL_IS;

                                                    if($selectkebun === '2500') {
                                                        $totalISMITRAK2 += $row->PRODUKSI_IS_MITRA_KALDA; 
                                                    }

                                                    if ($selectkebun === '5200') {
                                                        $totalISAPMR += $row->PRODUKSI_IS_APMR;
                                                        $totalISBMML += $row->PRODUKSI_IS_BMML;
                                                        $totalISSANR += $row->PRODUKSI_IS_SANR;
                                                        $totalISBMMLMITRA += $row->PRODUKSI_IS_BMML_MITRA;
                                                        $totalISSANRMITRA += $row->PRODUKSI_IS_SANR_MITRA;
                                                    }

                                                    if($selectkebun === '2400') {
                                                        $totaltitipolah += $row->PRODUKSI_IS_KALSA;
                                                    }
                                                ?>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>TOTAL </strong></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><strong>{{number_format($totalISKS,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalISP3,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalISMITRA,0,',','.')}}</strong></td>
                                            
                                            @if($selectkebun === '2500')
                                                <td><strong>{{number_format($totalISMITRAK2,0,',','.')}}</strong></td>
                                            @endif

                                            @if($selectkebun === '5200')
                                                <td><strong>{{number_format($totalISAPMR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalISBMML,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalISSANR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalISBMMLMITRA,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalISSANRMITRA,0,',','.')}} </strong></td>
                                            @endif
                                            @if($selectkebun === '2400' && $selectjenis === '2')
                                                <td>{{number_format($totaltitipolah,0,',','.')}}</td>
                                            @endif
                                            <td><strong>{{number_format($totalIS,0,',','.')}} </strong></td>
                                        </tr>
                                    </tbody>
                                @elseif ($selectjenis === '3') 
                                    <?php 
                                        $totalMSISKS = 0; 
                                        $totalMSISP3 = 0;
                                        $totalMSISMITRA = 0;
                                        $totalMSISMITRAK2 = 0;
                                        $totalMSIS = 0;
                                        $totalMSISAPMR = 0;
                                        $totalMSISBMML = 0;
                                        $totalMSISSANR = 0;
                                        $totalMSISBMMLMITRA = 0;
                                        $totalMSISSANRMITRA = 0;
                                        $totaltitipolah = 0;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px;">TAHUN</th>
                                            <th style="font-size: 12px;">BULAN</th>
                                            <th style="font-size: 12px;">KEBUN</th>
                                            <th style="font-size: 12px;">PRODUKSI MS IS KS [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI MS IS P3 [KG]</th>
                                            <th style="font-size: 12px;">PRODUKSI MS IS MITRA [KG]</th>

                                            @if($selectkebun === '2500')
                                                <th>PENGELUARAN MS IS MITRA KALDA</th>
                                            @endif
                                            
                                            @if($selectkebun === '5200')
                                                <th>PRODUKSI MS IS APMR [KG]</th>
                                                <th>PRODUKSI MS IS BMML [KG]</th>
                                                <th>PRODUKSI MS IS SANR [KG]</th>
                                                <th>PRODUKSI MS IS BMML MITRA [KG]</th>
                                                <th>PRODUKSI MS IS SANR MITRA [KG]</th>
                                            @endif
                                            @if($selectkebun === '2400' && $selectjenis === '3')
                                                <th style="font-size: 12px;">TITIP OLAH MS IS K1 [KG]</th>
                                            @endif
                                            <th style="font-size: 12px;">PRODUKSI TOTAL MS IS [KG]</th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        @foreach ($lhp_MSISPerGrup as $row)
                                            <tr>
                                                <td>{{$row->TAHUN}}</td>
                                                <td>{{number_format($row->BULAN,0,',','.')}}</td>
                                                <td>{{$row->KEBUN}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_IS_KEBUNSENDIRI,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_IS_P3,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI_MS_IS_MITRA,0,',','.')}}</td>

                                                @if($selectkebun === '2500')
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_MITRA_KALDA,0,',','.')}}</td>
                                                @endif
                                                
                                                @if($selectkebun === '5200')
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_APMR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_BMML,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_SANR,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_BMML_MITRA,0,',','.')}}</td>
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_SANR_MITRA,0,',','.')}}</td>
                                                @endif
                                                @if($selectkebun === '2400' && $selectjenis === '3')
                                                    <td>{{number_format($row->PRODUKSI_MS_IS_KALSA,0,',','.')}}</td>
                                                @endif
                                                <td>{{number_format($row->PRODUKSI_MS_IS_TOTAL,0,',','.')}}</td>
                                                <?php 
                                                    $totalMSISKS += $row->PRODUKSI_MS_IS_KEBUNSENDIRI; 
                                                    $totalMSISP3 += $row->PRODUKSI_MS_IS_P3;
                                                    $totalMSISMITRA += $row->PRODUKSI_MS_IS_MITRA;
                                                    $totalMSIS += $row->PRODUKSI_MS_IS_TOTAL;
                                                    if($selectkebun === '2500') {
                                                        $totalMSISMITRAK2 += $row->PRODUKSI_MS_IS_MITRA_KALDA;
                                                    }
                                                    if ($selectkebun === '5200') {
                                                        $totalMSISAPMR += $row->PRODUKSI_MS_IS_APMR;
                                                        $totalMSISBMML += $row->PRODUKSI_MS_IS_BMML;
                                                        $totalMSISSANR += $row->PRODUKSI_MS_IS_SANR;
                                                        $totalMSISBMMLMITRA += $row->PRODUKSI_MS_IS_BMML_MITRA;
                                                        $totalMSISSANRMITRA += $row->PRODUKSI_MS_IS_SANR_MITRA;
                                                    }
                                                    if($selectkebun === '2400') {
                                                        $totaltitipolah += $row->PRODUKSI_MS_IS_KALSA;
                                                    }
                                                ?>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>TOTAL </strong></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><strong>{{number_format($totalMSISKS,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalMSISP3,0,',','.')}}</strong></td>
                                            <td><strong>{{number_format($totalMSISMITRA,0,',','.')}}</strong></td>

                                            @if($selectkebun === '2500') 
                                                <td><strong>{{number_format($totalMSISMITRAK2,0,',','.')}}</strong></td>
                                            @endif
                                            
                                            @if($selectkebun === '5200')
                                                <td><strong>{{number_format($totalMSISAPMR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSISBMML,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSISSANR,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSISBMMLMITRA,0,',','.')}} </strong></td>
                                                <td><strong>{{number_format($totalMSISSANRMITRA,0,',','.')}} </strong></td>
                                            @endif

                                            @if($selectkebun === '2400' && $selectjenis === '3')
                                                <td>{{number_format($totaltitipolah,0,',','.')}}</td>
                                            @endif
                                            <td><strong>{{number_format($totalMSIS,0,',','.')}} </strong></td>
                                        </tr>
                                    </tbody>
                                @endif
                                
                            </table>        
                        </div>
                        <p>Keterangan: </p>
                        <p>KS = Kebun Sendiri, P3 = Pihak Ketiga</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('selectjenis').value = "<?php echo isset($_GET['selectjenis']) ? $_GET['selectjenis'] : '1'; ?>";
        var lhp_MSISPerGrup = <?php echo json_encode($lhp_MSISPerGrup); ?>;
        console.log(lhp_MSISPerGrup);
    </script>

@endsection

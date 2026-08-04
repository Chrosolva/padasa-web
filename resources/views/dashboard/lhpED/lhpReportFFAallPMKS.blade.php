@extends('dashboard.app')

@section('header-title')
    ALB MS Semua PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            ALB MS Semua PMKS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/ReportFFAallPMKS') }}">
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
                                <option value="TELDA">TELDA</option>
                                <option value="KALSA">KALSA</option>
                                <option value="KALDA">KALDA</option>
                                <option value="KOKAR">KOKAR</option>
                                <option value="RICKO">RICKO</option>
                                <option value="PASER">PASER</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='4.8' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="toleransiproduksi">Toleransi Produksi : </label>
                            <input type='number' step='0.01' value='4.5' placeholder='0.00' id = 'toleransiproduksi' name = 'toleransiproduksi'/>
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
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">TGL</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">ALB TT1 (%)</th>
                                        <th style="font-size: 12px;">VOL. TT1 (KG)</th>
                                        <th style="font-size: 12px;">ALB TT2 (%)</th>       
                                        <th style="font-size: 12px;">VOL. TT2 (KG)</th>
                                        <th style="font-size: 12px;">ALB TT3 (%) </th>
                                        <th style="font-size: 12px;">VOL. TT3 (KG)</th>             
                                        <th style="font-size: 12px;">ALB TT4 (%)</th>
                                        <th style="font-size: 12px;">VOL. TT4 (KG)</th>
                                        <th style="font-size: 12px;">ALB PROD (%)</th>
                                        <th style="font-size: 12px;">VOL. PROD (KG)</th>
                                        <th style="font-size: 12px;">ALB TPL1 (%)</th>
                                        <th style="font-size: 12px;">VOL. TPL1 (KG)</th>
                                        <th style="font-size: 12px;">ALB TPL2 (%)</th>
                                        <th style="font-size: 12px;">VOL. TPL2 (KG)</th>
                                    </tr>
                                </thead>
                                
                                <?php 
                                    $toleransipersediaan = isset($_REQUEST['toleransi']) ? $_REQUEST['toleransi'] :  '4.8';
                                    $toleransiproduksi = isset($_REQUEST['toleransiproduksi']) ? $_REQUEST['toleransiproduksi'] :  '4.5';  
                                ?>

                                <tbody>
                                    @foreach ($lhp_rffaPMKS as $row)
                                        <tr>

                                            <?php 
                                                $date = date_create($row->TGL);
                                                
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td> 
                                            <td>{{$row->KEBUN}}</td>
                                            {{-- FFA TT 1 & VOL TT 1--}}
                                            @if($row->FFA_T1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_T1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif 
                                            @if ( round($row->FFA_T1 ,2) >= (float) $toleransipersediaan && $row->FFA_T1 != null) 
                                                <td class ="bg-red">{{number_format($row->FFA_T1,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_T1,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_T1 ,2) < (float) $toleransipersediaan && $row->FFA_T1 != null)
                                                <td>{{number_format($row->FFA_T1,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_T1,0,',','.')}}</td>
                                            @endif
                                            {{-- FFA TT2 & VOL TT 2 --}}
                                            @if($row->FFA_T2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_T2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif 
                                            @if ( round($row->FFA_T2 ,2) >= (float) $toleransipersediaan && $row->FFA_T2 != null)
                                                <td class ="bg-red">{{number_format($row->FFA_T2,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_T2,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_T2 ,2) < (float) $toleransipersediaan && $row->FFA_T2 != null)
                                                <td>{{number_format($row->FFA_T2,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_T2,0,',','.')}}</td>
                                            @endif
                                            {{-- FFA TT3 & VOL TT 3 --}}
                                            @if($row->FFA_T3 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_T3 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif 
                                            @if ( round($row->FFA_T3 ,2) >= (float) $toleransipersediaan && $row->FFA_T3 != null)
                                                <td class ="bg-red">{{number_format($row->FFA_T3,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_T3,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_T3 ,2) < (float) $toleransipersediaan && $row->FFA_T3 != null)
                                                <td>{{number_format($row->FFA_T3,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_T3,0,',','.')}}</td>
                                            @endif
                                            {{-- FFA TT4 VOL TT 4--}}
                                            @if($row->FFA_T4 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_T4 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif 
                                            @if ( round($row->FFA_T4 ,2) >= (float) $toleransipersediaan && $row->FFA_T4 != null)
                                                <td class ="bg-red">{{number_format($row->FFA_T4,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_T4,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_T4 ,2) < (float) $toleransipersediaan && $row->FFA_T4 != null)
                                                <td>{{number_format($row->FFA_T4,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_T4,0,',','.')}}</td>
                                            @endif
                                            {{-- FFA PRODUKSI & VOL --}}

                                            @if ( round($row->ALB_PRODUKSI ,2) >= (float) $toleransiproduksi)
                                                <td class ="bg-red">{{number_format($row->ALB_PRODUKSI,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_PRODUKSI,0,',','.')}}</td>
                                            @else
                                                <td>{{number_format($row->ALB_PRODUKSI,2,',','.')}}</td>
                                                @if ($row->VOLUME_PRODUKSI == 0)
                                                    <td class="bg-red">{{number_format($row->VOLUME_PRODUKSI,0,',','.')}}</td>
                                                @else
                                                    <td>{{number_format($row->VOLUME_PRODUKSI,0,',','.')}}</td>
                                                @endif
                                            @endif
                                            {{-- FFA TPL1 && VOL TPL 1--}}
                                            @if($row->FFA_TPL1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_TPL1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif 
                                            @if ( round($row->FFA_TPL1 ,2) >= (float) $toleransipersediaan && $row->FFA_TPL1 != null)
                                                <td class ="bg-red">{{number_format($row->FFA_TPL1,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_TPL1,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_TPL1 ,2) < (float) $toleransipersediaan && $row->FFA_TPL1 != null)
                                                <td>{{number_format($row->FFA_TPL1,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_TPL1,0,',','.')}}</td>
                                            @endif
                                            {{--FFA TPL2 && VOL TPL 2--}}
                                            @if($row->FFA_TPL2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if($row->VOLUME_TPL2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ( round($row->FFA_TPL2 ,2) >= (float) $toleransipersediaan && $row->FFA_TPL2 != null)
                                                <td class ="bg-red">{{number_format($row->FFA_TPL2,2,',','.')}}</td>
                                                <td class ="bg-red">{{number_format($row->VOLUME_TPL2,0,',','.')}}</td>
                                            @elseif ( round($row->FFA_TPL2 ,2) < (float) $toleransipersediaan && $row->FFA_TPL2 != null)
                                                <td>{{number_format($row->FFA_TPL2,2,',','.')}}</td>
                                                <td>{{number_format($row->VOLUME_TPL2,0,',','.')}}</td>
                                            @endif
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
        
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8'; ?>";
        document.getElementById('toleransiproduksi').value = "<?php echo isset($_GET['toleransiproduksi']) ? $_GET['toleransiproduksi'] : '4.5'; ?>";
    </script>
@endsection
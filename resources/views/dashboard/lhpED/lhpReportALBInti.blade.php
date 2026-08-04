@extends('dashboard.app')

@section('header-title')
    ALB IS Per PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            ALB IS Per PMKS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/ReportALBInti') }}">
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
                            <input type='number' step='0.01' value='4.8' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
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
                                        $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'DBTimbPMKSTD';
                                        // echo $selectkebun;  
                                        // echo $selecttype;
                                ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">TGL</th>
                                        <th style="font-size: 12px;">ALB BIN1 (%)</th>
                                        <th style="font-size: 12px;">VOL BIN1 (%)</th>
                                        <th style="font-size: 12px;">ALB BIN2 (%)</th>
                                        <th style="font-size: 12px;">VOL BIN2 (%)</th>
                                        <th style="font-size: 12px;">ALB BIN3 (%)</th>
                                        <th style="font-size: 12px;">VOL BIN3 (%)</th>
                                        <th style="font-size: 12px;">ALB BIN4 (%)</th>
                                        <th style="font-size: 12px;">VOL BIN4 (%)</th>
                                        <th style="font-size: 12px;">ALB PROD (%)</th>
                                        <th style="font-size: 12px;">VOLUME PROD (%)</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($lhp_ralb as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->TGL);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td> 
                                            @if ($row->ALB_BIN_1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ($row->VOLUME_BIN1 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ( round($row->ALB_BIN_1 ,2) >= 4.8  && $row->ALB_BIN_1 != null)
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->ALB_BIN_1,2,',','.')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->VOLUME_BIN1,0,',','.')}}</td>
                                            @elseif ( round($row->ALB_BIN_1 ,2) < 4.8  && $row->ALB_BIN_1 != null)
                                                <td style="text-align: right;">{{number_format($row->ALB_BIN_1,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->VOLUME_BIN1,0,',','.')}}</td>
                                            @endif
                                            @if ($row->ALB_BIN_2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ($row->VOLUME_BIN2 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ( round($row->ALB_BIN_2 ,2) >= 4.8 && $row->ALB_BIN_2 != null  )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->ALB_BIN_2,2,',','.')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->VOLUME_BIN2,0,',','.')}}</td>
                                            @elseif ( round($row->ALB_BIN_2 ,2) < 4.8 && $row->ALB_BIN_2 != null  )
                                                <td style="text-align: right;">{{number_format($row->ALB_BIN_2,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->VOLUME_BIN2,0,',','.')}}</td>
                                            @endif
                                            {{-- ALB BIN 3 & VOL BIN 3  --}}
                                            @if ($row->ALB_BIN_3 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ($row->VOLUME_BIN3 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ( round($row->ALB_BIN_3 ,2) >= 4.8 && $row->ALB_BIN_3 != null )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->ALB_BIN_3,2,',','.')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->VOLUME_BIN3,0,',','.')}}</td>
                                            @elseif (round($row->ALB_BIN_3 ,2) < 4.8 && $row->ALB_BIN_3 != null)
                                                <td style="text-align: right;">{{number_format($row->ALB_BIN_3,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->VOLUME_BIN3,0,',','.')}}</td>
                                            @endif
                                            {{-- ALB BIN 4 & VOL BIN 4--}}
                                            @if ($row->ALB_BIN_4 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ($row->VOLUME_BIN4 == null) 
                                                <td style="text-align: center;"> - </td>
                                            @endif
                                            @if ( round($row->ALB_BIN_4 ,2) >= 4.8 && $row->ALB_BIN_4 != null)
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->ALB_BIN_4,2,',','.')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->VOLUME_BIN4,0,',','.')}}</td>
                                            @elseif (round($row->ALB_BIN_4 ,2) < 4.8 && $row->ALB_BIN_4 != null)
                                                <td style="text-align: right;">{{number_format($row->ALB_BIN_4,2,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->VOLUME_BIN4,0,',','.')}}</td>
                                            @endif
                                            @if ( round($row->ALB_PRODUKSI ,2) >= 4.5 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->ALB_PRODUKSI ,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->VOLUME_PRODUKSI ,0,'.',',')}}</td>
                                            @else
                                                <td>{{number_format($row->ALB_PRODUKSI ,2,'.',',')}}</td>
                                                @if ($row->VOLUME_PRODUKSI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->VOLUME_PRODUKSI,0,',','.')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->VOLUME_PRODUKSI,0,',','.')}}</td>
                                                @endif
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
        // var lhpralb = <?php echo json_encode($lhp_ralb); ?>;
        // console.log(lhpralb);
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8'; ?>";
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'DBTimbPMKSTD'; ?>";
    </script>
@endsection
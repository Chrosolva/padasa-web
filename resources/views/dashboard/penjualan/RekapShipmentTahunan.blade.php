@extends('dashboard.app')

@section('header-title')
    Rekap Shipment Tahunan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Rekap Shipment Tahunan
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/penjualan/RekapShipmentTahunan') }}">
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
                            <label for="selectproduct">Produk : </label>
                            <select class="form-control" id="selectproduct" name="selectproduct">
                                <option value="SEMUA">SEMUA</option>
                                <option value="MINYAK SAWIT">MINYAK SAWIT</option>
                                <option value="INTI SAWIT">INTI SAWIT</option>
                                <option value="CANGKANG">CANGKANG</option>
                                <option value="ABU BOILER">ABU BOILER</option>
                                <option value="JANJANGAN KOSONG">JANJANGAN KOSONG</option>
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
                                        $selectproduct = isset($_REQUEST['selectproduct']) ? $_REQUEST['selectproduct'] :  'MINYAK SAWIT'; 
                                        // echo $selectkebun;  
                                        // echo $selecttype;
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th style="display:none;">COMP ID</th>
                                        <th>NAMA CUSTOMER</th>
                                        <th>NAMA PRODUK</th>
                                        <th>TONASE JAN [KG]</th>
                                        <th>TOTAL JAN [RP]</th>
                                        <th>TONASE FEB [KG]</th>
                                        <th>TOTAL FEB [RP]</th>
                                        <th>TONASE MAR [KG]</th>
                                        <th>TOTAL MAR [RP]</th>
                                        <th>TONASE APR [KG]</th>
                                        <th>TOTAL APR [RP]</th>
                                        <th>TONASE MAY [KG]</th>
                                        <th>TOTAL MAY [RP]</th>
                                        <th>TONASE JUN [KG]</th>
                                        <th>TOTAL JUN [RP]</th>
                                        <th>TONASE JUL [KG]</th>
                                        <th>TOTAL JUL [RP]</th>
                                        <th>TONASE AUG [KG]</th>
                                        <th>TOTAL AUG [RP]</th>
                                        <th>TONASE SEP [KG]</th>
                                        <th>TOTAL SEP [RP]</th>
                                        <th>TONASE OCT [KG]</th>
                                        <th>TOTAL OCT [RP]</th>
                                        <th>TONASE NOV [KG]</th>
                                        <th>TOTAL NOV [RP]</th>
                                        <th>TONASE DEC [KG]</th>
                                        <th>TOTAL DEC [RP]</th>
                                        <th>TOTAL TONASE [KG]</th>
                                        <th>TOTAL [RP]</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Rekap_ShipmentTahunan as $row)
                                        <tr>
                                            @if($row->NAMACUSTOMER == 'TOTAL')
                                                <td style="display:none;">{{$row->COMP_ID}}</td>
                                                <td><strong>{{$row->NAMACUSTOMER}}</td></strong>
                                                <td><strong>{{$row->NAMAPRODUK}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_JAN,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_JAN,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_FEB,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_FEB,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_MAR,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_MAR,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_APR,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_APR,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_MAY,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_MAY,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_JUN,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_JUN,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_JUL,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_JUL,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_AUG,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_AUG,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_SEP,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_SEP,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_OCT,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_OCT,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_NOV,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_NOV,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TONASE_DEC,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_DEC,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL_TONASE,0,',','.')}}</td></strong>
                                                <td style="text-align: right;"><strong>{{number_format($row->TOTAL,0,',','.')}}</td></strong>
                                            @else
                                                <td style="display:none;">{{$row->COMP_ID}}</td>
                                                <td>{{$row->NAMACUSTOMER}}</td>
                                                <td>{{$row->NAMAPRODUK}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_JAN,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_JAN,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_FEB,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_FEB,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_MAR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_MAR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_APR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_APR,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_MAY,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_MAY,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_JUN,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_JUN,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_JUL,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_JUL,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_AUG,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_AUG,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_SEP,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_SEP,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_OCT,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_OCT,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_NOV,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_NOV,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TONASE_DEC,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_DEC,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL_TONASE,0,',','.')}}</td>
                                                <td style="text-align: right;">{{number_format($row->TOTAL,0,',','.')}}</td>
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'MINYAK SAWIT'; ?>";
    </script>

@endsection

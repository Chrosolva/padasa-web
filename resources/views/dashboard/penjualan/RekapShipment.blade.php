@extends('dashboard.app')

@section('header-title')
    Rekap Shipment
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Rekap Shipment
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/penjualan/RekapShipment') }}">
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
                                        // echo $selectkebun;  
                                        // echo $selecttype;

                                        $totalshipmentCPO = 0; 
                                        $totalshipmentPK = 0;
                                        $totalshipmentCPOPK = 0;
                                        $totalshipmentcangkang = 0;
                                        $totalshipmentjankos = 0;
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th>TAHUN</th>
                                        <th>BULAN</th>
                                        <th>TOTAL SHIPMENT CPO [KG]</th>
                                        <th>TOTAL SHIPMENT PK [KG]</th>
                                        <th>TOTAL SHIPMENT CPO PK [KG]</th>
                                        <th>TOTAL SHIPMENT CANGKANG</th>
                                        <th>TOTAL SHIPMENT JANKOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Rekap_Shipment as $row)
                                        <tr>
                                            <td>{{$row->TAHUN}}</td>
                                            <td>{{$row->BULAN}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL_SHIPMENT_CPO,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL_SHIPMENT_PK,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL_SHIPMENT_CPO_PK,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL_SHIPMENT_CANGKANG,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL_SHIPMENT_JANKOS,0,',','.')}}</td>
                                        </tr>

                                        <?php 
                                            $totalshipmentCPO += $row->TOTAL_SHIPMENT_CPO;
                                            $totalshipmentPK += $row->TOTAL_SHIPMENT_PK;
                                            $totalshipmentCPOPK += $row->TOTAL_SHIPMENT_CPO_PK;
                                            $totalshipmentcangkang += $row->TOTAL_SHIPMENT_CANGKANG;
                                            $totalshipmentjankos += $row->TOTAL_SHIPMENT_JANKOS;
                                        ?>
                                    @endforeach

                                    <tr>
                                            <td><strong>TOTAL </strong></td>
                                            <td>-</td>
                                            <td style="text-align: right;"><strong>{{number_format($totalshipmentCPO,0,',','.')}}</strong></td>
                                            <td style="text-align: right;"><strong>{{number_format($totalshipmentPK,0,',','.')}}</strong></td>
                                            <td style="text-align: right;"><strong>{{number_format($totalshipmentCPOPK,0,',','.')}}</strong></td>
                                            <td style="text-align: right;"><strong>{{number_format($totalshipmentcangkang,0,',','.')}}</strong></td>
                                            <td style="text-align: right;"><strong>{{number_format($totalshipmentjankos,0,',','.')}}</strong></td>
                                        </tr>
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
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>

@endsection

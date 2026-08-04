@extends('dashboard.app')

@section('header-title')
    Outstanding Per Kontrak
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Outstanding Per Kontrak
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/outstanding') }}">
                    <div class="form-group">
                        <label for="per_tanggal">Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selectkebun">MILL : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            <option value="TELDA">TELDA</option>
                            <option value="KALSA">KALSA</option>
                            <option value="KALDA">KALDA</option>
                            <option value="KOKAR">KOKAR</option>
                            <option value="RICKO">RICKO</option>
                            <option value="PASER">PASER</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="table-data" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>MILL</th>
                                    <th>NO KONTRAK</th>
                                    <th>TGL KONTRAK</th>
                                    <th>TGL DO</th>
                                    <th>PRODUCT CODE</th>
                                    <th>NAMA CUSTOMER</th>
                                    <th>QTY KONTRAK</th>
                                    <th>HARGA TOTAL KONTRAK [RP]</th>
                                    <th>QTY PENGIRIMAN</th>
                                    <th>HARGA TOTAL PENGIRIMAN [RP]</th>
                                    <th>QTY RETUR</th>
                                    <th>SISA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outstanding as $row)
                                    <tr>
                                        <?php 
                                            $date = date_create($row->TGL_KONTRAK);
                                            $date2 = date_create($row->TGL_DO);
                                        ?>
                                        <td>{{$row->MILL}}</td>
                                        <td>{{$row->NO_KONTRAK}}</td>
                                        <td>{{date_format($date, 'd/m/y')}}</td>
                                        <td>{{date_format($date2, 'd/m/y')}}</td>
                                        <td>{{$row->PRODUCTCODE}}</td> 
                                        <td>{{$row->NAMA_CUSTOMER}}</td> 
                                        <td style="text-align: right;">{{number_format($row->QTY_KONTRAK,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->HARGA_TOTAL_KONTRAK,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->QTY_PENGIRIMAN,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->HARGA_TOTAL_PENGIRIMAN,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->QTYRETUR,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->SISA,0,',','.')}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('per_tanggal');
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'TELDA'; ?>";
    </script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Kontrak Penjualan 
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Kontrak Penjualan
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/kontrak_jualV2') }}">
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
                        <label for="selectkebun">PABRIK : </label>
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
                    <div class="form-group">
                        <label for="selectproduct">Produk : </label>
                        <select class="form-control" id="selectproduct" name="selectproduct">
                            <option value="%%">SEMUA</option>
                            <option value="MINYAK SAWIT">MINYAK SAWIT</option>
                            <option value="INTI SAWIT">INTI SAWIT</option>
                            <option value="CANGKANG">CANGKANG</option>
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
                                    <th>PABRIK</th>
                                    <th>NO KONTRAK</th>
                                    <th>PRODUK</th>
                                    <th>QTY KONTRAK</th>
                                    <th>NAMA CUSTOMER</th>
                                    <th>UNIT PRICE</th>
                                    <th>TOTAL PENJUALAN</th>
                                    <th>SISA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kontrak_jualV2 as $row)
                                    <tr>
                                        <td>{{$row->MILL_KONTRAK}}</td>
                                        <td>{{$row->NO_KONTRAK}}</td>
                                        <td>{{$row->PRODUK}}</td>
                                        <td style="text-align: right;">{{number_format($row->KONTRAK_QTY,0,',','.')}}</td>
                                        <td>{{$row->NAMA_CUST}}</td>
                                        <td>{{number_format($row->UNITPRICE,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->TOTAL_PENJUALAN,0,',','.')}}</td>
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
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : '%%'; ?>";
    </script>
@endsection
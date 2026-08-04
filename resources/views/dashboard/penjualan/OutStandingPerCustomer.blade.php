@extends('dashboard.app')

@section('header-title')
    Outstanding Per Customer
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Outstanding Per Customer
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/outstandingpercust') }}">
                    <div class="form-group">
                        <label for="per_tanggal">Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label for="selectkebun">MILL : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            <option value="TELDA">TELDA</option>
                            <option value="KALSA">KALSA</option>
                            <option value="KALDA">KALDA</option>
                            <option value="KOKAR">KOKAR</option>
                            <option value="RICKO">RICKO</option>
                            <option value="PASER">PASER</option>
                        </select>
                    </div> --}}

                    <div class="form-group">
                        <label for="selectproduk">PRODUK : </label>
                        <select class="form-control" id="selectproduk" name="selectproduk">
                            <option value="SEMUA">SEMUA</option>
                            <option value="CPO">MINYAK SAWIT</option>
                            <option value="PK">INTI SAWIT</option>
                            <option value="CP1">CRUDE PALM OIL (CP1)</option>
                            <option value="CKG">CANGKANG</option>
                            <option value="ABB">ABU BOILER</option>
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
                                    <th>NAMA CUSTOMER</th>
                                    <th>PRODUK</th>
                                    <th>TELDA</th>
                                    <th>KALSA</th>
                                    <th>KALDA</th>
                                    <th>KOKAR</th>
                                    <th>RICKO</th>
                                    <th>PASER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outstandingpc as $row)
                                    <tr>
                                        <td>{{$row->NAMA_CUSTOMER}}</td>
                                        <td>{{$row->PRODUCTDESCRIPTION}}</td> 
                                        <td style="text-align: right;">{{number_format($row->TELDA,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->KALSA,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->KALDA,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->KOKAR,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->RICKO,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->PASER,0,',','.')}}</td>
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        // document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'TELDA'; ?>";
        document.getElementById('selectproduk').value = "<?php echo isset($_GET['selectproduk']) ? $_GET['selectproduk'] : 'CPO'; ?>";
    </script>
@endsection
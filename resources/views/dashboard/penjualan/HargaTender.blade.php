@extends('dashboard.app')

@section('header-title')
    Harga Tender
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Harga Tender 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/harga-tender') }}">
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
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
                                    <th>TANGGAL</th>
                                    <th>HARGA TENDER MS</th>
                                    <th>HARGA TENDER IS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($harga_tender as $row)
                                    <tr>
                                        <?php 
                                            $date = date_create($row->TANGGAL);
                                        ?>
                                        <td>{{date_format($date, 'd/m/y')}}</td> 
                                        @if ($row->HARGA_TENDER_CPO == null)
                                            <td style="text-align: right;">-</td>
                                        @else
                                            <td style="text-align: right;">{{number_format($row->HARGA_TENDER_CPO,0,',','.')}}</td>
                                        @endif
                                        @if ($row->HARGA_TENDER_PK == null)
                                            <td style="text-align: right;">-</td>
                                        @else
                                            <td style="text-align: right;">{{number_format($row->HARGA_TENDER_PK,0,',','.')}}</td>
                                        @endif
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
        makeDataTableResponsive('table-data', 0, 'desc', 10);
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
    </script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Harga Beli TBS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Harga Beli TBS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/hargaBeliTBS') }}">
                    <div class="form-group">
                        <label for="dari_tanggal">Dari Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampai_tanggal">Sampai Tanggal : </label>
                        <div class="input-group date input-inline">
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
            @for ($i = 0; $i < count($kode_kebun); $i++)
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $kode_kebun[$i]->nama_lengkap }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="table-data{{$i}}" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No Pembelian</th>
                                            <th>Tgl Pembelian</th>
                                            <th>Kode SP</th>
                                            <th>Jlh Sblm Sortasi</th>
                                            <th>Jlh Sesudah Sortasi</th>
                                            <th>Jlh Potongan</th>
                                            <th>Persen Potongan</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($harga_beliTBS[$i] as $row)
                                            <tr>
                                                <td>{{ $row->NoPembelian }}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->TglPembelian)) }}</td>
                                                <td>{{ $row->KodeSP }}</td>
                                                <td>{{ number_format(round($row->JlhSblmSortasi,2), 0, ',', '.')}}</td>
                                                <td>{{ number_format(round($row->JlhSesudahSortasi,2), 0, ',', '.')}}</td>
                                                <td>{{ number_format(round($row->JlhPotongan,2), 0, ',', '.') }}</td>
                                                <td>{{ number_format(round($row->PersenPotongan,2), 2, ',', '.')}}</td>
                                                <td>{{ number_format(round($row->Harga,2), 0, ',', '.')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        var kodeKebun = <? php echo json_encode($kode_kebun); ?>;
        console.log(kodeKebun);
        for(j =0; j< kodeKebun.length; j++) {
            makeDataTableResponsive('table-data' + j, 0, 'desc', 10);
        }
    </script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Hasil Timbang
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Hasil timbang TBS per Kebun
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/timbangan/hasil-timbang') }}">
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
                        <label for="status">Status : </label>
                        <select class="form-control" id="status" name="status">
                            <option class="form-control" value ="0">Kebun sendiri</option>
                            <option class="form-control" value ="1">Kebun seinduk - mitra</option>
                            <option class="form-control" value ="2">pihak 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @for ($i = 0; $i < count($kebun); $i++)
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $kebun[$i]->nama_lengkap }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="table-data{{$i}}" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>KodeSP</th>
                                            <th>Tgl TBS Masuk</th>
                                            <th>Brutto</th>
                                            <th>Tarra</th>
                                            <th>Netto1</th>
                                            <th>Total Potongan</th>
                                            <th>Persen Potongan</th>
                                            <th>Netto2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hasil_timbang[$i] as $row)
                                            <tr>
                                                <td>{{ $row->KodeSP }}</td>
                                                <td>{{ $row->TANGGAL_TBS_MASUK }}</td>
                                                <td>{{ $row->BRUTTO }}</td>
                                                <td>{{ $row->TARRA }}</td>
                                                <td>{{ $row->NETTO1}}</td>
                                                <td>{{ $row->TOTAL_POTONGAN }}</td>
                                                <td>{{ $row->PERSEN_POTONGAN}}</td>
                                                <td>{{ $row->NETTO2}}</td>
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
        var kodeKebun = <?php echo json_encode($kebun); ?>;
        console.log(kodeKebun);
        for(j =0; j< kodeKebun.length; j++) {
            makeDataTableResponsive('table-data' + j, 0, 'desc', 10);
        }
        document.getElementById('status').value = "<?php echo isset($_GET['status']) ? $_GET['status'] : '0'; ?>";
    </script>
@endsection
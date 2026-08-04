@extends('dashboard.app')

@section('header-title')
    Curah Hujan EPLANT
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Curah Hujan EPLANT
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpCurahHujan') }}">
                    <div class="row">
                        {{-- <div class="form-group">
                            <label for="pilih_tanggal">Pilih Tanggal : </label>
                            <div class="input-group date input-inline">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="pilih_tanggal" name="pilih_tanggal" value="{{ Request::get('pilih_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div> --}}
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
                                <option value="2200">TELDA</option>
                                <option value="2300">KALSA</option>
                                <option value="2400">KALDA</option>
                                <option value="2500">KOKAR</option>
                                <option value="2600">MITRA KOKAR</option>
                                <option value="3200">RICKO</option>
                                <option value="4200">MUARA</option>
                                <option value="5200">PASER</option>
                                <option value="6200">LANGGAI</option>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        {{-- <div class="form-group form-inline">
                            <a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenExport') }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div> --}}
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
                                        <th style="font-size: 12px;">Lokasi</th>
                                        <th style="font-size: 12px;">Curah Hujan(MM)</th>
                                        <th style="font-size: 12px;">Kategori</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_WeatherStation as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->Tgl);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            <td>{{$row->Lokasi}}</td>
                                            <td>{{number_format($row->CurahHujan,0,',','.')}}</td>
                                            <td>{{$row->Kategori}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <p><strong>Catatan : * Jika Curah Hujan pada tanggal tidak terisi, berarti curah hujan 0 mm</strong></p>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>
@endsection
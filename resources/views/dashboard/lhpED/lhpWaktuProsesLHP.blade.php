@extends('dashboard.app')

@section('header-title')
    Waktu Proses LHP
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Waktu Proses LHP
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpWaktuProsesLHP') }}">
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
                                <option value="SEMUA">SEMUA</option>
                                <option value="TELDA">TELDA</option>
                                <option value="KALSA">KALSA</option>
                                <option value="KALDA">KALDA</option>
                                <option value="KOKAR">KOKAR</option>
                                <option value="RICKO">RICKO</option>
                                <option value="PASER">PASER</option>
                            </select>
                        </div>
                        {{-- <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='4.8' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="toleransiproduksi">Toleransi Produksi : </label>
                            <input type='number' step='0.01' value='4.5' placeholder='0.00' id = 'toleransiproduksi' name = 'toleransiproduksi'/>
                        </div> --}}
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        {{-- <div class="form-group form-inline">
                            <a href="{{ url('/dashboard/lhpexecutive/lhpTBSOlahExport') }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
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
                                        <th style="font-size: 12px;display:none;">BARIS</th>
                                        <th style="font-size: 12px;">TGL LHP</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        {{-- <th style="font-size: 12px;">TARGET WAKTU PENYELESAIAN</th> --}}
                                        <th style="font-size: 12px;">TGL JAM PENYELESAIAN</th>
                                        <th style="font-size: 12px;">SELISIH WAKTU PENYELESAIAN</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_WaktuProsesLHP as $row)
                                        @if(str_contains($row->SELISIH_WAKTU_PENYELESAIAN, 'LEBIH'))
                                            <tr class="bg-red">
                                        @else
                                            <tr>
                                        @endif
                                            <?php
                                                $date = date_create($row->TANGGAL);
                                                $date2 = date_create($row->LHP);
                                            ?>
                                            <td style="display: none;">{{$row->BARIS}}</td>
                                            <td>{{date_format($date2, 'd/m/y')}}</td>
                                            <td>{{$row->KEBUN}}</td>
                                            {{-- <td style="text-align: right;">{{$row->TARGET_WAKTU_PENYELESAIAN}}</td> --}}
                                            <td style="text-align: right;">{{$row->TGL_JAM_PENYELESAIAN}}</td>
                                            <td style="text-align: right;">{{$row->SELISIH_WAKTU_PENYELESAIAN}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h5>Catatan: Target Penyelesaian LHP setiap jam 11 Pagi</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        // $(document).ready(function() {
        //     $('#table-data').DataTable( {
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'copy','csv','excel', 'pdf', 'print'
        //         ]
        //     } );
        // } );
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        // document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '4.8'; ?>";
        // document.getElementById('toleransiproduksi').value = "<?php echo isset($_GET['toleransiproduksi']) ? $_GET['toleransiproduksi'] : '4.5'; ?>";
    </script>

@endsection

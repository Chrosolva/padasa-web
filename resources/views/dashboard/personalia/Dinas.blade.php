@extends('dashboard.app')

@section('header-title')
    Dinas
@endsection

@section('main-content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Daftar Pegawai Dinas
			<small></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/personalia/dinas') }}">
                    {{-- <div class="form-group">
                        <label for="per_tanggal">Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div> --}}

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
							<input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"  value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-0 days')) }}">
						</div>
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
					<div class="box-body table-responsive">
						<table id="table-data" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th style="display: none;">No</th>
									<th>KodePT</th>
                                    <th>Lokasi</th>
									<th>Kode Pekerja</th>
									<th>Nama Pegawai</th>
									<th>Jabatan</th>
									<th>Tanggal Berangkat</th>
									<th>Tanggal Selesai Dinas</th>
									<th>Tujuan</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($dinas as $row)
									<tr>
										<td style="display: none;">{{ $row->No }}</td>
										<td>{{ $row->KodePT }}</td>
                                        <td>{{$row->Lokasi}}</td>
										<td>{{ $row->KodePekerja }}</td>
										<td>{{ $row->NamaPegawai }}</td>
										<td>{{ $row->Jabatan }}</td>
										<td>{{ $row->TglBerangkat}}</td>
										<td>{{ $row->TglSelesaiDinas }}</td>
										<td>{{ $row->Tujuan}}</td>
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
        makeDataTableResponsive('table-data', 0, 'asc', 25);
    </script>
@endsection

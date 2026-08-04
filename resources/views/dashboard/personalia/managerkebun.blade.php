@extends('dashboard.app')

@section('header-title')
    Daftar Manager
@endsection

@section('main-content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Daftar Manager Kebun
			<small></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body table-responsive">
						<table id="table-data" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
                                    <th style="display: none;">No Urut</th>
									<th>Kebun</th>
									<th>Jabatan</th>
									<th>Nama</th>
									<th>Kontak</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($daftar_manager as $row)
									<tr>
										<td style="display: none;">{{ $row->Urutan }}</td>
										<td>{{ $row->Kebun }}</td>
										<td>{{ $row->Jabatan }}</td>
										<td>{{ $row->Nama }}</td>
										<td>{{ $row->Kontak }}</td>
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
        setValidationDatePicker('per_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', 25);
        
    </script>
@endsection
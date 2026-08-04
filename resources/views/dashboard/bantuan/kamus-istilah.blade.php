@extends('dashboard.app')

@section('header-title')
    Daftar Kamus Istilah
@endsection

@section('main-content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Daftar Kamus Istilah
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
									<th>Singkatan</th>
									<th>Istilah</th>
									<th>Identik</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($kamus_istilah as $row)
									<tr>
										<td>{{ $row->Singkatan}}</td>
										<td>{{ $row->Istilah }}</td>
										<td>{{ $row->Identik }}</td>
										<td>{{ $row->Keterangan }}</td>
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        // var kamusIstilah = <?php echo json_encode($kamus_istilah); ?>;
        // console.log(kamusIstilah);
    </script>
@endsection

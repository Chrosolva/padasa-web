@extends('dashboard.app')

@section('header-title')
    Daftar Karyawan
@endsection

@section('main-content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Daftar Karyawan
			<small></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/personalia/daftarkaryawan') }}">
                    <div class="row">
                        <div class="form-group">
                            <label for="selectlokasi">Lokasi : </label>
                            <select class="form-control" id="selectlokasi" name="selectlokasi">
                                <option value="ALL">ALL</option>
                                <option value="KANDIR JAKARTA PEU">KANDIR JAKARTA PEU</option>
                                <option value="KANDIR MEDAN PEU">KANDIR MEDAN PEU</option>
                                <option value="TELUK DALAM PEU">TELUK DALAM PEU</option>
                                <option value="KALIANTA SATU PEU">KALIANTA SATU PEU</option>
                                <option value="KALIANTA DUA PEU">KALIANTA DUA PEU</option>
                                <option value="KOTO KAMPAR PEU">KOTO KAMPAR PEU</option>
                                <option value="MITRA KOTO KAMPAR PEU">MITRA KOTO KAMPAR PEU</option>
                                <option value="RICKO APMR">RICKO APMR</option>
                                <option value="KANDIR JAKARTA BMML">KANDIR JAKARTA BMML</option>
                                <option value="MUARA BMML">MUARA BMML</option>
                                <option value="PASER MMMA">PASER MMMA</option>
                                <option value="LANGGAI SANR">LANGGAI SANR</option>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
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
                                    <th style="display: none;">Baris</th>
									<th>Lokasi</th>
									<th>Jabatan</th>
									<th>Nama</th>
									<th>Kontak</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($daftar_karyawan as $row)
									<tr>
										<td style="display: none;">{{ $row->BARIS }}</td>
										<td>{{ $row->LOKASI }}</td>
										<td>{{ $row->JABATAN }}</td>
										<td>{{ $row->NAMA }}</td>
										<td>{{ $row->KONTAK }}</td>
										<td>{{ $row->EMAIL }}</td>
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
        document.getElementById('selectlokasi').value = "<?php echo isset($_GET['selectlokasi']) ? $_GET['selectlokasi'] : 'KANDIR JAKARTA PEU'; ?>";
    </script>
@endsection
@extends('admin.app')

@section('header-title')
    Modul Per Kebun
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Modul Per Kebun
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Modul Per Kebun</li>
		</ol>
	</section>

	<section class="content">

		@if (session()->has('message'))
		    <div class="alert alert-success alert-dismissable">
		        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		        {{ session('message') }}
		    </div>
		@endif

		@if (session()->has('error'))
		    <div class="alert alert-danger alert-dismissable">
		        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
		    </div>
		@endif

		<div class="box box-primary">
            <div class="box-header">
                <a class="btn btn-primary" href="{{ url('/admin/modul-per-kebun/edit') }}"><i class="fa fa-pencil-square-o"></i> Edit Modul Per Kebun</a>
            </div>
			<div class="box-body table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">Nama Modul / Kebun</th>
							@foreach ($kebun as $row)
								<th class="text-center">{{ $row->nama_singkat }}</th>
	                        @endforeach
						</tr>
					</thead>
					<tbody>
						@foreach ($modul as $row_modul)
							<tr>
                                <td><b>{{ $row_modul->nama_modul }}</b></td>
                                @foreach ($kebun as $row_kebun)
                                    <?php
                                        $status = false;
                                    ?>
                                	@foreach ($modul_per_kebun as $row)
                                		@if ($row->nama_modul == $row_modul->nama_modul && $row->kode_kebun == $row_kebun->kode_kebun)
                                            <?php
                                                $status = true;
                                            ?>
                                		@endif
                                	@endforeach

                                	<td class="text-center">
	                                	@if ($status == true)
	                                		<i class="fa fa-check fa-lg text-success"></i>
                                		@else
                                			<i class="fa fa-close fa-lg text-danger"></i>
	                            		@endif
	                            	</td>
		                        @endforeach
                            </tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
@endsection

@extends('admin.app')

@section('header-title')
    Hak Akses
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Hak Akses
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Hak Akses</li>
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
                <a class="btn btn-primary" href="{{ url('/admin/hak-akses/create') }}"><i class="fa fa-plus-circle"></i> Create New</a>
            </div>
			<div class="box-body table-responsive">
				<table id="table-data" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nama Modul</th>
							<th>Hak Akses</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($hak_akses as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->nama_modul }}</td>
                                <td>{{ $row->hak_akses }}</td>
                                <td>
                                    <button class="btn btn-primary btn-xs" onclick="window.location.href='{{ url('/admin/hak-akses') }}/{{ $row->id }}/edit'">Edit</button>
                                	<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-modal" data-id="{{ $row->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span></button>
                    	<h4 class="modal-title">Delete</h4>
		            </div>
		            <div class="modal-body">
		            	Sure you want to delete?
		            </div>
		            <div class="modal-footer">
		                <form role="form" method="POST" action="" id="form-delete">
		                	{{ csrf_field() }}
		                    <input type="hidden" name="_method" value="DELETE">
		                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
		                    <button type="submit" class="btn btn-danger">Submit</button>
		                </form>
		            </div>
		        </div>
		    </div>
		</div>
	</section>

@endsection

@section('script-content')
	<script>
		makeDataTableResponsive('table-data', 0, 'desc');

		$('#delete-modal').on('show.bs.modal', function(e) {
			var id = $(e.relatedTarget).data('id');
			$('#form-delete').attr('action', '{{ url('/admin/hak-akses') }}/' + id + '/delete');
        });
	</script>
@endsection

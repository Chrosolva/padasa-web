@extends('admin.app')

@section('header-title')
    User
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			User
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">User</li>
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
                <a class="btn btn-primary" href="{{ url('/admin/user/create') }}"><i class="fa fa-plus-circle"></i> Create New</a>
            </div>
			<div class="box-body table-responsive">
				<table id="table-data" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Username</th>
							<th>Nama</th>
							<th>Email</th>
							<th>Role</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($user as $row)
                            <tr>
                                <td>{{ $row->username }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->admin ? 'Admin' : 'User' }}</td>
                                <td>{{ $row->deleted_at == null ? 'Aktif' : 'Tidak Aktif' }}</td>
                                <td>
                                    <button class="btn btn-primary btn-xs" onclick="window.location.href='{{ url('/admin/user') }}/{{ $row->username }}/edit'">Edit</button>
                                    @if ($row->deleted_at == null)	
                                    	<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirmation-modal" data-username="{{ $row->username }}" data-value="non-aktif">Set Tidak Aktif</button>
                                    @else
                                    	<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#confirmation-modal" data-username="{{ $row->username }}" data-value="aktif">Set Aktif</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span></button>
                    	<h4 class="modal-title">Confirmation</h4>
		            </div>
		            <div class="modal-body">
		            </div>
		            <div class="modal-footer">
		                <form role="form" method="POST" action="">
		                	{{ csrf_field() }}
		                    <input type="hidden" name="_method" value="">
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
		makeDataTableResponsive('table-data');

		$('#confirmation-modal').on('show.bs.modal', function(e) {
			var username = $(e.relatedTarget).data('username');
			var value = $(e.relatedTarget).data('value');
			$($('#confirmation-modal .modal-body')[0]).html('Sure you want to set user "' + username + '" as ' + (value == 'aktif' ? 'Aktif' : 'Tidak Aktif') + ' ?');
            $($('#confirmation-modal form')[0]).attr('action', '{{ url('/admin/user') }}/' + username + '/' + value);
            $($('#confirmation-modal input[name="_method"]')[0]).attr('value', (value == 'aktif' ? 'PUT' : 'DELETE'));
        });
	</script>
@endsection

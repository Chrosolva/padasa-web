@extends('admin.app')

@section('header-title')
    Create Hak Akses
@endsection

@section('header-content')
    <style type="text/css">
        .form-input-closeable {
        	margin-right: 15px;
        }
    </style>
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Create Hak Akses
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/hak-akses') }}">Hak Akses</a></li>
			<li class="active">Create</li>
		</ol>
	</section>

	<section class="content">
		<div class="box box-primary">
            <form role="form" method="POST" action="{{ url('/admin/hak-akses/create') }}">
            	{{ csrf_field() }}
				<div class="box-body">
					@php $hak_akses = 1 @endphp
					@for ($i = 0; $i < $hak_akses; $i++)
						<div class="form-group" id="hak_akses_{{ $i }}">
		                    <button type="button" class="close">
		                    	<span aria-hidden="true" onclick="removeHakAkses({{ $i }})">&times;</span>
		                    </button>
		                    <div class="row form-input-closeable">
		                    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			                    	<label for="nama_modul[]">Nama Modul *</label>
									<input type="text" name="nama_modul[]" id="nama_modul" class="form-control" placeholder="Nama Modul" maxlength="50" value="" required>
		                    	</div>
		                    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			                    	<label for="hak_akses[]">Hak Akses *</label>
									<input type="text" name="hak_akses[]" id="hak_akses" class="form-control" placeholder="Hak Akses" maxlength="50" value="" required>
		                    	</div>
		                    </div>
						</div>
					@endfor
					<button type="button" class="btn btn-primary" onclick="addMoreHakAkses()" id="btn_add_more"><i class="fa fa-plus-circle"></i> Add More</button>
				</div>
            	<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/hak-akses') }}'"><i class="fa fa-list"></i> Cancel</button>
            	</div>
			</form>
		</div>
	</section>
@endsection

@section('script-content')
	<script type="text/javascript">
		var hak_akses_id = {{ $hak_akses }};

		function addMoreHakAkses() {
			hak_akses_id++;
			var hak_akses_html =
				'<div class="form-group" id="hak_akses_' + hak_akses_id + '">' +
                    '<button type="button" class="close">' +
                    	'<span aria-hidden="true" onclick="removeHakAkses(' + hak_akses_id + ')">&times;</span>' +
                    '</button>' +
                    '<div class="row form-input-closeable">' +
                    	'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
	                    	'<label for="nama_modul[]">Nama Modul *</label>' +
							'<input type="text" name="nama_modul[]" id="nama_modul" class="form-control" placeholder="Nama Modul" maxlength="50" required>' +
                    	'</div>' +
                    	'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
	                    	'<label for="hak_akses[]">Hak Akses *</label>' +
							'<input type="text" name="hak_akses[]" id="hak_akses" class="form-control" placeholder="Hak Akses" maxlength="50" required>' +
                    	'</div>' +
                    '</div>' +
				'</div>"';
			var new_hak_akses = $(hak_akses_html).hide();
			new_hak_akses.insertBefore($('#btn_add_more'));
			new_hak_akses.slideDown();
		}

		function removeHakAkses(id) {
			var hak_akses = $('#hak_akses_' + id).slideUp(function() {
				hak_akses.remove();
			});
		}

		@if ($hak_akses == 0)
			addMoreHakAkses();
		@endif
	</script>
@endsection

@extends('admin.app')

@section('header-title')
    Edit Hak Akses
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Edit Hak Akses
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/hak-akses') }}">Hak Akses</a></li>
			<li class="active">Edit</li>
		</ol>
	</section>

	<section class="content">
		
		<div class="box box-primary">
            <form role="form" method="POST" action="{{ url('/admin/hak-akses') }}/{{ $hak_akses->id }}/edit">
	        	{{ csrf_field() }}
	            <input type="hidden" name="_method" value="PUT">
				<div class="box-body">
					<div class="form-group">
						<label for="id">ID</label>
						<input type="number" class="form-control" id="id" placeholder="ID" value="{{ $hak_akses->id }}" disabled>
					</div>
					<div class="form-group {{ $errors->has('nama_modul') ? 'has-error' : '' }}">
						<label for="nama_modul">Nama Modul *</label>
						<input type="text" name="nama_modul" id="nama_modul" class="form-control" placeholder="Nama Modul" maxlength="50" value="{{ old('nama_modul', $hak_akses->nama_modul) }}" required autofocus>
                        @if ($errors->has('nama_modul'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_modul') }}</span>
                        @endif
					</div>
					<div class="form-group {{ $errors->has('hak_akses') ? 'has-error' : '' }}">
						<label for="hak_akses">Hak Akses *</label>
						<input type="text" name="hak_akses" id="hak_akses" class="form-control" placeholder="Hak Akses" maxlength="50" value="{{ old('hak_akses', $hak_akses->hak_akses) }}" required>
                        @if ($errors->has('hak_akses'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('hak_akses') }}</span>
                        @endif
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
			        <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/hak-akses') }}'"><i class="fa fa-list"></i> Cancel</button>
				</div>
			</form>
		</div>
	</section>
@endsection

@extends('admin.app')

@section('header-title')
    Create Modul
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Create Modul
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/modul') }}">Modul</a></li>
			<li class="active">Create</li>
		</ol>
	</section>

	<section class="content">
		<div class="box box-primary">
            <form role="form" method="POST" action="{{ url('/admin/modul/create') }}">
            	{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group {{ $errors->has('nama_modul') ? 'has-error' : '' }}">
                    	<label for="nama_modul">Nama Modul *</label>
						<input type="text" name="nama_modul" id="nama_modul" class="form-control" placeholder="Nama Modul" maxlength="50" value="{{ old('nama_modul') }}" required autofocus>
                        @if ($errors->has('nama_modul'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_modul') }}</span>
                        @endif
                    </div>
            	<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/modul') }}'"><i class="fa fa-list"></i> Cancel</button>
            	</div>
			</form>
		</div>
	</section>
@endsection

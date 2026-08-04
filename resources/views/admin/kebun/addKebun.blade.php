@extends('admin.app')

@section('header-title')
    Create Kebun
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Create Kebun
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/kebun') }}">Kebun</a></li>
			<li class="active">Create</li>
		</ol>
	</section>

	<section class="content">
		<div class="box box-primary">
            <form role="form" method="POST" action="{{ url('/admin/kebun/create') }}">
            	{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group {{ $errors->has('kode_kebun') ? 'has-error' : '' }}">
                    	<label for="kode_kebun">Kode Kebun *</label>
						<input type="text" name="kode_kebun" id="kode_kebun" class="form-control" placeholder="Kode Kebun" maxlength="10" value="{{ old('kode_kebun') }}" required autofocus>
                        @if ($errors->has('kode_kebun'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('kode_kebun') }}</span>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('nama_singkat') ? 'has-error' : '' }}">
                    	<label for="nama_singkat">Nama Singkat *</label>
						<input type="text" name="nama_singkat" id="nama_singkat" class="form-control" placeholder="Kode Kebun" maxlength="50" value="{{ old('nama_singkat') }}" required>
                        @if ($errors->has('nama_singkat'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_singkat') }}</span>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('nama_lengkap') ? 'has-error' : '' }}">
                    	<label for="nama_lengkap">Nama Lengkap *</label>
						<input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Kode Kebun" maxlength="50" value="{{ old('nama_lengkap') }}" required>
                        @if ($errors->has('nama_lengkap'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_lengkap') }}</span>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('kode_PT') ? 'has-error' : '' }}">
                    	<label for="kode_PT">Kode PT *</label>
						<input type="text" name="kode_PT" id="kode_PT" class="form-control" placeholder="Kode Kebun" maxlength="5" value="{{ old('kode_PT') }}" required>
                        @if ($errors->has('kode_PT'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('kode_PT') }}</span>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('nama_PT') ? 'has-error' : '' }}">
                    	<label for="nama_PT">Nama PT *</label>
						<input type="text" name="nama_PT" id="nama_PT" class="form-control" placeholder="Kode Kebun" maxlength="50" value="{{ old('nama_PT') }}" required>
                        @if ($errors->has('nama_PT'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_PT') }}</span>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('nama_DB') ? 'has-error' : '' }}">
                    	<label for="nama_DB">Nama DB *</label>
						<input type="text" name="nama_DB" id="nama_DB" class="form-control" placeholder="Kode Kebun" maxlength="20" value="{{ old('nama_DB') }}" required>
                        @if ($errors->has('nama_DB'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama_DB') }}</span>
                        @endif
                    </div>
				</div>
            	<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/kebun') }}'"><i class="fa fa-list"></i> Cancel</button>
            	</div>
			</form>
		</div>
	</section>
@endsection

@extends('admin.app')

@section('header-title')
    Edit Modul Per Kebun
@endsection

@section('header-content')
    <style type="text/css">
    	.material-switch > label::before {
    		margin-left: -20px;
    	}
    </style>
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Edit Modul Per Kebun
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/modul-per-kebun') }}">Modul Per Kebun</a></li>
			<li class="active">Edit</li>
		</ol>
	</section>

	<section class="content">
		<div class="box box-primary">
            <form role="form" method="POST" action="{{ url('/admin/modul-per-kebun') }}/edit">
            	{{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
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
							{{--*/ $index = 0; /*--}}
							@foreach ($modul as $row_modul)
								<tr>
	                                <td><b>{{ $row_modul->nama_modul }}</b></td>
	                                @foreach ($kebun as $row_kebun)
	                                	{{--*/ $status = false; /*--}}
	                                	@foreach ($modul_per_kebun as $row)
	                                		@if ($row->nama_modul == $row_modul->nama_modul && $row->kode_kebun == $row_kebun->kode_kebun)
	                                			{{--*/ $status = true; /*--}}
	                                		@endif
	                                	@endforeach

	                                	{{--*/ $index++; /*--}}
	                                	<td class="text-center">
	                                		<div class="material-switch">
					                            <input id="switch{{ $index }}" name="data[{{ $row_kebun->kode_kebun }}][]" value="{{ $row_modul->nama_modul }}" type="checkbox" {{ $status == true ? 'checked' : '' }}/>
					                            <label for="switch{{ $index }}" class="label-success"></label>
					                        </div>
		                            	</td>
			                        @endforeach
	                            </tr>
	                        @endforeach
						</tbody>
					</table>
				</div>
            	<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/modul-per-kebun') }}'"><i class="fa fa-list"></i> Cancel</button>
            	</div>
			</form>
		</div>
	</section>
@endsection

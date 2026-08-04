@extends('dashboard.app')

@section('header-title')
    Harga Kontrak vs Harga Tender
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Harga Kontrak vs Harga Tender per hari 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/harga-kontrak-vs-harga-tender') }}">
                    <div class="form-group">
                        <label for="per_tanggal">Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @for ($i = 0; $i < count($kebun); $i++)
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $kebun[$i]->nama_lengkap }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id="table-data" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No Kontrak</th>
                                        <th>Tanggal</th>
                                        <th>Kebun</th>
                                        <th>Nama Customer</th>
                                        <th>Produk</th>
                                        <th>Quantity (KG)</th>
                                        <th>Harga Kontrak</th>
                                        <th>Harga Tender</th>
                                        <th>Harga Kontrak vs<br>Harga Tender</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($harga_kontrak_vs_harga_tender[$i] as $row)
                                        <tr>
                                            <td>{{ $row->NoKontrak }}</td>
                                            <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $row->TglKontrak)->format('d/m/Y') }}</td>
                                            <td>{{ $row->NamaKebun }}</td>
                                            <td>{{ $row->NamaCustFP }}</td>
                                            <td>{{ $row->Produk }}</td>
                                            <td>{{ number_format($row->Quantity, 2, ',', '.') }}</td>
                                            <td>{{ number_format($row->HargaKontrak, 2, ',', '.') }}</td>
                                            <td>{{ $row->HargaTender == null ? '' : number_format($row->HargaTender, 2, ',', '.') }}</td>
                                            @if ($row->HargaTender == null)
                                                <td></td>
                                            @elseif ($row->HargaKontrak > $row->HargaTender)
                                                <td class="text-bold text-success">▲ {{ number_format($row->HargaKontrak - $row->HargaTender, 2, ',', '.') }}</td>
                                            @elseif ($row->HargaKontrak < $row->HargaTender)
                                                <td class="text-bold text-danger">▼ {{ number_format($row->HargaTender - $row->HargaKontrak, 2, ',', '.') }}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>
@endsection

@section('script-content')
    <script type="text/javascript">
        setValidationDatePicker('per_tanggal');
    </script>
@endsection
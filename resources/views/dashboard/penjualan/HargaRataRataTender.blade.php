@extends('dashboard.app')

@section('header-title')
    Harga Rata Rata Tender [WAITING FOR USER CONFIRMATION]
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Harga Rata Rata Tender [WAITING FOR USER CONFIRMATION]
        <small></small>
    </h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/harga-rata-rata-tender') }}">
                
                <div class="form-group">
                    <label for="tanggal_awal">Dari Tanggal : </label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                               value="{{ Request::get('tanggal_awal') ?: date('Y-m-d', strtotime('-7 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tanggal_akhir">Sampai Tanggal : </label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                               value="{{ Request::get('tanggal_akhir') ?: date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_id">Site : </label>
                    <select class="form-control" id="site_id" name="site_id">
                        <option value="2100">2100 - Padasa</option>
                        <option value="3100">3100 - APMR</option>
                        <option value="5100">5100 - MMMA</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="produk">Produk : </label>
                    <select class="form-control" id="produk" name="produk">
                        <option value="CPO">CPO</option>
                        <option value="PK">PK</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenis_tender">Jenis Tender : </label>
                    <select class="form-control" id="jenis_tender" name="jenis_tender">
                        <option value="AVG">AVG</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    @php
        $headers = [];

        if (!empty($data)) {
            $headers = array_keys((array) $data[0]);
        }

        $dateColumns = ['TANGGAL', 'TGL_DATA_AWAL', 'TGL_DATA_AKHIR'];
    @endphp

    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body table-responsive">

                    <table id="table-data" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                @forelse ($headers as $header)
                                    <th style="font-size:12px; text-align:center;">
                                        {{ strtoupper($header) }}
                                    </th>
                                @empty
                                    <th>Tidak ada data</th>
                                @endforelse
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $row)
                                @php
                                    $rowArray = (array) $row;
                                @endphp

                                <tr>
                                    @foreach ($headers as $index => $header)
                                        @php
                                            $value = $rowArray[$header] ?? null;
                                        @endphp

                                        @if (in_array(strtoupper($header), $dateColumns))
                                            <td style="text-align:center;">
                                                {{ $value ? date('d/m/Y', strtotime($value)) : '' }}
                                            </td>
                                        @elseif (is_numeric($value) || is_null($value))
                                            <td style="text-align:right;">
                                                {{ number_format(is_null($value) ? 0 : $value, 0, ',', '.') }}
                                            </td>
                                        @else
                                            <td>
                                                {{ $value }}
                                            </td>
                                        @endif
                                    @endforeach
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
    makeDataTableResponsive('table-data', 0, 'asc', -1);

    document.getElementById('site_id').value = "{{ Request::get('site_id') ?: '2100' }}";
    document.getElementById('produk').value = "{{ Request::get('produk') ?: 'CPO' }}";
    document.getElementById('jenis_tender').value = "{{ Request::get('jenis_tender') ?: 'AVG' }}";
</script>
@endsection
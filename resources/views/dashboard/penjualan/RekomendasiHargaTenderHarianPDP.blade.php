@extends('dashboard.app')

@section('header-title')
    Rekomendasi Harga Tender Harian PDP [Waiting User Confirmation]
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Rekomendasi Harga Tender Harian PDP [Waiting User Confirmation]
        <small></small>
    </h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/rekomendasi-harga-tender-harian-pdp') }}">
                
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
                    <label for="produk">Produk : </label>
                    <select class="form-control" id="produk" name="produk">
                        <option value="CPO">CPO</option>
                        <option value="PK">PK</option>
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

        $dateColumn = 'Tanggal';

        $chartLabels = [];
        $chartColumns = [];

        foreach ($headers as $header) {
            if ($header !== $dateColumn) {
                $chartColumns[] = $header;
            }
        }

        foreach ($data as $row) {
            $rowArray = (array) $row;

            if (!empty($rowArray[$dateColumn])) {
                $chartLabels[] = date('d-m-Y', strtotime($rowArray[$dateColumn]));
            } else {
                $chartLabels[] = '';
            }
        }
    @endphp

    {{-- CHART --}}
    @if(!empty($data))
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Rekomendasi Harga Tender Harian PDP</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChartPDP" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="row">
        <div class="col-md-12">
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
                                    @foreach ($headers as $header)
                                        @php
                                            $value = $rowArray[$header] ?? null;
                                        @endphp

                                        @if ($header === $dateColumn)
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

    document.getElementById('produk').value = "{{ Request::get('produk') ?: 'CPO' }}";

    @if(!empty($data))
        generateLineChartJS(
            'lineChartPDP',
            {!! json_encode($chartLabels) !!},
            [
                @foreach ($chartColumns as $column)
                    {
                        label: "{{ $column }}",
                        data: [
                            @foreach ($data as $row)
                                @php
                                    $rowArray = (array) $row;
                                    $value = $rowArray[$column] ?? null;
                                @endphp
                                {{ is_numeric($value) ? $value : 0 }},
                            @endforeach
                        ]
                    },
                @endforeach
            ],
            true,
            10000
        );
    @endif
</script>
@endsection
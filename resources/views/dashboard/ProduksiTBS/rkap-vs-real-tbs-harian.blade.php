@extends('dashboard.app')

@section('header-title')
    RKAP VS REAL TBS HARIAN
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        RKAP VS REAL TBS HARIAN
    </h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET"
                  action="{{ url('/dashboard/produksi/rkap-vs-real-tbs-harian') }}">

                {{-- Tahun --}}
                <div class="form-group">
                    <label for="tahun">Tahun : </label>
                    <div class="input-group date input-inline" style="width: 150px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="number" class="form-control" id="tahun" name="tahun"
                               value="{{ request('tahun', $tahun ?? date('Y')) }}">
                    </div>
                </div>

                {{-- Bulan --}}
                <div class="form-group">
                    <label for="bulan">Bulan : </label>
                    <div class="input-group date input-inline" style="width: 140px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="number" class="form-control" id="bulan" name="bulan"
                               min="1" max="12"
                               value="{{ request('bulan', $bulan ?? date('n')) }}">
                    </div>
                </div>

                {{-- Site --}}
                <div class="form-group">
                    <label for="site_id">Site : </label>
                    <select class="form-control" id="site_id" name="site_id">
                        <option value="2200">2200 - TELDA</option>
                        <option value="2300">2300 - KALSA</option>
                        <option value="2400">2400 - KALDA</option>
                        <option value="2500">2500 - KOKAR</option>
                        <option value="3200">3200 - RICKO</option>
                        <option value="5200">5200 - PASER GROUP</option>
                    </select>
                </div>

                {{-- Jenis --}}
                <div class="form-group">
                    <label for="jenis">Jenis : </label>
                    <select class="form-control" id="jenis" name="jenis">
                        <option value="P3" {{ (request('jenis', $jenis ?? '') == 'P3') ? 'selected' : '' }}>PIHAK 3</option>
                        <option value="INTI" {{ (request('jenis', $jenis ?? '') == 'INTI') ? 'selected' : '' }}>KEBUN INTI</option>
                        <option value="MITRA" {{ (request('jenis', $jenis ?? '') == 'MITRA') ? 'selected' : '' }}>MITRA</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="box-body table-responsive">

                        <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px;display:none;" rowspan="2">DETAIL</th>
                                    <th style="font-size: 12px;display:none;" rowspan="2">SITE ID</th>
                                    <th style="font-size: 12px;display:none;" rowspan="2">TAHUN</th>
                                    <th style="font-size: 12px;display:none;" rowspan="2">BULAN</th>
                                    <th style="font-size: 12px;" rowspan="2">TANGGAL</th>
                                    <th style="font-size: 12px;">REALISASI</th>
                                    <th style="font-size: 12px;">RKAP</th>
                                    <th style="font-size: 12px;">%</th>
                                </tr>
                                <tr>
                                    <th style="font-size: 12px;">A</th>
                                    <th style="font-size: 12px;">B</th>
                                    <th style="font-size: 12px;">C= B/A</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    @php
                                        $isTotal = isset($row->DETAIL) && strtoupper($row->DETAIL) === 'T';

                                        $tgl = '';
                                        if (!empty($row->TGLMASUK)) {
                                            try {
                                                $tgl = \Carbon\Carbon::parse($row->TGLMASUK)->format('d/m/Y');
                                            } catch (\Exception $e) {
                                                $tgl = $row->TGLMASUK;
                                            }
                                        }
                                        else {
                                            $tgl = "TOTAL";
                                        }

                                        $realisasi = is_numeric($row->REALISASI ?? null)
                                            ? number_format($row->REALISASI, 0, ',', '.')
                                            : ($row->REALISASI ?? '');
                                        $rkap = is_numeric($row->RKAP ?? null)
                                            ? number_format($row->RKAP, 0, ',', '.')
                                            : ($row->RKAP ?? '');
                                        $ach = is_numeric($row->ACHIEVMENT ?? null)
                                            ? number_format($row->ACHIEVMENT, 0, ',', '.')
                                            : ($row->ACHIEVMENT ?? '');
                                    @endphp

                                    <tr @if($isTotal) class="bg-info" @endif>
                                        <td style="display:none;">
                                            @if($isTotal)
                                                <strong>TOTAL</strong>
                                            @else
                                                {{ $row->DETAIL ?? '' }}
                                            @endif
                                        </td>
                                        <td style="display:none;">{{ $row->SITE_ID ?? '' }}</td>
                                        <td style="display:none;">{{ $row->TAHUN ?? '' }}</td>
                                        <td style="display:none;">{{ $row->BULAN ?? '' }}</td>
                                        <td>{{ $tgl }}</td>
                                        <td>{{ $realisasi }}</td>
                                        <td>{{ $rkap }}</td>
                                        <td>{{ $ach }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="callout callout-info" style="margin-top:15px;">
                <p>
                    Keterangan:
                    REALISASI &amp; RKAP dalam ton.
                    ACHIEVEMENT dalam % (0 = belum ada RKAP / realisasi tidak terdefinisi).
                    Baris <strong>TOTAL</strong> menunjukkan akumulasi bulan berjalan.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script-content')
<script type="text/javascript">
    // DataTable helper (same as your other pages)
    makeDataTableResponsive('table-data', 0, 'asc', -1);

    // Restore dropdowns based on current request / defaults
    document.getElementById('site_id').value = "{{ request('site_id', $siteId ?? '2200') }}";
</script>
@endsection

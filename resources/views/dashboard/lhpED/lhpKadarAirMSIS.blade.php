@extends('dashboard.app')

@section('header-title')
    Kadar Air {{ $jenis === 'MS' ? 'Minyak Sawit (MS)' : 'Inti Sawit (IS)' }} – PMKS
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Kadar Air {{ $jenis === 'MS' ? 'Minyak Sawit (MS)' : 'Inti Sawit (IS)' }} – PMKS
        <small></small>
    </h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/KadarAirMSIS') }}">
                <div class="row">
                    <div class="form-group">
                        <label for="dari_tanggal">Dari Tanggal : </label>
                        <div class="input-group date input-inline" style="width: 175px;">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ old('dari_tanggal', $dari_tanggal) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sampai_tanggal">Sampai Tanggal : </label>
                        <div class="input-group date input-inline" style="width: 175px;">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ old('sampai_tanggal', $sampai_tanggal) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selectkebun">Kebun : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            @foreach ($kebunOptions as $k)
                                <option value="{{ $k }}" {{ $k == $select_kebun ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis">Jenis : </label>
                        <select class="form-control" id="jenis" name="jenis">
                            <option value="MS" {{ $jenis === 'MS' ? 'selected' : '' }}>MINYAK SAWIT (MS)</option>
                            <option value="IS" {{ $jenis === 'IS' ? 'selected' : '' }}>INTI SAWIT (IS)</option>
                        </select>
                    </div>

                    <div class="form-group form-inline">
                        <button type="submit" class="form-control btn btn-primary">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="box-body table-responsive">
                        <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                            <thead>
                                @if ($jenis === 'MS')
                                    <tr>
                                        <th style="font-size: 12px;">TANGGAL</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">KADAR AIR T1 (%)</th>
                                        <th style="font-size: 12px;">VOL. T1 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T2 (%)</th>
                                        <th style="font-size: 12px;">VOL. T2 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T3 (%)</th>
                                        <th style="font-size: 12px;">VOL. T3 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T4 (%)</th>
                                        <th style="font-size: 12px;">VOL. T4 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR TPL1 (%)</th>
                                        <th style="font-size: 12px;">VOL. TPL1 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR TPL2 (%)</th>
                                        <th style="font-size: 12px;">VOL. TPL2 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR PROD (%)</th>
                                        <th style="font-size: 12px;">VOL. PROD (KG)</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th style="font-size: 12px;">TANGGAL</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">KADAR AIR T1 (%)</th>
                                        <th style="font-size: 12px;">VOL. BIN1 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T2 (%)</th>
                                        <th style="font-size: 12px;">VOL. BIN2 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T3 (%)</th>
                                        <th style="font-size: 12px;">VOL. BIN3 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR T4 (%)</th>
                                        <th style="font-size: 12px;">VOL. BIN4 (KG)</th>
                                        <th style="font-size: 12px;">KADAR AIR PRODUKSI (%)</th>
                                        <th style="font-size: 12px;">VOL. PRODUKSI (KG)</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        @php
                                            // Column names are assumed exactly like your samples
                                            $tgl = isset($row->TANGGAL) ? \DateTime::createFromFormat('Y-m-d', substr($row->TANGGAL,0,10)) : null;
                                            $fmt = function($val, $dec = 2) {
                                                if ($val === null) return '<td style="text-align:center;">-</td>';
                                                // Some procs may return 0/NULL; keep consistent Indonesian format
                                                return '<td>'.number_format((float)$val, $dec, ',', '.').'</td>';
                                            };
                                            $fmtInt = function($val) {
                                                if ($val === null) return '<td style="text-align:center;">-</td>';
                                                return '<td>'.number_format((float)$val, 0, ',', '.').'</td>';
                                            };
                                        @endphp

                                        {{-- TANGGAL, KEBUN --}}
                                        <td>{{ $tgl ? $tgl->format('d/m/Y') : (isset($row->TANGGAL) ? $row->TANGGAL : '-') }}</td>
                                        <td>{{ $row->KEBUN ?? '-' }}</td>

                                        @if ($jenis === 'MS')
                                            {!! $fmt($row->KADAR_AIR_T1 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_T1 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T2 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_T2 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T3 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_T3 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T4 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_T4 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_TPL1 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_TPL1 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_TPL2 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_TPL2 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_PRODUKSI ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_PRODUKSI ?? null) !!}
                                        @else
                                            {!! $fmt($row->KADAR_AIR_T1 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_BIN1 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T2 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_BIN2 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T3 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_BIN3 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_T4 ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_BIN4 ?? null) !!}

                                            {!! $fmt($row->KADAR_AIR_PRODUKSI ?? null) !!}
                                            {!! $fmtInt($row->VOLUME_PRODUKSI ?? null) !!}
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- table-responsive --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script-content')
<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
    makeDataTableResponsive('table-data', 0, 'asc', -1);

    // Restore selects (server already set selected, this is optional)
    document.getElementById('selectkebun').value = "{{ $select_kebun }}";
    document.getElementById('jenis').value = "{{ $jenis }}";
</script>
@endsection

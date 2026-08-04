@extends('dashboard.app')

@section('header-title')
    Realisasi Rendemen Minyak Sawit VS Target
@endsection

@section('main-content')

@php
    $selectkebun = Request::get('selectkebun') ?: '2200';
    $selecttype  = Request::get('type') ?: '0';
    $toleransi   = Request::get('toleransi') ?: '0.35';
    $harga       = Request::get('harga') ?: '5800';
    $formulaAktif = isset($formulaBonusDenda->FORMULA)
        ? strtoupper(trim($formulaBonusDenda->FORMULA))
        : '';

    $namaKebunFormula = isset($formulaBonusDenda->KEBUN)
        ? $formulaBonusDenda->KEBUN
        : '';

    $tahunFormula = isset($formulaBonusDenda->TAHUN)
        ? (int)$formulaBonusDenda->TAHUN
        : 0;

    $bulanFormula = isset($formulaBonusDenda->BULAN)
        ? (int)$formulaBonusDenda->BULAN
        : 0;

    $namaBulanIndonesia = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $periodeFormula = isset($namaBulanIndonesia[$bulanFormula])
        ? $namaBulanIndonesia[$bulanFormula] . ' ' . $tahunFormula
        : '';

    /*
        Data TAB 1 untuk Tabulator.
        Total row lama tidak dibuat lagi di HTML, karena total sekarang memakai bottomCalc Tabulator.
    */
    $proporsiData = [];

    foreach ($lhp_ProporsiPerPemasok as $row) {
        $rendProporsi = 0;

        if (isset($row->REND_PROPORSI)) {
            $rendProporsi = (float)$row->REND_PROPORSI;
        } elseif (isset($row->REND_PROPROSI)) {
            $rendProporsi = (float)$row->REND_PROPROSI;
        }

        $tanggalDisplay = '';
        $tanggalSort = '';

        if ($selecttype === '0' && !empty($row->TANGGAL)) {
            $tanggalDisplay = date('d/m/Y', strtotime($row->TANGGAL));
            $tanggalSort = date('Y-m-d', strtotime($row->TANGGAL));
        }

        $nama = isset($row->NAMA) ? $row->NAMA : '';
        $isTotalRow = stripos($nama, 'TOTAL') !== false;

        if (!$isTotalRow) {
            $proporsiData[] = [
                'TANGGAL' => $tanggalDisplay,
                'TANGGAL_SORT' => $tanggalSort,
                'INDEX' => isset($row->INDEX) ? $row->INDEX : '',
                'NAMA' => $nama,
                'TBSTERIMA' => (float)(isset($row->TBSTERIMA) ? $row->TBSTERIMA : 0),
                'TBS_OLAH_PROPORSI' => (float)(isset($row->TBS_OLAH_PROPORSI) ? $row->TBS_OLAH_PROPORSI : 0),
                'TARGET' => (float)(isset($row->TARGET) ? $row->TARGET : 0),
                'CPO_TARGET' => (float)(isset($row->CPO_TARGET) ? $row->CPO_TARGET : 0),
                'CPO_PROPORSI' => (float)(isset($row->CPO_PROPORSI) ? $row->CPO_PROPORSI : 0),
                'REND_PROPORSI' => $rendProporsi,
                'SELISIH' => (float)(isset($row->SELISIH) ? $row->SELISIH : 0),
                'SELISIH_REND' => (float)(isset($row->SELISIH_REND) ? $row->SELISIH_REND : 0),
                'HARGA' => (float)(isset($row->HARGA) ? $row->HARGA : 0)
            ];
        }
    }

    /*
        Data TAB 2 untuk Tabulator.
        Jika data bulanan dari SP sudah membawa row TOTAL, row tersebut tidak dimasukkan,
        supaya tidak double dengan bottomCalc Tabulator.
    */
    $rvstData = [];

    foreach ($lhp_RvsT as $row) {
        $namaGrup = isset($row->NAMA_GRUP) ? $row->NAMA_GRUP : '';
        $isTotalRow = stripos($namaGrup, 'TOTAL') !== false;

        if (!$isTotalRow) {
            $tglDisplay = '';
            $tglSort = '';

            if ($selecttype === '0' && !empty($row->TGL)) {
                $tglDisplay = date('d/m/Y', strtotime($row->TGL));
                $tglSort = date('Y-m-d', strtotime($row->TGL));
            }

            $rvstData[] = [
                'BARIS' => (float)(isset($row->BARIS) ? $row->BARIS : 0),
                'TGL' => $tglDisplay,
                'TGL_SORT' => $tglSort,
                'BULAN' => isset($row->BULAN) ? $row->BULAN : '',
                'NAMA_GRUP' => $namaGrup,
                'REALISASI_TBS_OLAH' => (float)(isset($row->REALISASI_TBS_OLAH) ? $row->REALISASI_TBS_OLAH : 0),
                'PRODUKSI_CPO_TARGET' => (float)(isset($row->PRODUKSI_CPO_TARGET) ? $row->PRODUKSI_CPO_TARGET : 0),
                'RENDEMEN_CPO_TARGET' => (float)(isset($row->RENDEMEN_CPO_TARGET) ? $row->RENDEMEN_CPO_TARGET : 0),
                'PRODUKSI_CPO_REALISASI' => (float)(isset($row->PRODUKSI_CPO_REALISASI) ? $row->PRODUKSI_CPO_REALISASI : 0),
                'RENDEMEN_CPO_REALISASI' => (float)(isset($row->RENDEMEN_CPO_REALISASI) ? $row->RENDEMEN_CPO_REALISASI : 0),
                'SELISIH_CPO' => (float)(isset($row->SELISIH_CPO) ? $row->SELISIH_CPO : 0),
                'SELISIH_RENDEMEN' => (float)(isset($row->SELISIH_RENDEMEN) ? $row->SELISIH_RENDEMEN : 0),
                'TOTAL_KERUGIAN' => (float)(isset($row->TOTAL_KERUGIAN) ? $row->TOTAL_KERUGIAN : 0),
                'TOTAL_SANKSI' => (float)(isset($row->TOTAL_SANKSI) ? $row->TOTAL_SANKSI : 0),
                'RESTAN_TBS_PABRIK' => (float)(isset($row->RESTAN_TBS_PABRIK) ? $row->RESTAN_TBS_PABRIK : 0)
            ];
        }
    }
@endphp

<style>
    .tab-content {
        padding-top: 15px;
    }

    .filter-panel {
        border: 0;
        border-radius: 4px;
        box-shadow: none;
        margin-bottom: 20px;
    }

    .filter-panel .panel-body {
        padding: 16px 18px;
    }

    .filter-panel .form-inline {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px 24px;
    }

    .filter-panel .form-group {
        margin-right: 0;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-panel label {
        margin: 0;
        font-size: 13px;
        white-space: nowrap;
    }

    .filter-panel .form-control,
    .filter-panel .input-group-addon,
    .filter-panel .btn {
        height: 38px;
    }

    .filter-panel .form-control {
        font-size: 13px;
        padding: 7px 12px;
    }

    .filter-panel .input-group-addon {
        min-width: 42px;
        padding: 8px 12px;
    }

    .filter-panel .btn {
        padding: 8px 13px;
    }

    #selectkebun {
        min-width: 140px;
    }

    #type {
        min-width: 110px;
    }

    .box.box-primary,
    .box.box-success {
        border-top-width: 3px;
        box-shadow: none;
    }

    .box .box-body {
        padding: 12px;
    }

    .tabulator-search-area {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .tabulator-search-area label {
        margin: 0;
        font-weight: normal;
    }

    .tabulator-search-area input {
        width: 240px;
        height: 32px;
        border: 1px solid #d2d6de;
        padding: 6px 10px;
        box-shadow: none;
    }

    .tabulator-executive {
        border: 1px solid #d2d6de;
        font-size: 12px;
        background: #fff;
    }

    .tabulator-executive .tabulator-header {
        background: #f4f6f9;
        border-bottom: 1px solid #d2d6de;
        font-weight: 700;
    }

    .tabulator-executive .tabulator-col {
        background: #f4f6f9;
        border-right: 1px solid #d2d6de;
    }

    .tabulator-executive .tabulator-col-title {
        text-align: center;
        white-space: normal;
        line-height: 1.15;
    }

    .tabulator-tableholder {
        border: 1px solid #d2d6de;
        font-size: 13px;
        background-color: #e0e0e0;
    }

    .tabulator-executive .tabulator-cell {
        border-right: 1px solid #e0e0e0;
        padding: 6px 7px;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .tabulator-executive .tabulator-row {
        border-bottom: 1px solid #e0e0e0;
    }

    .tabulator-executive .tabulator-row:hover {
        background: #f5fbff !important;
    }

    .tabulator-executive .tabulator-calcs {
        background: #eef5ff;
        font-weight: 700;
    }

    .tabulator-executive .tabulator-calcs .tabulator-cell {
        background: #eef5ff;
        border-top: 1px solid #b5b5b5;
    }

    .tabulator-executive .tabulator-footer {
        border-top: 1px solid #d2d6de;
        background: #fff;
        padding: 8px;
    }

    .tabulator-executive .tabulator-page {
        border: 1px solid #ddd;
        background: #fff;
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 3px;
    }

    .tabulator-executive .tabulator-page.active {
        background: #3c8dbc;
        color: #fff;
        border-color: #367fa9;
    }

    .table-note {
        font-size: 14px;
        font-weight: bold;
        margin-top: 12px;
        margin-bottom: 0;
    }

    @media (max-width: 991px) {
        .filter-panel .form-inline {
            display: block;
        }

        .filter-panel .form-group {
            display: block;
            margin-bottom: 10px;
        }

        .filter-panel .form-control,
        .filter-panel .input-group {
            width: 100% !important;
        }

        .tabulator-search-area {
            justify-content: flex-start;
        }

        .tabulator-search-area input {
            width: 100%;
        }
    }

    .formula-information {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 9px 12px;
        margin-bottom: 10px;
        border: 1px solid #b8d7e8;
        border-left: 4px solid #3c8dbc;
        border-radius: 3px;
        background: #f4faff;
        color: #34495e;
        font-size: 13px;
    }

    .formula-information .formula-icon {
        color: #3c8dbc;
        font-size: 16px;
    }

    .formula-information .formula-name {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 3px;
        background: #3c8dbc;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .formula-information.formula-empty {
        border-color: #ddd;
        border-left-color: #999;
        background: #fafafa;
        color: #777;
    }

    .formula-information.formula-empty .formula-icon {
        color: #999;
    }

    .nav-tabs .formula-tab-check {
        margin-left: 5px;
        color: #00a65a;
        font-size: 13px;
    }

    .nav-tabs .formula-tab-label {
        display: inline-block;
        margin-left: 5px;
        padding: 1px 5px;
        border-radius: 3px;
        background: #00a65a;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        vertical-align: middle;
    }
</style>

<section class="content-header">
    <h1>
        Realisasi Rendemen Minyak Sawit VS Target
        <small></small>
    </h1>
</section>

<section class="content">

    <div class="panel filter-panel">
        <div class="panel-body">
            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/lhpexecutive/lhpRealisasiVsTarget') }}">

                <div class="form-group">
                    <label for="dari_tanggal">Dari Tanggal :</label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="dari_tanggal"
                               name="dari_tanggal"
                               value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="sampai_tanggal">Sampai Tanggal :</label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="sampai_tanggal"
                               name="sampai_tanggal"
                               value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectkebun">Kebun :</label>
                    <select class="form-control" id="selectkebun" name="selectkebun">
                        @foreach($kebun as $row)
                            <option value="{{ $row->site_id }}" {{ (string)$selectkebun === (string)$row->site_id ? 'selected' : '' }}>
                                {{ $row->site_id }} - {{ $row->kode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">Jenis :</label>
                    <select class="form-control" id="type" name="type">
                        <option value="0" {{ $selecttype === '0' ? 'selected' : '' }}>Harian</option>
                        <option value="1" {{ $selecttype === '1' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="form-group" style="display:none;">
                    <input type="number" step="0.01" value="{{ $toleransi }}" id="toleransi" name="toleransi" />
                </div>

                <div class="form-group" style="display:none;">
                    <input type="number" step="100" value="{{ $harga }}" id="harga" name="harga" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-proporsi" data-toggle="tab">
                        Proporsi Per Pemasok

                        @if($formulaAktif === 'PROPORSI')
                            <i class="fa fa-check-circle formula-tab-check"
                            title="Formula ini sedang digunakan"></i>

                            <span class="formula-tab-label">
                                TERPAKAI
                            </span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="#tab-rvst" data-toggle="tab">
                        Proporsi Sesuai LHP

                        @if($formulaAktif === 'LHP')
                            <i class="fa fa-check-circle formula-tab-check"
                            title="Formula ini sedang digunakan"></i>

                            <span class="formula-tab-label">
                                TERPAKAI
                            </span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                {{-- TAB 1 : Proporsi Per Pemasok --}}
                <div class="tab-pane active" id="tab-proporsi">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <b>Proporsi Per Pemasok {{ $selecttype === '0' ? 'Harian' : 'Bulanan' }}</b>
                                    </h3>
                                </div>

                                <div class="box-body">
                                     @if($formulaAktif !== '')
                                        <div class="formula-information">
                                            <i class="fa fa-check-circle formula-icon"></i>

                                            <span>
                                                Pada kebun
                                                <strong>{{ $namaKebunFormula }}</strong>,
                                                periode
                                                <strong>{{ $periodeFormula }}</strong>,
                                                berlaku perhitungan
                                            </span>

                                            <span class="formula-name">
                                                {{ $formulaAktif }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="formula-information formula-empty">
                                            <i class="fa fa-info-circle formula-icon"></i>

                                            <span>
                                                Formula perhitungan untuk kebun dan periode
                                                yang dipilih belum ditentukan.
                                            </span>
                                        </div>
                                    @endif

                                    <div class="tabulator-search-area">
                                        <label for="search-proporsi">Search:</label>
                                        <input type="text" id="search-proporsi" placeholder="Cari data...">
                                    </div>

                                    <div id="table-proporsi-tabulator" class="tabulator-executive"></div>

                                    <p class="table-note">
                                        Perhitungan menggunakan proporsi produksi per pemasok TBS.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2 : Proporsi Sesuai LHP --}}
                <div class="tab-pane" id="tab-rvst">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <b>Proporsi Sesuai LHP {{ $selecttype === '0' ? 'Harian' : 'Bulanan' }}</b>
                                    </h3>
                                </div>

                                <div class="box-body">
                                     @if($formulaAktif !== '')
                                        <div class="formula-information">
                                            <i class="fa fa-check-circle formula-icon"></i>

                                            <span>
                                                Pada kebun
                                                <strong>{{ $namaKebunFormula }}</strong>,
                                                periode
                                                <strong>{{ $periodeFormula }}</strong>,
                                                berlaku perhitungan
                                            </span>

                                            <span class="formula-name">
                                                {{ $formulaAktif }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="formula-information formula-empty">
                                            <i class="fa fa-info-circle formula-icon"></i>

                                            <span>
                                                Formula perhitungan untuk kebun dan periode
                                                yang dipilih belum ditentukan.
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="tabulator-search-area">
                                        <label for="search-rvst">Search:</label>
                                        <input type="text" id="search-rvst" placeholder="Cari data...">
                                    </div>

                                    <div id="table-rvst-tabulator" class="tabulator-executive"></div>

                                    <p class="table-note">
                                        Harga Patokan : {{ number_format((float)$harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
@endsection

@section('script-content')
<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

    var selectType = "{{ $selecttype }}";
    var proporsiData = @json($proporsiData);
    var rvstData = @json($rvstData);

    function formatNumber(value, decimals) {
        var numberValue = parseFloat(value || 0);

        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(numberValue);
    }

    function numberFormatter(decimals) {
        return function(cell) {
            return formatNumber(cell.getValue(), decimals);
        };
    }

    function sumField(data, fieldName) {
        var total = 0;

        data.forEach(function(row) {
            total += parseFloat(row[fieldName] || 0);
        });

        return total;
    }

    /* TAB 1 formula total */
    function calcPropTargetPercent(values, data) {
        var totalCpoTarget = sumField(data, 'CPO_TARGET');
        var totalTbsOlahProporsi = sumField(data, 'TBS_OLAH_PROPORSI');

        return totalTbsOlahProporsi > 0
            ? (totalCpoTarget / totalTbsOlahProporsi) * 100
            : 0;
    }

    function calcPropRendProporsiPercent(values, data) {
        var totalCpoProporsi = sumField(data, 'CPO_PROPORSI');
        var totalTbsOlahProporsi = sumField(data, 'TBS_OLAH_PROPORSI');

        return totalTbsOlahProporsi > 0
            ? (totalCpoProporsi / totalTbsOlahProporsi) * 100
            : 0;
    }

    function calcPropSelisihRend(values, data) {
        return calcPropRendProporsiPercent(values, data) - calcPropTargetPercent(values, data);
    }

    /* TAB 2 formula total */
    function calcRvstRendemenTarget(values, data) {
        var totalProduksiTarget = sumField(data, 'PRODUKSI_CPO_TARGET');
        var totalRealisasiTbsOlah = sumField(data, 'REALISASI_TBS_OLAH');

        return totalRealisasiTbsOlah > 0
            ? (totalProduksiTarget / totalRealisasiTbsOlah) * 100
            : 0;
    }

    function calcRvstRendemenRealisasi(values, data) {
        var totalProduksiRealisasi = sumField(data, 'PRODUKSI_CPO_REALISASI');
        var totalRealisasiTbsOlah = sumField(data, 'REALISASI_TBS_OLAH');

        return totalRealisasiTbsOlah > 0
            ? (totalProduksiRealisasi / totalRealisasiTbsOlah) * 100
            : 0;
    }

    function calcRvstSelisihRendemen(values, data) {
        return calcRvstRendemenRealisasi(values, data) - calcRvstRendemenTarget(values, data);
    }

    function dateSorterFromField(sortField) {
        return function(a, b, aRow, bRow) {
            var dateA = aRow.getData()[sortField] || '';
            var dateB = bRow.getData()[sortField] || '';

            if (dateA === dateB) {
                return 0;
            }

            return dateA > dateB ? 1 : -1;
        };
    }

    function applyGlobalSearch(table, keyword) {
        if (!keyword) {
            table.clearFilter();
            return;
        }

        keyword = keyword.toString().toLowerCase();

        table.setFilter(function(data) {
            return Object.keys(data).some(function(key) {
                if (key.indexOf('_SORT') >= 0) {
                    return false;
                }

                return String(data[key] || '').toLowerCase().indexOf(keyword) > -1;
            });
        });
    }

    function baseTabulatorConfig(data) {
        return {
            data: data,
            layout: "fitData",
            layoutColumnsOnNewData: true,
            height: "520px",
            movableColumns: true,
            resizableColumns: true,
            resizableColumnFit: false,
            columnCalcs: "bottom",
            pagination: "local",
            paginationSize: 25,
            paginationSizeSelector: [10, 25, 50, 100, true],
            placeholder: "Data tidak ditemukan",
            columnDefaults: {
                resizable: true,
                headerSort: true,
                vertAlign: "middle"
            }
        };
    }

    function getProporsiColumns() {
        var columns = [];

        if (selectType === '0') {
            columns.push({
                title: "TANGGAL",
                field: "TANGGAL",
                minWidth: 95,
                hozAlign: "center",
                sorter: dateSorterFromField('TANGGAL_SORT'),
                bottomCalc: function() { return "TOTAL"; },
                bottomCalcFormatter: function() { return "<b>TOTAL</b>"; }
            });
        }

        columns = columns.concat([
            {
                title: "NO",
                field: "INDEX",
                minWidth: 55,
                hozAlign: "center",
                bottomCalc: selectType === '0' ? function() { return ""; } : function() { return "TOTAL"; },
                bottomCalcFormatter: selectType === '0' ? undefined : function() { return "<b>TOTAL</b>"; }
            },
            {
                title: "NAMA",
                field: "NAMA",
                minWidth: 115,
                hozAlign: "left",
                // headerFilter: "input",
                bottomCalc: function() { return ""; }
            },
            {
                title: "TBS TERIMA<br>[KG]",
                field: "TBSTERIMA",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "TBS OLAH<br>PROPORSI [KG]",
                field: "TBS_OLAH_PROPORSI",
                minWidth: 130,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "REND.MS<br>TARGET<br>(%)",
                field: "TARGET",
                minWidth: 90,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcPropTargetPercent,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "MS<br>TARGET<br>(KG)",
                field: "CPO_TARGET",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "MS PROPORSI<br>REAL.[KG]",
                field: "CPO_PROPORSI",
                minWidth: 125,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "REND MS<br>PROPORSI<br>REAL(%)",
                field: "REND_PROPORSI",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcPropRendProporsiPercent,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "SELISIH<br>MS[KG]",
                field: "SELISIH",
                minWidth: 110,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "SELISIH<br>REND [%]",
                field: "SELISIH_REND",
                minWidth: 105,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcPropSelisihRend,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "BONUS /<br>DENDA [RP]",
                field: "HARGA",
                minWidth: 125,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            }
        ]);

        return columns;
    }

    function getRvstColumns() {
        var firstColumn;

        if (selectType === '0') {
            firstColumn = {
                title: "TGL",
                field: "TGL",
                minWidth: 95,
                hozAlign: "center",
                sorter: dateSorterFromField('TGL_SORT'),
                bottomCalc: function() { return "TOTAL"; },
                bottomCalcFormatter: function() { return "<b>TOTAL</b>"; }
            };
        } else {
            firstColumn = {
                title: "BULAN",
                field: "BULAN",
                minWidth: 80,
                hozAlign: "center",
                bottomCalc: function() { return "TOTAL"; },
                bottomCalcFormatter: function() { return "<b>TOTAL</b>"; }
            };
        }

        return [
            { title: "BARIS", field: "BARIS", visible: false },
            firstColumn,
            {
                title: "NAMA<br>GRUP",
                field: "NAMA_GRUP",
                width: 80,
                minWidth: 60,
                hozAlign: "left",
                // headerFilter: "input",
                bottomCalc: function() { return ""; }
            },
            {
                title: "TBS<br>OLAH<br>(KG)",
                field: "REALISASI_TBS_OLAH",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "MS<br>TARGET<br>(KG)",
                field: "PRODUKSI_CPO_TARGET",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "REND.MS<br>TARGET<br>(%)",
                field: "RENDEMEN_CPO_TARGET",
                minWidth: 130,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcRvstRendemenTarget,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "MS<br>REAL.<br>(KG)",
                field: "PRODUKSI_CPO_REALISASI",
                minWidth: 115,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "REND.MS<br>REAL.<br>(%)",
                field: "RENDEMEN_CPO_REALISASI",
                minWidth: 130,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcRvstRendemenRealisasi,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "SELISIH<br>MS<br>(KG)",
                field: "SELISIH_CPO",
                minWidth: 120,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            {
                title: "SELISIH<br>REND.<br>(%)",
                field: "SELISIH_RENDEMEN",
                minWidth: 130,
                hozAlign: "right",
                formatter: numberFormatter(2),
                bottomCalc: calcRvstSelisihRendemen,
                bottomCalcFormatter: numberFormatter(2)
            },
            {
                title: "BONUS<br>/DENDA<br>(RP.)",
                field: "TOTAL_KERUGIAN",
                minWidth: 130,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            },
            // {
            //     title: "TOTAL<br>SANKSI<br>(RP.)",
            //     field: "TOTAL_SANKSI",
            //     minWidth: 130,
            //     hozAlign: "right",
            //     formatter: numberFormatter(0),
            //     bottomCalc: "sum",
            //     bottomCalcFormatter: numberFormatter(0)
            // },
            {
                title: "RESTAN TBS<br>PABRIK<br>(KG.)",
                field: "RESTAN_TBS_PABRIK",
                minWidth: 145,
                hozAlign: "right",
                formatter: numberFormatter(0),
                bottomCalc: "sum",
                bottomCalcFormatter: numberFormatter(0)
            }
        ];
    }

    $(document).ready(function () {
        $('#type').val("{{ $selecttype }}");
        $('#selectkebun').val("{{ $selectkebun }}");
        $('#toleransi').val("{{ $toleransi }}");
        $('#harga').val("{{ $harga }}");

        var proporsiConfig = baseTabulatorConfig(proporsiData);
        proporsiConfig.columns = getProporsiColumns();
        proporsiConfig.initialSort = selectType === '0'
            ? [{ column: "TANGGAL", dir: "asc" }]
            : [{ column: "INDEX", dir: "asc" }];

        var rvstConfig = baseTabulatorConfig(rvstData);
        rvstConfig.columns = getRvstColumns();
        rvstConfig.initialSort = selectType === '0'
            ? [{ column: "TGL", dir: "asc" }]
            : [{ column: "BULAN", dir: "asc" }];

        var tableProporsi = new Tabulator("#table-proporsi-tabulator", proporsiConfig);
        var tableRvst = new Tabulator("#table-rvst-tabulator", rvstConfig);

        $('#search-proporsi').on('keyup', function () {
            applyGlobalSearch(tableProporsi, $(this).val());
        });

        $('#search-rvst').on('keyup', function () {
            applyGlobalSearch(tableRvst, $(this).val());
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            tableProporsi.redraw(true);
            tableRvst.redraw(true);
        });

        setTimeout(function () {
            tableProporsi.redraw(true);
            tableRvst.redraw(true);
        }, 250);
    });
</script>
@endsection

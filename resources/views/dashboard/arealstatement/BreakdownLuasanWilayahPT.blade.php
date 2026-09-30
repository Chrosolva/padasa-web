@extends('dashboard.app')

@section('header-title')
    Luasan Per PT
@endsection

@section('main-content')

<link
    rel="stylesheet"
    href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css"
>

<style>
    .chart-container {
        position: relative;
        width: 100%;
        height: 260px;
    }

    .compact-box {
        margin-bottom: 10px;
    }

    .compact-box .box-header {
        padding: 8px 10px;
    }

    .compact-box .box-body {
        padding: 10px;
    }

    .tabulator-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .tabulator {
        width: 100%;
        border: 1px solid #d2d6de;
        font-size: 12px;
        background-color: #ffffff;
    }

    .tabulator .tabulator-header {
        background-color: #f4f4f4;
        border-bottom: 2px solid #d2d6de;
        font-weight: 600;
    }

    .tabulator .tabulator-header .tabulator-col {
        background-color: #f4f4f4;
        border-right: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header .tabulator-col-content {
        padding: 6px 5px;
    }

    .tabulator .tabulator-header .tabulator-col-title {
        white-space: normal;
        line-height: 15px;
        text-align: center;
    }

    .tabulator .tabulator-row {
        min-height: 27px;
    }

    .tabulator .tabulator-row .tabulator-cell {
        padding: 4px 6px;
        border-right: 1px solid #eeeeee;
        border-bottom: 1px solid #eeeeee;
    }

    .tabulator .tabulator-row:nth-child(even) {
        background-color: #fafafa;
    }

    .tabulator .tabulator-row:hover {
        background-color: #eef6ff;
    }

    .tabulator .tabulator-calcs-holder {
        background-color: #eaf2ff;
        border-top: 2px solid #3c8dbc;
    }

    .tabulator .tabulator-calcs-holder .tabulator-row,
    .tabulator .tabulator-calcs-holder .tabulator-cell {
        background-color: #eaf2ff !important;
        font-weight: bold;
    }

    .tabulator .tabulator-footer {
        padding: 5px;
        background-color: #ffffff;
    }

    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 8px;
    }

    .table-toolbar-left,
    .table-toolbar-right {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .table-page-size {
        width: 80px !important;
    }

    .table-search {
        width: 230px !important;
    }

    #table-wilayah,
    #table-pt,
    #table-bibit,
    #table-umur {
        width: 100%;
        max-width: 100%;
        height: 280px;
    }

    .table-with-note {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        width: 100%;
    }

    .table-main {
        flex: 1 1 auto;
        min-width: 0;
    }

    .umur-note-box {
        flex: 0 0 300px;
        border: 1px solid #d2d6de;
        background-color: #ffffff;
        padding: 8px;
        border-radius: 3px;
    }

    .umur-note-title {
        font-weight: 600;
        margin-bottom: 7px;
        color: #444444;
    }

    .umur-note-table {
        margin-bottom: 0;
        font-size: 11px;
    }

    .umur-note-table th,
    .umur-note-table td {
        padding: 5px 6px !important;
        vertical-align: middle !important;
        white-space: nowrap;
    }

    @media (max-width: 991px) {
        .table-with-note {
            flex-direction: column;
        }

        .umur-note-box {
            width: 100%;
            flex: none;
        }
    }

    @media (max-width: 767px) {
        .table-toolbar {
            display: block;
        }

        .table-toolbar-right {
            margin-top: 8px;
        }

        .table-search {
            width: 100% !important;
        }

        .chart-container {
            height: 300px;
        }

        #table-wilayah,
        #table-pt,
        #table-bibit,
        #table-umur {
            height: 300px;
        }
    }
</style>

<section class="content-header">
    <h1>
        Luasan Per PT
        <small>Areal Statement</small>
    </h1>
</section>

<section class="content">

    {{-- FILTER --}}
    <div class="panel">
        <div class="panel-body">
            <form
                method="GET"
                action="{{ url('/dashboard/arealstatement/breakdown-luasan-wilayah-pt') }}"
                class="form-inline"
            >
                <div class="form-group">
                    <label for="tahun">Tahun :</label>

                    <div
                        class="input-group date input-inline"
                        style="width: 175px;"
                    >
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input
                            type="number"
                            class="form-control"
                            id="tahun"
                            name="tahun"
                            value="{{ Request::get('tahun') ?: ($tahun ?? date('Y', strtotime('first day of last month'))) }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan :</label>

                    <div
                        class="input-group date input-inline"
                        style="width: 175px;"
                    >
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input
                            type="number"
                            class="form-control"
                            id="bulan"
                            name="bulan"
                            min="1"
                            max="12"
                            value="{{ Request::get('bulan') ?: ($bulan ?? date('m', strtotime('first day of last month'))) }}"
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa fa-filter"></i> Filter
                </button>
            </form>
        </div>
    </div>

    {{-- TABS --}}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">

            <li class="active">
                <a
                    href="#tab-wilayah"
                    data-toggle="tab"
                >
                    <i class="fa fa-map"></i>
                    Wilayah
                </a>
            </li>

            <li>
                <a
                    href="#tab-pt"
                    data-toggle="tab"
                >
                    <i class="fa fa-building"></i>
                    PT
                </a>
            </li>

            <li>
                <a
                    href="#tab-bibit"
                    data-toggle="tab"
                >
                    <i class="fa fa-leaf"></i>
                    Bibit
                </a>
            </li>

            <li>
                <a
                    href="#tab-topografi"
                    data-toggle="tab"
                >
                    <i class="fa fa-chart-area"></i>
                    Topografi
                </a>
            </li>

            <li>
                <a
                    href="#tab-umur"
                    data-toggle="tab"
                >
                    <i class="fa fa-clock"></i>
                    Grup Umur
                </a>
            </li>

        </ul>

        <div class="tab-content">

            {{-- ========================================================= --}}
            {{-- PER WILAYAH --}}
            {{-- ========================================================= --}}
            <div
                class="tab-pane active"
                id="tab-wilayah"
            >
                <div class="box box-primary compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Komposisi Luasan HA Per Wilayah
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartWilayah"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Luasan Per Wilayah
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-toolbar">
                            <div class="table-toolbar-left">
                                <label for="page-size-wilayah">
                                    Tampilkan:
                                </label>

                                <select
                                    id="page-size-wilayah"
                                    class="form-control input-sm table-page-size"
                                >
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <span>baris</span>
                            </div>

                            <div class="table-toolbar-right">
                                <label for="search-wilayah">
                                    Search:
                                </label>

                                <input
                                    type="text"
                                    id="search-wilayah"
                                    class="form-control input-sm table-search"
                                    placeholder="Cari data wilayah..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="tabulator-wrapper">
                            <div id="table-wilayah"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- PER PT --}}
            {{-- ========================================================= --}}
            <div
                class="tab-pane"
                id="tab-pt"
            >
                <div class="box box-primary compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Komposisi Luasan HA Per PT
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartPT"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-warning compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Luasan Per PT
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-toolbar">
                            <div class="table-toolbar-left">
                                <label for="page-size-pt">
                                    Tampilkan:
                                </label>

                                <select
                                    id="page-size-pt"
                                    class="form-control input-sm table-page-size"
                                >
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <span>baris</span>
                            </div>

                            <div class="table-toolbar-right">
                                <label for="search-pt">
                                    Search:
                                </label>

                                <input
                                    type="text"
                                    id="search-pt"
                                    class="form-control input-sm table-search"
                                    placeholder="Cari data PT..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="tabulator-wrapper">
                            <div id="table-pt"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- PER BIBIT --}}
            {{-- ========================================================= --}}
            <div
                class="tab-pane"
                id="tab-bibit"
            >
                <div class="box box-primary compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Distribusi Luasan Berdasarkan Bibit
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartBibit"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Luasan Per Bibit
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-toolbar">
                            <div class="table-toolbar-left">
                                <label for="page-size-bibit">
                                    Tampilkan:
                                </label>

                                <select
                                    id="page-size-bibit"
                                    class="form-control input-sm table-page-size"
                                >
                                    <option value="10">
                                        10
                                    </option>

                                    <option
                                        value="25"
                                        selected
                                    >
                                        25
                                    </option>

                                    <option value="50">
                                        50
                                    </option>

                                    <option value="100">
                                        100
                                    </option>
                                </select>

                                <span>baris</span>
                            </div>

                            <div class="table-toolbar-right">
                                <label for="search-bibit">
                                    Search:
                                </label>

                                <input
                                    type="text"
                                    id="search-bibit"
                                    class="form-control input-sm table-search"
                                    placeholder="Cari kebun atau bibit..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="tabulator-wrapper">
                            <div id="table-bibit"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- PER UMUR --}}
            {{-- ========================================================= --}}
            <div
                class="tab-pane"
                id="tab-umur"
            >
                <div class="box box-primary compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Distribusi Luasan Berdasarkan Kelompok Umur Tanaman
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartUmur"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-info compact-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Luasan Per Umur Tanaman
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-toolbar">
                            <div class="table-toolbar-left">
                                <label for="page-size-umur">
                                    Tampilkan:
                                </label>

                                <select
                                    id="page-size-umur"
                                    class="form-control input-sm table-page-size"
                                >
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <span>baris</span>
                            </div>

                            <div class="table-toolbar-right">
                                <label for="search-umur">
                                    Search:
                                </label>

                                <input
                                    type="text"
                                    id="search-umur"
                                    class="form-control input-sm table-search"
                                    placeholder="Cari kebun atau nilai..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="table-with-note">
                            <div class="table-main">
                                <div class="tabulator-wrapper">
                                    <div id="table-umur"></div>
                                </div>
                            </div>

                            <div class="umur-note-box">
                                <div class="umur-note-title">
                                    Keterangan Kelompok Umur
                                </div>

                                <table
                                    class="table table-bordered table-condensed umur-note-table"
                                >
                                    <thead>
                                        <tr>
                                            <th>Kelompok Umur</th>
                                            <th>Kelompok</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>0 - 3 tahun</td>
                                            <td>TBM</td>
                                            <td>TBM</td>
                                        </tr>

                                        <tr>
                                            <td>4 - 7 tahun</td>
                                            <td>TM</td>
                                            <td>Muda</td>
                                        </tr>

                                        <tr>
                                            <td>8 - 13 tahun</td>
                                            <td>TM</td>
                                            <td>Remaja</td>
                                        </tr>

                                        <tr>
                                            <td>14 - 20 tahun</td>
                                            <td>TM</td>
                                            <td>Dewasa</td>
                                        </tr>

                                        <tr>
                                            <td>&gt; 20 tahun</td>
                                            <td>TM</td>
                                            <td>Tua</td>
                                        </tr>

                                        <tr>
                                            <td>&gt; 25 tahun</td>
                                            <td>Replanting</td>
                                            <td>Replanting</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- PER TOPOGRAFI --}}
            {{-- ========================================================= --}}

            <div
                class="tab-pane"
                id="tab-topografi"
            >
                <div class="box box-primary compact-box">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Distribusi Luasan Berdasarkan Topografi
                        </h3>
                    </div>

                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartTopografi"></canvas>
                        </div>
                    </div>

                </div>

                <div class="box box-success compact-box">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Luasan Per Topografi
                        </h3>
                    </div>

                    <div class="box-body">

                        <div class="table-toolbar">

                            <div class="table-toolbar-left">
                                <label for="page-size-topografi">
                                    Tampilkan:
                                </label>

                                <select
                                    id="page-size-topografi"
                                    class="form-control input-sm table-page-size"
                                >
                                    <option value="10">
                                        10
                                    </option>

                                    <option
                                        value="25"
                                        selected
                                    >
                                        25
                                    </option>

                                    <option value="50">
                                        50
                                    </option>

                                    <option value="100">
                                        100
                                    </option>
                                </select>

                                <span>baris</span>
                            </div>

                            <div class="table-toolbar-right">

                                <label for="search-topografi">
                                    Search:
                                </label>

                                <input
                                    type="text"
                                    id="search-topografi"
                                    class="form-control input-sm table-search"
                                    placeholder="Cari PT atau topografi..."
                                    autocomplete="off"
                                >

                            </div>

                        </div>

                        <div class="tabulator-wrapper">
                            <div id="table-topografi"></div>
                        </div>

                    </div>
                </div>
            </div>


        </div>

        <p>
            <strong>HA</strong> = Hektare
        </p>
    </div>

</section>
@endsection

@section('script-content')

<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    function toNumber(value) {
        var number = parseFloat(value);

        return isNaN(number)
            ? 0
            : number;
    }

    function formatNumberIndonesia(value, decimalPlaces) {
        return toNumber(value).toLocaleString('id-ID', {
            minimumFractionDigits: decimalPlaces,
            maximumFractionDigits: decimalPlaces
        });
    }

    function numberFormatter(cell) {
        return formatNumberIndonesia(
            cell.getValue(),
            2
        );
    }

    function bottomNumberFormatter(cell) {
        return formatNumberIndonesia(
            cell.getValue(),
            2
        );
    }

    function abbreviatePTName(name) {
        var normalizedName = String(name || '')
            .trim()
            .toUpperCase();

        var ptAbbreviations = {
            'PT. PADASA ENAM UTAMA': 'PEU',
            'PT PADASA ENAM UTAMA': 'PEU',
            'PADASA ENAM UTAMA': 'PEU',

            'PT. ALAM PERMAI MAKMUR RAYA': 'APMR',
            'PT ALAM PERMAI MAKMUR RAYA': 'APMR',
            'ALAM PERMAI MAKMUR RAYA': 'APMR',

            'PT. BUMI MULIA MAKMUR LESTARI': 'BMML',
            'PT BUMI MULIA MAKMUR LESTARI': 'BMML',
            'BUMI MULIA MAKMUR LESTARI': 'BMML',

            'PT. MULTI MAKMUR MITRA ALAM': 'MMMA',
            'PT MULTI MAKMUR MITRA ALAM': 'MMMA',
            'MULTI MAKMUR MITRA ALAM': 'MMMA',

            'PT. SINAR ALAM NIAGA RAYA': 'SANR',
            'PT SINAR ALAM NIAGA RAYA': 'SANR',
            'SINAR ALAM NIAGA RAYA': 'SANR'
        };

        return ptAbbreviations[normalizedName]
            || name
            || '';
    }


    /*
    |--------------------------------------------------------------------------
    | Raw Data
    |--------------------------------------------------------------------------
    */

    var rawWilayahData = @json($wilayah ?? []);
    var rawPTData = @json($pt ?? []);
    var rawUmurData = @json($dataUmur ?? []);
    var rawBibitData =
        @json($dataBibit ?? []);

    var rawBibitColumns =
        @json($columnsBibit ?? []);

    var rawTopografiData =
        @json($dataTopografi ?? []);

    var rawTopografiColumns =
        @json($columnsTopografi ?? []);


    /*
    |--------------------------------------------------------------------------
    | Data Wilayah
    |--------------------------------------------------------------------------
    */

    var wilayahTableData = [];

    rawWilayahData.forEach(function (row) {
        var isTotal =
            row.IS_TOTAL === true
            || row.IS_TOTAL === 1
            || row.IS_TOTAL === '1';

        if (isTotal) {
            return;
        }

        wilayahTableData.push({
            NO:
                row.NoUrut !== undefined
                    ? row.NoUrut
                    : (
                        row.NOURUT !== undefined
                            ? row.NOURUT
                            : 998
                    ),

            WILAYAH: row.WILAYAH || '',

            HA_TM: toNumber(row.HA_TM),
            HA_TBM: toNumber(row.HA_TBM),
            HA_TB: toNumber(row.HA_TB),
            HA_LAIN: toNumber(row.HA_LAIN),
            TOTAL_HA: toNumber(row.TOTAL_HA)
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Data PT
    |--------------------------------------------------------------------------
    */

    var ptTableData = [];

    rawPTData.forEach(function (row) {
        var isTotal =
            row.IS_TOTAL === true
            || row.IS_TOTAL === 1
            || row.IS_TOTAL === '1';

        if (isTotal) {
            return;
        }

        ptTableData.push({
            NO:
                row.NOURUT !== undefined
                    ? row.NOURUT
                    : (
                        row.NoUrut !== undefined
                            ? row.NoUrut
                            : 998
                    ),

            NAMA: abbreviatePTName(row.NAMA),

            HA_TM: toNumber(row.HA_TM),
            HA_TBM: toNumber(row.HA_TBM),
            HA_TB: toNumber(row.HA_TB),
            HA_LAIN: toNumber(row.HA_LAIN),
            TOTAL_HA: toNumber(row.TOTAL_HA)
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Data Umur
    |--------------------------------------------------------------------------
    */

    var umurTableData = rawUmurData.map(function (row, index) {
        var tbm = toNumber(row.TBM);
        var muda = toNumber(row.MUDA);
        var remaja = toNumber(row.REMAJA);
        var dewasa = toNumber(row.DEWASA);
        var tua = toNumber(row.TUA);
        var replanting = toNumber(row.REPLANTING);

        return {
            NO: row.NOURUT || (index + 1),

            PT: String(row.PT || '')
                .trim()
                .toUpperCase(),

            TBM: tbm,
            MUDA: muda,
            REMAJA: remaja,
            DEWASA: dewasa,
            TUA: tua,
            REPLANTING: replanting,

            TOTAL_HA:
                tbm
                + muda
                + remaja
                + dewasa
                + tua
                + replanting
        };
    });

    /*
    |--------------------------------------------------------------------------
    | Data Bibit
    |--------------------------------------------------------------------------
    */

    var bibitTableData =
        rawBibitData.map(
            function (row, index) {
                var result = {
                    NO:
                        row.NO
                        || (index + 1),

                    PT:
                        String(
                            row.PT || ''
                        )
                        .trim()
                        .toUpperCase()
                };

                Object.keys(row)
                    .forEach(
                        function (columnName) {
                            if (
                                columnName
                                    .toUpperCase()
                                    .indexOf(
                                        'HA '
                                    ) === 0
                            ) {
                                result[columnName] =
                                    toNumber(
                                        row[columnName]
                                    );
                            }
                        }
                    );

                return result;
            }
        );
    
    function getBibitHaColumns() {
        if (
            bibitTableData.length === 0
        ) {
            return [];
        }

        return Object.keys(
            bibitTableData[0]
        ).filter(
            function (columnName) {
                return (
                    columnName
                        .toUpperCase()
                        .indexOf('HA ')
                    === 0
                );
            }
        );
    }

    function getBibitLabel(columnName) {
        return String(columnName)
            .replace(/^HA\s+/i, '')
            .trim();
    }

    var topografiTableData =
        rawTopografiData.map(
            function (row, index) {

                var result = {
                    NO:
                        row.NO
                        || (index + 1),

                    PT:
                        String(
                            row.PT || ''
                        )
                        .trim()
                        .toUpperCase()
                };

                Object.keys(row)
                    .forEach(
                        function (columnName) {

                            if (
                                columnName
                                    .toUpperCase()
                                    .indexOf('HA ')
                                === 0
                            ) {
                                result[columnName] =
                                    toNumber(
                                        row[columnName]
                                    );
                            }
                        }
                    );

                return result;
            }
        );

    function getTopografiHaColumns() {
        if (
            topografiTableData.length === 0
        ) {
            return [];
        }

        return Object.keys(
            topografiTableData[0]
        ).filter(
            function (columnName) {
                return (
                    columnName
                        .toUpperCase()
                        .indexOf('HA ')
                    === 0
                );
            }
        );
    }


    function getTopografiLabel(columnName) {
        return String(columnName)
            .replace(/^HA\s+/i, '')
            .trim();
    }

    function createTopografiColumns() {
        var columns = [
            {
                title: 'NO',
                field: 'NO',

                width: 55,

                sorter: 'number',

                hozAlign: 'center',

                bottomCalc:
                    function () {
                        return '~';
                    }
            },

            {
                title: 'PT',
                field: 'PT',

                minWidth: 120,

                sorter: 'string',

                bottomCalc:
                    function () {
                        return 'TOTAL';
                    }
            }
        ];

        getTopografiHaColumns()
            .forEach(
                function (columnName) {

                    columns.push({
                        title:
                            getTopografiLabel(
                                columnName
                            )
                            + '<br>[HA]',

                        field:
                            columnName,

                        minWidth:
                            145,

                        sorter:
                            'number',

                        hozAlign:
                            'right',

                        formatter:
                            numberFormatter,

                        bottomCalc:
                            'sum',

                        bottomCalcFormatter:
                            bottomNumberFormatter
                    });
                }
            );

        return columns;
    }

    /*
    |--------------------------------------------------------------------------
    | Kolom Tabulator Wilayah / PT
    |--------------------------------------------------------------------------
    */

    function createStandardColumns(nameTitle, nameField) {
        return [
            {
                title: 'NO',
                field: 'NO',
                width: 55,
                minWidth: 50,
                sorter: 'number',
                hozAlign: 'center',
                bottomCalc: function () {
                    return '~';
                }
            },
            {
                title: nameTitle,
                field: nameField,
                minWidth: 150,
                sorter: 'string',
                bottomCalc: function () {
                    return 'TOTAL';
                }
            },
            {
                title: 'TM<br>[HA]',
                field: 'HA_TM',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TBM<br>[HA]',
                field: 'HA_TBM',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TB<br>[HA]',
                field: 'HA_TB',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'LAIN<br>[HA]',
                field: 'HA_LAIN',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TOTAL<br>[HA]',
                field: 'TOTAL_HA',
                minWidth: 105,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            }
        ];
    }

    function createBibitColumns() {
        var columns = [
            {
                title: 'NO',
                field: 'NO',
                width: 55,
                minWidth: 50,
                sorter: 'number',
                hozAlign: 'center',

                bottomCalc:
                    function () {
                        return '~';
                    }
            },

            {
                title: 'PT',
                field: 'PT',
                minWidth: 130,
                sorter: 'string',

                bottomCalc:
                    function () {
                        return 'TOTAL';
                    }
            }
        ];

        getBibitHaColumns()
            .forEach(
                function (columnName) {
                    columns.push({
                        title:
                            getBibitLabel(
                                columnName
                            )
                            + '<br>[HA]',

                        field:
                            columnName,

                        minWidth:
                            110,

                        sorter:
                            'number',

                        hozAlign:
                            'right',

                        formatter:
                            numberFormatter,

                        bottomCalc:
                            'sum',

                        bottomCalcFormatter:
                            bottomNumberFormatter
                    });
                }
            );

        return columns;
    }


    /*
    |--------------------------------------------------------------------------
    | Create Tabulator
    |--------------------------------------------------------------------------
    */

    function createTable(
        elementSelector,
        rows,
        columns
    ) {
        return new Tabulator(elementSelector, {
            data: rows,

            height: '280px',
            layout: 'fitData',

            pagination: 'local',
            paginationSize: 25,
            paginationSizeSelector: false,
            paginationCounter: 'rows',

            movableColumns: true,
            resizableColumns: true,

            placeholder: 'Data tidak tersedia',

            initialSort: [
                {
                    column: 'NO',
                    dir: 'asc'
                }
            ],

            columnDefaults: {
                headerHozAlign: 'center',
                vertAlign: 'middle',
                headerSort: true,
                resizable: true
            },

            columns: columns
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Initialize Tables
    |--------------------------------------------------------------------------
    */

    var tableWilayah = createTable(
        '#table-wilayah',
        wilayahTableData,
        createStandardColumns(
            'WILAYAH',
            'WILAYAH'
        )
    );

    var tablePT = createTable(
        '#table-pt',
        ptTableData,
        createStandardColumns(
            'PT',
            'NAMA'
        )
    );

    var tableUmur = createTable(
        '#table-umur',
        umurTableData,
        [
            {
                title: 'NO',
                field: 'NO',
                width: 55,
                minWidth: 50,
                sorter: 'number',
                hozAlign: 'center',
                bottomCalc: function () {
                    return '~';
                }
            },
            {
                title: 'PT',
                field: 'PT',
                width: 130,
                minWidth: 120,
                sorter: 'string',
                bottomCalc: function () {
                    return 'TOTAL';
                }
            },
            {
                title: 'TBM<br>[HA]',
                field: 'TBM',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'MUDA<br>[HA]',
                field: 'MUDA',
                minWidth: 90,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'REMAJA<br>[HA]',
                field: 'REMAJA',
                minWidth: 95,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'DEWASA<br>[HA]',
                field: 'DEWASA',
                minWidth: 95,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TUA<br>[HA]',
                field: 'TUA',
                minWidth: 85,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'REPLANTING<br>[HA]',
                field: 'REPLANTING',
                minWidth: 110,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TOTAL<br>[HA]',
                field: 'TOTAL_HA',
                minWidth: 105,
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            }
        ]
    );

    var tableBibit =
        createTable(
            '#table-bibit',
            bibitTableData,
            createBibitColumns()
        );
    
    var tableTopografi =
        createTable(
            '#table-topografi',
            topografiTableData,
            createTopografiColumns()
        );

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    function bindSearch(
        inputSelector,
        table,
        searchableFields
    ) {
        $(inputSelector).on('keyup change', function () {
            var keyword = String($(this).val() || '')
                .trim()
                .toLowerCase();

            if (keyword === '') {
                table.clearFilter();
                return;
            }

            table.setFilter(function (rowData) {
                return searchableFields.some(function (field) {
                    var value = rowData[field];

                    if (
                        value === null
                        || value === undefined
                    ) {
                        return false;
                    }

                    var originalValue =
                        String(value).toLowerCase();

                    var formattedValue =
                        typeof value === 'number'
                            ? formatNumberIndonesia(
                                value,
                                2
                            ).toLowerCase()
                            : originalValue;

                    return (
                        originalValue.indexOf(keyword) !== -1
                        || formattedValue.indexOf(keyword) !== -1
                    );
                });
            });
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Page Size
    |--------------------------------------------------------------------------
    */

    function bindPageSize(
        selectSelector,
        table
    ) {
        $(selectSelector).on('change', function () {
            var pageSize =
                parseInt(
                    $(this).val(),
                    10
                );

            if (isNaN(pageSize)) {
                pageSize = 25;
            }

            table.setPageSize(pageSize);
            table.setPage(1);
        });
    }


    bindSearch(
        '#search-wilayah',
        tableWilayah,
        [
            'NO',
            'WILAYAH',
            'HA_TM',
            'HA_TBM',
            'HA_TB',
            'HA_LAIN',
            'TOTAL_HA'
        ]
    );

    bindSearch(
        '#search-pt',
        tablePT,
        [
            'NO',
            'NAMA',
            'HA_TM',
            'HA_TBM',
            'HA_TB',
            'HA_LAIN',
            'TOTAL_HA'
        ]
    );

    bindSearch(
        '#search-umur',
        tableUmur,
        [
            'NO',
            'PT',
            'TBM',
            'MUDA',
            'REMAJA',
            'DEWASA',
            'TUA',
            'REPLANTING',
            'TOTAL_HA'
        ]
    );

    bindSearch(
        '#search-bibit',
        tableBibit,
        [
            'NO',
            'PT'
        ].concat(
            getBibitHaColumns()
        )
    );

    bindSearch(
        '#search-topografi',
        tableTopografi,
        [
            'NO',
            'PT'
        ].concat(
            getTopografiHaColumns()
        )
    );

    bindPageSize(
        '#page-size-wilayah',
        tableWilayah
    );

    bindPageSize(
        '#page-size-pt',
        tablePT
    );

    bindPageSize(
        '#page-size-umur',
        tableUmur
    );

    bindPageSize(
        '#page-size-bibit',
        tableBibit
    );

    bindPageSize(
    '#page-size-topografi',
    tableTopografi
    );


    /*
    |--------------------------------------------------------------------------
    | Chart Colors
    |--------------------------------------------------------------------------
    */

    var STANDARD_CHART_COLORS = {
        TM: '#1565C0',
        TBM: '#00897B',
        TB: '#FB8C00',
        LAIN: '#E53935'
    };

    var UMUR_CHART_COLORS = {
        TBM: '#1565C0',
        MUDA: '#E53935',
        REMAJA: '#00897B',
        DEWASA: '#FB8C00',
        TUA: '#6A1B9A',
        REPLANTING: '#7CB342'
    };

    var BIBIT_CHART_COLORS = [
        '#1565C0',
        '#00897B',
        '#FB8C00',
        '#E53935',
        '#6A1B9A',
        '#7CB342',
        '#00ACC1',
        '#8D6E63',
        '#3949AB',
        '#F4511E'
    ];

    function buildBibitDatasets() {
        return getBibitHaColumns()
            .map(
                function (
                    columnName,
                    index
                ) {
                    return {
                        label:
                            getBibitLabel(
                                columnName
                            ),

                        data:
                            bibitTableData.map(
                                function (row) {
                                    return toNumber(
                                        row[columnName]
                                    );
                                }
                            ),

                        backgroundColor:
                            BIBIT_CHART_COLORS[
                                index
                                % BIBIT_CHART_COLORS.length
                            ],

                        borderColor:
                            BIBIT_CHART_COLORS[
                                index
                                % BIBIT_CHART_COLORS.length
                            ],

                        borderWidth:
                            1
                    };
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Horizontal Stacked Chart
    |--------------------------------------------------------------------------
    */

    function createHorizontalStackedChart(
        canvasId,
        labels,
        datasets
    ) {
        var canvas =
            document.getElementById(canvasId);

        if (!canvas) {
            return null;
        }

        return new Chart(
            canvas.getContext('2d'),
            {
                type: 'horizontalBar',

                data: {
                    labels: labels,
                    datasets: datasets
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    layout: {
                        padding: {
                            right: 45
                        }
                    },

                    legend: {
                        display: true,
                        position: 'top',

                        labels: {
                            boxWidth: 11,
                            padding: 10,
                            fontSize: 10
                        }
                    },

                    scales: {
                        xAxes: [
                            {
                                stacked: true,

                                ticks: {
                                    beginAtZero: true,

                                    callback: function (value) {
                                        return formatNumberIndonesia(
                                            value,
                                            0
                                        );
                                    }
                                },

                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total Hektare'
                                }
                            }
                        ],

                        yAxes: [
                            {
                                stacked: true,

                                gridLines: {
                                    display: false
                                },

                                ticks: {
                                    autoSkip: false,
                                    fontSize: 10
                                }
                            }
                        ]
                    },

                    tooltips: {
                        mode: 'index',
                        intersect: true,

                        callbacks: {
                            title: function (
                                tooltipItems,
                                chartData
                            ) {
                                if (
                                    !tooltipItems
                                    || tooltipItems.length === 0
                                ) {
                                    return '';
                                }

                                return chartData.labels[
                                    tooltipItems[0].index
                                ] || '';
                            },

                            label: function (
                                tooltipItem,
                                chartData
                            ) {
                                var dataset =
                                    chartData.datasets[
                                        tooltipItem.datasetIndex
                                    ];

                                var value =
                                    toNumber(
                                        dataset.data[
                                            tooltipItem.index
                                        ]
                                    );

                                if (value === 0) {
                                    return null;
                                }

                                return (
                                    dataset.label
                                    + ': '
                                    + formatNumberIndonesia(
                                        value,
                                        2
                                    )
                                    + ' HA'
                                );
                            },

                            footer: function (
                                tooltipItems,
                                chartData
                            ) {
                                if (
                                    !tooltipItems
                                    || tooltipItems.length === 0
                                ) {
                                    return '';
                                }

                                var rowIndex =
                                    tooltipItems[0].index;

                                var total = 0;

                                chartData.datasets.forEach(
                                    function (dataset) {
                                        total += toNumber(
                                            dataset.data[
                                                rowIndex
                                            ]
                                        );
                                    }
                                );

                                return (
                                    'TOTAL: '
                                    + formatNumberIndonesia(
                                        total,
                                        2
                                    )
                                    + ' HA'
                                );
                            }
                        },

                        footerFontStyle: 'bold'
                    },

                    hover: {
                        mode: 'nearest',
                        intersect: true
                    }
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Chart Wilayah
    |--------------------------------------------------------------------------
    */

    var chartWilayah =
        createHorizontalStackedChart(
            'chartWilayah',

            wilayahTableData.map(function (row) {
                return row.WILAYAH;
            }),

            [
                {
                    label: 'TM',

                    data: wilayahTableData.map(
                        function (row) {
                            return row.HA_TM;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TM,

                    borderColor:
                        STANDARD_CHART_COLORS.TM,

                    borderWidth: 1
                },

                {
                    label: 'TBM',

                    data: wilayahTableData.map(
                        function (row) {
                            return row.HA_TBM;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TBM,

                    borderColor:
                        STANDARD_CHART_COLORS.TBM,

                    borderWidth: 1
                },

                {
                    label: 'TB',

                    data: wilayahTableData.map(
                        function (row) {
                            return row.HA_TB;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TB,

                    borderColor:
                        STANDARD_CHART_COLORS.TB,

                    borderWidth: 1
                },

                {
                    label: 'LAIN',

                    data: wilayahTableData.map(
                        function (row) {
                            return row.HA_LAIN;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.LAIN,

                    borderColor:
                        STANDARD_CHART_COLORS.LAIN,

                    borderWidth: 1
                }
            ]
        );


    /*
    |--------------------------------------------------------------------------
    | Chart PT
    |--------------------------------------------------------------------------
    */

    var chartPT =
        createHorizontalStackedChart(
            'chartPT',

            ptTableData.map(function (row) {
                return row.NAMA;
            }),

            [
                {
                    label: 'TM',

                    data: ptTableData.map(
                        function (row) {
                            return row.HA_TM;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TM,

                    borderColor:
                        STANDARD_CHART_COLORS.TM,

                    borderWidth: 1
                },

                {
                    label: 'TBM',

                    data: ptTableData.map(
                        function (row) {
                            return row.HA_TBM;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TBM,

                    borderColor:
                        STANDARD_CHART_COLORS.TBM,

                    borderWidth: 1
                },

                {
                    label: 'TB',

                    data: ptTableData.map(
                        function (row) {
                            return row.HA_TB;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.TB,

                    borderColor:
                        STANDARD_CHART_COLORS.TB,

                    borderWidth: 1
                },

                {
                    label: 'LAIN',

                    data: ptTableData.map(
                        function (row) {
                            return row.HA_LAIN;
                        }
                    ),

                    backgroundColor:
                        STANDARD_CHART_COLORS.LAIN,

                    borderColor:
                        STANDARD_CHART_COLORS.LAIN,

                    borderWidth: 1
                }
            ]
        );


    /*
    |--------------------------------------------------------------------------
    | Chart Umur
    |--------------------------------------------------------------------------
    */

    var chartUmur =
        createHorizontalStackedChart(
            'chartUmur',

            umurTableData.map(function (row) {
                return row.PT;
            }),

            [
                {
                    label: 'TBM',

                    data: umurTableData.map(
                        function (row) {
                            return row.TBM;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.TBM,

                    borderColor:
                        UMUR_CHART_COLORS.TBM,

                    borderWidth: 1
                },

                {
                    label: 'MUDA',

                    data: umurTableData.map(
                        function (row) {
                            return row.MUDA;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.MUDA,

                    borderColor:
                        UMUR_CHART_COLORS.MUDA,

                    borderWidth: 1
                },

                {
                    label: 'REMAJA',

                    data: umurTableData.map(
                        function (row) {
                            return row.REMAJA;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.REMAJA,

                    borderColor:
                        UMUR_CHART_COLORS.REMAJA,

                    borderWidth: 1
                },

                {
                    label: 'DEWASA',

                    data: umurTableData.map(
                        function (row) {
                            return row.DEWASA;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.DEWASA,

                    borderColor:
                        UMUR_CHART_COLORS.DEWASA,

                    borderWidth: 1
                },

                {
                    label: 'TUA',

                    data: umurTableData.map(
                        function (row) {
                            return row.TUA;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.TUA,

                    borderColor:
                        UMUR_CHART_COLORS.TUA,

                    borderWidth: 1
                },

                {
                    label: 'REPLANTING',

                    data: umurTableData.map(
                        function (row) {
                            return row.REPLANTING;
                        }
                    ),

                    backgroundColor:
                        UMUR_CHART_COLORS.REPLANTING,

                    borderColor:
                        UMUR_CHART_COLORS.REPLANTING,

                    borderWidth: 1
                }
            ]
        );

    
    var chartBibit =
        createHorizontalStackedChart(
            'chartBibit',

            bibitTableData.map(
                function (row) {
                    return row.PT;
                }
            ),

            buildBibitDatasets()
        );

    function buildTopografiDatasets() {
        return getTopografiHaColumns()
            .map(
                function (
                    columnName,
                    index
                ) {
                    return {
                        label:
                            getTopografiLabel(
                                columnName
                            ),

                        data:
                            topografiTableData.map(
                                function (row) {
                                    return toNumber(
                                        row[columnName]
                                    );
                                }
                            ),

                        backgroundColor:
                            BIBIT_CHART_COLORS[
                                index
                                % BIBIT_CHART_COLORS.length
                            ],

                        borderColor:
                            BIBIT_CHART_COLORS[
                                index
                                % BIBIT_CHART_COLORS.length
                            ],

                        borderWidth:
                            1
                    };
                }
            );
    }

    var chartTopografi =
        createHorizontalStackedChart(
            'chartTopografi',

            topografiTableData.map(
                function (row) {
                    return row.PT;
                }
            ),

            buildTopografiDatasets()
        );

    /*
    |--------------------------------------------------------------------------
    | Redraw Saat Tab Dibuka
    |--------------------------------------------------------------------------
    */

    $('a[data-toggle="tab"]').on(
        'shown.bs.tab',
        function (event) {
            var target =
                $(event.target).attr('href');

            setTimeout(function () {
                if (target === '#tab-wilayah') {
                    tableWilayah.redraw(true);

                    if (chartWilayah) {
                        chartWilayah.resize();
                    }
                }

                if (target === '#tab-pt') {
                    tablePT.redraw(true);

                    if (chartPT) {
                        chartPT.resize();
                    }
                }

                if (target === '#tab-umur') {
                    tableUmur.redraw(true);

                    if (chartUmur) {
                        chartUmur.resize();
                    }
                }

                if (
                    target === '#tab-bibit'
                ) {
                    tableBibit.redraw(true);

                    if (chartBibit) {
                        chartBibit.resize();
                    }
                }

                if (
                    target === '#tab-topografi'
                ) {
                    tableTopografi.redraw(true);

                    if (chartTopografi) {
                        chartTopografi.resize();
                    }
                }

            }, 100);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Resize
    |--------------------------------------------------------------------------
    */

    $(window).on('resize', function () {
        tableWilayah.redraw(true);
        tablePT.redraw(true);
        tableUmur.redraw(true);

        if (chartWilayah) {
            chartWilayah.resize();
        }

        if (chartPT) {
            chartPT.resize();
        }

        if (chartUmur) {
            chartUmur.resize();
        }

        tableBibit.redraw(true);

        if (chartBibit) {
            chartBibit.resize();
        }

        tableTopografi.redraw(true);

        if (chartTopografi) {
            chartTopografi.resize();
        }

    });
});
</script>

@endsection
@extends('dashboard.app')

@section('header-title')
    Luasan Per Wilayah dan PT
@endsection

@section('main-content')

{{-- ========================================================= --}}
{{-- TABULATOR CSS --}}
{{-- ========================================================= --}}
<link rel="stylesheet"
      href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">

<style>
    /*
    |--------------------------------------------------------------------------
    | Tabulator Container
    |--------------------------------------------------------------------------
    */
    .tabulator-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .tabulator {
        width: 100%;
        border: 1px solid #d2d6de;
        font-size: 13px;
        background-color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-header {
        border-bottom: 2px solid #d2d6de;
        background-color: #f4f4f4;
        color: #333333;
        font-weight: 600;
    }

    .tabulator .tabulator-header .tabulator-col {
        background-color: #f4f4f4;
        border-right: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header .tabulator-col:last-child {
        border-right: none;
    }

    .tabulator .tabulator-header .tabulator-col-content {
        padding: 8px 5px;
    }

    .tabulator .tabulator-header .tabulator-col-title {
        white-space: normal;
        line-height: 17px;
        text-align: center;
    }

    /*
    |--------------------------------------------------------------------------
    | Row dan Cell
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-row {
        min-height: 31px;
        border-bottom: 1px solid #eeeeee;
    }

    .tabulator .tabulator-row:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tabulator .tabulator-row:hover {
        background-color: #eef6ff;
    }

    .tabulator .tabulator-row .tabulator-cell {
        padding: 6px 7px;
        border-right: 1px solid #eeeeee;
        line-height: 18px;
    }

    /*
    |--------------------------------------------------------------------------
    | Bottom Calculation / Total
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-calcs-holder {
        background-color: #eaf2ff;
        border-top: 2px solid #3c8dbc;
    }

    .tabulator .tabulator-calcs-holder .tabulator-row {
        background-color: #eaf2ff !important;
        color: #222222;
        font-weight: bold;
    }

    .tabulator .tabulator-calcs-holder .tabulator-cell {
        background-color: #eaf2ff !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-footer {
        padding: 7px;
        background-color: #ffffff;
        border-top: 1px solid #d2d6de;
    }

    .tabulator .tabulator-footer .tabulator-page {
        padding: 4px 9px;
        margin: 2px;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        background-color: #ffffff;
    }

    .tabulator .tabulator-footer .tabulator-page.active {
        color: #ffffff;
        background-color: #3c8dbc;
        border-color: #367fa9;
    }

    /*
    |--------------------------------------------------------------------------
    | Table Toolbar
    |--------------------------------------------------------------------------
    */
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .table-toolbar-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .table-search {
        width: 240px;
        max-width: 100%;
    }

    /*
    |--------------------------------------------------------------------------
    | Chart
    |--------------------------------------------------------------------------
    */
    .chart-container {
        position: relative;
        width: 100%;
        height: 320px;  
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */
    @media (max-width: 767px) {
        .table-toolbar {
            display: block;
        }

        .table-toolbar-left {
            margin-bottom: 8px;
        }

        .table-search {
            width: 100%;
        }

        .chart-container {
            height: 420px;
        }
    }
</style>

<section class="content-header">
    <h1>
        Luasan Per Wilayah dan PT
        <small>Areal Statement</small>
    </h1>
</section>

<section class="content">

    {{-- ===================================================== --}}
    {{-- FILTER --}}
    {{-- ===================================================== --}}
    <div class="panel">
        <div class="panel-body">

            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/arealstatement/breakdown-luasan-wilayah-pt') }}">

                <div class="form-group">
                    <label for="tahun">Tahun : </label>

                    <div class="input-group date input-inline"
                         style="width:175px;">

                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input type="number"
                               class="form-control"
                               id="tahun"
                               name="tahun"
                               value="{{ Request::get('tahun') ?: ($tahun ?? date('Y', strtotime('first day of last month'))) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan : </label>

                    <div class="input-group date input-inline"
                         style="width:175px;">

                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input type="number"
                               class="form-control"
                               id="bulan"
                               name="bulan"
                               min="1"
                               max="12"
                               value="{{ Request::get('bulan') ?: ($bulan ?? date('m', strtotime('first day of last month'))) }}">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="fa fa-filter"></i>
                        Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">

            <div class="nav-tabs-custom">

                {{-- ===================================================== --}}
                {{-- TAB HEADER --}}
                {{-- ===================================================== --}}
                <ul class="nav nav-tabs">

                    <li class="active">
                        <a href="#tab-wilayah"
                           data-toggle="tab">
                            <i class="fa fa-map"></i>
                            Per Wilayah
                        </a>
                    </li>

                    <li>
                        <a href="#tab-pt"
                           data-toggle="tab">
                            <i class="fa fa-building"></i>
                            Per PT
                        </a>
                    </li>
                    <li>
                        <a href="#tab-umur"
                        data-toggle="tab">
                            <i class="fa fa-clock-o"></i>
                            Per Umur
                        </a>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- ================================================= --}}
                    {{-- TAB WILAYAH --}}
                    {{-- ================================================= --}}
                    <div class="tab-pane active"
                         id="tab-wilayah">

                        {{-- CHART WILAYAH --}}
                        <div class="box box-primary">

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

                        {{-- TABLE WILAYAH --}}
                        <div class="box box-success">

                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    Luasan Per Wilayah
                                </h3>
                            </div>

                            <div class="box-body">

                                <div class="table-toolbar">

                                    <div class="table-toolbar-left">

                                        <div class="input-group table-search">

                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>

                                            <input type="text"
                                                   id="search-wilayah"
                                                   class="form-control"
                                                   placeholder="Cari data wilayah...">

                                        </div>

                                        <button type="button"
                                                id="reset-wilayah"
                                                class="btn btn-default">
                                            <i class="fa fa-times"></i>
                                            Reset
                                        </button>

                                    </div>

                                    <div>
                                        <small class="text-muted">
                                            Klik header kolom untuk mengurutkan
                                        </small>
                                    </div>

                                </div>

                                <div class="tabulator-wrapper">
                                    <div id="table-wilayah"></div>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- TAB PT --}}
                    {{-- ================================================= --}}
                    <div class="tab-pane"
                         id="tab-pt">

                        {{-- CHART PT --}}
                        <div class="box box-primary">

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

                        {{-- TABLE PT --}}
                        <div class="box box-warning">

                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    Luasan Per PT
                                </h3>
                            </div>

                            <div class="box-body">

                                <div class="table-toolbar">

                                    <div class="table-toolbar-left">

                                        <div class="input-group table-search">

                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>

                                            <input type="text"
                                                   id="search-pt"
                                                   class="form-control"
                                                   placeholder="Cari data PT...">

                                        </div>

                                        <button type="button"
                                                id="reset-pt"
                                                class="btn btn-default">
                                            <i class="fa fa-times"></i>
                                            Reset
                                        </button>

                                    </div>

                                    <div>
                                        <small class="text-muted">
                                            Klik header kolom untuk mengurutkan
                                        </small>
                                    </div>

                                </div>

                                <div class="tabulator-wrapper">
                                    <div id="table-pt"></div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="tab-umur">

                        <div class="box box-primary">
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

                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    Luasan Per Umur Tanaman
                                </h3>
                            </div>

                            <div class="box-body">

                                <div class="table-toolbar">

                                    <div class="table-toolbar-left">
                                        <div class="input-group table-search">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>

                                            <input type="text"
                                                id="search-umur"
                                                class="form-control"
                                                placeholder="Cari kebun atau nilai...">
                                        </div>

                                        <button type="button"
                                                id="reset-umur"
                                                class="btn btn-default">
                                            <i class="fa fa-times"></i>
                                            Reset
                                        </button>
                                    </div>

                                </div>

                                <div class="tabulator-wrapper">
                                    <div id="table-umur"></div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <p>
                    HA = HEKTARE
                </p>

            </div>
        </div>
    </div>

</section>
@endsection

@section('script-content')

{{-- ========================================================= --}}
{{-- TABULATOR JS --}}
{{-- ========================================================= --}}
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script type="text/javascript">

    /*
    |--------------------------------------------------------------------------
    | Format Angka Indonesia
    |--------------------------------------------------------------------------
    */
    function formatNumberIndonesia(value, decimalPlaces) {
        var number = parseFloat(value);

        if (isNaN(number)) {
            number = 0;
        }

        return number.toLocaleString('id-ID', {
            minimumFractionDigits: decimalPlaces,
            maximumFractionDigits: decimalPlaces
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tabulator Number Formatter
    |--------------------------------------------------------------------------
    */
    function numberFormatter(cell) {
        return formatNumberIndonesia(cell.getValue(), 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Tabulator Bottom Calculation Formatter
    |--------------------------------------------------------------------------
    */
    function bottomNumberFormatter(cell) {
        return formatNumberIndonesia(cell.getValue(), 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Konversi Nilai Menjadi Number
    |--------------------------------------------------------------------------
    */
    function toNumber(value) {
        var number = parseFloat(value);
        return isNaN(number) ? 0 : number;
    }

    /*
    |--------------------------------------------------------------------------
    | Singkatan Nama PT
    |--------------------------------------------------------------------------
    */
    function abbreviatePTName(name) {
        var normalizedName = String(name || '').trim().toUpperCase();

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

        return ptAbbreviations[normalizedName] || name || '';
    }

    /*
    |--------------------------------------------------------------------------
    | Data Wilayah
    |--------------------------------------------------------------------------
    |
    | Baris IS_TOTAL tidak dimasukkan ke body Tabulator karena total dibuat
    | otomatis menggunakan bottomCalc.
    |
    */
    var rawWilayahData = @json($wilayah);
    var wilayahTableData = [];

    rawWilayahData.forEach(function(row) {

        var isTotal =
            row.IS_TOTAL === true ||
            row.IS_TOTAL === 1 ||
            row.IS_TOTAL === '1';

        if (isTotal) {
            return;
        }

        wilayahTableData.push({
            NO: row.NoUrut !== undefined
                ? row.NoUrut
                : (
                    row.NOURUT !== undefined
                        ? row.NOURUT
                        : 998
                ),

            REGION: row.REGION || '',

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
    var rawPTData = @json($pt);
    var ptTableData = [];

    rawPTData.forEach(function(row) {

        var isTotal =
            row.IS_TOTAL === true ||
            row.IS_TOTAL === 1 ||
            row.IS_TOTAL === '1';

        if (isTotal) {
            return;
        }

        ptTableData.push({
            NO: row.NOURUT !== undefined
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

    var rawUmurData = @json($dataUmur ?? []);

    var umurTableData = rawUmurData.map(function (row, index) {
        return {
            NO: row.NOURUT || (index + 1),

            KEBUN: String(row.KEBUN || '')
                .trim()
                .toUpperCase(),

            TBM: toNumber(row.TBM),
            MUDA: toNumber(row.MUDA),
            REMAJA: toNumber(row.REMAJA),
            DEWASA: toNumber(row.DEWASA),
            TUA: toNumber(row.TUA),
            REPLANTING: toNumber(row.REPLANTING),

            TOTAL_HA:
                toNumber(row.TBM) +
                toNumber(row.MUDA) +
                toNumber(row.REMAJA) +
                toNumber(row.DEWASA) +
                toNumber(row.TUA) +
                toNumber(row.REPLANTING)
        };
    });

    /*
    |--------------------------------------------------------------------------
    | Kolom Tabulator
    |--------------------------------------------------------------------------
    */
    function createColumns(nameTitle, nameField) {
        return [
            {
                title: 'NO',
                field: 'NO',
                width: 55,
                minWidth: 50,
                hozAlign: 'center',
                headerHozAlign: 'center',
                sorter: 'number',
                bottomCalc: function() {
                    return '~';
                }
            },
            {
                title: nameTitle,
                field: nameField,
                minWidth: 170,
                widthGrow: 2,
                sorter: 'string',
                bottomCalc: function() {
                    return 'TOTAL';
                }
            },
            {
                title: 'TM<br>[HA]',
                field: 'HA_TM',
                minWidth: 90,
                widthGrow: 1,
                hozAlign: 'right',
                headerHozAlign: 'center',
                sorter: 'number',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TBM<br>[HA]',
                field: 'HA_TBM',
                minWidth: 90,
                widthGrow: 1,
                hozAlign: 'right',
                headerHozAlign: 'center',
                sorter: 'number',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TB<br>[HA]',
                field: 'HA_TB',
                minWidth: 90,
                widthGrow: 1,
                hozAlign: 'right',
                headerHozAlign: 'center',
                sorter: 'number',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'LAIN<br>[HA]',
                field: 'HA_LAIN',
                minWidth: 90,
                widthGrow: 1,
                hozAlign: 'right',
                headerHozAlign: 'center',
                sorter: 'number',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TOTAL<br>[HA]',
                field: 'TOTAL_HA',
                minWidth: 105,
                widthGrow: 1,
                hozAlign: 'right',
                headerHozAlign: 'center',
                sorter: 'number',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            }
        ];
    }

    var tableUmur = new Tabulator('#table-umur', {
        data: umurTableData,
        layout: 'fitData',
        height: '350px',

        pagination: 'local',
        paginationSize: 25,

        movableColumns: true,
        resizableColumns: true,

        initialSort: [
            {
                column: 'NO',
                dir: 'asc'
            }
        ],

        columns: [
            {
                title: 'NO',
                field: 'NO',
                sorter: 'number',
                width: 60,
                hozAlign: 'center'
            },
            {
                title: 'KEBUN',
                field: 'KEBUN',
                sorter: 'string',
                width: 140,
                bottomCalc: function () {
                    return 'TOTAL';
                }
            },
            {
                title: 'TBM<br>[HA]',
                field: 'TBM',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'MUDA<br>[HA]',
                field: 'MUDA',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'REMAJA<br>[HA]',
                field: 'REMAJA',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'DEWASA<br>[HA]',
                field: 'DEWASA',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TUA<br>[HA]',
                field: 'TUA',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'REPLANTING<br>[HA]',
                field: 'REPLANTING',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            },
            {
                title: 'TOTAL<br>[HA]',
                field: 'TOTAL_HA',
                sorter: 'number',
                hozAlign: 'right',
                formatter: numberFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: bottomNumberFormatter
            }
        ]
    });

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Umum Tabulator
    |--------------------------------------------------------------------------
    */
    function createTabulator(selector, data, columns) {
        return new Tabulator(selector, {
            data: data,
            layout: 'fitData',
            responsiveLayout: false,
            movableColumns: false,
            resizableColumns: true,
            placeholder: 'Tidak ada data yang tersedia',
            pagination: 'local',
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100, true],
            paginationCounter: 'rows',
            initialSort: [
                {
                    column: 'NO',
                    dir: 'asc'
                }
            ],
            columnDefaults: {
                vertAlign: 'middle',
                headerSort: true,
                resizable: true,
                tooltip: false
            },
            columns: columns,
            langs: {
                'id-id': {
                    data: {
                        loading: 'Memuat data...',
                        error: 'Terjadi kesalahan'
                    },
                    pagination: {
                        page_size: 'Jumlah baris',
                        first: 'Awal',
                        first_title: 'Halaman pertama',
                        last: 'Akhir',
                        last_title: 'Halaman terakhir',
                        prev: 'Sebelumnya',
                        prev_title: 'Halaman sebelumnya',
                        next: 'Berikutnya',
                        next_title: 'Halaman berikutnya',
                        all: 'Semua',
                        counter: {
                            showing: 'Menampilkan',
                            of: 'dari',
                            rows: 'baris',
                            pages: 'halaman'
                        }
                    }
                }
            },
            locale: 'id-id'
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize Tabulator
    |--------------------------------------------------------------------------
    */
    var tableWilayah = createTabulator(
        '#table-wilayah',
        wilayahTableData,
        createColumns('REGION', 'REGION')
    );

    var tablePT = createTabulator(
        '#table-pt',
        ptTableData,
        createColumns('PT', 'NAMA')
    );

    /*
    |--------------------------------------------------------------------------
    | Global Search Tabulator
    |--------------------------------------------------------------------------
    */
    function applyGlobalSearch(table, keyword, searchableFields) {

        keyword = String(keyword || '')
            .trim()
            .toLowerCase();

        if (keyword === '') {
            table.clearFilter(true);
            return;
        }

        table.setFilter(function(rowData) {

            for (var index = 0; index < searchableFields.length; index++) {

                var field = searchableFields[index];
                var value = rowData[field];
                var formattedValue;

                if (
                    field === 'HA_TM' ||
                    field === 'HA_TBM' ||
                    field === 'HA_TB' ||
                    field === 'HA_LAIN' ||
                    field === 'TOTAL_HA'
                ) {
                    formattedValue = formatNumberIndonesia(value, 2);
                } else {
                    formattedValue = String(
                        value === null || value === undefined
                            ? ''
                            : value
                    );
                }

                if (formattedValue.toLowerCase().indexOf(keyword) !== -1) {
                    return true;
                }
            }

            return false;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Search Wilayah
    |--------------------------------------------------------------------------
    */
    var wilayahSearchTimer = null;

    $('#search-wilayah').on('input', function() {

        var keyword = this.value;

        clearTimeout(wilayahSearchTimer);

        wilayahSearchTimer = setTimeout(function() {
            applyGlobalSearch(
                tableWilayah,
                keyword,
                [
                    'NO',
                    'REGION',
                    'HA_TM',
                    'HA_TBM',
                    'HA_TB',
                    'HA_LAIN',
                    'TOTAL_HA'
                ]
            );
        }, 200);
    });

    $('#reset-wilayah').on('click', function() {
        $('#search-wilayah').val('');
        tableWilayah.clearFilter(true);
        tableWilayah.setSort('NO', 'asc');
        tableWilayah.setPage(1);
    });

    /*
    |--------------------------------------------------------------------------
    | Search PT
    |--------------------------------------------------------------------------
    */
    var ptSearchTimer = null;

    $('#search-pt').on('input', function() {

        var keyword = this.value;

        clearTimeout(ptSearchTimer);

        ptSearchTimer = setTimeout(function() {
            applyGlobalSearch(
                tablePT,
                keyword,
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
        }, 200);
    });

    $('#reset-pt').on('click', function() {
        $('#search-pt').val('');
        tablePT.clearFilter(true);
        tablePT.setSort('NO', 'asc');
        tablePT.setPage(1);
    });

    var UMUR_CHART_COLORS = {
        TBM: '#1565C0',
        MUDA: '#E53935',
        REMAJA: '#00897B',
        DEWASA: '#FB8C00',
        TUA: '#6A1B9A',
        REPLANTING: '#7CB342'
    };

    /*
    |--------------------------------------------------------------------------
    | Plugin Total di Ujung Horizontal Stacked Bar
    |--------------------------------------------------------------------------
    */
    Chart.plugins.register({
        afterDatasetsDraw: function(chart) {

            if (chart.config.type !== 'horizontalBar') {
                return;
            }

            var datasets = chart.data.datasets || [];

            if (!datasets.length) {
                return;
            }

            var ctx = chart.ctx;
            var labels = chart.data.labels || [];

            ctx.save();
            ctx.font = 'bold 11px Arial';
            ctx.fillStyle = '#333333';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';

            labels.forEach(function(label, dataIndex) {

                var total = 0;
                var lastElement = null;

                datasets.forEach(function(dataset, datasetIndex) {

                    var value = toNumber(dataset.data[dataIndex]);
                    total += value;

                    var meta = chart.getDatasetMeta(datasetIndex);

                    if (
                        !meta.hidden &&
                        meta.data &&
                        meta.data[dataIndex]
                    ) {
                        lastElement = meta.data[dataIndex];
                    }
                });

                if (!lastElement || total <= 0) {
                    return;
                }

                var model = lastElement._model || lastElement._view;

                if (!model) {
                    return;
                }

                ctx.fillText(
                    formatNumberIndonesia(total, 2),
                    model.x + 8,
                    model.y
                );
            });

            ctx.restore();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Siapkan Data Chart
    |--------------------------------------------------------------------------
    */
    function prepareStackedChartData(data, labelField) {

        var labels = [];
        var haTM = [];
        var haTBM = [];
        var haTB = [];
        var haLain = [];

        data.forEach(function(row) {
            labels.push(row[labelField] || '');
            haTM.push(toNumber(row.HA_TM));
            haTBM.push(toNumber(row.HA_TBM));
            haTB.push(toNumber(row.HA_TB));
            haLain.push(toNumber(row.HA_LAIN));
        });

        return {
            labels: labels,
            HA_TM: haTM,
            HA_TBM: haTBM,
            HA_TB: haTB,
            HA_LAIN: haLain
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Create Horizontal Stacked Bar Chart
    |--------------------------------------------------------------------------
    */
    function createHorizontalStackedBarChart(elementId, chartData, categoryLabel) {

        var canvas = document.getElementById(elementId);

        if (!canvas) {
            return null;
        }

        var ctx = canvas.getContext('2d');

        return new Chart(ctx, {
            type: 'horizontalBar',

            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'TM [HA]',
                        data: chartData.HA_TM,
                        backgroundColor: '#16a34a',
                        borderColor: '#15803d',
                        borderWidth: 1
                    },
                    {
                        label: 'TBM [HA]',
                        data: chartData.HA_TBM,
                        backgroundColor: '#2563eb',
                        borderColor: '#1d4ed8',
                        borderWidth: 1
                    },
                    {
                        label: 'TB [HA]',
                        data: chartData.HA_TB,
                        backgroundColor: '#f59e0b',
                        borderColor: '#d97706',
                        borderWidth: 1
                    },
                    {
                        label: 'LAIN [HA]',
                        data: chartData.HA_LAIN,
                        backgroundColor: '#7c3aed',
                        borderColor: '#6d28d9',
                        borderWidth: 1
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        right: 70
                    }
                },

                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 14,
                        padding: 15
                    }
                },

                scales: {
                    xAxes: [{
                        stacked: true,
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return formatNumberIndonesia(value, 0);
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Luasan [HA]'
                        }
                    }],

                    yAxes: [{
                        stacked: true,
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontSize: 10
                        },
                        scaleLabel: {
                            display: true,
                            labelString: categoryLabel
                        }
                    }]
                },

                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        title: function(tooltipItems, data) {
                            if (!tooltipItems.length) {
                                return '';
                            }

                            return data.labels[tooltipItems[0].index] || '';
                        },

                        label: function(tooltipItem, data) {

                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var value = toNumber(dataset.data[tooltipItem.index]);

                            return dataset.label + ': ' + formatNumberIndonesia(value, 2) + ' HA';
                        },

                        footer: function(tooltipItems, data) {

                            if (!tooltipItems.length) {
                                return '';
                            }

                            var dataIndex = tooltipItems[0].index;
                            var total = 0;

                            data.datasets.forEach(function(dataset) {
                                total += toNumber(dataset.data[dataIndex]);
                            });

                            return 'TOTAL: ' + formatNumberIndonesia(total, 2) + ' HA';
                        }
                    },
                    footerFontStyle: 'bold'
                },

                hover: {
                    mode: 'index',
                    intersect: false
                },

                animation: {
                    duration: 500
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Data Chart Wilayah
    |--------------------------------------------------------------------------
    */
    var wilayahChartData = prepareStackedChartData(
        wilayahTableData,
        'REGION'
    );

    /*
    |--------------------------------------------------------------------------
    | Data Chart PT
    |--------------------------------------------------------------------------
    */
    var ptChartData = prepareStackedChartData(
        ptTableData,
        'NAMA'
    );

    var chartUmur = createHorizontalStackedChart(
        'chartUmur',

        umurTableData.map(function (row) {
            return row.KEBUN;
        }),

        [
            {
                label: 'TBM',
                data: umurTableData.map(function (row) {
                    return row.TBM;
                }),
                backgroundColor: UMUR_CHART_COLORS.TBM,
                borderColor: UMUR_CHART_COLORS.TBM,
                borderWidth: 1
            },
            {
                label: 'MUDA',
                data: umurTableData.map(function (row) {
                    return row.MUDA;
                }),
                backgroundColor: UMUR_CHART_COLORS.MUDA,
                borderColor: UMUR_CHART_COLORS.MUDA,
                borderWidth: 1
            },
            {
                label: 'REMAJA',
                data: umurTableData.map(function (row) {
                    return row.REMAJA;
                }),
                backgroundColor: UMUR_CHART_COLORS.REMAJA,
                borderColor: UMUR_CHART_COLORS.REMAJA,
                borderWidth: 1
            },
            {
                label: 'DEWASA',
                data: umurTableData.map(function (row) {
                    return row.DEWASA;
                }),
                backgroundColor: UMUR_CHART_COLORS.DEWASA,
                borderColor: UMUR_CHART_COLORS.DEWASA,
                borderWidth: 1
            },
            {
                label: 'TUA',
                data: umurTableData.map(function (row) {
                    return row.TUA;
                }),
                backgroundColor: UMUR_CHART_COLORS.TUA,
                borderColor: UMUR_CHART_COLORS.TUA,
                borderWidth: 1
            },
            {
                label: 'REPLANTING',
                data: umurTableData.map(function (row) {
                    return row.REPLANTING;
                }),
                backgroundColor: UMUR_CHART_COLORS.REPLANTING,
                borderColor: UMUR_CHART_COLORS.REPLANTING,
                borderWidth: 1
            }
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Initialize Charts
    |--------------------------------------------------------------------------
    */
    var chartWilayah = createHorizontalStackedBarChart(
        'chartWilayah',
        wilayahChartData,
        'Wilayah'
    );

    var chartPT = createHorizontalStackedBarChart(
        'chartPT',
        ptChartData,
        'PT'
    );

    /*
    |--------------------------------------------------------------------------
    | Redraw Saat Tab Dibuka
    |--------------------------------------------------------------------------
    |
    | Tabulator dan Chart yang berada pada tab tersembunyi perlu di-redraw
    | ketika tab ditampilkan agar ukuran kolom dan canvas tidak rusak.
    |
    */
    $('a[href="#tab-wilayah"]').on('shown.bs.tab', function() {

        setTimeout(function() {

            tableWilayah.redraw(true);

            if (chartWilayah) {
                chartWilayah.resize();
            }

        }, 100);
    });

    $('a[href="#tab-pt"]').on('shown.bs.tab', function() {

        setTimeout(function() {

            tablePT.redraw(true);

            if (chartPT) {
                chartPT.resize();
            }

        }, 100);
    });

    /*
    |--------------------------------------------------------------------------
    | Redraw Saat Ukuran Window Berubah
    |--------------------------------------------------------------------------
    */
    var resizeTimer = null;

    $(window).on('resize', function() {

        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function() {

            tableWilayah.redraw(true);
            tablePT.redraw(true);

            if (chartWilayah) {
                chartWilayah.resize();
            }

            if (chartPT) {
                chartPT.resize();
            }

        }, 200);
    });

</script>
@endsection
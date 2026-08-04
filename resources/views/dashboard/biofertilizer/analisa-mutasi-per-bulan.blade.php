@extends('dashboard.app')

@section('header-title')
    Pupuk BioFertilizer 
@endsection

@section('main-content')

{{-- ========================================================= --}}
{{-- TABULATOR CSS --}}
{{-- ========================================================= --}}


<style>
    /*
    |--------------------------------------------------------------------------
    | Tabulator
    |--------------------------------------------------------------------------
    */
    #table-data {
        width: 100%;
    }

    .tabulator {
        width: 100%;
        border: 1px solid #d2d6de;
        background-color: #ffffff;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-header {
        background-color: #f4f4f4;
        border-bottom: 2px solid #d2d6de;
        color: #333333;
        font-weight: bold;
    }

    .tabulator .tabulator-header .tabulator-col {
        background-color: #f4f4f4;
        border-right: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header .tabulator-col-content {
        padding: 7px 5px;
    }

    .tabulator .tabulator-header .tabulator-col-title {
        white-space: normal;
        line-height: 16px;
        text-align: center;
    }

    /*
    |--------------------------------------------------------------------------
    | Row
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-row {
        min-height: 30px;
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
        line-height: 17px;
    }

    /*
    |--------------------------------------------------------------------------
    | Footer dan Pagination
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-footer {
        padding: 7px;
        background-color: #ffffff;
        border-top: 1px solid #d2d6de;
    }

    .tabulator .tabulator-footer .tabulator-page {
        margin: 2px;
        padding: 4px 9px;
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
    | Toolbar
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
        width: 260px;
        max-width: 100%;
    }

    /*
    |--------------------------------------------------------------------------
    | Total Row
    |--------------------------------------------------------------------------
    */
    .tabulator .tabulator-calcs-holder {
        background-color: #eaf2ff;
        border-top: 2px solid #3c8dbc;
    }

    .tabulator .tabulator-calcs-holder .tabulator-row,
    .tabulator .tabulator-calcs-holder .tabulator-cell {
        background-color: #eaf2ff !important;
        font-weight: bold;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
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
    }
</style>

<section class="content-header">
    <h1>
        Pupuk BioFertilizer
        <small>Waiting User Confirmation</small>
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
                  action="{{ url('/dashboard/biofertilizer/AnalisaMutasiPupukPerBulan') }}">

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
                               min="2000"
                               max="2100"
                               value="{{ request('tahun', $tahun ?? date('Y')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan : </label>

                    <div class="input-group date input-inline"
                         style="width:150px;">

                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input type="number"
                               class="form-control"
                               id="bulan"
                               name="bulan"
                               min="1"
                               max="12"
                               value="{{ request('bulan', $bulan ?? date('n')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_id">Site ID : </label>

                    <select class="form-control"
                            id="site_id"
                            name="site_id">

                        <option value="2200"
                            {{ (string) request('site_id', $siteId ?? '2200') === '2200' ? 'selected' : '' }}>
                            2200 - TELDA
                        </option>

                        <option value="2300"
                            {{ (string) request('site_id', $siteId ?? '2200') === '2300' ? 'selected' : '' }}>
                            2300 - KALSA
                        </option>

                        <option value="2400"
                            {{ (string) request('site_id', $siteId ?? '2200') === '2400' ? 'selected' : '' }}>
                            2400 - KALDA
                        </option>

                        <option value="2500"
                            {{ (string) request('site_id', $siteId ?? '2200') === '2500' ? 'selected' : '' }}>
                            2500 - KOKAR
                        </option>

                        <option value="3200"
                            {{ (string) request('site_id', $siteId ?? '2200') === '3200' ? 'selected' : '' }}>
                            3200 - RICKO
                        </option>

                        <option value="5200"
                            {{ (string) request('site_id', $siteId ?? '2200') === '5200' ? 'selected' : '' }}>
                            5200 - PASER
                        </option>

                    </select>
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

    {{-- ===================================================== --}}
    {{-- TABLE --}}
    {{-- ===================================================== --}}
    <div class="row">
        <div class="col-md-10 col-sm-12 col-xs-12">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        Analisa Mutasi Pupuk Per Bulan
                    </h3>
                </div>

                <div class="box-body">

                    {{-- Toolbar --}}
                    <div class="table-toolbar">

                        <div class="table-toolbar-left">

                            <div class="input-group table-search">

                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>

                                <input type="text"
                                       id="search-table"
                                       class="form-control"
                                       placeholder="Cari semua kolom...">

                            </div>

                            <button type="button"
                                    id="reset-table"
                                    class="btn btn-default">

                                <i class="fa fa-times"></i>
                                Reset
                            </button>

                        </div>

                        <div>
                            <small class="text-muted">
                                Klik header untuk mengurutkan data
                            </small>
                        </div>

                    </div>

                    {{-- Tabulator Container --}}
                    <div id="table-data"></div>

                </div>
            </div>

        </div>
    </div>

</section>
@endsection

@section('script-content')

{{-- ========================================================= --}}
{{-- TABULATOR JS --}}
{{-- ========================================================= --}}

<script type="text/javascript">

    /*
    |--------------------------------------------------------------------------
    | Data dari Laravel
    |--------------------------------------------------------------------------
    */
    var rawTableData = @json($rows ?? []);

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Kolom
    |--------------------------------------------------------------------------
    */
    var hiddenColumns = [
        'COMP_ID',
        'SITE_ID'
    ];

    var labelMap = {
        'COMPOSTTYPE': 'TIPE COMPOST'
    };

    var integerColumns = [
        'SALDOAWAL',
        'KELUAR',
        'MASUK',
        'RETUR',
        'SALDOAKHIR'
    ];

    /*
    |--------------------------------------------------------------------------
    | Format Angka Indonesia
    |--------------------------------------------------------------------------
    */
    function formatIntegerIndonesia(value) {

        var number = parseFloat(value);

        if (isNaN(number)) {
            number = 0;
        }

        return number.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Number Formatter
    |--------------------------------------------------------------------------
    */
    function integerFormatter(cell) {

        var value = cell.getValue();

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return '';
        }

        return formatIntegerIndonesia(value);
    }

    /*
    |--------------------------------------------------------------------------
    | Mendapatkan Key Data Secara Case-Insensitive
    |--------------------------------------------------------------------------
    */
    function getRowValue(row, fieldName) {

        if (!row || !fieldName) {
            return null;
        }

        if (Object.prototype.hasOwnProperty.call(row, fieldName)) {
            return row[fieldName];
        }

        var requestedField = String(fieldName).toUpperCase();
        var keys = Object.keys(row);

        for (var index = 0; index < keys.length; index++) {

            if (String(keys[index]).toUpperCase() === requestedField) {
                return row[keys[index]];
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil Header dari Baris Pertama
    |--------------------------------------------------------------------------
    */
    var originalHeaders = [];

    if (rawTableData.length > 0) {
        originalHeaders = Object.keys(rawTableData[0]);
    }

    /*
    |--------------------------------------------------------------------------
    | Header yang Ditampilkan
    |--------------------------------------------------------------------------
    */
    var visibleHeaders = originalHeaders.filter(function(header) {

        return hiddenColumns.indexOf(
            String(header).toUpperCase()
        ) === -1;
    });

    /*
    |--------------------------------------------------------------------------
    | Normalisasi Data
    |--------------------------------------------------------------------------
    |
    | Nama field dibuat uppercase supaya konsisten walaupun key dari SQL
    | berbeda huruf besar dan kecil.
    |
    */
    var tableData = rawTableData.map(function(row) {

        var normalizedRow = {};

        visibleHeaders.forEach(function(header) {

            var fieldName = String(header).toUpperCase();
            var value = getRowValue(row, header);

            if (
                integerColumns.indexOf(fieldName) !== -1 &&
                value !== null &&
                value !== '' &&
                !isNaN(parseFloat(value))
            ) {
                normalizedRow[fieldName] = parseFloat(value);
            } else {
                normalizedRow[fieldName] =
                    value === null || value === undefined
                        ? ''
                        : value;
            }
        });

        return normalizedRow;
    });

    /*
    |--------------------------------------------------------------------------
    | Tentukan Lebar Minimum Kolom
    |--------------------------------------------------------------------------
    */
    function getColumnMinWidth(fieldName) {

        fieldName = String(fieldName).toUpperCase();

        if (
            fieldName === 'NO' ||
            fieldName === 'NOURUT'
        ) {
            return 60;
        }

        if (
            fieldName === 'TAHUN' ||
            fieldName === 'BULAN'
        ) {
            return 80;
        }

        if (integerColumns.indexOf(fieldName) !== -1) {
            return 110;
        }

        if (
            fieldName.indexOf('NAMA') !== -1 ||
            fieldName.indexOf('TYPE') !== -1 ||
            fieldName.indexOf('KETERANGAN') !== -1
        ) {
            return 160;
        }

        return 110;
    }

    /*
    |--------------------------------------------------------------------------
    | Pembuatan Kolom Dinamis
    |--------------------------------------------------------------------------
    */
    var tableColumns = visibleHeaders.map(function(header, index) {

        var fieldName = String(header).toUpperCase();

        var columnTitle =
            labelMap[fieldName] !== undefined
                ? labelMap[fieldName]
                : fieldName;

        var isIntegerColumn =
            integerColumns.indexOf(fieldName) !== -1;

        var columnConfig = {
            title: columnTitle,
            field: fieldName,

            minWidth: getColumnMinWidth(fieldName),

            headerHozAlign: 'center',
            vertAlign: 'middle',

            sorter: isIntegerColumn
                ? 'number'
                : 'string',

            hozAlign: isIntegerColumn
                ? 'right'
                : 'left',

            formatter: isIntegerColumn
                ? integerFormatter
                : undefined,

            headerSort: true,
            resizable: true
        };

        /*
        | Kolom pertama dibuat frozen agar tetap terlihat
        | ketika tabel digeser horizontal.
        */
        if (index === 0) {
            columnConfig.frozen = true;
        }

        /*
        | Total otomatis untuk kolom angka.
        |
        | Hapus bagian bottomCalc ini apabila data SALDO sudah memiliki
        | baris TOTAL dari stored procedure dan tidak ingin dihitung ulang.
        */
        if (isIntegerColumn) {
            columnConfig.bottomCalc = 'sum';
            columnConfig.bottomCalcFormatter = integerFormatter;
        }

        return columnConfig;
    });

    /*
    |--------------------------------------------------------------------------
    | Jika Data Kosong
    |--------------------------------------------------------------------------
    */
    if (tableColumns.length === 0) {
        tableColumns = [
            {
                title: 'INFORMASI',
                field: 'INFORMASI',
                minWidth: 250,
                headerSort: false
            }
        ];

        tableData = [
            {
                INFORMASI: 'Tidak ada data yang tersedia'
            }
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize Tabulator
    |--------------------------------------------------------------------------
    */
    var table = new Tabulator('#table-data', {
        data: tableData,

        /*
        | fitDataStretch:
        | Lebar kolom menyesuaikan isi data dan sisa ruang tabel.
        | Jika total lebar melebihi container, horizontal scroll muncul.
        */
        layout: 'fitData',

        responsiveLayout: false,

        height: '40vh',

        movableColumns: false,
        resizableColumns: true,

        placeholder: 'Tidak ada data yang tersedia',

        pagination: 'local',
        paginationSize: 25,
        paginationSizeSelector: [
            10,
            25,
            50,
            100,
            true
        ],

        paginationCounter: 'rows',

        initialSort: tableColumns.length > 0
            ? [
                {
                    column: tableColumns[0].field,
                    dir: 'asc'
                }
            ]
            : [],

        columnDefaults: {
            vertAlign: 'middle',
            headerSort: true,
            resizable: true
        },

        columns: tableColumns,

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

    /*
    |--------------------------------------------------------------------------
    | Global Search
    |--------------------------------------------------------------------------
    */
    function applyGlobalSearch(keyword) {

        keyword = String(keyword || '')
            .trim()
            .toLowerCase();

        if (keyword === '') {
            table.clearFilter(true);
            return;
        }

        table.setFilter(function(rowData) {

            var fields = Object.keys(rowData);

            for (var index = 0; index < fields.length; index++) {

                var fieldName = fields[index];
                var value = rowData[fieldName];

                var searchableValue;

                if (
                    integerColumns.indexOf(
                        String(fieldName).toUpperCase()
                    ) !== -1
                ) {
                    searchableValue =
                        formatIntegerIndonesia(value);
                } else {
                    searchableValue =
                        value === null || value === undefined
                            ? ''
                            : String(value);
                }

                if (
                    searchableValue
                        .toLowerCase()
                        .indexOf(keyword) !== -1
                ) {
                    return true;
                }
            }

            return false;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Search dengan Delay
    |--------------------------------------------------------------------------
    */
    var searchTimer = null;

    $('#search-table').on('input', function() {

        var keyword = this.value;

        clearTimeout(searchTimer);

        searchTimer = setTimeout(function() {
            applyGlobalSearch(keyword);
        }, 200);
    });

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */
    $('#reset-table').on('click', function() {

        $('#search-table').val('');

        table.clearFilter(true);
        table.clearSort();

        if (tableColumns.length > 0) {
            table.setSort(
                tableColumns[0].field,
                'asc'
            );
        }

        table.setPage(1);
    });

    /*
    |--------------------------------------------------------------------------
    | Redraw Ketika Window Berubah
    |--------------------------------------------------------------------------
    */
    var resizeTimer = null;

    $(window).on('resize', function() {

        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function() {
            table.redraw(true);
        }, 200);
    });

</script>
@endsection


@extends('dashboard.app')

@section('header-title')
    Persediaan Produk Sampingan
@endsection

@section('main-content')

<link
    rel="stylesheet"
    href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css"
>

<style>
    /* =========================================================
       GENERAL
       ========================================================= */

    .filter-panel {
        margin-bottom: 20px;
    }

    .filter-panel .panel-heading {
        font-size: 16px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 10px;
    }

    .filter-row .form-group {
        margin-bottom: 0;
    }

    .filter-row label {
        display: block;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .filter-date {
        width: 170px;
    }

    .filter-kebun {
        width: 150px;
    }

    .filter-product {
        width: 220px;
    }


    /* =========================================================
       TABS
       ========================================================= */

    .nav-tabs-custom {
        margin-bottom: 0;
    }

    .tab-content {
        padding-top: 15px;
    }


    /* =========================================================
       TABLE TOOLBAR
       ========================================================= */

    .table-toolbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 10px;
    }

    .table-search {
        width: 340px;
    }

    .table-search .input-group-addon {
        background-color: #fff;
    }


    /* =========================================================
       TABULATOR
       ========================================================= */

    #table-harian,
    #table-bulanan {
        width: 100%;
        min-height: 100px;
    }

    #table-harian .tabulator,
    #table-bulanan .tabulator {
        font-size: 12px;
        border: 1px solid #d2d6de;
    }

    .tabulator {
        font-size: 12px;
        border: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header {
        font-weight: 600;
        background-color: #f4f4f4;
        border-bottom: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header
    .tabulator-col
    .tabulator-col-content
    .tabulator-col-title {
        white-space: normal;
    }

    .tabulator-row.row-total {
        font-weight: bold;
        background-color: #e7e7e7 !important;
    }

    .tabulator-row.row-pao-warning {
        background-color: #dd4b39 !important;
        color: #fff !important;
    }

    .tabulator-row.row-pao-warning .tabulator-cell {
        background-color: #dd4b39 !important;
        color: #fff !important;
    }

    .tabulator .tabulator-footer {
        background-color: #f4f4f4;
    }


    /* =========================================================
       NOTE
       ========================================================= */

    .pao-note {
        margin-top: 15px;
        font-size: 12px;
    }

    .pao-note ul {
        margin-top: 5px;
        padding-left: 20px;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 768px) {
        .filter-row {
            display: block;
        }

        .filter-row .form-group {
            margin-bottom: 10px;
        }

        .filter-date,
        .filter-kebun,
        .filter-product {
            width: 100%;
        }

        .table-search {
            width: 100%;
        }

        .table-toolbar {
            display: block;
        }
    }
</style>


<section class="content-header">
    <h1>
        Persediaan Produk Sampingan
        <small></small>
    </h1>
</section>


<section class="content">

    {{-- =========================================================
         FILTER
         ========================================================= --}}
    <div class="panel panel-default filter-panel">

        <div class="panel-heading">
            <i class="fa fa-filter"></i>
            Filter Data
        </div>

        <div class="panel-body">

            <form
                role="form"
                method="GET"
                action="{{ url('/dashboard/lhpexecutive/lhpByProduct') }}"
            >

                {{-- Menyimpan tab aktif saat filter --}}
                <input
                    type="hidden"
                    name="tab"
                    id="active_tab"
                    value="{{ Request::get('tab') ?: 'harian' }}"
                >

                <div class="filter-row">

                    {{-- DARI TANGGAL --}}
                    <div class="form-group">

                        <label for="dari_tanggal">
                            Dari Tanggal :
                        </label>

                        <div
                            class="input-group date input-inline filter-date"
                        >

                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <input
                                type="text"
                                class="form-control"
                                id="dari_tanggal"
                                name="dari_tanggal"
                                autocomplete="off"
                                value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}"
                            >

                        </div>

                    </div>


                    {{-- SAMPAI TANGGAL --}}
                    <div class="form-group">

                        <label for="sampai_tanggal">
                            Sampai Tanggal :
                        </label>

                        <div
                            class="input-group date input-inline filter-date"
                        >

                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <input
                                type="text"
                                class="form-control"
                                id="sampai_tanggal"
                                name="sampai_tanggal"
                                autocomplete="off"
                                value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}"
                            >

                        </div>

                    </div>


                    {{-- KEBUN --}}
                    <div class="form-group">

                        <label for="selectkebun">
                            Kebun :
                        </label>

                        <select
                            class="form-control filter-kebun"
                            id="selectkebun"
                            name="selectkebun"
                        >

                            <option
                                value="SEMUA"
                                {{ Request::get('selectkebun', '2200') == 'SEMUA' ? 'selected' : '' }}
                            >
                                SEMUA
                            </option>

                            <option
                                value="2200"
                                {{ Request::get('selectkebun', '2200') == '2200' ? 'selected' : '' }}
                            >
                                TELDA
                            </option>

                            <option
                                value="2300"
                                {{ Request::get('selectkebun') == '2300' ? 'selected' : '' }}
                            >
                                KALSA
                            </option>

                            <option
                                value="2400"
                                {{ Request::get('selectkebun') == '2400' ? 'selected' : '' }}
                            >
                                KALDA
                            </option>

                            <option
                                value="2500"
                                {{ Request::get('selectkebun') == '2500' ? 'selected' : '' }}
                            >
                                KOKAR
                            </option>

                            <option
                                value="3200"
                                {{ Request::get('selectkebun') == '3200' ? 'selected' : '' }}
                            >
                                RICKO
                            </option>

                            <option
                                value="5200"
                                {{ Request::get('selectkebun') == '5200' ? 'selected' : '' }}
                            >
                                PASER
                            </option>

                        </select>

                    </div>


                    {{-- PRODUK --}}
                    <div class="form-group">

                        <label for="selectproduct">
                            Produk :
                        </label>

                        <select
                            class="form-control filter-product"
                            id="selectproduct"
                            name="selectproduct"
                        >

                            <option
                                value="SEMUA"
                                {{ Request::get('selectproduct', 'PALM ACID OIL') == 'SEMUA' ? 'selected' : '' }}
                            >
                                SEMUA
                            </option>

                            <option
                                value="PALM ACID OIL"
                                {{ Request::get('selectproduct', 'PALM ACID OIL') == 'PALM ACID OIL' ? 'selected' : '' }}
                            >
                                PALM ACID OIL
                            </option>

                            <option
                                value="Crude Palm Oil (CP1)"
                                {{ Request::get('selectproduct') == 'Crude Palm Oil (CP1)' ? 'selected' : '' }}
                            >
                                Crude Palm Oil (CP1)
                            </option>

                            <option
                                value="Cangkang"
                                {{ Request::get('selectproduct') == 'Cangkang' ? 'selected' : '' }}
                            >
                                Cangkang
                            </option>

                            <option
                                value="Fiber"
                                {{ Request::get('selectproduct') == 'Fiber' ? 'selected' : '' }}
                            >
                                Fiber
                            </option>

                            <option
                                value="Janjangan Kosong"
                                {{ Request::get('selectproduct') == 'Janjangan Kosong' ? 'selected' : '' }}
                            >
                                Janjangan Kosong
                            </option>

                            <option
                                value="Abu"
                                {{ Request::get('selectproduct') == 'Abu' ? 'selected' : '' }}
                            >
                                Abu
                            </option>

                        </select>

                    </div>


                    {{-- BUTTON FILTER --}}
                    <div class="form-group">

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            <i class="fa fa-search"></i>
                            Tampilkan
                        </button>

                    </div>


                    {{-- BUTTON RESET --}}
                    <div class="form-group">

                        <a
                            href="{{ url('/dashboard/lhpexecutive/lhpByProduct') }}"
                            class="btn btn-default"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
         TABLE BOX
         ========================================================= --}}
    <div class="box box-primary">

        <div class="box-header with-border">

            <h3 class="box-title">
                <i class="fa fa-cubes"></i>
                Rekap Persediaan Produk Sampingan
            </h3>

        </div>


        <div class="box-body">

            {{-- =================================================
                 TABS
                 ================================================= --}}
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">

                    <li
                        class="{{ Request::get('tab', 'harian') == 'harian' ? 'active' : '' }}"
                    >
                        <a
                            href="#tab-harian"
                            data-toggle="tab"
                            data-tab="harian"
                        >
                            <i class="fa fa-calendar"></i>
                            Harian
                        </a>
                    </li>

                    <li
                        class="{{ Request::get('tab') == 'bulanan' ? 'active' : '' }}"
                    >
                        <a
                            href="#tab-bulanan"
                            data-toggle="tab"
                            data-tab="bulanan"
                        >
                            <i class="fa fa-calendar-o"></i>
                            Bulanan
                        </a>
                    </li>

                </ul>



                <div class="tab-content">

                    {{-- =========================================
                         TAB HARIAN
                         ========================================= --}}
                    <div
                        class="tab-pane {{ Request::get('tab', 'harian') == 'harian' ? 'active' : '' }}"
                        id="tab-harian"
                    >

                        <div class="table-toolbar">

                            <div class="input-group table-search">

                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>

                                <input
                                    type="text"
                                    id="search-harian"
                                    class="form-control"
                                    placeholder="Cari kebun, produk atau data lainnya..."
                                >

                                <span
                                    class="input-group-addon"
                                    id="clear-search-harian"
                                    style="cursor:pointer;"
                                >
                                    <i class="fa fa-times"></i>
                                </span>

                            </div>

                        </div>


                        <div id="table-harian"></div>

                    </div>



                    {{-- =========================================
                         TAB BULANAN
                         ========================================= --}}
                    <div
                        class="tab-pane {{ Request::get('tab') == 'bulanan' ? 'active' : '' }}"
                        id="tab-bulanan"
                    >

                        <div class="table-toolbar">

                            <div class="input-group table-search">

                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>

                                <input
                                    type="text"
                                    id="search-bulanan"
                                    class="form-control"
                                    placeholder="Cari kebun, produk atau data lainnya..."
                                >

                                <span
                                    class="input-group-addon"
                                    id="clear-search-bulanan"
                                    style="cursor:pointer;"
                                >
                                    <i class="fa fa-times"></i>
                                </span>

                            </div>

                        </div>


                        <div id="table-bulanan"></div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 CATATAN PAO
                 ================================================= --}}
            <div class="pao-note">

                <strong>
                    Catatan Palm Acid Oil:
                </strong>

                Jumlah PAO Max didalam kolam limbah
                (JKT-PEU/SE/00/II/2022):

                <ul>
                    <li>TELDA : 30 TON</li>
                    <li>KALSA : 30 TON</li>
                    <li>KALDA : 50 TON</li>
                    <li>KOKAR : 50 TON</li>
                    <li>RICKO : 30 TON</li>
                    <li>PASER : 50 TON</li>
                </ul>

            </div>

        </div>

    </div>

</section>

@endsection



@section('script-content')

<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script type="text/javascript">

    /*
    |--------------------------------------------------------------------------
    | DATE PICKER
    |--------------------------------------------------------------------------
    */

    setValidationRangeDatePicker(
        'dari_tanggal',
        'sampai_tanggal'
    );


    /*
    |--------------------------------------------------------------------------
    | DATA DARI LARAVEL
    |--------------------------------------------------------------------------
    */

    var dataHarian = @json($lhp_ProdukSampingan);

    var dataBulanan = @json($lhp_ProdukSampinganBulanan);


    /*
    |--------------------------------------------------------------------------
    | MAPPING KEBUN
    |--------------------------------------------------------------------------
    */

    var kebunMap = {
        '2200': 'TELDA',
        '2300': 'KALSA',
        '2400': 'KALDA',
        '2500': 'KOKAR',
        '3200': 'RICKO',
        '5200': 'PASER'
    };


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function getNamaKebun(siteId) {

        if (siteId === null || siteId === undefined) {
            return '';
        }

        var id = String(siteId);

        return kebunMap[id] || id;
    }


    function normalizeNumber(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return 0;
        }

        var number = Number(value);

        return isNaN(number) ? 0 : number;
    }


    function formatNumber(cell) {

        var value = normalizeNumber(cell.getValue());

        return value.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL HELPER
    |--------------------------------------------------------------------------
    |
    | Menghitung total tetapi mengabaikan baris TOTAL dari stored procedure.
    |
    */

    function grandTotal(field) {
        return function(values, data) {
            return data.reduce(
                function(total, row) {

                    if (
                        row.KETERANGAN &&
                        String(
                            row.KETERANGAN
                        ).toUpperCase() === "TOTAL"
                    ) {
                        return total;
                    }

                    return (
                        total
                        + normalizeNumber(
                            row[field]
                        )
                    );
                },
                0
            );
        };
    }


    function grandTotalLabel() {
        return "GRAND TOTAL";
    }


    function grandTotalFormatter(cell) {
        return normalizeNumber(
            cell.getValue()
        ).toLocaleString(
            "id-ID",
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }
        );
    }


    function siteFormatter(cell) {

        return getNamaKebun(cell.getValue());
    }


    function dateFormatter(cell) {

        var value = cell.getValue();

        if (!value) {
            return '';
        }

        /*
         * Mengantisipasi format:
         * 2026-08-31
         * 2026-08-31 00:00:00
         * 2026-08-31T00:00:00
         */

        var dateOnly = String(value).substring(0, 10);

        var parts = dateOnly.split('-');

        if (parts.length === 3) {
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }

        return value;
    }


    function bulanFormatter(cell) {

        var bulan = parseInt(cell.getValue());

        var namaBulan = {
            1:  'Januari',
            2:  'Februari',
            3:  'Maret',
            4:  'April',
            5:  'Mei',
            6:  'Juni',
            7:  'Juli',
            8:  'Agustus',
            9:  'September',
            10: 'Oktober',
            11: 'November',
            12: 'Desember'
        };

        return namaBulan[bulan] || cell.getValue();
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA
    |--------------------------------------------------------------------------
    |
    | Tambahkan NAMA_KEBUN ke object supaya:
    | - dapat ditampilkan pada Tabulator
    | - dapat dicari dari search box
    |
    */

    dataHarian = dataHarian.map(function(row) {

        row.NAMA_KEBUN = getNamaKebun(row.SITE_ID);

        return row;
    });


    dataBulanan = dataBulanan.map(function(row) {

        row.NAMA_KEBUN = getNamaKebun(row.SITE_ID);

        return row;
    });



    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI PAGINATION
    |--------------------------------------------------------------------------
    |
    | Default = All
    |
    */

    var paginationSize = true;

    var paginationSizeSelector = [
        true,
        10,
        25,
        50
    ];


    /*
    |--------------------------------------------------------------------------
    | TABLE HARIAN
    |--------------------------------------------------------------------------
    */

    var tableHarian = new Tabulator("#table-harian", {

        data: dataHarian,

        layout: "fitData",

        responsiveLayout: false,

        pagination: "local",

        paginationSize: paginationSize,

        paginationSizeSelector: paginationSizeSelector,

        paginationCounter: "rows",

        movableColumns: false,
        columnDefaults: {
            headerSort:
                false,

            minWidth:
                60
        },

        placeholder: "Tidak ada data.",

        rowFormatter: function(row) {

            var data = row.getData();

            /*
             * Baris TOTAL dari stored procedure
             */
            if (
                data.KETERANGAN &&
                String(data.KETERANGAN).toUpperCase() === 'TOTAL'
            ) {
                row.getElement().classList.add('row-total');
            }


            /*
             * Warning Palm Acid Oil
             *
             * Aturan dari Blade lama:
             * PRODUK = PALM ACID OIL
             * SALDOAKHIR > TONASE_MAX
             */
            if (
                data.PRODUK &&
                String(data.PRODUK).toUpperCase() === 'PALM ACID OIL' &&
                normalizeNumber(data.SALDOAKHIR) >
                    normalizeNumber(data.TONASE_MAX)
            ) {
                row.getElement().classList.add('row-pao-warning');
            }
        },


        columns: [

            {
                title: "TGL",
                field: "TGL",
                formatter: dateFormatter,
                hozAlign: "center",
                headerHozAlign: "center"
            },

            {
                title: "KEBUN",
                field: "NAMA_KEBUN",
                headerHozAlign: "center",
                bottomCalc:grandTotalLabel  
            },

            {
                title: "PRODUK",
                field: "PRODUK",
                headerHozAlign: "center"
            },

            {
                title: "SALDO<br>AWAL<br>(KG)",
                field: "SALDOAWAL",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            },

            {
                title: "MASUK<br>/ DITERIMA<br>(KG)",
                field: "MASUK",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            },

            {
                title: "KELUAR",
                headerHozAlign: "center",

                columns: [

                    {
                        title: "DIPAKAI<br>(KG)",
                        field: "PAKAI",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "KIRIM<br>JUAL<br>(KG)",
                        field: "KIRIM_JUAL",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "TRANSFER<br>OUT<br>(KG)",
                        field: "TRANSFER_OUT",  
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "TOTAL<br>(KG)",
                        field: "TOTAL",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    }

                ]
            },

            {
                title: "SALDO<br>AKHIR<br>(KG)",
                field: "SALDOAKHIR",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            }

        ]

    });



    /*
    |--------------------------------------------------------------------------
    | TABLE BULANAN
    |--------------------------------------------------------------------------
    */

    var tableBulanan = new Tabulator("#table-bulanan", {

        data: dataBulanan,

        layout: "fitData",

        responsiveLayout: false,

        pagination: "local",

        paginationSize: paginationSize,

        paginationSizeSelector: paginationSizeSelector,

        paginationCounter: "rows",
        columnDefaults: {
            headerSort:
                false,

            minWidth:
                60
        },

        movableColumns: false,

        placeholder: "Tidak ada data.",


        columns: [

            {
                title: "TAHUN",
                field: "TAHUN",
                hozAlign: "center",
                headerHozAlign: "center",
                sorter: "number"
            },

            {
                title: "BULAN",
                field: "BULAN",
                formatter: bulanFormatter,
                hozAlign: "center",
                headerHozAlign: "center",
                sorter: "number"
            },

            {
                title: "KEBUN",
                field: "NAMA_KEBUN",
                headerHozAlign: "center",
                bottomCalc:grandTotalLabel
            },

            {
                title: "PRODUCT CODE",
                field: "PRODUCTCODE",
                hozAlign: "center",
                headerHozAlign: "center",
                visible: false
            },

            {
                title: "PRODUK",
                field: "PRODUK",
                headerHozAlign: "center"
            },

            {
                title: "SALDO<br>AWAL<br>(KG)",
                field: "SALDOAWAL",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            },

            {
                title: "MASUK /<br> DITERIMA<br>(KG)",
                field: "MASUK",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            },

            {
                title: "KELUAR",
                headerHozAlign: "center",

                columns: [

                    {
                        title: "DIPAKAI<br>(KG)",
                        field: "PAKAI",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "KIRIM<br>JUAL<br>(KG)",
                        field: "KIRIM_JUAL",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "TRANSFER<br>OUT<br>(KG)",
                        field: "TRANSFER_OUT",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "KIRIM<br>INTERNAL<br>(KG)",
                        field: "KIRIM_INTERNAL",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    },

                    {
                        title: "TOTAL (KG)",
                        field: "TOTAL",
                        formatter: formatNumber,
                        hozAlign: "right",
                        headerHozAlign: "center",
                        sorter: "number",
                        bottomCalcFormatter:grandTotalFormatter
                    }

                ]
            },

            {
                title: "SALDO<br>AKHIR<br>(KG)",
                field: "SALDOAKHIR",
                formatter: formatNumber,
                hozAlign: "right",
                headerHozAlign: "center",
                sorter: "number",
                bottomCalcFormatter:grandTotalFormatter
            }

        ]

    });



    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    function applyGlobalSearch(table, keyword) {

        keyword = String(keyword || '')
            .trim()
            .toLowerCase();


        if (keyword === '') {

            table.clearFilter();

            return;
        }


        table.setFilter(function(data) {

            for (var key in data) {

                if (!data.hasOwnProperty(key)) {
                    continue;
                }

                var value = data[key];

                if (
                    value !== null &&
                    value !== undefined &&
                    String(value)
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
    | SEARCH HARIAN
    |--------------------------------------------------------------------------
    */

    $('#search-harian').on('keyup input', function() {

        applyGlobalSearch(
            tableHarian,
            $(this).val()
        );

    });


    $('#clear-search-harian').on('click', function() {

        $('#search-harian').val('');

        tableHarian.clearFilter();

        $('#search-harian').focus();

    });



    /*
    |--------------------------------------------------------------------------
    | SEARCH BULANAN
    |--------------------------------------------------------------------------
    */

    $('#search-bulanan').on('keyup input', function() {

        applyGlobalSearch(
            tableBulanan,
            $(this).val()
        );

    });


    $('#clear-search-bulanan').on('click', function() {

        $('#search-bulanan').val('');

        tableBulanan.clearFilter();

        $('#search-bulanan').focus();

    });



    /*
    |--------------------------------------------------------------------------
    | TAB HANDLER
    |--------------------------------------------------------------------------
    |
    | Tabulator yang berada pada hidden Bootstrap tab kadang belum menghitung
    | ukuran kolom dengan sempurna.
    |
    | Saat tab dibuka, redraw tabel.
    |
    */

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

        var tab = $(e.target).data('tab');

        $('#active_tab').val(tab);


        if (tab === 'harian') {

            setTimeout(function() {

                tableHarian.redraw(true);

            }, 100);

        }


        if (tab === 'bulanan') {

            setTimeout(function() {

                tableBulanan.redraw(true);

            }, 100);

        }

    });



    /*
    |--------------------------------------------------------------------------
    | INITIAL DRAW
    |--------------------------------------------------------------------------
    */

    $(document).ready(function() {

        var activeTab = $('#active_tab').val();


        if (activeTab === 'bulanan') {

            setTimeout(function() {

                tableBulanan.redraw(true);

            }, 150);

        }
        else {

            setTimeout(function() {

                tableHarian.redraw(true);

            }, 150);

        }

    });

</script>

@endsection
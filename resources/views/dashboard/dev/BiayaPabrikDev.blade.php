@extends('dashboard.app')
@section('header-title')
    Biaya Pabrik
@endsection
@section('main-content')
<link
    rel="stylesheet"
    href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css"
>
<style>
    .filter-panel {
        margin-bottom: 15px;
    }
    .filter-panel .form-group {
        margin-right: 10px;
        margin-bottom: 10px;
        vertical-align: top;
    }
    .site-dropdown {
        position: relative;
        display: inline-block;
    }
    .site-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1050;
        width: 300px;
        max-height: 340px;
        overflow-y: auto;
        padding: 10px;
        margin-top: 2px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    }
    .site-dropdown.open .site-dropdown-menu {
        display: block;
    }
    .site-dropdown-menu label {
        display: block;
        padding: 5px 7px;
        margin: 0;
        font-weight: normal;
        cursor: pointer;
    }
    .site-dropdown-menu label:hover {
        background: #f4f4f4;
    }
    .site-dropdown-actions {
        display: flex;
        justify-content: space-between;
        padding-bottom: 8px;
        margin-bottom: 5px;
        border-bottom: 1px solid #eeeeee;
    }
    .period-information {
        margin-bottom: 15px;
        border: 1px solid #d2d6de;
    }
    .period-information td {
        padding: 5px 10px;
        border: 1px solid #d2d6de;
    }
    .period-information .period-label {
        width: 160px;
        font-weight: 600;
        background: #f4f4f4;
    }
    .period-information .period-value {
        min-width: 250px;
        background: #ffff66;
    }
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }
    .table-toolbar-left,
    .table-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-search {
        width: 250px;
    }
    .produksi-tabs {
        margin-bottom: 15px;
    }
    .produksi-tabs > li > a {
        font-weight: 600;
        cursor: pointer;
    }
    .produksi-tabs > li.active > a,
    .produksi-tabs > li.active > a:hover,
    .produksi-tabs > li.active > a:focus {
        color: #ffffff;
        background: #3c8dbc;
        border-color: #3c8dbc;
    }
    #produksi-tbs-table {
        width: 100%;
        min-height: 250px;
    }
    #produksi-tbs-table .tabulator {
        font-size: 12px;
        border: 1px solid #d2d6de;
    }
    #produksi-tbs-table .tabulator-header {
        font-weight: 600;
        color: #333333;
        background: #f4f4f4;
        border-bottom: 1px solid #d2d6de;
    }
    #produksi-tbs-table .tabulator-header .tabulator-col {
        background: #f4f4f4;
        border-right: 1px solid #d2d6de;
    }
    #produksi-tbs-table .tabulator-header .tabulator-col-group {
        background: #f4f4f4;
    }
    #produksi-tbs-table
        .tabulator-header
        .tabulator-col-group
        > .tabulator-col-content {
        background: #f4f4f4;
    }
    #produksi-tbs-table .tabulator-col-title {
        white-space: normal;
        line-height: 1.2;
    }
    #produksi-tbs-table .tabulator-row .tabulator-cell {
        border-right: 1px solid #dddddd;
    }
    #produksi-tbs-table .tabulator-calcs-holder {
        font-weight: bold;
        background: #ecf0f5;
    }
    #produksi-tbs-table .tabulator-calcs-holder .tabulator-row {
        background: #ecf0f5;
    }
    #produksi-tbs-table .variance-positive {
        color: #008000;
        font-weight: 600;
    }
    #produksi-tbs-table .variance-negative {
        color: #d73925;
        font-weight: 600;
    }
    .period-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 24px;
        padding: 9px 15px;
        margin-bottom: 10px;
        font-size: 13px;
        background: #f8f9fa;
        border: 1px solid #d2d6de;
        border-radius: 3px;
    }
    .period-summary span {
        white-space: nowrap;
    }
    .period-summary strong {
        margin-right: 4px;
    }
    #produksi-tbs-table
    .tabulator-group
    .tabulator-calcs-holder
    .tabulator-cell {
        background-color: #f5f5f5 !important;
        font-weight: 600;
    }
    #produksi-tbs-table
    .tabulator-group
    .tabulator-calcs-holder {
        border-top: 1px solid #cccccc;
        border-bottom: 2px solid #b8b8b8;
    }
    #produksi-tbs-table
    .tabulator-footer
    .tabulator-calcs-holder {
        background-color: #d9edf7;
        font-weight: bold;
    }
    #produksi-tbs-table .tabulator-group {
        padding: 5px 8px;
        background-color: #eef4f8;
        border-top: 1px solid #d2d6de;
        border-bottom: 1px solid #d2d6de;
        font-size: 12px;
    }
    /* *SUBTOTAL per kebun* */
    #produksi-tbs-table
    .tabulator-group
    .tabulator-calcs-bottom {
        background-color: #eeeeee !important;
        font-weight: 600;
    }
    /* *GRAND TOTAL* */
    #produksi-tbs-table
    .tabulator-footer
    .tabulator-calcs-bottom {
        background-color: #d9edf7 !important;
        font-weight: 700;
        border-top: 2px solid #3c8dbc;
    }

    #produksi-tbs-table
    .tabulator-group:not(.tabulator-group-visible)
    + .tabulator-calcs-holder {
        background-color: #f3f3f3;
        border-bottom: 2px solid #b8b8b8;
    }

    #produksi-tbs-table
    .tabulator-group:not(.tabulator-group-visible)
    + .tabulator-calcs-holder
    .tabulator-cell {
        font-weight: 700;
    }
    @media (max-width: 767px) {
        .period-summary {
            display: block;
        }
        .period-summary span {
            display: block;
            margin-bottom: 5px;
            white-space: normal;
        }
        .period-summary span:last-child {
            margin-bottom: 0;
        }
    }
    @media (max-width: 767px) {
        .table-toolbar {
            display: block;
        }
        .table-toolbar-left,
        .table-toolbar-right {
            margin-bottom: 8px;
        }
        .table-search {
            width: 100%;
        }
        .site-dropdown-menu {
            width: 270px;
        }
    }
</style>
<section class="content-header">
    <h1>
        Biaya Pabrik DEV
        <small>Development</small>
    </h1>
</section>
<section class="content">
    <div class="panel panel-default filter-panel">
        <div class="panel-body">
            <form
                id="filter-form"
                method="GET"
                action="{{ route('dev.biaya-pabrik') }}"
                class="form-inline"
            >
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input
                        type="number"
                        class="form-control"
                        id="tahun"
                        name="tahun"
                        min="2000"
                        max="2100"
                        value="{{ $tahun }}"
                        style="width: 100px;"
                    >
                </div>
                <div class="form-group">
                    <label for="bulan">Bulan</label>
                    <select
                        class="form-control"
                        id="bulan"
                        name="bulan"
                        style="width: 150px;"
                    >
                        @foreach($namaBulan as $nomorBulan => $nama)
                            <option
                                value="{{ $nomorBulan }}"
                                {{ (int) $bulan === (int) $nomorBulan ? 'selected' : '' }}
                            >
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kebun</label>
                    <div class="site-dropdown" id="site-dropdown">
                        <button
                            type="button"
                            class="btn btn-default"
                            id="site-dropdown-button"
                            style="min-width: 220px; text-align: left;"
                        >
                            <span id="site-dropdown-label">Semua Kebun</span>
                            <span class="caret pull-right" style="margin-top: 8px;"></span>
                        </button>
                        <div class="site-dropdown-menu">
                            <div class="site-dropdown-actions">
                                <button
                                    type="button"
                                    class="btn btn-xs btn-primary"
                                    id="select-all-sites"
                                >
                                    Pilih Semua
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-xs btn-default"
                                    id="clear-all-sites"
                                >
                                    Semua Kebun
                                </button>
                            </div>
                            @foreach($siteOptions as $siteId => $siteName)
                                <label>
                                    <input
                                        type="checkbox"
                                        name="site_id[]"
                                        class="site-checkbox"
                                        value="{{ $siteId }}"
                                        data-site-name="{{ $siteName }}"
                                        {{ in_array((string) $siteId, array_map('strval', $selectedSites), true) ? 'checked' : '' }}
                                    >
                                    <strong>{{ $siteName }}</strong>
                                    <small class="text-muted">({{ $siteId }})</small>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                        Tampilkan
                    </button>
                    <a
                        href="{{ route('dev.biaya-pabrik') }}"
                        class="btn btn-default"
                    >
                        <i class="fa fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="period-summary">
        <span>
            <strong>Periode:</strong>
            Bulanan
        </span>
        <span>
            <strong>Tahun:</strong>
            {{ $tahun }}
        </span>
        <span>
            <strong>Bulan:</strong>
            {{ $namaBulan[$bulan] ?? '' }}
        </span>
        <span>
            <strong>Kebun:</strong>
            @if(count($selectedSiteNames) === 0)
                Semua Kebun
            @else
                {{ implode(', ', $selectedSiteNames) }}
            @endif
        </span>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs produksi-tabs" id="produksi-tabs">
                <li class="active">
                    <a href="#" data-view="REGION">
                        PER WILAYAH
                    </a>
                </li>
                <li>
                    <a href="#" data-view="KEBUN">
                        PER KEBUN
                    </a>
                </li>
                <li>
                    <a href="#" data-view="INTI">
                        INTI
                    </a>
                </li>
                <li>
                    <a href="#" data-view="MITRA">
                        MITRA
                    </a>
                </li>
            </ul>
            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <label for="page-size" style="margin: 0;">
                        Tampilkan
                    </label>
                    <select
                        id="page-size"
                        class="form-control input-sm"
                        style="width: 85px;"
                    >
                        <option value="all" selected>All</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>data</span>
                </div>
                <div
                    style="
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        margin-left: 12px;
                    "
                >
                    <strong>Satuan:</strong>

                    <label
                        style="
                            margin: 0;
                            font-weight: normal;
                            cursor: pointer;
                        "
                    >
                        <input
                            type="radio"
                            name="unit-mode"
                            id="unit-kg"
                            value="KG"
                        >
                        KG
                    </label>

                    <label
                        style="
                            margin: 0;
                            font-weight: normal;
                            cursor: pointer;
                        "
                    >
                        <input
                            type="radio"
                            name="unit-mode"
                            id="unit-ton"
                            value="TON"
                            checked
                        >
                        TON
                    </label>
                </div>
                <div class="table-toolbar-right">
                    <button
                        type="button"
                        id="export-excel"
                        class="btn btn-success btn-sm"
                    >
                        <i class="fa fa-file-excel-o"></i>
                        Export Excel
                    </button>

                    <label for="table-search" style="margin: 0;">
                        Pencarian
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input
                            type="text"
                            id="table-search"
                            class="form-control input-sm table-search"
                            placeholder="Cari kebun, site, grup..."
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
            <div id="produksi-tbs-table"></div>
        </div>
        
        <div
            style="margin-top: 12px;"
        >
            <button
                type="button"
                class="btn btn-default btn-xs"
                data-toggle="collapse"
                data-target="#tbs-calculation-notes"
                aria-expanded="false"
                aria-controls="tbs-calculation-notes"
            >
                <i class="fa fa-info-circle"></i>
                    Notes.
                <i class="fa fa-angle-down"></i>
            </button>

            <div
                id="tbs-calculation-notes"
                class="collapse"
                style="margin-top: 10px;"
            >
                <div
                    class="well well-sm"
                    style="margin-bottom: 0;"
                >
                    <div>
                        - Var YTD (%) =
                        YTD Tahun Ini / YTD Tahun Lalu.
                    </div>

                    <div>
                        - Var Bulan ini (%) =
                        Aktual / Budget.
                    </div>

                    <div>
                        - Var YTD Budget (%) =
                        Aktual YTD / Budget YTD.
                    </div>

                    <div>
                        - ACH (%) =
                        Aktual YTD / Budget Full Year.
                    </div>

                    <div>
                        - >=95  : Hijau , < 95 : merah.
                    </div>

                    <div style="margin-top: 5px;">
                        AKT = AKTUAL,
                        VAR = VARIAN,
                        ACH = ACHIEVEMENT,
                        BUD = BUDGET
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = @json($dataProduksi);
    var regionData = @json($dataRegion ?? []);
    var activeView ="REGION";
    var searchKeyword = "";
    var selectedPageSize ="all";
    var useTonMode = true;
    var kgFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
    var tonFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
    var percentageFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    function toNumber(value) {
        var result = Number(value);
        return isNaN(result)
            ? 0
            : result;
    }

    function prepareAchievement(rows) {
        rows.forEach(function (row) {
            var actualYtd =
                toNumber(
                    row.PRODUKSI_TBS_AKTUAL_YTD
                );

            var annualBudget =
                toNumber(
                    row.ANUALBUDGET
                );

            row.ACH_FULL_YEAR =
                annualBudget === 0
                    ? 0
                    : (
                        actualYtd
                        / annualBudget
                    ) * 100;
        });
    }
    prepareAchievement(tableData);
    prepareAchievement(regionData);

    function getDisplayTonnage(value) {
        var number = toNumber(value);
        if (useTonMode) {
            return Math.round(number / 1000);
        }
        return number;
    }
    function formatTonnage(value) {
        var convertedValue = getDisplayTonnage(value);
        if (useTonMode) {
            return tonFormatter.format(convertedValue);
        }
        return kgFormatter.format(convertedValue);
    }
    function sumField(data, field) {
        return data.reduce(function (total, row) {
            return total + toNumber(row[field]);
        }, 0);
    }
    function calculateVariance(actualField, comparisonField) {
        return function (values, data) {
            var actual = sumField(data, actualField);
            var comparison = sumField(data, comparisonField);
            if (comparison === 0) {
                return 0;
            }
            //return ((actual / comparison) - 1) * 100;
            return ((actual / comparison)) * 100;
        };
    }

    function calculateAchievement(actualField, budgetField) {
        return function (values, data) {
            var actual =
                sumField(
                    data,
                    actualField
                );

            var budget =
                sumField(
                    data,
                    budgetField
                );

            if (budget === 0) {
                return 0;
            }

            return (actual / budget) * 100;
        };
    }

    function numberCellFormatter(cell) {
        return formatTonnage(
            cell.getValue()
        );
    }
    function percentageCellFormatter(cell) {
        var value = toNumber(cell.getValue());
        var element = cell.getElement();
        element.classList.remove(
            "variance-positive",
            "variance-negative"
        );
        if (value >= 95) {
            element.classList.add("variance-positive");
        } else if (value < 95) {
            element.classList.add("variance-negative");
        }
        return percentageFormatter.format(value) + "%";
    }

    function achievementFormatter(cell) {
        var value =
            toNumber(
                cell.getValue()
            );

        var element =
            cell.getElement();

        element.classList.remove(
            "variance-positive",
            "variance-negative"
        );

        if (value >= 95) {
            element.classList.add(
                "variance-positive"
            );
        } else {
            element.classList.add(
                "variance-negative"
            );
        }

        return (
            percentageFormatter.format(value)
            + "%"
        );
    }
    function numberBottomFormatter(cell) {
        /*
         * bottomCalc tetap menghitung dari data asli KG.
         * Formatter ini hanya mengubah tampilannya menjadi KG / TON.
         */
        return formatTonnage(
            cell.getValue()
        );
    }

    function exportTonnageAccessor(value) {
        var number =
            toNumber(value);

        if (useTonMode) {
            return Math.round(
                number / 1000
            );
        }

        return number;
    }

    function exportPercentageAccessor(value) {
        return (
            percentageFormatter.format(
                toNumber(value)
            )
            + "%"
        );
    }

    function percentageBottomFormatter(cell) {
        var value = toNumber(
            cell.getValue()
        );

        var element = cell.getElement();

        element.classList.remove(
            "variance-positive",
            "variance-negative"
        );

        if (value >= 95) {
            element.classList.add(
                "variance-positive"
            );
        } else if (value < 95) {
            element.classList.add(
                "variance-negative"
            );
        }

        return percentageFormatter.format(value) + "%";
    }

    var table = null;

    function buildTable() {
        if (table) {
            table.destroy();
        }

        var isRegionView =
            activeView === "REGION";

        var currentData =
            isRegionView
                ? regionData
                : tableData;

        var labelTitle =
            isRegionView
                ? "WILAYAH"
                : "KET. KEBUN";


        var tableOptions = {
            data:
                currentData,

            layout:
                "fitData",

            height:
                "560px",

            placeholder:
                "Data produksi TBS tidak ditemukan.",


            /*
            |--------------------------------------------------------------------------
            | Default seluruh kolom
            |--------------------------------------------------------------------------
            */

            columnDefaults: {
                headerSort:
                    false,

                minWidth:
                    60
            },


            /*
            |--------------------------------------------------------------------------
            | Download
            |--------------------------------------------------------------------------
            */

            downloadConfig: {
                columnHeaders:
                    true,

                columnGroups:
                    true,

                rowGroups:
                    true,

                columnCalcs:
                    true,

                dataTree:
                    false
            },


            movableColumns:
                false,

            resizableColumns:
                true,

            columnHeaderVertAlign:
                "middle",


            /*
            |--------------------------------------------------------------------------
            | Tetap sort berdasarkan INDEX,
            | walaupun INDEX tidak ditampilkan.
            |--------------------------------------------------------------------------
            */

            initialSort: [
                {
                    column:
                        "INDEX",

                    dir:
                        "asc"
                }
            ],


            /*
            |--------------------------------------------------------------------------
            | Columns
            |--------------------------------------------------------------------------
            */

            columns: [
                /*
                |--------------------------------------------------------------------------
                | INDEX - hidden
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        "INDEX",

                    field:
                        "INDEX",

                    visible:
                        false
                },


                /*
                |--------------------------------------------------------------------------
                | TAHUN LALU YTD
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        "TAHUN LALU (YTD)",

                    headerHozAlign:
                        "center",

                    columns: [
                        {
                            title:
                                "AKT",

                            field:
                                "PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "VAR (%)",

                            field:
                                "VARIAN_TAHUNLALU",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                percentageCellFormatter,

                            accessorDownload:
                                exportPercentageAccessor,

                            bottomCalc:
                                calculateVariance(
                                    "PRODUKSI_TBS_AKTUAL_YTD",
                                    "PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU"
                                ),

                            bottomCalcFormatter:
                                percentageBottomFormatter
                        }
                    ]
                },


                /*
                |--------------------------------------------------------------------------
                | WILAYAH / KET. KEBUN
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        labelTitle,

                    field:
                        "KET_KEBUN",

                    frozen:
                        true,

                    headerHozAlign:
                        "center",

                    minWidth:
                        100,

                    bottomCalc:
                        function () {
                            return "SUBTOTAL";
                        }
                },


                /*
                |--------------------------------------------------------------------------
                | BULAN INI
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        "BULAN INI",

                    headerHozAlign:
                        "center",

                    columns: [
                        {
                            title:
                                "AKT",

                            field:
                                "PRODUKSI_TBS_AKTUAL_BULAN_INI",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "BUD",

                            field:
                                "MONTHLYBUDGET",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "VAR (%)",

                            field:
                                "VARIAN_TAHUN_INI",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                percentageCellFormatter,

                            accessorDownload:
                                exportPercentageAccessor,

                            bottomCalc:
                                calculateVariance(
                                    "PRODUKSI_TBS_AKTUAL_BULAN_INI",
                                    "MONTHLYBUDGET"
                                ),

                            bottomCalcFormatter:
                                percentageBottomFormatter
                        }
                    ]
                },


                /*
                |--------------------------------------------------------------------------
                | SAMPAI DENGAN YTD
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        "SAMPAI DENGAN (YTD)",

                    headerHozAlign:
                        "center",

                    columns: [
                        {
                            title:
                                "AKT",

                            field:
                                "PRODUKSI_TBS_AKTUAL_YTD",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "BUD",

                            field:
                                "BUDGETYTD",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "VAR (%)",

                            field:
                                "VARIAN_YTD",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                percentageCellFormatter,

                            accessorDownload:
                                exportPercentageAccessor,

                            bottomCalc:
                                calculateVariance(
                                    "PRODUKSI_TBS_AKTUAL_YTD",
                                    "BUDGETYTD"
                                ),

                            bottomCalcFormatter:
                                percentageBottomFormatter
                        }
                    ]
                },


                /*
                |--------------------------------------------------------------------------
                | BUDGET FULL YEAR
                |--------------------------------------------------------------------------
                */

                {
                    title:
                        "BUD FULL YEAR",

                    headerHozAlign:
                        "center",

                    columns: [
                        {
                            title:
                                "BUD",

                            field:
                                "ANUALBUDGET",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                numberCellFormatter,

                            accessorDownload:
                                exportTonnageAccessor,

                            bottomCalc:
                                "sum",

                            bottomCalcFormatter:
                                numberBottomFormatter
                        },

                        {
                            title:
                                "ACH (%)",

                            field:
                                "ACH_FULL_YEAR",

                            hozAlign:
                                "right",

                            headerHozAlign:
                                "center",

                            formatter:
                                achievementFormatter,

                            accessorDownload:
                                exportPercentageAccessor,

                            bottomCalc:
                                calculateAchievement(
                                    "PRODUKSI_TBS_AKTUAL_YTD",
                                    "ANUALBUDGET"
                                ),

                            bottomCalcFormatter:
                                achievementFormatter
                        }
                    ]
                }
            ]
        };


        /*
        |--------------------------------------------------------------------------
        | Grouping
        |--------------------------------------------------------------------------
        |
        | PER WILAYAH tidak menggunakan grouping.
        |
        | PER KEBUN / INTI / MITRA menggunakan SITE_ID.
        |
        */

        if (!isRegionView) {
            tableOptions.columnCalcs =
                "both";

            tableOptions.groupBy =
                "SITE_ID";

            tableOptions.groupStartOpen =
                true;

            tableOptions.groupClosedShowCalcs =
                true;

            tableOptions.groupHeader =
                function (
                    value,
                    count
                ) {
                    var siteNames = {
                        "2200":
                            "TELDA",

                        "2300":
                            "KALSA",

                        "2400":
                            "KALDA",

                        "2500":
                            "KOKAR",

                        "2600":
                            "MITRA KOKAR",

                        "3200":
                            "RICKO",

                        "4200":
                            "MUARA",

                        "5200":
                            "PASER",

                        "6200":
                            "LANGGAI"
                    };

                    var namaKebun =
                        siteNames[
                            String(value)
                        ]
                        || String(value);

                    return (
                        "<strong>"
                        + namaKebun
                        + "</strong>"
                        + " <span style='"
                        + "color:#999;"
                        + "font-weight:normal;"
                        + "'>"
                        + count
                        + " data"
                        + "</span>"
                    );
                };
        }


        /*
        |--------------------------------------------------------------------------
        | Build
        |--------------------------------------------------------------------------
        */

        table =
            new Tabulator(
                "#produksi-tbs-table",
                tableOptions
            );

        attachCalculationEvents();

        return table;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Label Calculation Rows
    |--------------------------------------------------------------------------
    |
    | Semua bottomCalc KET_KEBUN menghasilkan "SUBTOTAL".
    | Calculation row pada footer utama tabel kemudian diganti menjadi
    | "GRAND TOTAL". Cara ini tidak bergantung pada struktur DOM group.
    |
    */
    function updateCalculationLabels() {
        var tableElement = document.getElementById("produksi-tbs-table");
        if (!tableElement) {
            return;
        }
        var footerCalcRows = tableElement.querySelectorAll(
            ".tabulator-footer .tabulator-calcs-bottom"
        );
        footerCalcRows.forEach(function (calcRow) {
            var cells = calcRow.querySelectorAll(".tabulator-cell");
            cells.forEach(function (cell) {
                if (cell.textContent.trim() === "SUBTOTAL") {
                    cell.textContent = "GRAND TOTAL";
                }
            });
        });
    }
    function attachCalculationEvents() {
        table.on("tableBuilt", function () {
            setTimeout(updateCalculationLabels, 0);
        });

        table.on("dataProcessed", function () {
            setTimeout(updateCalculationLabels, 0);
        });

        table.on("renderComplete", function () {
            setTimeout(updateCalculationLabels, 0);
        });

        table.on("pageLoaded", function () {
            setTimeout(updateCalculationLabels, 0);
        });

        setTimeout(updateCalculationLabels, 50);
    }

    function applyTableFilter() {
        var keyword =
            searchKeyword.toLowerCase();

        table.setFilter(function (data) {

            /*
            |--------------------------------------------------------------------------
            | Filter tab
            |--------------------------------------------------------------------------
            */

            if (activeView === "INTI") {
                if (
                    String(
                        data.GRUP || ""
                    ).toUpperCase()
                    !== "INTI"
                ) {
                    return false;
                }
            }

            if (activeView === "MITRA") {
                if (
                    String(
                        data.GRUP || ""
                    ).toUpperCase()
                    !== "MITRA"
                ) {
                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            if (keyword === "") {
                return true;
            }

            var searchableText;

            if (
                activeView === "REGION"
            ) {
                searchableText = [
                    data.INDEX,
                    data.REGION,
                    data.KET_KEBUN
                ]
                    .join(" ")
                    .toLowerCase();

            } else {
                searchableText = [
                    data.INDEX,
                    data.SITE_ID,
                    data.DIVISIONCODE,
                    data.DIVISIONNAME,
                    data.KET_KEBUN,
                    data.GRUP
                ]
                    .join(" ")
                    .toLowerCase();
            }

            return (
                searchableText
                    .indexOf(keyword)
                !== -1
            );
        });


        table.setPage(1);

        applyPageSize();
    }

    function applyPageSize() {
        if (selectedPageSize === "all") {
            var totalActiveRows = table.getDataCount("active");
            table.setPageSize(
                totalActiveRows > 0 ? totalActiveRows : 1
            );
        } else {
            table.setPageSize(parseInt(selectedPageSize, 10));
        }
        table.setPage(1);
    }
    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    |
    | Default tab adalah PER REGION.
    | Generate tabel langsung saat halaman pertama kali dibuka.
    |
    */

    buildTable();

    applyTableFilter();

    /*
    |--------------------------------------------------------------------------
    | Tab Per Region / Per Kebun / Inti / Mitra
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll("#produksi-tabs a")
        .forEach(function (tab) {

            tab.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    /*
                    * Hilangkan active dari semua tab.
                    */
                    document
                        .querySelectorAll(
                            "#produksi-tabs li"
                        )
                        .forEach(function (item) {
                            item.classList.remove(
                                "active"
                            );
                        });


                    /*
                    * Aktifkan tab yang dipilih.
                    */
                    this
                        .parentElement
                        .classList
                        .add("active");


                    /*
                    * REGION
                    * KEBUN
                    * INTI
                    * MITRA
                    */
                    activeView =
                        this.getAttribute(
                            "data-view"
                        );


                    /*
                    * REGION tidak memakai grouping.
                    *
                    * PER KEBUN / INTI / MITRA
                    * memakai grouping SITE_ID.
                    *
                    * Maka tabel perlu dibangun ulang,
                    * tetapi data tidak diambil ulang
                    * dari database.
                    */
                    buildTable();

                    applyTableFilter();
                }
            );
        });

    document
        .getElementById("table-search")
        .addEventListener("input", function () {
            searchKeyword = this.value.trim();
            applyTableFilter();
        });
    document
        .getElementById("page-size")
        .addEventListener("change", function () {
            selectedPageSize = this.value;
            applyPageSize();
        });

    /*
*     * Multi-checkbox site selector.*
*     **/

    var siteDropdown = document.getElementById("site-dropdown");
    var siteDropdownButton = document.getElementById(
        "site-dropdown-button"
    );
    var siteCheckboxes = document.querySelectorAll(".site-checkbox");
    var siteDropdownLabel = document.getElementById(
        "site-dropdown-label"
    );
    function updateSiteDropdownLabel() {
        var checkedSites = Array.prototype.slice
            .call(siteCheckboxes)
            .filter(function (checkbox) {
                return checkbox.checked;
            });
        if (checkedSites.length === 0) {
            siteDropdownLabel.textContent = "Semua Kebun";
            return;
        }
        if (checkedSites.length === 1) {
            siteDropdownLabel.textContent =
                checkedSites[0].getAttribute("data-site-name");
            return;
        }
        siteDropdownLabel.textContent =
            checkedSites.length + " kebun dipilih";
    }
    siteDropdownButton.addEventListener("click", function (event) {
        event.stopPropagation();
        siteDropdown.classList.toggle("open");
    });
    document
        .querySelector(".site-dropdown-menu")
        .addEventListener("click", function (event) {
            event.stopPropagation();
        });
    document.addEventListener("click", function () {
        siteDropdown.classList.remove("open");
    });
    siteCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", updateSiteDropdownLabel);
    });
    document
        .getElementById("select-all-sites")
        .addEventListener("click", function () {
            siteCheckboxes.forEach(function (checkbox) {
                checkbox.checked = true;
            });
            updateSiteDropdownLabel();
        });
    document
        .getElementById("clear-all-sites")
        .addEventListener("click", function () {
            siteCheckboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });
            updateSiteDropdownLabel();
        });
    updateSiteDropdownLabel();

    function getSelectedKebunText() {
        var checkedSites =
            Array.prototype.slice
                .call(siteCheckboxes)
                .filter(
                    function (checkbox) {
                        return checkbox.checked;
                    }
                );

        if (
            checkedSites.length === 0
        ) {
            return "Semua Kebun";
        }

        return checkedSites
            .map(
                function (checkbox) {
                    return checkbox
                        .getAttribute(
                            "data-site-name"
                        );
                }
            )
            .join(", ");
    }


    function shiftWorksheetDown(
        worksheet,
        rowCount
    ) {
        var cells = [];

        Object.keys(worksheet)
            .forEach(
                function (address) {
                    if (
                        address.charAt(0)
                        === "!"
                    ) {
                        return;
                    }

                    cells.push({
                        address:
                            address,

                        cell:
                            worksheet[address]
                    });
                }
            );


        cells.forEach(
            function (item) {
                delete worksheet[
                    item.address
                ];
            }
        );


        cells.forEach(
            function (item) {
                var decoded =
                    XLSX.utils
                        .decode_cell(
                            item.address
                        );

                decoded.r +=
                    rowCount;

                var newAddress =
                    XLSX.utils
                        .encode_cell(
                            decoded
                        );

                worksheet[
                    newAddress
                ] =
                    item.cell;
            }
        );


        if (
            worksheet["!merges"]
        ) {
            worksheet["!merges"]
                .forEach(
                    function (merge) {
                        merge.s.r +=
                            rowCount;

                        merge.e.r +=
                            rowCount;
                    }
                );
        }


        if (
            worksheet["!ref"]
        ) {
            var range =
                XLSX.utils
                    .decode_range(
                        worksheet[
                            "!ref"
                        ]
                    );

            range.s.r +=
                rowCount;

            range.e.r +=
                rowCount;

            worksheet["!ref"] =
                XLSX.utils
                    .encode_range(
                        range
                    );
        }
    }

    document
        .getElementById(
            "export-excel"
        )
        .addEventListener(
            "click",
            function () {

                var unit =
                    useTonMode
                        ? "TON"
                        : "KG";


                /*
                |--------------------------------------------------------------------------
                | Nama tab yang sedang aktif
                |--------------------------------------------------------------------------
                */

                var viewLabel = "";

                if (
                    activeView === "REGION"
                ) {
                    viewLabel =
                        "PER WILAYAH";

                } else if (
                    activeView === "KEBUN"
                ) {
                    viewLabel =
                        "PER KEBUN";

                } else if (
                    activeView === "INTI"
                ) {
                    viewLabel =
                        "INTI";

                } else if (
                    activeView === "MITRA"
                ) {
                    viewLabel =
                        "MITRA";
                }


                var kebunText =
                    getSelectedKebunText();


                var fileView =
                    activeView === "REGION"
                        ? "WILAYAH"
                        : activeView;


                var fileName =
                    "Produksi_TBS_"
                    + fileView
                    + "_"
                    + "{{ $tahun }}"
                    + "_"
                    + "{{ $namaBulan[$bulan] ?? $bulan }}"
                    + "_"
                    + unit
                    + ".xlsx";


                table.download(
                    "xlsx",

                    fileName,

                    {
                        sheetName:
                            "Produksi TBS",

                        documentProcessing:
                            function (
                                workbook
                            ) {

                                var sheetName =
                                    workbook
                                        .SheetNames[0];

                                var worksheet =
                                    workbook
                                        .Sheets[
                                            sheetName
                                        ];


                                /*
                                * Sisakan 9 baris
                                * sebelum tabel.
                                */
                                shiftWorksheetDown(
                                    worksheet,
                                    9
                                );


                                var filterInformation = [
                                    [
                                        "LAPORAN PRODUKSI TBS"
                                    ],

                                    [
                                        "Periode",
                                        "Bulanan"
                                    ],

                                    [
                                        "Tahun",
                                        "{{ $tahun }}"
                                    ],

                                    [
                                        "Bulan",
                                        "{{ $namaBulan[$bulan] ?? $bulan }}"
                                    ],

                                    [
                                        "Kebun",
                                        kebunText
                                    ],

                                    [
                                        "Tampilan",
                                        viewLabel
                                    ],

                                    [
                                        "Satuan",
                                        unit
                                    ],

                                    [
                                        ""
                                    ],

                                    [
                                        ""
                                    ]
                                ];


                                XLSX.utils
                                    .sheet_add_aoa(
                                        worksheet,
                                        filterInformation,
                                        {
                                            origin:
                                                "A1"
                                        }
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Merge judul laporan
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    !worksheet[
                                        "!merges"
                                    ]
                                ) {
                                    worksheet[
                                        "!merges"
                                    ] = [];
                                }

                                worksheet[
                                    "!merges"
                                ].push({
                                    s: {
                                        r: 0,
                                        c: 0
                                    },

                                    e: {
                                        r: 0,
                                        c: 5
                                    }
                                });


                                return workbook;
                            }
                    },

                    "active"
                );
            }
        );

    var unitKgRadio =
        document.getElementById("unit-kg");

    var unitTonRadio =
        document.getElementById("unit-ton");

    function changeUnitMode(unit) {
        useTonMode = unit === "TON";

        /*
        |--------------------------------------------------------------------------
        | Rebuild tabel dari data yang sudah ada di browser
        |--------------------------------------------------------------------------
        |
        | Tidak melakukan request ulang ke server.
        |
        | Rebuild diperlukan supaya formatter pada:
        | - cell biasa
        | - SUBTOTAL per SITE_ID
        | - GRAND TOTAL
        |
        | semuanya dijalankan ulang dengan satuan terbaru.
        |
        */
        buildTable();

        /*
         * Kembalikan kondisi filter/tab/search/page-size yang sedang aktif.
         */
        applyTableFilter();

        setTimeout(function () {
            updateCalculationLabels();
        }, 50);
    }

    unitKgRadio.addEventListener(
        "change",
        function () {
            if (this.checked) {
                changeUnitMode("KG");
            }
        }
    );

    unitTonRadio.addEventListener(
        "change",
        function () {
            if (this.checked) {
                changeUnitMode("TON");
            }
        }
    );

});
</script>
@endsection

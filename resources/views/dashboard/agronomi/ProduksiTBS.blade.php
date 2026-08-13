@extends('dashboard.app')

@section('header-title')
    Produksi TBS
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
        Produksi TBS
        <small>Laporan Bulanan, Budget dan YTD</small>
    </h1>
</section>

<section class="content">

    <div class="panel panel-default filter-panel">
        <div class="panel-body">

            <form
                id="filter-form"
                method="GET"
                action="{{ route('agronomi.produksi-tbs') }}"
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
                        href="{{ route('agronomi.produksi-tbs') }}"
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
                    <a href="#" data-group="SEMUA">
                        KESELURUHAN
                    </a>
                </li>

                <li>
                    <a href="#" data-group="INTI">
                        INTI
                    </a>
                </li>

                <li>
                    <a href="#" data-group="MITRA">
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

                <div class="table-toolbar-right">
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
    </div>

</section>

<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = @json($dataProduksi);

    var activeGroup = "SEMUA";
    var searchKeyword = "";
    var selectedPageSize = "all";

    var numberFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    var percentageFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function toNumber(value) {
        var result = Number(value);

        return isNaN(result) ? 0 : result;
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

            return ((actual / comparison) - 1) * 100;
        };
    }

    function numberCellFormatter(cell) {
        return numberFormatter.format(toNumber(cell.getValue()));
    }

    function percentageCellFormatter(cell) {
        var value = toNumber(cell.getValue());
        var element = cell.getElement();

        element.classList.remove(
            "variance-positive",
            "variance-negative"
        );

        if (value > 0) {
            element.classList.add("variance-positive");
        } else if (value < 0) {
            element.classList.add("variance-negative");
        }

        return percentageFormatter.format(value) + "%";
    }

    function numberBottomFormatter(cell) {
        return numberFormatter.format(toNumber(cell.getValue()));
    }

    function percentageBottomFormatter(cell) {
        return percentageFormatter.format(toNumber(cell.getValue())) + "%";
    }

    var table = new Tabulator("#produksi-tbs-table", {
        data: tableData,
        layout: "fitData",
        height: "560px",
        placeholder: "Data produksi TBS tidak ditemukan.",
        pagination: "local",
        paginationSize: tableData.length > 0 ? tableData.length : 1,
        paginationCounter: "rows",
        movableColumns: false,
        resizableColumns: true,
        columnHeaderVertAlign: "middle",
        initialSort: [
            {
                column: "INDEX",
                dir: "asc"
            }
        ],
        columns: [
            {
                title: "INDEX",
                field: "INDEX",
                hozAlign: "center",
                headerHozAlign: "center",
                width: 75,
                minWidth: 70,
                sorter: "number",
                frozen: true,
                bottomCalc: function () {
                    return "";
                }
            },
            {
                title: "TAHUN LALU",
                headerHozAlign: "center",
                columns: [
                    {
                        title: "AKTUAL",
                        field: "PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 135,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "VARIAN",
                        field: "VARIAN_TAHUNLALU",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 100,
                        formatter: percentageCellFormatter,
                        bottomCalc: calculateVariance(
                            "PRODUKSI_TBS_AKTUAL_BULAN_INI",
                            "PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU"
                        ),
                        bottomCalcFormatter: percentageBottomFormatter
                    }
                ]
            },
            {
                title: "KET. KEBUN",
                field: "KET_KEBUN",
                width: 155,
                minWidth: 140,
                frozen: true,
                headerHozAlign: "center",
                bottomCalc: function () {
                    return "TOTAL";
                }
            },
            {
                title: "BULAN INI",
                headerHozAlign: "center",
                columns: [
                    {
                        title: "AKTUAL",
                        field: "PRODUKSI_TBS_AKTUAL_BULAN_INI",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 135,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "BUDGET",
                        field: "MONTHLYBUDGET",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 135,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "VARIAN (%)",
                        field: "VARIAN_TAHUN_INI",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 105,
                        formatter: percentageCellFormatter,
                        bottomCalc: calculateVariance(
                            "PRODUKSI_TBS_AKTUAL_BULAN_INI",
                            "MONTHLYBUDGET"
                        ),
                        bottomCalcFormatter: percentageBottomFormatter
                    }
                ]
            },
            {
                title: "SAMPAI DENGAN (YTD)",
                headerHozAlign: "center",
                columns: [
                    {
                        title: "AKTUAL",
                        field: "PRODUKSI_TBS_AKTUAL_YTD",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 135,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "BUDGET",
                        field: "BUDGETYTD",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 135,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "VARIAN (%)",
                        field: "VARIAN_YTD",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 105,
                        formatter: percentageCellFormatter,
                        bottomCalc: calculateVariance(
                            "PRODUKSI_TBS_AKTUAL_YTD",
                            "BUDGETYTD"
                        ),
                        bottomCalcFormatter: percentageBottomFormatter
                    }
                ]
            },
            {
                title: "BUDGET FULL YEAR",
                headerHozAlign: "center",
                columns: [
                    {
                        title: "BUDGET",
                        field: "ANUALBUDGET",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 140,
                        formatter: numberCellFormatter,
                        bottomCalc: "sum",
                        bottomCalcFormatter: numberBottomFormatter
                    },
                    {
                        title: "VARIAN",
                        field: "VARIAN_TOTAL",
                        hozAlign: "right",
                        headerHozAlign: "center",
                        width: 105,
                        formatter: percentageCellFormatter,
                        bottomCalc: calculateVariance(
                            "PRODUKSI_TBS_AKTUAL_YTD",
                            "ANUALBUDGET"
                        ),
                        bottomCalcFormatter: percentageBottomFormatter
                    }
                ]
            }
        ]
    });

    function applyTableFilter() {
        var keyword = searchKeyword.toLowerCase();

        table.setFilter(function (data) {
            var groupMatched =
                activeGroup === "SEMUA" ||
                String(data.GRUP || "").toUpperCase() === activeGroup;

            if (!groupMatched) {
                return false;
            }

            if (keyword === "") {
                return true;
            }

            var searchableText = [
                data.INDEX,
                data.SITE_ID,
                data.DIVISIONCODE,
                data.DIVISIONNAME,
                data.KET_KEBUN,
                data.GRUP
            ].join(" ").toLowerCase();

            return searchableText.indexOf(keyword) !== -1;
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

    document.querySelectorAll("#produksi-tabs a").forEach(function (tab) {
        tab.addEventListener("click", function (event) {
            event.preventDefault();

            document
                .querySelectorAll("#produksi-tabs li")
                .forEach(function (item) {
                    item.classList.remove("active");
                });

            this.parentElement.classList.add("active");
            activeGroup = this.getAttribute("data-group");

            applyTableFilter();
        });
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
     * Multi-checkbox site selector.
     */
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
});
</script>

@endsection
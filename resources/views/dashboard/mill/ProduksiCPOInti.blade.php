@extends('dashboard.app')

@section('header-title')
    Produksi CPO & Inti
@endsection

@section('main-content')

<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">

<style>
    .filter-panel { margin-bottom: 15px; }
    .filter-panel .form-group { margin-right: 10px; margin-bottom: 10px; vertical-align: top; }
    .site-dropdown { position: relative; display: inline-block; }
    .site-dropdown-menu {
        display: none; position: absolute; top: 100%; left: 0; z-index: 1050;
        width: 300px; max-height: 340px; overflow-y: auto; padding: 10px; margin-top: 2px;
        background: #fff; border: 1px solid #d2d6de; border-radius: 3px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    .site-dropdown.open .site-dropdown-menu { display: block; }
    .site-dropdown-menu label { display: block; padding: 5px 7px; margin: 0; font-weight: normal; cursor: pointer; }
    .site-dropdown-menu label:hover { background: #f4f4f4; }
    .site-dropdown-actions { display: flex; justify-content: space-between; padding-bottom: 8px; margin-bottom: 5px; border-bottom: 1px solid #eee; }

    .period-summary {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px 24px;
        padding: 9px 15px; margin-bottom: 10px; font-size: 13px;
        background: #f8f9fa; border: 1px solid #d2d6de; border-radius: 3px;
    }
    .period-summary span { white-space: nowrap; }
    .period-summary strong { margin-right: 4px; }

    .produksi-tabs { margin-bottom: 15px; }
    .produksi-tabs > li > a { font-weight: 600; cursor: pointer; }
    .produksi-tabs > li.active > a,
    .produksi-tabs > li.active > a:hover,
    .produksi-tabs > li.active > a:focus {
        color: #fff; background: #3c8dbc; border-color: #3c8dbc;
    }

    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; margin-bottom: 10px;
    }
    .table-toolbar-left, .table-toolbar-right { display: flex; align-items: center; gap: 8px; }
    .table-search { width: 250px; }

    #produksi-cpo-inti-table { width: 100%; min-height: 250px; }
    #produksi-cpo-inti-table .tabulator { font-size: 12px; border: 1px solid #d2d6de; }
    #produksi-cpo-inti-table .tabulator-header { font-weight: 600; color: #333; background: #f4f4f4; border-bottom: 1px solid #d2d6de; }
    #produksi-cpo-inti-table .tabulator-header .tabulator-col,
    #produksi-cpo-inti-table .tabulator-header .tabulator-col-group { background: #f4f4f4; }
    #produksi-cpo-inti-table .tabulator-row .tabulator-cell { border-right: 1px solid #ddd; }
    #produksi-cpo-inti-table .tabulator-calcs-holder { font-weight: bold; background: #ecf0f5; }
    #produksi-cpo-inti-table .tabulator-footer .tabulator-calcs-bottom {
        background: #d9edf7 !important; font-weight: 700; border-top: 2px solid #3c8dbc;
    }
    #produksi-cpo-inti-table .variance-positive { color: #008000; font-weight: 600; }
    #produksi-cpo-inti-table .variance-negative { color: #d73925; font-weight: 600; }

    @media (max-width: 767px) {
        .table-toolbar { display: block; }
        .table-toolbar-left, .table-toolbar-right { margin-bottom: 8px; }
        .table-search { width: 100%; }
        .site-dropdown-menu { width: 270px; }
    }
</style>

<section class="content-header">
    <h1>
        Produksi CPO & Inti
        <small>
            Laporan Bulanan, Budget dan YTD
        </small>
    </h1>
</section>

<section class="content">
    <div class="panel panel-default filter-panel">
        <div class="panel-body">
            <form id="filter-form" method="GET" action="{{ route('mill.produksi-cpo-inti') }}" class="form-inline">
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input type="number" class="form-control" id="tahun" name="tahun"
                           min="2000" max="2100" value="{{ $tahun }}" style="width:100px;">
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan</label>
                    <select class="form-control" id="bulan" name="bulan" style="width:150px;">
                        @foreach($namaBulan as $nomorBulan => $nama)
                            <option value="{{ $nomorBulan }}" {{ (int)$bulan === (int)$nomorBulan ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>PABRIK</label>
                    <div class="site-dropdown" id="site-dropdown">
                        <button type="button" class="btn btn-default" id="site-dropdown-button"
                                style="min-width:220px;text-align:left;">
                            <span id="site-dropdown-label">Semua PABRIK</span>
                            <span class="caret pull-right" style="margin-top:8px;"></span>
                        </button>

                        <div class="site-dropdown-menu">
                            <div class="site-dropdown-actions">
                                <button type="button" class="btn btn-xs btn-primary" id="select-all-sites">Pilih Semua</button>
                                <button type="button" class="btn btn-xs btn-default" id="clear-all-sites">Semua PABRIK</button>
                            </div>

                            @foreach($siteOptions as $siteId => $siteName)
                                <label>
                                    <input type="checkbox" name="site_id[]" class="site-checkbox"
                                           value="{{ $siteId }}" data-site-name="{{ $siteName }}"
                                           {{ in_array((string)$siteId, array_map('strval',$selectedSites), true) ? 'checked' : '' }}>
                                    <strong>{{ $siteName }}</strong>
                                    <small class="text-muted">({{ $siteId }})</small>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('mill.produksi-cpo-inti') }}" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="period-summary">
        <span><strong>Periode:</strong> Bulanan</span>
        <span><strong>Tahun:</strong> {{ $tahun }}</span>
        <span><strong>Bulan:</strong> {{ $namaBulan[$bulan] ?? '' }}</span>
        <span>
            <strong>PABRIK:</strong>
            @if(count($selectedSiteNames) === 0)
                Semua PABRIK
            @else
                {{ implode(', ', $selectedSiteNames) }}
            @endif
        </span>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs produksi-tabs" id="produksi-tabs">
                <li class="active"><a href="#" data-view="REGION">PER WILAYAH</a></li>
                <li><a href="#" data-view="KEBUN">PER PABRIK</a></li>
            </ul>

            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <label for="page-size" style="margin:0;">Tampilkan</label>
                    <select id="page-size" class="form-control input-sm" style="width:85px;">
                        <option value="all" selected>All</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>data</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin-left:12px;">
                    <strong>Satuan:</strong>
                    <label style="margin:0;font-weight:normal;cursor:pointer;">
                        <input type="radio" name="unit-mode" id="unit-kg" value="KG"> KG
                    </label>
                    <label style="margin:0;font-weight:normal;cursor:pointer;">
                        <input type="radio" name="unit-mode" id="unit-ton" value="TON" checked> TON
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
                    <label for="table-search" style="margin:0;">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="table-search" class="form-control input-sm table-search"
                               placeholder="Cari Wilayah atau PABRIK..." autocomplete="off">
                    </div>
                </div>
            </div>

            <div id="produksi-cpo-inti-table"></div>
        </div>

        <div
            class="notes-wrapper"
            style="margin-top:12px;"
        >
            <button
                type="button"
                class="btn btn-default btn-xs"
                data-toggle="collapse"
                data-target="#calculation-notes"
                aria-expanded="false"
                aria-controls="calculation-notes"
            >
                <i class="fa fa-info-circle"></i>
                Notes.
                <i class="fa fa-angle-down"></i>
            </button>

            <div
                id="calculation-notes"
                class="collapse"
                style="margin-top:10px;"
            >
                <div
                    class="well well-sm"
                    style="margin-bottom:0;"
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

                    <div style="margin-top:5px;">
                        AKT = AKTUAL,
                        VAR = VARIAN,
                        ACH = ACHIEVEMENT
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
    var activeView = "REGION";
    var searchKeyword = "";
    var selectedPageSize = "all";
    var useTonMode = true;

    var kgFormatter = new Intl.NumberFormat("id-ID", { minimumFractionDigits:0, maximumFractionDigits:0 });
    var tonFormatter = new Intl.NumberFormat("id-ID", { minimumFractionDigits:0, maximumFractionDigits:0 });
    var percentageFormatter = new Intl.NumberFormat("id-ID", { minimumFractionDigits:2, maximumFractionDigits:2 });

    function toNumber(value) {
        var result = Number(value);
        return isNaN(result) ? 0 : result;
    }

    function prepareAchievement(rows) {
        rows.forEach(function (row) {
            var actual = toNumber(row.PRODUKSI_THISYEAR_YTD);
            var budget = toNumber(row.BUDGET_THISYEAR_ALL);
            row.ACH_FULL_YEAR = budget === 0 ? 0 : (actual / budget) * 100;
        });
    }

    prepareAchievement(tableData);
    prepareAchievement(regionData);

    function formatTonnage(value) {
        var number = toNumber(value);
        var display = useTonMode ? Math.round(number / 1000) : number;
        return useTonMode ? tonFormatter.format(display) : kgFormatter.format(display);
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
            // return comparison === 0 ? 0 : ((actual / comparison) - 1) * 100;
            return comparison === 0 ? 0 : ((actual / comparison)) * 100;
        };
    }

    function calculateAchievement(actualField, budgetField) {
        return function (values, data) {
            var actual = sumField(data, actualField);
            var budget = sumField(data, budgetField);
            return budget === 0 ? 0 : (actual / budget) * 100;
        };
    }

    function numberFormatter(cell) {
        return formatTonnage(cell.getValue());
    }

    function percentageFormatterCell(cell) {
        var value = toNumber(cell.getValue());
        var el = cell.getElement();

        el.classList.remove("variance-positive", "variance-negative");

        if (value >= 95) el.classList.add("variance-positive");
        else if (value < 95) el.classList.add("variance-negative");

        return percentageFormatter.format(value) + "%";
    }

    function achievementFormatter(cell) {
        var value = toNumber(cell.getValue());
        var el = cell.getElement();

        el.classList.remove("variance-positive", "variance-negative");

        if (value >= 95) el.classList.add("variance-positive");
        else if (value < 95) el.classList.add("variance-negative");

        return percentageFormatter.format(value) + "%";
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

    var table = null;

    function buildTable() {
        if (table) table.destroy();

        var isRegion = activeView === "REGION";
        var currentData = isRegion ? regionData : tableData;
        var labelTitle = isRegion ? "WILAYAH" : "PABRIK";
        var labelField = isRegion ? "REGION" : "MILLCODE";

        table = new Tabulator("#produksi-cpo-inti-table", {
            data: currentData,
            layout: "fitData",
            height: "560px",
            placeholder: "Data Produksi CPO tidak ditemukan.",

            columnDefaults: {
                headerSort: false,
                minWidth: 60
            },

            /*
            |--------------------------------------------------------------------------
            | Grouping CPO / INTI
            |--------------------------------------------------------------------------
            */

            groupBy:
                "PRODUK",

            groupStartOpen:
                true,

            groupClosedShowCalcs:
                true,

            /*
             * HANYA calculation per group.
             * Tidak ada table / grand total.
             */
            columnCalcs:
                "group",

            groupHeader:
                function (
                    value,
                    count
                ) {
                    return (
                        "<strong>"
                        + value
                        + "</strong>"
                        + " <span style='"
                        + "color:#999;"
                        + "font-weight:normal;"
                        + "'>"
                        + count
                        + " data"
                        + "</span>"
                    );
                },

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

            initialSort: [
                {
                    column:
                        "INDEX",

                    dir:
                        "asc"
                }
            ],

            columns: [
                {
                    title:"INDEX", field:"INDEX", hozAlign:"center", headerHozAlign:"center",
                    sorter:"number", frozen:true, visible:false,
                    bottomCalc:function(){ return ""; }
                },
                {
                    title:"TAHUN LALU (YTD)", headerHozAlign:"center",
                    columns:[
                        {
                            title:"AKT", field:"PRODUKSI_LASTYEAR_YTD", hozAlign:"right",
                            headerHozAlign:"center", 
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"VAR (%)", field:"VARIAN_YTD_TAHUN_LALU", hozAlign:"right",
                            headerHozAlign:"center", formatter:percentageFormatterCell, accessorDownload:exportPercentageAccessor,
                            bottomCalc:calculateVariance("PRODUKSI_THISYEAR_YTD","PRODUKSI_LASTYEAR_YTD"),
                            bottomCalcFormatter:percentageFormatterCell
                        }
                    ]
                },
                {
                    title:labelTitle, field:labelField,
                    frozen:true, headerHozAlign:"center",
                    bottomCalc:function(){ return "SUB TOTAL"; }
                },
                {
                    title:"BULAN INI", headerHozAlign:"center",
                    columns:[
                        {
                            title:"AKT", field:"PRODUKSI_THISYEAR_SELECTED_MONTH", hozAlign:"right",
                            headerHozAlign:"center",
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"BUD", field:"BUDGET_THISYEAR_SELECTED_MONTH", hozAlign:"right",
                            headerHozAlign:"center",
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"VAR (%)", field:"VARIAN_BULAN_INI", hozAlign:"right",
                            headerHozAlign:"center", formatter:percentageFormatterCell, accessorDownload:exportPercentageAccessor,
                            bottomCalc:calculateVariance("PRODUKSI_THISYEAR_SELECTED_MONTH","BUDGET_THISYEAR_SELECTED_MONTH"),
                            bottomCalcFormatter:percentageFormatterCell
                        }
                    ]
                },
                {
                    title:"SAMPAI DENGAN (YTD)", headerHozAlign:"center",
                    columns:[
                        {
                            title:"AKT", field:"PRODUKSI_THISYEAR_YTD", hozAlign:"right",
                            headerHozAlign:"center",
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"BUD", field:"BUDGET_THISYEAR_YTD", hozAlign:"right",
                            headerHozAlign:"center",
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"VAR (%)", field:"VARIAN_YTD", hozAlign:"right",
                            headerHozAlign:"center", formatter:percentageFormatterCell, accessorDownload:exportPercentageAccessor,
                            bottomCalc:calculateVariance("PRODUKSI_THISYEAR_YTD","BUDGET_THISYEAR_YTD"),
                            bottomCalcFormatter:percentageFormatterCell
                        }
                    ]
                },
                {
                    title:"BUD FULL YEAR", headerHozAlign:"center",
                    columns:[
                        {
                            title:"BUD", field:"BUDGET_THISYEAR_ALL", hozAlign:"right",
                            headerHozAlign:"center",
                            formatter:numberFormatter, accessorDownload:exportTonnageAccessor, bottomCalc:"sum", bottomCalcFormatter:numberFormatter
                        },
                        {
                            title:"ACH (%)", field:"ACH_FULL_YEAR", hozAlign:"right",
                            headerHozAlign:"center", formatter:achievementFormatter, accessorDownload:exportPercentageAccessor,
                            bottomCalc:calculateAchievement("PRODUKSI_THISYEAR_YTD","BUDGET_THISYEAR_ALL"),
                            bottomCalcFormatter:achievementFormatter
                        }
                    ]
                }
            ]
        });
    }

    function applyPageSize() {
        if (selectedPageSize === "all") {
            var count = table.getDataCount("active");
            table.setPageSize(count > 0 ? count : 1);
        } else {
            table.setPageSize(parseInt(selectedPageSize,10));
        }
        table.setPage(1);
    }

    function applyTableFilter() {
        var keyword = searchKeyword.toLowerCase();

        table.setFilter(function (data) {
            if (keyword === "") return true;

            var text = activeView === "REGION"
                ? [data.INDEX, data.REGION].join(" ").toLowerCase()
                : [data.INDEX, data.SITE_ID_MILL, data.MILLCODE].join(" ").toLowerCase();

            return text.indexOf(keyword) !== -1;
        });

        applyPageSize();
    }

    buildTable();
    applyTableFilter();

    document.querySelectorAll("#produksi-tabs a").forEach(function(tab) {
        tab.addEventListener("click", function(event) {
            event.preventDefault();

            document.querySelectorAll("#produksi-tabs li").forEach(function(item) {
                item.classList.remove("active");
            });

            this.parentElement.classList.add("active");
            activeView = this.getAttribute("data-view");

            buildTable();
            applyTableFilter();
        });
    });

    document.getElementById("table-search").addEventListener("input", function() {
        searchKeyword = this.value.trim();
        applyTableFilter();
    });

    document.getElementById("page-size").addEventListener("change", function() {
        selectedPageSize = this.value;
        applyPageSize();
    });

    var siteDropdown = document.getElementById("site-dropdown");
    var siteDropdownButton = document.getElementById("site-dropdown-button");
    var siteCheckboxes = document.querySelectorAll(".site-checkbox");
    var siteDropdownLabel = document.getElementById("site-dropdown-label");

    function updateSiteDropdownLabel() {
        var checked = Array.prototype.slice.call(siteCheckboxes).filter(function(cb) {
            return cb.checked;
        });

        if (checked.length === 0) {
            siteDropdownLabel.textContent = "Semua PABRIK";
        } else if (checked.length === 1) {
            siteDropdownLabel.textContent = checked[0].getAttribute("data-site-name");
        } else {
            siteDropdownLabel.textContent = checked.length + " PABRIK dipilih";
        }
    }

    siteDropdownButton.addEventListener("click", function(event) {
        event.stopPropagation();
        siteDropdown.classList.toggle("open");
    });

    document.querySelector(".site-dropdown-menu").addEventListener("click", function(event) {
        event.stopPropagation();
    });

    document.addEventListener("click", function() {
        siteDropdown.classList.remove("open");
    });

    siteCheckboxes.forEach(function(cb) {
        cb.addEventListener("change", updateSiteDropdownLabel);
    });

    document.getElementById("select-all-sites").addEventListener("click", function() {
        siteCheckboxes.forEach(function(cb) { cb.checked = true; });
        updateSiteDropdownLabel();
    });

    document.getElementById("clear-all-sites").addEventListener("click", function() {
        siteCheckboxes.forEach(function(cb) { cb.checked = false; });
        updateSiteDropdownLabel();
    });

    updateSiteDropdownLabel();

    function changeUnitMode(unit) {
        useTonMode = unit === "TON";
        buildTable();
        applyTableFilter();
    }

    document.getElementById("unit-kg").addEventListener("change", function() {
        if (this.checked) changeUnitMode("KG");
    });

    document.getElementById("unit-ton").addEventListener("change", function() {
        if (this.checked) changeUnitMode("TON");
    });

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


                var viewLabel =
                    activeView === "REGION"
                        ? "PER WILAYAH"
                        : "PER PMKS";


                var pmksText =
                    getSelectedPmksText();


                var fileName =
                    "Produksi_CPO_Inti_"
                    + activeView
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
                            "Produksi CPO Inti",

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
                                * Sediakan 9 baris
                                * sebelum table Tabulator.
                                */
                                shiftWorksheetDown(
                                    worksheet,
                                    9
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Informasi laporan
                                |--------------------------------------------------------------------------
                                */

                                var headerData = [
                                    [
                                        "LAPORAN PRODUKSI CPO & INTI"
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
                                        "PABRIK",
                                        pmksText
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
                                        headerData,
                                        {
                                            origin:
                                                "A1"
                                        }
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Merge Title
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


                                /*
                                |--------------------------------------------------------------------------
                                | Styling ringan jika XLSX mendukung
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    worksheet[
                                        "A1"
                                    ]
                                ) {
                                    worksheet[
                                        "A1"
                                    ].s = {
                                        font: {
                                            bold:
                                                true,

                                            sz:
                                                14
                                        },

                                        alignment: {
                                            horizontal:
                                                "left"
                                        }
                                    };
                                }


                                return workbook;
                            }
                    },

                    "active"
                );
            }
        );

    /*
    |--------------------------------------------------------------------------
    | Geser isi worksheet ke bawah
    |--------------------------------------------------------------------------
    */

    function shiftWorksheetDown(
        worksheet,
        rowCount
    ) {
        var cells = [];

        Object.keys(worksheet)
            .forEach(function (address) {

                if (
                    address.charAt(0) === "!"
                ) {
                    return;
                }

                cells.push({
                    address: address,
                    cell: worksheet[address]
                });
            });


        /*
        * Delete existing cells first.
        */
        cells.forEach(
            function (item) {
                delete worksheet[
                    item.address
                ];
            }
        );


        /*
        * Put them back at shifted row.
        */
        cells.forEach(
            function (item) {

                var decoded =
                    XLSX.utils.decode_cell(
                        item.address
                    );

                decoded.r +=
                    rowCount;

                var newAddress =
                    XLSX.utils.encode_cell(
                        decoded
                    );

                worksheet[newAddress] =
                    item.cell;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Shift merged cells
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Update worksheet range
        |--------------------------------------------------------------------------
        */

        if (
            worksheet["!ref"]
        ) {
            var range =
                XLSX.utils.decode_range(
                    worksheet["!ref"]
                );

            range.s.r +=
                rowCount;

            range.e.r +=
                rowCount;

            worksheet["!ref"] =
                XLSX.utils.encode_range(
                    range
                );
        }
    }

    function getSelectedPmksText() {

        var checked =
            Array.prototype.slice
                .call(siteCheckboxes)
                .filter(
                    function (cb) {
                        return cb.checked;
                    }
                );

        if (
            checked.length === 0
        ) {
            return "Semua PMKS";
        }

        return checked
            .map(
                function (cb) {
                    return cb.getAttribute(
                        "data-site-name"
                    );
                }
            )
            .join(", ");
    }

});
</script>

@endsection

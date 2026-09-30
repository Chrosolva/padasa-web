@extends('dashboard.app')

@section('header-title')
    Pupuk BioFertilizer
@endsection

@section('main-content')

<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">

<style>
    .filter-panel { margin-bottom: 15px; }
    .filter-panel .form-group { margin-right: 10px; margin-bottom: 10px; vertical-align: top; }
    .bio-tabs { margin-bottom: 15px; }
    .bio-tabs > li > a { font-weight: 600; cursor: pointer; }
    .bio-tabs > li.active > a,
    .bio-tabs > li.active > a:hover,
    .bio-tabs > li.active > a:focus {
        color: #fff; background: #3c8dbc; border-color: #3c8dbc;
    }
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 8px; margin-bottom: 10px;
    }
    .table-toolbar-left, .table-toolbar-right {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
    }
    .table-search { width: 260px; max-width: 100%; }
    #table-mutasi, #table-produksi-pupuk { width: 100%; }
    .tabulator { width: 100%; border: 1px solid #d2d6de; background: #fff; font-size: 12px; }
    .tabulator .tabulator-header { background: #f4f4f4; border-bottom: 2px solid #d2d6de; color: #333; font-weight: bold; }
    .tabulator .tabulator-header .tabulator-col { background: #f4f4f4; border-right: 1px solid #d2d6de; }
    .tabulator .tabulator-header .tabulator-col-content { padding: 7px 5px; }
    .tabulator .tabulator-header .tabulator-col-title { white-space: normal; line-height: 16px; text-align: center; }
    .tabulator .tabulator-row { min-height: 30px; border-bottom: 1px solid #eee; }
    .tabulator .tabulator-row:nth-child(even) { background: #f9f9f9; }
    .tabulator .tabulator-row:hover { background: #eef6ff; }
    .tabulator .tabulator-row .tabulator-cell { padding: 6px 7px; border-right: 1px solid #eee; line-height: 17px; }
    .tabulator .tabulator-footer { padding: 7px; background: #fff; border-top: 1px solid #d2d6de; }
    .tabulator .tabulator-footer .tabulator-page { margin: 2px; padding: 4px 9px; border: 1px solid #d2d6de; border-radius: 3px; background: #fff; }
    .tabulator .tabulator-footer .tabulator-page.active { color: #fff; background: #3c8dbc; border-color: #367fa9; }
    .tabulator .tabulator-calcs-holder { background: #eaf2ff; border-top: 2px solid #3c8dbc; }
    .tabulator .tabulator-calcs-holder .tabulator-row,
    .tabulator .tabulator-calcs-holder .tabulator-cell { background: #eaf2ff !important; font-weight: bold; }
    @media (max-width: 767px) {
        .table-toolbar { display: block; }
        .table-toolbar-left, .table-toolbar-right { margin-bottom: 8px; }
        .table-search { width: 100%; }
    }
</style>

<section class="content-header">
    <h1>Pupuk BioFertilizer <small>Analisa Mutasi dan Produksi Pupuk</small></h1>
</section>

<section class="content">
    <div class="panel panel-default filter-panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET" action="{{ route('biofertilizer.analisa-mutasi-per-bulan') }}">
                <div class="form-group">
                    <label for="tahun">Tahun :</label>
                    <div class="input-group date input-inline" style="width:175px;">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2100" value="{{ request('tahun', $tahun ?? date('Y')) }}">
                    </div>
                </div>

                <div class="form-group" style="display:none;">
                    <label for="bulan">Bulan :</label>
                    <div class="input-group date input-inline" style="width:150px;">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="number" class="form-control" id="bulan" name="bulan" min="1" max="12" value="12">
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_id">Site ID :</label>
                    <select class="form-control" id="site_id" name="site_id">
                        @foreach($siteOptions as $siteCode => $siteName)
                            <option value="{{ $siteCode }}" {{ (string) request('site_id', $siteId ?? '2200') === (string) $siteCode ? 'selected' : '' }}>
                                {{ $siteCode }} - {{ $siteName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenis_produk">Jenis Produk :</label>
                    <select class="form-control" id="jenis_produk" name="jenis_produk">
                        <option value="SEMUA" {{ strtoupper((string) request('jenis_produk', $jenisProduk ?? 'SEMUA')) === 'SEMUA' ? 'selected' : '' }}>SEMUA</option>
                        <option value="SSB" {{ strtoupper((string) request('jenis_produk', $jenisProduk ?? 'SEMUA')) === 'SSB' ? 'selected' : '' }}>SSB</option>
                        <option value="SSC" {{ strtoupper((string) request('jenis_produk', $jenisProduk ?? 'SEMUA')) === 'SSC' ? 'selected' : '' }}>SSC</option>
                        <option value="SSK" {{ strtoupper((string) request('jenis_produk', $jenisProduk ?? 'SEMUA')) === 'SSK' ? 'selected' : '' }}>SSK</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    <a href="{{ route('biofertilizer.analisa-mutasi-per-bulan') }}" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs bio-tabs" id="bio-tabs">
                <li class="active"><a href="#tab-mutasi" data-toggle="tab"><i class="fa"></i> Analisa Mutasi</a></li>
                <li><a href="#tab-produksi-pupuk" data-toggle="tab"><i class="fa"></i> Produksi Pupuk</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab-mutasi">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title">Analisa Mutasi Pupuk Per Bulan</h3></div>
                        <div class="box-body">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <div class="input-group table-search">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input type="text" id="search-mutasi" class="form-control" placeholder="Cari semua kolom..." autocomplete="off">
                                    </div>
                                    <button type="button" id="reset-mutasi" class="btn btn-default"><i class="fa fa-times"></i> Reset</button>
                                </div>
                            </div>
                            <div id="table-mutasi"></div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-produksi-pupuk">
                    <div class="box box-success">
                        <div class="box-header with-border"><h3 class="box-title">Produksi Pupuk YTD</h3></div>
                        <div class="box-body">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <div class="input-group table-search">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input type="text" id="search-produksi-pupuk" class="form-control" placeholder="Cari tahun, bulan, produk..." autocomplete="off">
                                    </div>
                                    <button type="button" id="reset-produksi-pupuk" class="btn btn-default"><i class="fa fa-times"></i> Reset</button>
                                </div>
                            </div>
                            <div id="table-produksi-pupuk"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script-content')
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {
    var rawMutasiData = @json($rows ?? []);
    var rawProduksiPupukData = @json($produksiPupuk ?? []);

    function toNumber(value) {
        var number = parseFloat(value);
        return isNaN(number) ? 0 : number;
    }

    function formatIntegerIndonesia(value) {
        return toNumber(value).toLocaleString("id-ID", { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function integerFormatter(cell) {
        var value = cell.getValue();
        if (value === null || value === undefined || value === "") return "";
        return formatIntegerIndonesia(value);
    }

    function getRowValue(row, fieldName) {
        if (!row || !fieldName) return null;
        if (Object.prototype.hasOwnProperty.call(row, fieldName)) return row[fieldName];
        var requestedField = String(fieldName).toUpperCase();
        var keys = Object.keys(row);
        for (var index = 0; index < keys.length; index++) {
            if (String(keys[index]).toUpperCase() === requestedField) return row[keys[index]];
        }
        return null;
    }

    // TAB 1 - ANALISA MUTASI
    var mutasiHiddenColumns = ["COMP_ID", "SITE_ID"];
    var mutasiLabelMap = { "COMPOSTTYPE": "TIPE COMPOST" };
    var mutasiIntegerColumns = [
        "SALDOAWAL",
        "KELUAR",
        "MASUK",
        "RETUR",
        "ADJUSTMENT",
        "SALDOAKHIR"
    ];
    var mutasiOriginalHeaders = rawMutasiData.length > 0 ? Object.keys(rawMutasiData[0]) : [];
    var mutasiVisibleHeaders = mutasiOriginalHeaders.filter(function (header) {
        return mutasiHiddenColumns.indexOf(String(header).toUpperCase()) === -1;
    });

    var mutasiData = rawMutasiData.map(function (row) {
        var normalizedRow = {};
        mutasiVisibleHeaders.forEach(function (header) {
            var fieldName = String(header).toUpperCase();
            var value = getRowValue(row, header);
            if (mutasiIntegerColumns.indexOf(fieldName) !== -1 && value !== null && value !== "" && !isNaN(parseFloat(value))) {
                normalizedRow[fieldName] = parseFloat(value);
            } else {
                normalizedRow[fieldName] = value === null || value === undefined ? "" : value;
            }
        });
        return normalizedRow;
    });

    var mutasiColumns = mutasiVisibleHeaders.map(function (header, index) {
        var fieldName = String(header).toUpperCase();
        var title = mutasiLabelMap[fieldName] !== undefined ? mutasiLabelMap[fieldName] : fieldName;
        var isInteger = mutasiIntegerColumns.indexOf(fieldName) !== -1;
        var column = {
            title: title,
            field: fieldName,
            minWidth: 50,
            headerSort: false,
            headerHozAlign: "center",
            vertAlign: "middle",
            hozAlign: isInteger ? "right" : "left",
            formatter: isInteger ? integerFormatter : undefined,
            resizable: true
        };
        if (index === 0) column.frozen = true;
        if (isInteger) {
            column.bottomCalc = "sum";
            column.bottomCalcFormatter = integerFormatter;
        }
        return column;
    });

    if (mutasiColumns.length === 0) {
        mutasiColumns = [{ title: "INFORMASI", field: "INFORMASI", minWidth: 50, headerSort: false }];
        mutasiData = [{ INFORMASI: "Tidak ada data yang tersedia" }];
    }

    var tableMutasi = new Tabulator("#table-mutasi", {
        data: mutasiData,
        layout: "fitData",
        responsiveLayout: false,
        height: "45vh",
        movableColumns: false,
        resizableColumns: true,
        placeholder: "Tidak ada data yang tersedia",
        pagination: "local",
        paginationSize: 25,
        paginationSizeSelector: [10, 25, 50, 100, true],
        paginationCounter: "rows",
        columnDefaults: {
            minWidth: 50,
            headerSort: false,
            vertAlign: "middle",
            resizable: true
        },
        columns: mutasiColumns
    });

    // TAB 2 - PRODUKSI PUPUK
    var produksiPupukData = rawProduksiPupukData.map(function (row) {
        return {
            TAHUN: toNumber(getRowValue(row, "TAHUN")),
            BULAN: toNumber(getRowValue(row, "BULAN")),
            COMPOSTTYPE: String(getRowValue(row, "COMPOSTTYPE") || "").trim().toUpperCase(),
            PRODUKSI: toNumber(getRowValue(row, "PRODUKSI")),
            PRODUKSI_YTD: toNumber(getRowValue(row, "PRODUKSI_YTD")),
            SATUAN: String(getRowValue(row, "SATUAN") || "").trim().toUpperCase()
        };
    });

    var tableProduksiPupuk = new Tabulator("#table-produksi-pupuk", {
        data: produksiPupukData,
        layout: "fitData",
        responsiveLayout: false,
        height: "45vh",
        movableColumns: false,
        resizableColumns: true,
        placeholder: "Tidak ada data produksi pupuk",
        pagination: "local",
        paginationSize: 25,
        paginationSizeSelector: [10, 25, 50, 100, true],
        paginationCounter: "rows",
        columnDefaults: {
            minWidth: 50,
            headerSort: false,
            vertAlign: "middle",
            resizable: true
        },
        columns: [
            { title: "TAHUN", field: "TAHUN", hozAlign: "center", headerHozAlign: "center" },
            { title: "BULAN", field: "BULAN", hozAlign: "center", headerHozAlign: "center" },
            { title: "JENIS<br>PRODUK", field: "COMPOSTTYPE", hozAlign: "left", headerHozAlign: "center" },
            { title: "PRODUKSI", field: "PRODUKSI", hozAlign: "right", headerHozAlign: "center", formatter: integerFormatter},
            { title: "PRODUKSI<br>YTD", field: "PRODUKSI_YTD", hozAlign: "right", headerHozAlign: "center", formatter: integerFormatter },
            { title: "SATUAN", field: "SATUAN", hozAlign: "left", headerHozAlign: "center" }
        ]
    });

    function applyGlobalSearch(table, keyword) {
        keyword = String(keyword || "").trim().toLowerCase();
        if (keyword === "") {
            table.clearFilter(true);
            return;
        }
        table.setFilter(function (rowData) {
            var fields = Object.keys(rowData);
            for (var index = 0; index < fields.length; index++) {
                var value = rowData[fields[index]];
                var text = value === null || value === undefined ? "" : String(value);
                if (text.toLowerCase().indexOf(keyword) !== -1) return true;
            }
            return false;
        });
    }

    var mutasiSearchTimer = null;
    $("#search-mutasi").on("input", function () {
        var keyword = this.value;
        clearTimeout(mutasiSearchTimer);
        mutasiSearchTimer = setTimeout(function () {
            applyGlobalSearch(tableMutasi, keyword);
        }, 200);
    });

    $("#reset-mutasi").on("click", function () {
        $("#search-mutasi").val("");
        tableMutasi.clearFilter(true);
        tableMutasi.clearSort();
        tableMutasi.setPage(1);
    });

    var produksiSearchTimer = null;
    $("#search-produksi-pupuk").on("input", function () {
        var keyword = this.value;
        clearTimeout(produksiSearchTimer);
        produksiSearchTimer = setTimeout(function () {
            applyGlobalSearch(tableProduksiPupuk, keyword);
        }, 200);
    });

    $("#reset-produksi-pupuk").on("click", function () {
        $("#search-produksi-pupuk").val("");
        tableProduksiPupuk.clearFilter(true);
        tableProduksiPupuk.clearSort();
        tableProduksiPupuk.setPage(1);
    });

    $('a[data-toggle="tab"]').on("shown.bs.tab", function (event) {
        var target = $(event.target).attr("href");
        if (target === "#tab-mutasi") tableMutasi.redraw(true);
        if (target === "#tab-produksi-pupuk") tableProduksiPupuk.redraw(true);
    });

    var resizeTimer = null;
    $(window).on("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            tableMutasi.redraw(true);
            tableProduksiPupuk.redraw(true);
        }, 200);
    });
});
</script>
@endsection
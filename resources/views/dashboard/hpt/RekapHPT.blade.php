@extends('dashboard.app')

@section('header-title')
    Rekap HPT
@endsection

@section('main-content')

<link
    rel="stylesheet"
    href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css"
>

<style>
    #rekap-hpt-table {
        width: 100%;
        min-height: 100px;
    }

    #rekap-hpt-table .tabulator {
        border: 1px solid #d2d6de;
        font-size: 13px;
    }

    #rekap-hpt-table .tabulator-header {
        background-color: #f4f4f4;
        font-weight: 600;
    }

    #rekap-hpt-table .tabulator-header .tabulator-col {
        background-color: #f4f4f4;
    }

    #rekap-hpt-table .tabulator-calcs-holder {
        background-color: #ecf0f5;
        font-weight: 700;
    }

    #rekap-hpt-table .tabulator-calcs {
        font-weight: 700;
    }

    .compact-filter {
        padding: 10px 12px;
    }

    .compact-filter .form-group {
        margin-right: 10px;
        margin-bottom: 0;
    }

    .compact-filter label {
        margin-right: 5px;
        font-size: 12px;
    }

    .compact-filter #endDate {
        width: 155px;
    }

    .compact-filter #jenis_penyakit {
        width: 285px;
    }

    .compact-filter .btn {
        margin-right: 3px;
    }

    .table-toolbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 10px;
    }

    .table-search {
        width: 340px;
        max-width: 100%;
    }

    .hpt-formula {
        margin: 10px 12px;
        font-size: 12px;
        font-style: italic;
    }

    @media (max-width: 767px) {
        .compact-filter .form-group {
            display: block;
            margin-right: 0;
            margin-bottom: 8px;
        }

        .compact-filter #endDate,
        .compact-filter #jenis_penyakit {
            width: 100%;
        }

        .compact-filter .btn {
            margin-top: 4px;
        }

        .table-toolbar {
            justify-content: stretch;
        }

        .table-search {
            width: 100%;
        }
    }
</style>

<section class="content-header">
    <h1>
        Rekap Hama &amp; Penyakit Tumbuhan
    </h1>

    <ol class="breadcrumb">
        <li>
            <a href="{{ url('/dashboard') }}">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>

        <li>Hama &amp; Penyakit Tumbuhan</li>
        <li class="active">Rekap HPT</li>
    </ol>
</section>

<section class="content">

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true"
            >
                &times;
            </button>

            <h4>
                <i class="icon fa fa-ban"></i>
                Terjadi Kesalahan
            </h4>

            {{ session('error') }}
        </div>
    @endif

    @if (!empty($queryError))
        <div class="alert alert-danger alert-dismissible">
            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true"
            >
                &times;
            </button>

            <h4>
                <i class="icon fa fa-ban"></i>
                Terjadi Kesalahan
            </h4>

            {{ $queryError }}
        </div>
    @endif

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-filter"></i>
                Filter Data
            </h3>
        </div>

        <div class="box-body compact-filter">
            <form
                method="GET"
                action="{{ route('hpt.rekap') }}"
                class="form-inline"
            >
                <div class="form-group">
                    <label for="endDate">Tanggal:</label>

                    <div class="input-group input-group-sm">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <input
                            type="date"
                            class="form-control"
                            id="endDate"
                            name="endDate"
                            value="{{ $endDate }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="jenis_penyakit">
                        Jenis Penyakit:
                    </label>

                    <select
                        class="form-control input-sm"
                        id="jenis_penyakit"
                        name="jenis_penyakit"
                        required
                    >
                        <option
                            value="Busuk Pangkal Batang (Basal Stem Rot)"
                        >
                            Busuk Pangkal Batang (Basal Stem Rot)
                        </option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="btn btn-success btn-sm"
                >
                    <i class="fa fa-search"></i>
                    Tampilkan
                </button>

                <a
                    href="{{ route('hpt.rekap') }}"
                    class="btn btn-default btn-sm"
                >
                    <i class="fa fa-refresh"></i>
                    Reset
                </a>
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-bug"></i>
                Rekap HPT per
                {{ date('d-m-Y', strtotime($endDate)) }}
            </h3>

            <div class="box-tools pull-right">
                <button
                    type="button"
                    class="btn btn-box-tool"
                    data-widget="collapse"
                >
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="box-body">
            <div class="table-toolbar">
                <div class="input-group input-group-sm table-search">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>

                    <input
                        type="text"
                        id="search-rekap-hpt"
                        class="form-control"
                        placeholder="Cari kebun atau data lainnya..."
                        autocomplete="off"
                    >

                    <span class="input-group-btn">
                        <button
                            type="button"
                            id="clear-search-rekap-hpt"
                            class="btn btn-default"
                            title="Hapus pencarian"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div id="rekap-hpt-table"></div>
        </div>

        <div class="hpt-formula">
            Catatan : % Terserang = PKK Terserang / Jumlah PKK × 100%
        </div>
    </div>
</section>

<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = @json($rekapHPT);

    function formatInteger(cell) {
        var value = Number(cell.getValue() || 0);

        return value.toLocaleString("id-ID", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function formatPercent(cell) {
        var value = Number(cell.getValue() || 0);

        return value.toLocaleString("id-ID", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + "%";
    }

    function calculateTotalPercentage(values, data) {
        var totalPkk = 0;
        var totalPkkTerserang = 0;

        data.forEach(function (row) {
            totalPkk += Number(row.jumlah_pkk || 0);

            totalPkkTerserang += Number(
                row.pkk_terserang || 0
            );
        });

        if (totalPkk === 0) {
            return 0;
        }

        return (totalPkkTerserang / totalPkk) * 100;
    }

    if (typeof Tabulator === "undefined") {
        document.getElementById("rekap-hpt-table").innerHTML =
            '<div class="alert alert-danger">' +
                '<i class="fa fa-warning"></i> ' +
                'Library Tabulator gagal dimuat.' +
            '</div>';

        return;
    }

    var rekapHPTTable = new Tabulator(
        "#rekap-hpt-table",
        {
            data: tableData,
            layout: "fitData",

            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [
                10,
                25,
                50,
                true
            ],

            paginationCounter: function (
                pageSize,
                currentRow,
                currentPage,
                totalRows,
                totalPages
            ) {
                if (totalRows === 0) {
                    return "Tidak ada data";
                }

                if (pageSize === true) {
                    return "Menampilkan seluruh " +
                        totalRows +
                        " data";
                }

                var rowAwal =
                    ((currentPage - 1) * pageSize) + 1;

                var rowAkhir = Math.min(
                    currentPage * pageSize,
                    totalRows
                );

                return "Menampilkan " +
                    rowAwal +
                    " - " +
                    rowAkhir +
                    " dari " +
                    totalRows +
                    " data";
            },

            placeholder: "Tidak ada data Rekap HPT.",
            movableColumns: true,
            resizableColumns: true,
            columnHeaderVertAlign: "middle",

            columns: [
                {
                    title: "KEBUN",
                    field: "kebun",
                    minWidth: 150,
                    frozen: true,
                    headerHozAlign: "center",
                    hozAlign: "left",
                    sorter: "string",

                    bottomCalc: function () {
                        return "TOTAL";
                    }
                },
                {
                    title: "JUMLAH PKK",
                    field: "jumlah_pkk",
                    minWidth: 160,
                    headerHozAlign: "center",
                    hozAlign: "right",
                    sorter: "number",
                    formatter: formatInteger,
                    bottomCalc: "sum",
                    bottomCalcFormatter: formatInteger
                },
                {
                    title: "PKK TERSERANG",
                    field: "pkk_terserang",
                    minWidth: 180,
                    headerHozAlign: "center",
                    hozAlign: "right",
                    sorter: "number",
                    formatter: formatInteger,
                    bottomCalc: "sum",
                    bottomCalcFormatter: formatInteger
                },
                {
                    title: "% TERSERANG",
                    field: "persen_terserang",
                    minWidth: 165,
                    headerHozAlign: "center",
                    hozAlign: "right",
                    sorter: "number",
                    formatter: formatPercent,
                    bottomCalc: calculateTotalPercentage,
                    bottomCalcFormatter: formatPercent
                }
            ]
        }
    );

    var searchInput = document.getElementById(
        "search-rekap-hpt"
    );

    var clearSearchButton = document.getElementById(
        "clear-search-rekap-hpt"
    );

    function applyRekapHPTSearch() {
        var keyword = searchInput.value
            .trim()
            .toLowerCase();

        if (keyword === "") {
            rekapHPTTable.clearFilter();
            return;
        }

        rekapHPTTable.setFilter(function (data) {
            var values = [
                data.kebun,
                data.jumlah_pkk,
                data.pkk_terserang,
                data.persen_terserang
            ];

            return values.some(function (value) {
                return String(value || "")
                    .toLowerCase()
                    .indexOf(keyword) !== -1;
            });
        });
    }

    searchInput.addEventListener("input", function () {
        applyRekapHPTSearch();
    });

    clearSearchButton.addEventListener(
        "click",
        function () {
            searchInput.value = "";
            rekapHPTTable.clearFilter();
            searchInput.focus();
        }
    );
});
</script>

@endsection
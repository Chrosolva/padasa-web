@extends('dashboard.app')

@section('header-title')
    Detail HPT
@endsection

@section('main-content')

{{-- Hapus apabila Tabulator sudah dipanggil secara global --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css"
>

<style>
    #detail-hpt-table {
        width: 100%;
        min-height: 100px;
    }

    #detail-hpt-table .tabulator {
        border: 1px solid #d2d6de;
        font-size: 13px;
    }

    #detail-hpt-table .tabulator-header {
        background-color: #f4f4f4;
        font-weight: 600;
    }

    #detail-hpt-table .tabulator-header .tabulator-col {
        background-color: #f4f4f4;
    }

    #detail-hpt-table .tabulator-calcs-holder {
        background-color: #ecf0f5;
        font-weight: 700;
    }

    #detail-hpt-table .tabulator-calcs {
        font-weight: 700;
    }

    .filter-button-group {
        margin-top: 24px;
    }

    #detail-hpt-table .tabulator-group {
        padding: 6px 10px;
        background-color: #eef4f8;
        border-top: 1px solid #d2d6de;
        border-bottom: 1px solid #d2d6de;
        font-size: 12px;
    }

    #detail-hpt-table .tabulator-group:hover {
        background-color: #e5eef5;
    }

    #detail-hpt-table
    .tabulator-group
    .tabulator-calcs-holder {
        background-color: #f3f3f3;
        font-weight: 700;
    }

    #detail-hpt-table
    .tabulator-footer
    .tabulator-calcs-holder {
        background-color: #d9edf7;
        border-top: 2px solid #3c8dbc;
        font-weight: 700;
    }

    @media (max-width: 767px) {
        .filter-button-group {
            margin-top: 10px;
        }
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

    @media (max-width: 767px) {
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
        Detail Hama &amp; Penyakit Tumbuhan
        <small>Detail HPT per blok</small>
    </h1>

    <ol class="breadcrumb">
        <li>
            <a href="{{ url('/dashboard') }}">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li>Hama &amp; Penyakit Tumbuhan</li>
        <li class="active">Detail HPT</li>
    </ol>
</section>

<section class="content">

    @if (!empty($errorMessage))
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

            {{ $errorMessage }}
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
                action="{{ route('hpt.detail') }}"
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
                    <label for="site_id">Kebun:</label>

                    <select
                        class="form-control input-sm"
                        id="site_id"
                        name="site_id"
                        required
                    >
                        @foreach ($daftarKebun as $kode => $nama)
                            <option
                                value="{{ $kode }}"
                                {{ (string) $siteId === (string) $kode ? 'selected' : '' }}
                            >
                                {{ $nama }} — {{ $kode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenis_penyakit">Jenis Penyakit:</label>

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

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-search"></i> Tampilkan
                </button>

                <a
                    href="{{ route('hpt.detail') }}"
                    class="btn btn-default btn-sm"
                >
                    <i class="fa fa-refresh"></i> Reset
                </a>

                {{-- <a
                    href="{{ route('hpt.rekap') }}"
                    class="btn btn-primary btn-sm"
                >
                    <i class="fa fa-table"></i> Rekap HPT
                </a> --}}
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-bug"></i>

                Detail HPT
                {{ $daftarKebun[$siteId] ?? $siteId }}

                per
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
                        id="search-detail-hpt"
                        class="form-control"
                        placeholder="Cari kode site atau data lainnya..."
                        autocomplete="off"
                    >

                    <span class="input-group-btn">
                        <button
                            type="button"
                            id="clear-search-detail-hpt"
                            class="btn btn-default"
                            title="Hapus pencarian"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div id="detail-hpt-table"></div>
        </div>

        <h5> Catatan : %Terserang = Jumlah Pokok sakit / total Pokok</h5>
    </div>
</section>
<style>
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

    .compact-filter #site_id {
        width: 190px;
    }

    .compact-filter .btn {
        margin-right: 3px;
    }

    @media (max-width: 767px) {
        .compact-filter .form-group {
            display: block;
            margin-right: 0;
            margin-bottom: 8px;
        }

        .compact-filter #endDate,
        .compact-filter #site_id {
            width: 100%;
        }
    }
</style>
{{-- Hapus apabila Tabulator sudah dipanggil secara global --}}
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = @json($detailHPT);

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

    function formatDate(cell) {
        var value = cell.getValue();

        if (!value) {
            return "-";
        }

        var parts = value.split("-");

        if (parts.length !== 3) {
            return value;
        }

        return parts[2] + "-" + parts[1] + "-" + parts[0];
    }

    /*
     * Total persentase dihitung berdasarkan:
     *
     * total pokok sakit / total pokok × 100
     *
     * Bukan menjumlahkan persentase setiap blok.
     */
    function calculateTotalPercentage(values, data) {
        var totalPokokSakit = 0;
        var totalPokok = 0;

        data.forEach(function (row) {
            totalPokokSakit += Number(
                row.jumlah_pokok_sakit || 0
            );

            totalPokok += Number(
                row.total_pokok || 0
            );
        });

        if (totalPokok === 0) {
            return 0;
        }

        return (totalPokokSakit / totalPokok) * 100;
    }

    if (typeof Tabulator === "undefined") {
        document.getElementById("detail-hpt-table").innerHTML =
            '<div class="alert alert-danger">' +
                '<i class="fa fa-warning"></i> ' +
                'Library Tabulator gagal dimuat.' +
            '</div>';

        return;
    }

    var detailHPTTable = new Tabulator("#detail-hpt-table", {
        data: tableData,
        layout: "fitData",

        pagination: "local",
        paginationSize: true,
        paginationSizeSelector: [10, 25, 50, true],

        height: "500px",

        placeholder: "Tidak ada data Detail HPT.",
        movableColumns: true,
        resizableColumns: true,
        columnHeaderVertAlign: "middle",
        columnCalcs: "both",

        groupBy: "tahun_tanam",

        groupStartOpen: true,

        groupHeader: function (
            value,
            count
        ) {
            return (
                "<strong>TAHUN TANAM "
                + value
                + "</strong>"
                + " <span style='"
                + "color:#999;"
                + "font-weight:normal;"
                + "'>"
                + count
                + " blok"
                + "</span>"
            );
        },

        initialSort: [
            {
                column: "tahun_tanam",
                dir: "asc"
            },
            {
                column: "kodesite",
                dir: "asc"
            }
        ],

        columns: [
            {
                title: "TAHUN TANAM",
                field: "tahun_tanam",
                sorter: "number",
                visible: false
            },
            {
                title: "KODE BLOK",
                field: "kodesite",
                minWidth: 130,
                frozen: true,
                headerHozAlign: "center",
                hozAlign: "left",
                sorter: "string",
                bottomCalc: function () {
                    return "SUBTOTAL";
                }
            },
            {
                title: "JUMLAH PKK",
                field: "total_pokok",
                minWidth: 150,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatInteger,
                bottomCalc: "sum",
                bottomCalcFormatter: formatInteger
            },
            {
                title: "RINGAN",
                field: "ringan",
                minWidth: 160,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatInteger,
                bottomCalc: "sum",
                bottomCalcFormatter: formatInteger
            },
            {
                title: "SEDANG",
                field: "sedang",
                minWidth: 160,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatInteger,
                bottomCalc: "sum",
                bottomCalcFormatter: formatInteger
            },
            {
                title: "BERAT",
                field: "berat",
                minWidth: 160,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatInteger,
                bottomCalc: "sum",
                bottomCalcFormatter: formatInteger
            },
            {
                title: "MATI",
                field: "mati",
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
                field: "jumlah_pokok_sakit",
                minWidth: 190,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatInteger,
                bottomCalc: "sum",
                bottomCalcFormatter: formatInteger
            },
            {
                title: "PERSEN SAKIT",
                field: "persen_sakit",
                minWidth: 155,
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatPercent,
                bottomCalc: calculateTotalPercentage,
                bottomCalcFormatter: formatPercent
            }
        ]
    });

    var searchInput = document.getElementById("search-detail-hpt");
    var clearSearchButton = document.getElementById(
        "clear-search-detail-hpt"
    );

    function updateDetailCalculationLabels() {
        var tableElement =
            document.getElementById(
                "detail-hpt-table"
            );

        if (!tableElement) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya footer utama = GRAND TOTAL
        |--------------------------------------------------------------------------
        |
        | BottomCalc pada masing-masing group tetap SUBTOTAL.
        |
        */

        var footerCalcRows =
            tableElement.querySelectorAll(
                ".tabulator-footer "
                + ".tabulator-calcs-bottom"
            );

        footerCalcRows.forEach(function (calcRow) {
            var cells =
                calcRow.querySelectorAll(
                    ".tabulator-cell"
                );

            cells.forEach(function (cell) {
                if (
                    cell.textContent.trim()
                    === "SUBTOTAL"
                ) {
                    cell.textContent =
                        "GRAND TOTAL";
                }
            });
        });
    }

    detailHPTTable.on(
        "tableBuilt",
        function () {
            setTimeout(
                updateDetailCalculationLabels,
                0
            );
        }
    );

    detailHPTTable.on(
        "renderComplete",
        function () {
            setTimeout(
                updateDetailCalculationLabels,
                0
            );
        }
    );

    detailHPTTable.on(
        "dataProcessed",
        function () {
            setTimeout(
                updateDetailCalculationLabels,
                0
            );
        }
    );

    detailHPTTable.on(
        "pageLoaded",
        function () {
            setTimeout(
                updateDetailCalculationLabels,
                0
            );
        }
    );

    function applyDetailHPTSearch() {
        var keyword = searchInput.value.trim().toLowerCase();

        if (keyword === "") {
            detailHPTTable.clearFilter();
            return;
        }

        detailHPTTable.setFilter(function (data) {
            var values = [
                data.tahun_tanam,
                data.kodesite,
                data.jumlah_pokok_sakit,
                data.ringan,
                data.sedang,
                data.berat,
                data.mati,
                data.total_pokok,
                data.persen_sakit
            ];

            return values.some(function (value) {
                return String(value || "")
                    .toLowerCase()
                    .indexOf(keyword) !== -1;
            });
        });
    }

    searchInput.addEventListener("keyup", function () {
        applyDetailHPTSearch();
    });

    clearSearchButton.addEventListener("click", function () {
        searchInput.value = "";
        detailHPTTable.clearFilter();
        searchInput.focus();
    });
});
</script>

@endsection
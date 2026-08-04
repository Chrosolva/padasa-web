@extends('dashboard.app')

@section('header-title')
    Proporsi Produksi P3 Harian
@endsection

@section('main-content')

@php
    $selectedKebun = Request::get('selectkebun') ?: 2200;
    $selectedHarga = Request::get('harga') ?: 5800;

    /*
        Data untuk Tabulator.
        Angka disimpan sebagai angka asli agar sorting tetap benar.
        Format ribuan/desimal dilakukan di JavaScript formatter.
    */
    $tableData = [];

    $totalTbsTerima = 0;
    $totalTbsOlahProporsi = 0;
    $totalCpoTarget = 0;
    $totalCpoProporsi = 0;
    $totalSelisih = 0;
    $totalHarga = 0;

    foreach ($data as $row) {
        $selisih = (float)($row->SELISIH ?? 0);

        $tanggalDisplay = '';
        $tanggalSort = '';

        if (!empty($row->TANGGAL)) {
            $tanggalDisplay = date('d/m/Y', strtotime($row->TANGGAL));
            $tanggalSort = date('Y-m-d', strtotime($row->TANGGAL));
        }

        $tableData[] = [
            'INDEX' => $row->INDEX ?? '~',
            'SITE_ID' => $row->SITE_ID ?? '',
            'TAHUN' => (float)($row->TAHUN ?? 0),
            'BULAN' => (float)($row->BULAN ?? 0),
            'TANGGAL' => $tanggalDisplay,
            'TANGGAL_SORT' => $tanggalSort,
            'SUPPLIERCODE' => $row->SUPPLIERCODE ?? '',
            'NAMA' => $row->NAMA ?? '',

            'TBSTERIMA' => (float)($row->TBSTERIMA ?? 0),
            'TBS_OLAH_PROPORSI' => (float)($row->TBS_OLAH_PROPORSI ?? 0),
            'CPO_TARGET' => (float)($row->CPO_TARGET ?? 0),
            'CPO_PROPORSI' => (float)($row->CPO_PROPORSI ?? 0),
            'TARGET' => (float)($row->TARGET ?? 0),
            'REND_PROPORSI' => (float)($row->REND_PROPORSI ?? 0),
            'SELISIH' => $selisih,
            'SELISIH_REND' => (float)($row->SELISIH_REND ?? 0),
            'HARGA' => (float)($row->HARGA ?? 0),

            '_rowClass' => $selisih < 0 ? 'row-minus' : ($selisih > 0 ? 'row-plus' : '')
        ];

        $totalTbsTerima += (float)($row->TBSTERIMA ?? 0);
        $totalTbsOlahProporsi += (float)($row->TBS_OLAH_PROPORSI ?? 0);
        $totalCpoTarget += (float)($row->CPO_TARGET ?? 0);
        $totalCpoProporsi += (float)($row->CPO_PROPORSI ?? 0);
        $totalSelisih += (float)($row->SELISIH ?? 0);
        $totalHarga += (float)($row->HARGA ?? 0);
    }

    $totalTarget = $totalTbsOlahProporsi > 0
        ? ($totalCpoTarget / $totalTbsOlahProporsi) * 100
        : 0;

    $totalRendProporsi = $totalTbsOlahProporsi > 0
        ? ($totalCpoProporsi / $totalTbsOlahProporsi) * 100
        : 0;

    $totalSelisihRend = $totalRendProporsi - $totalTarget;
@endphp

<style>
    .filter-panel {
        border: 0;
        border-radius: 4px;
        box-shadow: none;
        margin-bottom: 20px;
    }

    .filter-panel .panel-body {
        padding: 16px 18px;
    }

    .filter-panel .form-inline {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px 24px;
    }

    .filter-panel .form-group {
        margin-right: 0;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-panel label {
        margin: 0;
        font-size: 13px;
        white-space: nowrap;
    }

    .filter-panel .form-control,
    .filter-panel .input-group-addon,
    .filter-panel .btn {
        height: 38px;
    }

    .filter-panel .form-control {
        font-size: 13px;
        padding: 7px 12px;
    }

    .filter-panel .input-group-addon {
        min-width: 42px;
        padding: 8px 12px;
    }

    .filter-panel .btn {
        padding: 8px 13px;
    }

    #selectkebun {
        min-width: 155px;
    }

    .content-table-wrap {
        padding-left: 0;
        padding-right: 0;
    }

    .proporsi-box {
        border-top: 3px solid #00a65a;
        box-shadow: none;
    }

    .proporsi-box .box-header {
        padding: 12px 15px 8px 15px;
    }

    .proporsi-box .box-body {
        padding: 12px;
    }

    .table-note {
        margin: 8px 12px 12px 12px;
        font-size: 14px;
        font-weight: bold;
    }

    #table-data-tabulator {
        border: 1px solid #d2d6de;
        font-size: 13px;
        background: #fff;
    }

    #table-data-tabulator .tabulator-header {
        background: #f4f6f9;
        border-bottom: 1px solid #d2d6de;
        font-weight: 700;
    }

    #table-data-tabulator .tabulator-col {
        background: #f4f6f9;
        border-right: 1px solid #d2d6de;
    }

    #table-data-tabulator .tabulator-col-title {
        text-align: center;
        white-space: normal;
        line-height: 1.2;
    }

    #table-data-tabulator .tabulator-cell {
        border-right: 1px solid #e0e0e0;
        padding: 7px 8px;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    #table-data-tabulator .tabulator-row {
        border-bottom: 1px solid #e0e0e0;
    }

    #table-data-tabulator .tabulator-row:hover {
        background: #f5fbff !important;
    }

    #table-data-tabulator .row-minus {
        background: #fff3f3 !important;
    }

    #table-data-tabulator .row-plus {
        background: #f3fff5 !important;
    }

    #table-data-tabulator .tabulator-footer {
        border-top: 1px solid #d2d6de;
        background: #fff;
        padding: 8px;
    }

    #table-data-tabulator .tabulator-calcs {
        background: #f9fafc;
        font-weight: 700;
    }

    #table-data-tabulator .tabulator-calcs .tabulator-cell {
        background: #f9fafc;
        border-top: 1px solid #b5b5b5;
    }

    #table-data-tabulator .tabulator-calcs .tabulator-cell:first-child {
        text-align: center;
    }

    #table-data-tabulator .tabulator-page {
        border: 1px solid #ddd;
        background: #fff;
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 3px;
    }

    #table-data-tabulator .tabulator-page.active {
        background: #3c8dbc;
        color: #fff;
        border-color: #367fa9;
    }

    .tabulator-search-area {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .tabulator-search-area label {
        margin: 0;
        font-weight: normal;
    }

    .tabulator-search-area input {
        width: 220px;
        height: 32px;
        border: 1px solid #d2d6de;
        padding: 6px 10px;
        box-shadow: none;
    }

    .total-table {
        margin-top: 18px;
        margin-bottom: 0;
        border-collapse: collapse !important;
    }

    .total-table th,
    .total-table td {
        border: 1px solid #d2d6de !important;
        padding: 8px 10px !important;
        font-size: 12px;
        white-space: nowrap;
        vertical-align: middle !important;
    }

    .total-table thead th {
        text-align: center !important;
        background: #f4f6f9;
        font-weight: 700;
    }

    .total-title th {
        background: #dff0d8 !important;
        font-size: 13px;
    }

    .col-num {
        text-align: right !important;
        font-variant-numeric: tabular-nums;
    }

    @media (max-width: 991px) {
        .filter-panel .form-inline {
            display: block;
        }

        .filter-panel .form-group {
            display: block;
            margin-bottom: 10px;
        }

        .filter-panel .form-control,
        .filter-panel .input-group {
            width: 100% !important;
        }

        .tabulator-search-area {
            justify-content: flex-start;
        }

        .tabulator-search-area input {
            width: 100%;
        }
    }
</style>

<section class="content-header">
    <h1>
        Proporsi Produksi P3 Harian
    </h1>
</section>

<section class="content">

    <div class="panel filter-panel">
        <div class="panel-body">
            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/lhpexecutive/proporsiProduksiP3Harian') }}">

                <div class="form-group">
                    <label for="dari_tanggal">Dari Tanggal :</label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="dari_tanggal"
                               name="dari_tanggal"
                               value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="sampai_tanggal">Sampai Tanggal :</label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="sampai_tanggal"
                               name="sampai_tanggal"
                               value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectkebun">Kebun :</label>
                    <select class="form-control" id="selectkebun" name="selectkebun">
                        @foreach($kebun as $row)
                            <option value="{{ $row->site_id }}" {{ (string)$selectedKebun === (string)$row->site_id ? 'selected' : '' }}>
                                {{ $row->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display:none;">
                    <label for="harga">Harga :</label>
                    <input type="number"
                           step="100"
                           value="{{ $selectedHarga }}"
                           id="harga"
                           name="harga">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="col-md-10 content-table-wrap">
        <div class="box box-success proporsi-box">
            <div class="box-header">
                <h3 class="box-title">
                    <b>Detail Proporsi Produksi P3 Harian</b>
                </h3>
            </div>

            <div class="box-body">
                <div class="tabulator-search-area">
                    <label for="table-search">Search:</label>
                    <input type="text" id="table-search" placeholder="Cari data...">
                </div>

                <div id="table-data-tabulator"></div>

                </div>
            </div>

            <p class="table-note">
                Perhitungan menggunakan proporsi produksi Pihak 3 harian.
            </p>
        </div>
    </div>

</section>
@endsection

@section('script-content')
<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

    var tableData = @json($tableData);

    function formatNumber(value, decimals) {
        var numberValue = parseFloat(value || 0);

        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(numberValue);
    }

    function numberFormatter(decimals) {
        return function(cell) {
            return formatNumber(cell.getValue(), decimals);
        };
    }

    function sumField(values, data, fieldName) {
        var total = 0;

        data.forEach(function(row) {
            total += parseFloat(row[fieldName] || 0);
        });

        return total;
    }

    function calcTargetPercent(values, data) {
        var totalCpoTarget = sumField(values, data, 'CPO_TARGET');
        var totalTbsOlahProporsi = sumField(values, data, 'TBS_OLAH_PROPORSI');

        return totalTbsOlahProporsi > 0
            ? (totalCpoTarget / totalTbsOlahProporsi) * 100
            : 0;
    }

    function calcRendProporsiPercent(values, data) {
        var totalCpoProporsi = sumField(values, data, 'CPO_PROPORSI');
        var totalTbsOlahProporsi = sumField(values, data, 'TBS_OLAH_PROPORSI');

        return totalTbsOlahProporsi > 0
            ? (totalCpoProporsi / totalTbsOlahProporsi) * 100
            : 0;
    }

    function calcSelisihRend(values, data) {
        return calcRendProporsiPercent(values, data) - calcTargetPercent(values, data);
    }

    function dateSorter(a, b, aRow, bRow) {
        var dateA = aRow.getData().TANGGAL_SORT || '';
        var dateB = bRow.getData().TANGGAL_SORT || '';

        if (dateA === dateB) {
            return 0;
        }

        return dateA > dateB ? 1 : -1;
    }

    $(document).ready(function () {
        var table = new Tabulator("#table-data-tabulator", {
            data: tableData,

            /*
                fitDataStretch = kolom mengikuti isi data, tapi tetap stretch ke lebar container.
                User tetap bisa resize kolom manual dengan drag garis header.
            */
            layout: "fitData",

            height: "520px",
            movableColumns: true,
            resizableColumns: true,

            pagination: "local",
            paginationSize: 25,
            paginationSizeSelector: [10, 25, 50, 100, true],

            columnCalcs: "bottom",

            initialSort: [
                { column: "TANGGAL", dir: "asc" }
            ],

            placeholder: "Data tidak ditemukan",

            rowFormatter: function(row) {
                var data = row.getData();

                if (data._rowClass) {
                    row.getElement().classList.add(data._rowClass);
                }
            },

            columns: [
                { 
                    title: "NO", 
                    field: "INDEX", 
                    width: 55,
                    minWidth: 50
                },
                { title: "SITE ID", field: "SITE_ID", visible: false },
                { title: "TAHUN", field: "TAHUN", visible: false },
                { title: "BULAN", field: "BULAN", visible: false },
                {
                    title: "TGL",
                    field: "TANGGAL",
                    width: 80,
                    minWidth: 80,
                    hozAlign: "center",
                    sorter: dateSorter,
                    headerSort: true,
                    bottomCalc: function() {
                        return "TOTAL";
                    },
                    bottomCalcFormatter: function(cell) {
                        return "<b>TOTAL</b>";
                    }
                },
                { title: "SUPPLIER CODE", field: "SUPPLIERCODE", visible: false },
                {
                    title: "NAMA",
                    field: "NAMA",
                    width: 90,
                    minWidth: 90,
                    hozAlign: "left",
                    // headerFilter: "input",
                    bottomCalc: function() {
                        return "";
                    }
                },
                {
                    title: "TBS<br>TERIMA<br>[KG]",
                    field: "TBSTERIMA",
                    width: 115,
                    minWidth: 110,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                },
                {
                    title: "TBS OLAH<br>PROPORSI<br>[KG]",
                    field: "TBS_OLAH_PROPORSI",
                    width: 110,
                    minWidth: 125,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                },
                {
                    title: "REND MS<br>TARGET(%)",
                    field: "TARGET",
                    width: 110,
                    minWidth: 90,
                    hozAlign: "right",
                    formatter: numberFormatter(2),
                    bottomCalc: calcTargetPercent,
                    bottomCalcFormatter: numberFormatter(2)
                },
                {
                    title: "MS<br>TARGET<br>[KG]",
                    field: "CPO_TARGET",
                    width: 110,
                    minWidth: 110,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                },
                {
                    title: "MS<br>PROPORSI<br>REAL[KG]",
                    field: "CPO_PROPORSI",
                    width: 110,
                    minWidth: 110,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                },
                {
                    title: "REND MS<br>PROPORSI<br>REAL(%)",
                    field: "REND_PROPORSI",
                    width: 110,
                    minWidth: 105,
                    hozAlign: "right",
                    formatter: numberFormatter(2),
                    bottomCalc: calcRendProporsiPercent,
                    bottomCalcFormatter: numberFormatter(2)
                },
                {
                    title: "SELISIH<br>MS[KG]",
                    field: "SELISIH",
                    width: 110,
                    minWidth: 110,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                },
                {
                    title: "SELISIH<br>REND<br>[%]",
                    field: "SELISIH_REND",
                    width: 110,
                    minWidth: 100,
                    hozAlign: "right",
                    formatter: numberFormatter(2),
                    bottomCalc: calcSelisihRend,
                    bottomCalcFormatter: numberFormatter(2)
                },
                {
                    title: "BONUS<br>/ DENDA<br>[RP]",
                    field: "HARGA",
                    width: 120,
                    minWidth: 120,
                    hozAlign: "right",
                    formatter: numberFormatter(0),
                    bottomCalc: "sum",
                    bottomCalcFormatter: numberFormatter(0)
                }
            ]
        });

        /* Search global sederhana. */
        $('#table-search').on('keyup', function () {
            var keyword = $(this).val();

            if (!keyword) {
                table.clearFilter();
                return;
            }

            keyword = keyword.toString().toLowerCase();

            table.setFilter(function(data) {
                return Object.keys(data).some(function(key) {
                    if (key === '_rowClass' || key === 'TANGGAL_SORT') {
                        return false;
                    }

                    return String(data[key] || '').toLowerCase().indexOf(keyword) > -1;
                });
            });
        });

        $('#selectkebun').val("{{ $selectedKebun }}");
        $('#harga').val("{{ $selectedHarga }}");
    });
</script>
@endsection

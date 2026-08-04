@extends('dashboard.app')

@section('header-title')
    Pencapaian Produksi TBS
@endsection

@section('main-content')

@php
    $selectTBS   = Request::get('selectTBS') ?: 'A';
    $selectjenis = Request::get('selectjenis') ?: '1';
    $selectkebun = Request::get('selectkebun') ?: 'A1';
@endphp

<style>
    /*
        FIX UTAMA:
        1. Jangan pakai responsive:true bersamaan dengan scrollX DataTables.
        2. Paksa table width 100% dan border-collapse.
        3. Rapikan wrapper scrollHead / scrollBody bawaan DataTables.
    */

    .filter-panel {
        border: 0;
        border-radius: 4px;
        box-shadow: none;
        margin-bottom: 20px;
    }

    .filter-panel .panel-body {
        padding: 16px;
    }

    .filter-panel .form-group {
        margin-right: 14px;
        margin-bottom: 8px;
        vertical-align: middle;
    }

    .filter-panel label {
        margin-right: 5px;
        font-size: 12px;
    }

    .input-inline .form-control {
        height: 34px;
    }

    .compact-box {
        min-width: 0;
    }

    .pencapaian-box {
        border-top: 3px solid #3c8dbc;
        box-shadow: none;
    }

    .pencapaian-box .box-body {
        padding: 10px;
        overflow: hidden;
    }

    /* Wrapper DataTables */
    #table-data_wrapper {
        width: 100%;
    }

    #table-data_wrapper .row {
        margin-left: 0;
        margin-right: 0;
    }

    #table-data_wrapper .col-sm-6,
    #table-data_wrapper .col-sm-12 {
        padding-left: 0;
        padding-right: 0;
    }

    #table-data_wrapper .dataTables_length,
    #table-data_wrapper .dataTables_filter {
        margin-bottom: 10px;
    }

    #table-data_wrapper .dataTables_filter {
        text-align: right;
    }

    #table-data_wrapper .dataTables_filter input {
        height: 30px;
        border: 1px solid #d2d6de;
        box-shadow: none;
        margin-left: 6px;
    }

    #table-data_wrapper .dataTables_length select {
        height: 30px;
        border: 1px solid #d2d6de;
        box-shadow: none;
    }

    /* Scroll wrapper bawaan DataTables */
    #table-data_wrapper .dataTables_scroll {
        border-left: 1px solid #d2d6de;
        border-right: 1px solid #d2d6de;
        border-bottom: 1px solid #d2d6de;
    }

    #table-data_wrapper .dataTables_scrollHead {
        background: #f9fafc;
        border-top: 1px solid #d2d6de !important;
        border-bottom: 0 !important;
    }

    #table-data_wrapper .dataTables_scrollHeadInner,
    #table-data_wrapper .dataTables_scrollHeadInner table {
        width: 100% !important;
    }

    #table-data_wrapper .dataTables_scrollBody {
        border-top: 0 !important;
    }

    /*
        DataTables dengan scrollX membuat 2 table:
        - table header asli di .dataTables_scrollHead
        - table body di .dataTables_scrollBody

        Di table body, DataTables menyimpan thead dummy/clone.
        Kalau CSS global kita memberi padding/border ke semua th,
        thead dummy ini muncul seperti 1 baris kosong extra.
        Block ini menyembunyikan thead dummy tersebut.
    */
    #table-data_wrapper .dataTables_scrollBody thead,
    #table-data_wrapper .dataTables_scrollBody thead tr,
    #table-data_wrapper .dataTables_scrollBody thead th {
        height: 0 !important;
        max-height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        border-top: 0 !important;
        border-bottom: 0 !important;
        line-height: 0 !important;
        overflow: hidden !important;
    }

    #table-data_wrapper .dataTables_scrollBody thead th div {
        height: 0 !important;
        max-height: 0 !important;
        line-height: 0 !important;
        overflow: hidden !important;
    }

    /* Table utama */
    #table-data {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        table-layout: auto;
    }

    #table-data th,
    #table-data td {
        border: 1px solid #d2d6de !important;
        padding: 8px 10px !important;
        font-size: 12px;
        line-height: 1.35;
        white-space: nowrap;
        vertical-align: middle !important;
    }

    #table-data thead th {
        background: #f4f6f9;
        color: #333;
        font-weight: 700;
        text-align: center !important;
        border-bottom: 1px solid #d2d6de !important;
    }

    #table-data tbody td {
        background: #fff;
    }

    #table-data tbody tr:nth-child(even) td {
        background: #fafafa;
    }

    #table-data tbody tr:hover td {
        background: #f5fbff;
    }

    #table-data tbody tr.row-total td {
        background: #eef7ff !important;
        font-weight: 700;
    }

    .dt-center {
        text-align: center !important;
    }

    .dt-right {
        text-align: right !important;
        font-variant-numeric: tabular-nums;
    }

    .dt-left {
        text-align: left !important;
    }

    #table-data_wrapper .dataTables_info {
        padding-top: 10px;
        font-size: 12px;
    }

    #table-data_wrapper .dataTables_paginate {
        padding-top: 8px;
    }

    #table-data_wrapper .pagination {
        margin: 0;
    }

    /* Supaya tampilan tetap enak kalau layar kecil */
    @media (max-width: 991px) {
        .filter-panel .form-group {
            display: block;
            margin-right: 0;
        }

        .filter-panel .form-control,
        .filter-panel .input-group {
            width: 100% !important;
        }

        #table-data_wrapper .dataTables_filter {
            text-align: left;
            margin-top: 8px;
        }
    }
</style>

<section class="content-header">
    <h1>
        Pencapaian Produksi TBS
    </h1>
</section>

<section class="content">

    <div class="panel filter-panel">
        <div class="panel-body">
            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/produksi/lhpPencapaianProduksiMain') }}">

                <div class="form-group">
                    <label for="dari_tanggal">Dari Tanggal :</label>
                    <div class="input-group date input-inline" style="width: 180px;">
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
                    <div class="input-group date input-inline" style="width: 180px;">
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
                    <label for="selectTBS">TBS :</label>
                    <select class="form-control" id="selectTBS" name="selectTBS">
                        <option value="A" {{ $selectTBS == 'A' ? 'selected' : '' }}>KEBUN INTI</option>
                        <option value="B" {{ $selectTBS == 'B' ? 'selected' : '' }}>PIHAK 3</option>
                        <option value="C" {{ $selectTBS == 'C' ? 'selected' : '' }}>MITRA</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="selectjenis">Jenis :</label>
                    <select class="form-control" id="selectjenis" name="selectjenis">
                        <option value="1" {{ $selectjenis == '1' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="2" {{ $selectjenis == '2' ? 'selected' : '' }}>Per Quartal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="selectkebun">Kebun :</label>
                    <select class="form-control" id="selectkebun" name="selectkebun">
                        <option value="A1" class="opt-kebun opt-A" {{ $selectkebun == 'A1' ? 'selected' : '' }}>TELDA</option>
                        <option value="A2" class="opt-kebun opt-A" {{ $selectkebun == 'A2' ? 'selected' : '' }}>KALSA</option>
                        <option value="A3" class="opt-kebun opt-A" {{ $selectkebun == 'A3' ? 'selected' : '' }}>KALDA</option>
                        <option value="A4" class="opt-kebun opt-A" {{ $selectkebun == 'A4' ? 'selected' : '' }}>KOKAR</option>
                        <option value="A5" class="opt-kebun opt-A" {{ $selectkebun == 'A5' ? 'selected' : '' }}>RICKO</option>
                        <option value="A6" class="opt-kebun opt-A" {{ $selectkebun == 'A6' ? 'selected' : '' }}>MUARA</option>
                        <option value="A7" class="opt-kebun opt-A" {{ $selectkebun == 'A7' ? 'selected' : '' }}>PASER</option>
                        <option value="A8" class="opt-kebun opt-A" {{ $selectkebun == 'A8' ? 'selected' : '' }}>LANGGAI</option>

                        <option value="B1" class="opt-kebun opt-B" {{ $selectkebun == 'B1' ? 'selected' : '' }}>P3 TELDA</option>
                        <option value="B2" class="opt-kebun opt-B" {{ $selectkebun == 'B2' ? 'selected' : '' }}>P3 KALSA</option>
                        <option value="B3" class="opt-kebun opt-B" {{ $selectkebun == 'B3' ? 'selected' : '' }}>P3 KALDA</option>
                        <option value="B4" class="opt-kebun opt-B" {{ $selectkebun == 'B4' ? 'selected' : '' }}>P3 KOKAR</option>
                        <option value="B5" class="opt-kebun opt-B" {{ $selectkebun == 'B5' ? 'selected' : '' }}>P3 RICKO</option>
                        <option value="B6" class="opt-kebun opt-B" {{ $selectkebun == 'B6' ? 'selected' : '' }}>P3 PASER</option>

                        <option value="C1" class="opt-kebun opt-C" {{ $selectkebun == 'C1' ? 'selected' : '' }}>MITRA KOKAR</option>
                        <option value="C6" class="opt-kebun opt-C" {{ $selectkebun == 'C6' ? 'selected' : '' }}>MITRA KALDA</option>
                        <option value="C2" class="opt-kebun opt-C" {{ $selectkebun == 'C2' ? 'selected' : '' }}>MITRA RICKO</option>
                        <option value="C3" class="opt-kebun opt-C" {{ $selectkebun == 'C3' ? 'selected' : '' }}>MITRA MUARA</option>
                        <option value="C4" class="opt-kebun opt-C" {{ $selectkebun == 'C4' ? 'selected' : '' }}>MITRA PASER</option>
                        <option value="C5" class="opt-kebun opt-C" {{ $selectkebun == 'C5' ? 'selected' : '' }}>MITRA LANGGAI</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 compact-box">
            <div class="box box-primary pencapaian-box">
                <div class="box-body">

                    <table id="table-data" class="table table-bordered table-hover" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="dt-center">TAHUN</th>

                                @if($selectjenis == '1')
                                    <th class="dt-center">BULAN</th>
                                @else
                                    <th class="dt-left">KEBUN</th>
                                @endif

                                <th class="dt-right">RKAP <br>[KG]</th>
                                <th class="dt-right">REALISASI <br>[KG]</th>
                                <th class="dt-right">PENCAPAIAN <br>[%]</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($lhp_PencapaianProduksi as $row)
                                @php
                                    $isTotal = ((float)($row->TAHUN ?? 0) == 0);
                                @endphp

                                <tr class="{{ $isTotal ? 'row-total' : '' }}">
                                    @if ($isTotal)
                                        <td class="dt-center">TOTAL</td>
                                        <td class="dt-center">-</td>
                                    @else
                                        <td class="dt-center">{{ number_format((float)$row->TAHUN, 0, ',', '') }}</td>

                                        @if($selectjenis == '1')
                                            <td class="dt-center">{{ number_format((float)$row->BULAN, 0, ',', '.') }}</td>
                                        @else
                                            <td class="dt-left">{{ $row->KEBUN }}</td>
                                        @endif
                                    @endif

                                    <td class="dt-right">{{ number_format((float)$row->RKAP, 0, ',', '.') }}</td>
                                    <td class="dt-right">{{ number_format((float)$row->REALISASI, 0, ',', '.') }}</td>
                                    <td class="dt-right">{{ number_format((float)$row->PENCAPAIAN, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('script-content')
<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

    var selectedTBS = "{{ $selectTBS }}";
    var selectedJenis = "{{ $selectjenis }}";
    var selectedKebun = "{{ $selectkebun }}";

    function refreshKebunOptions(resetValue) {
        var tbs = $('#selectTBS').val();

        $('#selectkebun option').hide();
        $('#selectkebun option.opt-' + tbs).show();

        var selectedOptionIsVisible = $('#selectkebun option:selected').hasClass('opt-' + tbs);

        if (resetValue || !selectedOptionIsVisible) {
            var firstValue = $('#selectkebun option.opt-' + tbs + ':first').val();
            $('#selectkebun').val(firstValue);
        }
    }

    function initPencapaianDataTable() {
        var tableId = '#table-data';

        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().clear().destroy();
        }

        var table = $(tableId).DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,

            /*
                Penting:
                responsive:false karena responsive:true sering bentrok dengan scrollX,
                efeknya border/header/body DataTables jadi tampak pecah atau tidak sejajar.
            */
            responsive: false,

            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,
            destroy: true,

            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
            pageLength: -1,

            columnDefs: [
                { targets: 0, className: 'dt-center' },
                { targets: 1, className: selectedJenis == '1' ? 'dt-center' : 'dt-left' },
                { targets: [2, 3, 4], className: 'dt-right' }
            ],

            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Search:',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 to 0 of 0 entries',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    previous: 'Previous',
                    next: 'Next'
                }
            }
        });

        /*
            Adjust setelah render agar lebar header dan body DataTables sejajar.
            Ini membantu terutama saat table berada dalam col-md-9 / tab / box.
        */
        setTimeout(function () {
            table.columns.adjust().draw(false);
        }, 200);

        $(window).on('resize.pencapaianProduksi', function () {
            table.columns.adjust();
        });
    }

    $(document).ready(function () {
        $('#selectTBS').val(selectedTBS);
        $('#selectjenis').val(selectedJenis);

        refreshKebunOptions(false);

        if ($('#selectkebun option[value="' + selectedKebun + '"]').length) {
            $('#selectkebun').val(selectedKebun);
            refreshKebunOptions(false);
        } else {
            refreshKebunOptions(true);
        }

        $('#selectTBS').on('change', function () {
            refreshKebunOptions(true);
        });

        initPencapaianDataTable();
    });
</script>
@endsection

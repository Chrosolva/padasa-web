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
        font-size: 13px;
        border: 1px solid #d2d6de;
    }

    #rekap-hpt-table .tabulator-header {
        font-weight: 600;
        background-color: #f4f4f4;
    }

    #rekap-hpt-table .tabulator-calcs-holder {
        font-weight: bold;
        background-color: #ecf0f5;
    }
</style>

<section class="content-header">
    <h1>
        Rekap Hama &amp; Penyakit Tumbuhan
        {{-- <small>Rekap HPT per Kebun</small> --}}
    </h1>
</section>

<section class="content">

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-filter"></i> Filter Data
            </h3>
        </div>

        <div class="box-body">
            <form
                method="GET"
                action="{{ url('/dashboard/hpt/Rekap-HPT') }}"
                class="form-inline"
            >
                <div class="form-group">
                    <label for="endDate">Tanggal Sensus:</label>

                    <div class="input-group">
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

                <button type="submit" class="btn btn-success">
                    <i class="fa fa-search"></i> Filter
                </button>

                <a
                    href="{{ url('/dashboard/hpt/Rekap-HPT') }}"
                    class="btn btn-default"
                >
                    Reset
                </a>
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-bug"></i>
                Rekap HPT per {{ date('d-m-Y', strtotime($endDate)) }}
            </h3>
        </div>

        <div class="box-body">
            <div id="rekap-hpt-table"></div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = {!! json_encode($rekapHPT) !!};

    console.log("Data Rekap HPT:", tableData);
    console.log("Tabulator tersedia:", typeof Tabulator);

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

    function totalPersentase(values, data) {
        var totalPkk = 0;
        var totalTerserang = 0;

        data.forEach(function (row) {
            totalPkk += Number(row.jumlah_pkk || 0);
            totalTerserang += Number(row.pkk_terserang || 0);
        });

        if (totalPkk === 0) {
            return 0;
        }

        return (totalTerserang / totalPkk) * 100;
    }

    if (typeof Tabulator === "undefined") {
        document.getElementById("rekap-hpt-table").innerHTML =
            '<div class="alert alert-danger">' +
            'Library Tabulator gagal dimuat.' +
            '</div>';

        return;
    }

    new Tabulator("#rekap-hpt-table", {
        data: tableData,
        layout: "fitData",
        placeholder: "Tidak ada data Rekap HPT.",
        movableColumns: true,
        resizableColumns: true,

        columns: [
            {
                title: "KEBUN",
                field: "kebun",
                headerHozAlign: "center",
                hozAlign: "left",
                bottomCalc: function () {
                    return "TOTAL";
                }
            },
            {
                title: "JUMLAH PKK",
                field: "jumlah_pkk",
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
                headerHozAlign: "center",
                hozAlign: "right",
                sorter: "number",
                formatter: formatPercent,
                bottomCalc: totalPersentase,
                bottomCalcFormatter: formatPercent
            }
        ]
    });
});
</script>

@endsection
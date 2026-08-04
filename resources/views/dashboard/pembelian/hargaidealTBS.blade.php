@extends('dashboard.app')

@section('header-title')
    Harga Beli TBS P-3
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Harga Beli TBS P-3
        <small>Realisasi Harga Beli TBS P3</small>
    </h1>
</section>

<section class="content">

    {{-- FILTER --}}
    <div class="panel">
        <div class="panel-body">
            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/pembelian/hargaIdealTBS') }}">

                <div class="form-group">
                    <label for="dari_tanggal">Dari Tanggal :</label>
                    <div class="input-group date input-inline">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="dari_tanggal"
                               name="dari_tanggal"
                               value="{{ Request::get('dari_tanggal') ?: $dari_tanggal }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="sampai_tanggal">Sampai Tanggal :</label>
                    <div class="input-group date input-inline">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="sampai_tanggal"
                               name="sampai_tanggal"
                               value="{{ Request::get('sampai_tanggal') ?: $sampai_tanggal }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectkebun">Kebun :</label>
                    <select class="form-control" id="selectkebun" name="selectkebun">
                        <option value="SEMUA" {{ $selectkebun == 'SEMUA' ? 'selected' : '' }}>SEMUA</option>
                        <option value="TELDA" {{ $selectkebun == 'TELDA' ? 'selected' : '' }}>TELDA</option>
                        <option value="KALSA" {{ $selectkebun == 'KALSA' ? 'selected' : '' }}>KALSA</option>
                        <option value="KALDA" {{ $selectkebun == 'KALDA' ? 'selected' : '' }}>KALDA</option>
                        <option value="KOKAR" {{ $selectkebun == 'KOKAR' ? 'selected' : '' }}>KOKAR</option>
                        <option value="RICKO" {{ $selectkebun == 'RICKO' ? 'selected' : '' }}>RICKO</option>
                        <option value="PASER" {{ $selectkebun == 'PASER' ? 'selected' : '' }}>PASER</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- CHART --}}
    <div class="box box-primary">
        <div class="box-header with-border" style="width:80%;">
            <h3 class="box-title">Grafik Realisasi Harga Beli</h3>

            <div class="box-tools pull-right">
                <select id="chartSupplier" class="form-control input-sm" style="width:120px; display:inline-block;">
                    <option value="EGP">EGP</option>
                    <option value="NPS">NPS</option>
                </select>

                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="box-body">
            <div style="height:320px;width:80%;">
                <canvas id="hargaBeliChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Tabel Realisasi Harga Beli TBS P-3</h3>
        </div>

        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-egp" aria-controls="tab-egp" role="tab" data-toggle="tab">
                        EGP - FEGP001
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-nps" aria-controls="tab-nps" role="tab" data-toggle="tab">
                        NPS - FNPS001
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding-top:15px;">
                <div role="tabpanel" class="tab-pane active" id="tab-egp">
                    <div id="table-egp"></div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab-nps">
                    <div id="table-nps"></div>
                </div>
            </div>

            {{-- <div class="harga-beli-note">
                Keterangan warna: hijau = nilai tersedia, strip = nilai kosong / 0.
            </div> --}}

        </div>
    </div>

</section>

@endsection

@section('script-content')

<style>
    .harga-beli-table .tabulator-header {
        font-size: 12px;
        font-weight: bold;
    }

    .harga-beli-table .tabulator-cell {
        font-size: 12px;
        padding: 4px 6px;
    }

    .harga-beli-table .tabulator-col-title {
        text-align: center;
    }

    .harga-beli-table .tabulator-calcs-bottom .tabulator-cell {
        font-weight: bold;
        background: #f5f5f5;
    }

    .harga-beli-note {
        font-size: 12px;
        color: #777;
        margin-top: 8px;
    }

    /* .harga-cell-normal {
        background-color: #dff0d8 !important;
    }

    .harga-cell-empty {
        background-color: #f7f7f7 !important;
        color: #999;
    } */
</style>

<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

    var dataEGP = @json($harga_beli_egp);
    var dataNPS = @json($harga_beli_nps);
    var selectedKebun = "{{ $selectkebun }}";

    function numValue(value) {
        var n = parseFloat(value);
        return isNaN(n) ? 0 : n;
    }

    function priceFormatter(cell) {
        var value = numValue(cell.getValue());

        return value.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function avgCalc(values) {
        var total = 0;
        var count = 0;

        values.forEach(function(v) {
            var n = numValue(v);

            if (n !== 0) {
                total += n;
                count++;
            }
        });

        if (count === 0) {
            return '';
        }

        return Math.round(total / count);
    }

    function avgFormatter(cell) {
        var value = cell.getValue();

        if (value === '' || value === null || typeof value === 'undefined') {
            return '';
        }

        return numValue(value).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function buildPriceCol(fieldName) {
        return {
            title: fieldName,
            field: fieldName,
            hozAlign: 'right',
            headerHozAlign: 'center',
            formatter: priceFormatter,
            bottomCalc: avgCalc,
            bottomCalcFormatter: avgFormatter,
            width: 110,
            minWidth: 90,
            headerSort: false
        };
    }

    function getColumns() {
        var baseColumns = [
            {
                title: 'TGL',
                field: 'TANGGAL',
                frozen: true,
                width: 110,
                minWidth: 95,
                headerHozAlign: 'center',
                hozAlign: 'center',
                headerSort: false,
                bottomCalc: function() {
                    return 'AVG';
                }
            },
            {
                title: 'SUPPLIER',
                field: 'SUPPLIERCODE',
                frozen: true,
                width: 115,
                minWidth: 100,
                headerHozAlign: 'center',
                hozAlign: 'center',
                headerSort: false
            }
        ];

        var priceColumns = [];

        if (selectedKebun === 'SEMUA' || selectedKebun === 'TELDA') {
            priceColumns.push(buildPriceCol('TELDA'));
        }

        if (selectedKebun === 'SEMUA' || selectedKebun === 'KALSA') {
            priceColumns.push(buildPriceCol('KALSA'));
        }

        if (selectedKebun === 'SEMUA' || selectedKebun === 'KALDA') {
            priceColumns.push(buildPriceCol('KALDA'));
        }

        if (selectedKebun === 'SEMUA' || selectedKebun === 'KOKAR') {
            priceColumns.push(buildPriceCol('KOKAR'));
        }

        if (selectedKebun === 'SEMUA' || selectedKebun === 'RICKO') {
            priceColumns.push(buildPriceCol('RICKO'));
        }

        if (selectedKebun === 'SEMUA' || selectedKebun === 'PASER') {
            priceColumns.push(buildPriceCol('PASER'));
        }

        return baseColumns.concat(priceColumns);
    }

    var tableOptions = {
        layout: 'fitData',
        height: '320px',
        movableColumns: true,
        resizableColumns: true,
        columnHeaderVertAlign: 'middle',
        placeholder: 'Tidak ada data',
        columns: getColumns(),
        initialSort: [
            { column: 'TANGGAL', dir: 'asc' }
        ]
    };

    var tableEGP = new Tabulator('#table-egp', Object.assign({}, tableOptions, {
        data: dataEGP
    }));

    var tableNPS = new Tabulator('#table-nps', Object.assign({}, tableOptions, {
        data: dataNPS
    }));

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        tableEGP.redraw(true);
        tableNPS.redraw(true);
    });

    // =========================
    // CHART
    // =========================

    var chartInstance = null;

    var kebunList = [
        'TELDA',
        'KALSA',
        'KALDA',
        'KOKAR',
        'RICKO',
        'PASER'
    ];

    var chartColors = [
        '#3c8dbc',
        '#00a65a',
        '#f39c12',
        '#dd4b39',
        '#605ca8',
        '#00c0ef'
    ];

    function getActiveChartData() {
        return $('#chartSupplier').val() === 'NPS' ? dataNPS : dataEGP;
    }

    function getActiveChartKebunList() {
        if (selectedKebun === 'SEMUA') {
            return kebunList;
        }

        return [selectedKebun];
    }

    function renderHargaBeliChart() {
        var sourceData = getActiveChartData();
        var activeKebunList = getActiveChartKebunList();

        var labels = sourceData.map(function(row) {
            return row.TANGGAL;
        });

        var datasets = activeKebunList.map(function(kebun, index) {
            return {
                label: kebun,
                data: sourceData.map(function(row) {
                    var value = numValue(row[kebun]);
                    return value === 0 ? null : value;
                }),
                borderColor: chartColors[index],
                backgroundColor: chartColors[index],
                fill: false,
                lineTension: 0.1,
                pointRadius: 2,
                pointHoverRadius: 4,
                spanGaps: false
            };
        });

        var ctx = document.getElementById('hargaBeliChart').getContext('2d');

        if (chartInstance !== null) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var value = tooltipItem.yLabel;

                            if (value === null || typeof value === 'undefined') {
                                return label + ': -';
                            }

                            return label + ': ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }],
                    yAxes: [{
                        maxTicksLimit: 5,
                        ticks: {
                            callback: function(value) {
                                return Number(value).toLocaleString('id-ID');
                            }
                        }
                    }]
                }
            }
        });
    }

    $('#chartSupplier').on('change', function() {
        renderHargaBeliChart();
    });

    renderHargaBeliChart();
</script>

@endsection
@extends('dashboard.app')

@section('header-title')
    Harga Ideal
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Harga Ideal
        {{-- <small>Simulasi Harga TBS P3</small> --}}
    </h1>
</section>

<section class="content">

    {{-- FILTER --}}
    <div class="panel">
        <div class="panel-body">
            <form role="form"
                  class="form-inline"
                  method="GET"
                  action="{{ url('/dashboard/pembelian/harga-idealNew') }}">

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
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- SUMMARY CHART --}}
    <div class="box box-primary">
        <div class="box-header with-border" style="width:80%;">
            <h3 class="box-title">
                Grafik Harga Ideal
            </h3>

            <div class="box-tools pull-right">
                <select id="chartSource" class="form-control input-sm" style="width:120px; display:inline-block;">
                    <option value="EGP">EGP</option>
                    <option value="NPS">NPS</option>
                </select>

                <select id="chartKebun" class="form-control input-sm" style="width:130px; display:inline-block;">
                    <option value="TELDA">TELDA</option>
                    <option value="KALSA">KALSA</option>
                    <option value="KALDA">KALDA</option>
                    <option value="KOKAR">KOKAR</option>
                    <option value="RICKO">RICKO</option>
                    <option value="PASER">PASER</option>
                </select>

                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="box-body">
            <div style="height:300px; width:80%;">
                <canvas id="hargaIdealChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Tabel Harga Ideal
            </h3>
        </div>

        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-egp" aria-controls="tab-egp" role="tab" data-toggle="tab">
                        EGP
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-nps" aria-controls="tab-nps" role="tab" data-toggle="tab">
                        NPS
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

        </div>
    </div>

</section>

@endsection

@section('script-content')

<style>
    .harga-ideal-table .tabulator-header {
        font-size: 12px;
        font-weight: bold;
    }

    .harga-ideal-table .tabulator-cell {
        font-size: 12px;
        padding: 4px 6px;
    }

    .harga-ideal-table .tabulator-col-title {
        text-align: center;
    }

    .harga-ideal-table .tabulator-calcs-bottom .tabulator-cell {
        font-weight: bold;
        background: #f5f5f5;
    }

    .harga-ideal-note {
        font-size: 12px;
        color: #777;
        margin-top: 8px;
    }
</style>

<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');

    var dataEGP = @json($harga_egp);
    var dataNPS = @json($harga_nps);

    function numValue(value) {
        var n = parseFloat(value);
        return isNaN(n) ? 0 : n;
    }

    function priceFormatter(cell) {
        var value = numValue(cell.getValue());

        // Untuk kasus NPS RICKO/PASER yang return 0, tampilkan strip agar lebih bersih
        // if (value === 0) {
        //     return '-';
        // }

        return value.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function avgCalc(values, data, calcParams) {
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

    function buildPriceColumn(title, idealField, zmField) {
        return {
            title: title,
            headerHozAlign: 'center',
            columns: [
                {
                    title: 'IDEAL PRICE',
                    field: idealField,
                    hozAlign: 'right',
                    headerHozAlign: 'center',
                    formatter: priceFormatter,
                    bottomCalc: avgCalc,
                    bottomCalcFormatter: avgFormatter,
                    width: 105,
                    minWidth: 95,
                    headerSort: false
                },
                {
                    title: 'ZM PRICE',
                    field: zmField,
                    hozAlign: 'right',
                    headerHozAlign: 'center',
                    formatter: priceFormatter,
                    bottomCalc: avgCalc,
                    bottomCalcFormatter: avgFormatter,
                    width: 95,
                    minWidth: 85,
                    headerSort: false
                }
            ]
        };
    }

    var hargaColumns = [
        {
            title: 'TGL',
            field: 'Tanggal',
            frozen: true,
            width: 105,
            minWidth: 95,
            headerHozAlign: 'center',
            hozAlign: 'center',
            headerSort: false,
            bottomCalc: function() {
                return 'AVG';
            }
        },

        buildPriceColumn('TELDA', 'IDEAL_PRICE_TELDA', 'ZM_IDEAL_PRICE_TELDA'),
        buildPriceColumn('KALSA', 'IDEAL_PRICE_KALSA', 'ZM_IDEAL_PRICE_KALSA'),
        buildPriceColumn('KALDA', 'IDEAL_PRICE_KALDA', 'ZM_IDEAL_PRICE_KALDA'),
        buildPriceColumn('KOKAR', 'IDEAL_PRICE_KOKAR', 'ZM_IDEAL_PRICE_KOKAR'),
        buildPriceColumn('RICKO', 'IDEAL_PRICE_RICKO', 'ZM_IDEAL_PRICE_RICKO'),
        buildPriceColumn('PASER', 'IDEAL_PRICE_PASER', 'ZM_IDEAL_PRICE_PASER')
    ];

    var tableOptions = {
        layout: 'fitData',
        height: '320px',
        movableColumns: true,
        resizableColumns: true,
        columnHeaderVertAlign: 'middle',
        placeholder: 'Tidak ada data',
        columns: hargaColumns,
        initialSort: [
            { column: 'Tanggal', dir: 'asc' }
        ]
    };

    var tableEGP = new Tabulator('#table-egp', Object.assign({}, tableOptions, {
        data: dataEGP
    }));

    var tableNPS = new Tabulator('#table-nps', Object.assign({}, tableOptions, {
        data: dataNPS
    }));

    // Supaya table di tab kedua tidak gepeng saat pertama kali dibuka
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        tableEGP.redraw(true);
        tableNPS.redraw(true);
    });

    // =========================
    // CHART
    // =========================

    var chartInstance = null;

    var chartFieldMap = {
        TELDA: {
            ideal: 'IDEAL_PRICE_TELDA',
            zm: 'ZM_IDEAL_PRICE_TELDA'
        },
        KALSA: {
            ideal: 'IDEAL_PRICE_KALSA',
            zm: 'ZM_IDEAL_PRICE_KALSA'
        },
        KALDA: {
            ideal: 'IDEAL_PRICE_KALDA',
            zm: 'ZM_IDEAL_PRICE_KALDA'
        },
        KOKAR: {
            ideal: 'IDEAL_PRICE_KOKAR',
            zm: 'ZM_IDEAL_PRICE_KOKAR'
        },
        RICKO: {
            ideal: 'IDEAL_PRICE_RICKO',
            zm: 'ZM_IDEAL_PRICE_RICKO'
        },
        PASER: {
            ideal: 'IDEAL_PRICE_PASER',
            zm: 'ZM_IDEAL_PRICE_PASER'
        }
    };

    function getChartDataSource() {
        return $('#chartSource').val() === 'NPS' ? dataNPS : dataEGP;
    }

    function renderHargaIdealChart() {
        var sourceData = getChartDataSource();
        var selectedKebun = $('#chartKebun').val();
        var fields = chartFieldMap[selectedKebun];

        var labels = sourceData.map(function(row) {
            return row.Tanggal;
        });

        var idealData = sourceData.map(function(row) {
            var value = numValue(row[fields.ideal]);
            return value === 0 ? null : value;
        });

        var zmData = sourceData.map(function(row) {
            var value = numValue(row[fields.zm]);
            return value === 0 ? null : value;
        });

        var ctx = document.getElementById('hargaIdealChart').getContext('2d');

        if (chartInstance !== null) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: selectedKebun + ' - Ideal Price',
                        data: idealData,
                        borderColor: '#3c8dbc',
                        backgroundColor: '#3c8dbc',
                        fill: false,
                        lineTension: 0.1,
                        pointRadius: 2,
                        pointHoverRadius: 4
                    },
                    {
                        label: selectedKebun + ' - Zero Margin Price',
                        data: zmData,
                        borderColor: '#f39c12',
                        backgroundColor: '#f39c12',
                        fill: false,
                        lineTension: 0.1,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        borderDash: [6, 4]
                    }
                ]
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
                                return label + ': 0';
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
                        ticks: {
                            maxTicksLimit: 6,
                            callback: function(value) {
                                return Number(value).toLocaleString('id-ID');
                            }
                        }
                    }]
                }
            }
        });
    }

    $('#chartSource, #chartKebun').on('change', function() {
        renderHargaIdealChart();
    });

    renderHargaIdealChart();
</script>

@endsection
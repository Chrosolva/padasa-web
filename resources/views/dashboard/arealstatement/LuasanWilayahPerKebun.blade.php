@extends('dashboard.app')

@section('header-title')
    Luasan Wilayah
@endsection

@section('main-content')
<section class="content-header">
    <h1>Luasan Wilayah <small>Areal Statement</small></h1>
</section>

<section class="content">
    {{-- FILTER --}}
    <div class="panel">
        <div class="panel-body">
            <form method="GET"
                  action="{{ url('/dashboard/arealstatement/luasan-wilayah-per-kebun') }}"
                  class="form-inline">

                <div class="form-group">
                    <label for="site_id">Site :</label>
                    <select class="form-control" id="site_id" name="site_id">
                        <option value="2200" {{ $site_id == '2200' ? 'selected' : '' }}>TELDA</option>
                        <option value="2300" {{ $site_id == '2300' ? 'selected' : '' }}>KALSA</option>
                        <option value="2400" {{ $site_id == '2400' ? 'selected' : '' }}>KALDA</option>
                        <option value="2500" {{ $site_id == '2500' ? 'selected' : '' }}>KOKAR</option>
                        <option value="2600" {{ $site_id == '2600' ? 'selected' : '' }}>MITRA KOKAR</option>
                        <option value="3200" {{ $site_id == '3200' ? 'selected' : '' }}>RICKO</option>
                        <option value="4200" {{ $site_id == '4200' ? 'selected' : '' }}>MUARA</option>
                        <option value="5200" {{ $site_id == '5200' ? 'selected' : '' }}>PASER</option>
                        <option value="6200" {{ $site_id == '6200' ? 'selected' : '' }}>LANGGAI</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tahun">Tahun :</label>
                    <input type="number"
                           class="form-control"
                           id="tahun"
                           name="tahun"
                           value="{{ $tahun }}">
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan :</label>
                    <input type="number"
                           class="form-control"
                           id="bulan"
                           name="bulan"
                           min="1"
                           max="12"
                           value="{{ $bulan }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </form>
        </div>
    </div>

    {{-- TABS --}}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-afdeling" data-toggle="tab">
                    <i class="fa fa-map-marker"></i> Per AFD
                </a>
            </li>
            <li>
                <a href="#tab-tahun-tanam" data-toggle="tab">
                    <i class="fa fa-calendar"></i> Per Tahun Tanam
                </a>
            </li>
            <li>
                <a href="#tab-bibit" data-toggle="tab">
                    <i class="fa fa-leaf"></i> Per Bibit
                </a>
            </li>
            <li>
                <a href="#tab-topografi" data-toggle="tab">
                    <i class="fa fa-area-chart"></i> Per Topografi
                </a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- PER AFD --}}
            <div class="tab-pane active" id="tab-afdeling">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Komposisi Luasan Per Afdeling</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartAfdeling"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="table-area">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <label for="page-size-afdeling">Tampilkan:</label>
                                    <select id="page-size-afdeling"
                                            class="form-control input-sm table-page-size">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>baris</span>
                                </div>

                                <div class="table-toolbar-right">
                                    <label for="search-afdeling">Search:</label>
                                    <input type="text"
                                           id="search-afdeling"
                                           class="form-control input-sm table-search"
                                           placeholder="Cari afdeling atau nilai..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div id="table-afdeling"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PER TAHUN TANAM --}}
            <div class="tab-pane" id="tab-tahun-tanam">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Distribusi Luasan Berdasarkan Tahun Tanam</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartTahunTanam"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-body">
                        <div class="table-area">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <label for="page-size-tahun-tanam">Tampilkan:</label>
                                    <select id="page-size-tahun-tanam"
                                            class="form-control input-sm table-page-size">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>baris</span>
                                </div>

                                <div class="table-toolbar-right">
                                    <label for="search-tahun-tanam">Search:</label>
                                    <input type="text"
                                           id="search-tahun-tanam"
                                           class="form-control input-sm table-search"
                                           placeholder="Cari afdeling atau nilai..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div id="table-tahun-tanam"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PER BIBIT --}}
            <div class="tab-pane" id="tab-bibit">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Distribusi Luasan Berdasarkan Bibit</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartBibit"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="table-area">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <label for="page-size-bibit">Tampilkan:</label>
                                    <select id="page-size-bibit"
                                            class="form-control input-sm table-page-size">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>baris</span>
                                </div>

                                <div class="table-toolbar-right">
                                    <label for="search-bibit">Search:</label>
                                    <input type="text"
                                           id="search-bibit"
                                           class="form-control input-sm table-search"
                                           placeholder="Cari afdeling atau nilai..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div id="table-bibit"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PER TOPOGRAFI --}}
            <div class="tab-pane" id="tab-topografi">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Distribusi Luasan Berdasarkan Topografi</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chartTopografi"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-warning">
                    <div class="box-body">
                        <div class="table-area">
                            <div class="table-toolbar">
                                <div class="table-toolbar-left">
                                    <label for="page-size-topografi">Tampilkan:</label>
                                    <select id="page-size-topografi"
                                            class="form-control input-sm table-page-size">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>baris</span>
                                </div>

                                <div class="table-toolbar-right">
                                    <label for="search-topografi">Search:</label>
                                    <input type="text"
                                           id="search-topografi"
                                           class="form-control input-sm table-search"
                                           placeholder="Cari afdeling atau nilai..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div id="table-topografi"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p><strong>HA</strong> = Hektare</p>
</section>
@endsection

@section('script-content')
<style type="text/css">
    .chart-container {
        position: relative;
        width: 100%;
        height: 320px;
    }

    .table-area {
        width: 100%;
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }

    .table-toolbar-left,
    .table-toolbar-right {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .table-page-size {
        width: 80px !important;
    }

    .table-search {
        width: 230px !important;
    }

    #table-afdeling,
    #table-tahun-tanam,
    #table-bibit,
    #table-topografi {
        width: 100%;
        max-width: 100%;
        height: 350px;
        font-size: 12px;
    }

    .tabulator {
        max-width: 100%;
        border: 1px solid #d2d6de;
        background-color: #ffffff;
    }

    .tabulator .tabulator-tableholder {
        overflow-x: auto;
    }

    .tabulator .tabulator-header {
        background-color: #f4f4f4;
        border-bottom: 2px solid #d2d6de;
        font-weight: bold;
    }

    .tabulator .tabulator-header .tabulator-col {
        cursor: grab;
        background-color: #f4f4f4;
        border-right: 1px solid #d2d6de;
    }

    .tabulator .tabulator-header .tabulator-col:active {
        cursor: grabbing;
    }

    .tabulator .tabulator-header .tabulator-col-content {
        padding: 7px 6px;
    }

    .tabulator .tabulator-header .tabulator-col-title {
        white-space: normal;
        line-height: 1.25;
    }

    .tabulator .tabulator-row {
        min-height: 29px;
    }

    .tabulator .tabulator-row .tabulator-cell {
        padding: 5px 7px;
        border-right: 1px solid #eeeeee;
        border-bottom: 1px solid #eeeeee;
    }

    .tabulator .tabulator-row.tabulator-row-even {
        background-color: #fafafa;
    }

    .tabulator .tabulator-row:hover {
        background-color: #f5f5f5;
    }

    .tabulator .tabulator-calcs-holder {
        background-color: #eef5ff;
        border-top: 2px solid #3c8dbc;
    }

    .tabulator .tabulator-calcs-holder .tabulator-row {
        background-color: #eef5ff !important;
        font-weight: bold;
    }

    .tabulator .tabulator-footer {
        background-color: #ffffff;
    }

    @media (max-width: 767px) {
        .table-toolbar {
            display: block;
        }

        .table-toolbar-right {
            margin-top: 10px;
        }

        .table-search {
            width: 100% !important;
        }

        .chart-container {
            height: 320px;
        }
    }
</style>

<script type="text/javascript">
$(document).ready(function () {
    var dataAfdeling = {!! json_encode(
        $dataAfdeling ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) !!};

    var dataTahunTanam = {!! json_encode(
        $dataTahunTanam ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) !!};

    var dataBibit = {!! json_encode(
        $dataBibit ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) !!};

    var dataTopografi = {!! json_encode(
        $dataTopografi ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) !!};

    function padTwoDigits(value) {
        var result = String(value || '');
        return result.length < 2 ? '0' + result : result;
    }

    function toNumber(value) {
        var number = parseFloat(value || 0);
        return isNaN(number) ? 0 : number;
    }

    function formatNumber(value, decimals) {
        return toNumber(value).toLocaleString('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function hectareFormatter(cell) {
        return formatNumber(cell.getValue(), 2);
    }

    function hectareBottomFormatter(cell) {
        return formatNumber(cell.getValue(), 2);
    }

    function normalizeColumnName(columnName) {
        return String(columnName || '').trim().toUpperCase();
    }

    function isPkkColumn(columnName) {
        return normalizeColumnName(columnName).indexOf('PKK ') === 0;
    }

    function isHaColumn(columnName) {
        return normalizeColumnName(columnName).indexOf('HA ') === 0;
    }

    function extractAfdelingName(value) {
        var originalName = String(value || '').trim();
        var match = originalName.match(/afdeling\s*[-_:]?\s*0*(\d+)/i);

        if (match && match[1]) {
            var afdelingNumber = parseInt(match[1], 10);

            if (!isNaN(afdelingNumber)) {
                return 'AFD ' + padTwoDigits(afdelingNumber);
            }
        }

        return originalName
            .replace(/^HA\s+/i, '')
            .trim()
            .toUpperCase();
    }

    function removeHaPrefix(columnName) {
        return String(columnName || '')
            .replace(/^HA\s+/i, '')
            .trim();
    }

    function getDynamicColumnLabel(columnName) {
        return removeHaPrefix(columnName).toUpperCase();
    }

    function getTableColumnTitle(columnName) {
        return getDynamicColumnLabel(columnName) + '<br>[HA]';
    }

    function getColumnNames(rows) {
        if (!rows || rows.length === 0) {
            return [];
        }

        return Object.keys(rows[0]);
    }

    function getHaColumns(rows) {
        return getColumnNames(rows).filter(function (columnName) {
            return isHaColumn(columnName);
        });
    }

    function removePkkFields(rows) {
        return (rows || []).map(function (row) {
            var cleanRow = {};

            Object.keys(row || {}).forEach(function (columnName) {
                if (!isPkkColumn(columnName)) {
                    cleanRow[columnName] = row[columnName];
                }
            });

            return cleanRow;
        });
    }

    dataTahunTanam = removePkkFields(dataTahunTanam);
    dataBibit = removePkkFields(dataBibit);
    dataTopografi = removePkkFields(dataTopografi);

    dataAfdeling = (dataAfdeling || []).map(function (row, index) {
        row.NOURUT = toNumber(row.NOURUT || index + 1);
        row.HA_TM = toNumber(row.HA_TM);
        row.HA_TBM = toNumber(row.HA_TBM);
        row.HA_TB = toNumber(row.HA_TB);
        row.HA_LAIN = toNumber(row.HA_LAIN);
        row.TOTAL_HA = row.HA_TM + row.HA_TBM + row.HA_TB + row.HA_LAIN;

        return row;
    });

    function buildPivotColumns(rows) {
        var columns = [
            {
                title: 'AFD',
                field: 'DIVISIONNAME',
                sorter: 'string',
                minWidth: 80,
                width: 80,
                formatter: function (cell) {
                    return extractAfdelingName(cell.getValue());
                },
                bottomCalc: function () {
                    return 'TOTAL';
                }
            }
        ];

        getHaColumns(rows).forEach(function (columnName) {
            columns.push({
                title: getTableColumnTitle(columnName),
                field: columnName,
                sorter: 'number',
                hozAlign: 'right',
                headerHozAlign: 'center',
                minWidth: 95,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            });
        });

        return columns;
    }

    function createTable(elementSelector, rows, columns, initialSort) {
        return new Tabulator(elementSelector, {
            data: rows,
            height: '350px',
            layout: 'fitData',
            pagination: 'local',
            paginationSize: 25,
            paginationSizeSelector: false,
            paginationCounter: 'rows',
            movableColumns: true,
            resizableColumns: true,
            placeholder: 'Data tidak tersedia',
            initialSort: initialSort || [],
            columnDefaults: {
                headerHozAlign: 'center',
                vertAlign: 'middle',
                headerSort: true,
                resizable: true
            },
            columns: columns
        });
    }

    var tableAfdeling = createTable(
        '#table-afdeling',
        dataAfdeling,
        [
            {
                title: 'NO',
                field: 'NOURUT',
                sorter: 'number',
                hozAlign: 'center',
                width: 60,
                minWidth: 55,
                bottomCalc: function () {
                    return '~';
                }
            },
            {
                title: 'AFD',
                field: 'DIVISIONNAME',
                sorter: 'string',
                width: 100,
                minWidth: 100,
                formatter: function (cell) {
                    return extractAfdelingName(cell.getValue());
                },
                bottomCalc: function () {
                    return 'TOTAL';
                }
            },
            {
                title: 'TM<br>[HA]',
                field: 'HA_TM',
                sorter: 'number',
                hozAlign: 'right',
                width: 100,
                minWidth: 90,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            },
            {
                title: 'TBM<br>[HA]',
                field: 'HA_TBM',
                sorter: 'number',
                hozAlign: 'right',
                width: 100,
                minWidth: 90,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            },
            {
                title: 'TB<br>[HA]',
                field: 'HA_TB',
                sorter: 'number',
                hozAlign: 'right',
                width: 100,
                minWidth: 90,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            },
            {
                title: 'LAIN<br>[HA]',
                field: 'HA_LAIN',
                sorter: 'number',
                hozAlign: 'right',
                width: 105,
                minWidth: 95,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            },
            {
                title: 'TOTAL<br>[HA]',
                field: 'TOTAL_HA',
                sorter: 'number',
                hozAlign: 'right',
                width: 115,
                minWidth: 105,
                formatter: hectareFormatter,
                bottomCalc: 'sum',
                bottomCalcFormatter: hectareBottomFormatter
            }
        ],
        [
            {
                column: 'NOURUT',
                dir: 'asc'
            }
        ]
    );

    var tableTahunTanam = createTable(
        '#table-tahun-tanam',
        dataTahunTanam,
        buildPivotColumns(dataTahunTanam),
        [
            {
                column: 'DIVISIONNAME',
                dir: 'asc'
            }
        ]
    );

    var tableBibit = createTable(
        '#table-bibit',
        dataBibit,
        buildPivotColumns(dataBibit),
        [
            {
                column: 'DIVISIONNAME',
                dir: 'asc'
            }
        ]
    );

    var tableTopografi = createTable(
        '#table-topografi',
        dataTopografi,
        buildPivotColumns(dataTopografi),
        [
            {
                column: 'DIVISIONNAME',
                dir: 'asc'
            }
        ]
    );

    function bindSearch(inputSelector, table) {
        $(inputSelector).on('keyup change', function () {
            var keyword = String($(this).val() || '')
                .trim()
                .toLowerCase();

            if (keyword === '') {
                table.clearFilter();
                return;
            }

            table.setFilter(function (rowData) {
                return Object.keys(rowData || {}).some(function (key) {
                    var value = rowData[key];

                    if (value === null || value === undefined) {
                        return false;
                    }

                    var originalValue = String(value).toLowerCase();
                    var displayedAfdeling = key === 'DIVISIONNAME'
                        ? extractAfdelingName(value).toLowerCase()
                        : '';

                    return originalValue.indexOf(keyword) !== -1 ||
                        displayedAfdeling.indexOf(keyword) !== -1;
                });
            });
        });
    }

    function bindPageSize(selectSelector, table) {
        $(selectSelector).on('change', function () {
            var pageSize = parseInt($(this).val(), 10);

            if (isNaN(pageSize)) {
                pageSize = 25;
            }

            table.setPageSize(pageSize);
            table.setPage(1);
        });
    }

    bindSearch('#search-afdeling', tableAfdeling);
    bindSearch('#search-tahun-tanam', tableTahunTanam);
    bindSearch('#search-bibit', tableBibit);
    bindSearch('#search-topografi', tableTopografi);

    bindPageSize('#page-size-afdeling', tableAfdeling);
    bindPageSize('#page-size-tahun-tanam', tableTahunTanam);
    bindPageSize('#page-size-bibit', tableBibit);
    bindPageSize('#page-size-topografi', tableTopografi);

    var chartColors = [
        '#3c8dbc',
        '#00a65a',
        '#f39c12',
        '#dd4b39',
        '#605ca8',
        '#00c0ef',
        '#d81b60',
        '#39cccc',
        '#ff851b',
        '#001f3f',
        '#b10dc9',
        '#01ff70',
        '#7f8c8d',
        '#e67e22',
        '#2c3e50',
        '#16a085',
        '#8e44ad',
        '#c0392b',
        '#27ae60',
        '#2980b9'
    ];

    function getChartColor(index) {
        return chartColors[index % chartColors.length];
    }

    function getAfdelingLabels(rows) {
        return (rows || []).map(function (row) {
            return extractAfdelingName(row.DIVISIONNAME || '');
        });
    }

    function buildPivotChartDatasets(rows) {
        return getHaColumns(rows).map(function (columnName, index) {
            return {
                label: getDynamicColumnLabel(columnName),
                data: (rows || []).map(function (row) {
                    return toNumber(row[columnName]);
                }),
                backgroundColor: getChartColor(index),
                borderColor: getChartColor(index),
                borderWidth: 1
            };
        });
    }

    function createHorizontalStackedChart(canvasId, labels, datasets) {
        var canvas = document.getElementById(canvasId);

        if (!canvas) {
            return null;
        }

        return new Chart(canvas.getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        fontSize: 10
                    }
                },
                scales: {
                    xAxes: [
                        {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                callback: function (value) {
                                    return formatNumber(value, 0);
                                }
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Total Hektare'
                            }
                        }
                    ],
                    yAxes: [
                        {
                            stacked: true,
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                autoSkip: false
                            }
                        }
                    ]
                },
                tooltips: {
                    mode: 'index',
                    intersect: true,
                    position: 'nearest',
                    filter: function (tooltipItem) {
                        return toNumber(tooltipItem.xLabel) !== 0;
                    },
                    callbacks: {
                        title: function (tooltipItems, chartData) {
                            if (!tooltipItems || tooltipItems.length === 0) {
                                return '';
                            }

                            return chartData.labels[tooltipItems[0].index] || '';
                        },
                        label: function (tooltipItem, chartData) {
                            var dataset = chartData.datasets[tooltipItem.datasetIndex];

                            return dataset.label +
                                ': ' +
                                formatNumber(tooltipItem.xLabel, 2) +
                                ' HA';
                        },
                        footer: function (tooltipItems) {
                            var total = 0;

                            if (tooltipItems && tooltipItems.length > 0) {
                                var rowIndex = tooltipItems[0].index;
                                var chart = tooltipItems[0]._chart;

                                if (chart && chart.data && chart.data.datasets) {
                                    chart.data.datasets.forEach(function (dataset) {
                                        total += toNumber(dataset.data[rowIndex]);
                                    });
                                }
                            }

                            return 'TOTAL: ' + formatNumber(total, 2) + ' HA';
                        }
                    },
                    footerFontStyle: 'bold'
                },
                hover: {
                    mode: 'index',
                    intersect: true
                }
            }
        });
    }

    var chartAfdeling = createHorizontalStackedChart(
        'chartAfdeling',
        getAfdelingLabels(dataAfdeling),
        [
            {
                label: 'TM',
                data: dataAfdeling.map(function (row) {
                    return row.HA_TM;
                }),
                backgroundColor: '#3c8dbc',
                borderColor: '#3c8dbc',
                borderWidth: 1
            },
            {
                label: 'TBM',
                data: dataAfdeling.map(function (row) {
                    return row.HA_TBM;
                }),
                backgroundColor: '#00a65a',
                borderColor: '#00a65a',
                borderWidth: 1
            },
            {
                label: 'TB',
                data: dataAfdeling.map(function (row) {
                    return row.HA_TB;
                }),
                backgroundColor: '#f39c12',
                borderColor: '#f39c12',
                borderWidth: 1
            },
            {
                label: 'LAIN',
                data: dataAfdeling.map(function (row) {
                    return row.HA_LAIN;
                }),
                backgroundColor: '#dd4b39',
                borderColor: '#dd4b39',
                borderWidth: 1
            }
        ]
    );

    var chartTahunTanam = createHorizontalStackedChart(
        'chartTahunTanam',
        getAfdelingLabels(dataTahunTanam),
        buildPivotChartDatasets(dataTahunTanam)
    );

    var chartBibit = createHorizontalStackedChart(
        'chartBibit',
        getAfdelingLabels(dataBibit),
        buildPivotChartDatasets(dataBibit)
    );

    var chartTopografi = createHorizontalStackedChart(
        'chartTopografi',
        getAfdelingLabels(dataTopografi),
        buildPivotChartDatasets(dataTopografi)
    );

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
        var target = $(event.target).attr('href');

        setTimeout(function () {
            if (target === '#tab-afdeling') {
                tableAfdeling.redraw(true);

                if (chartAfdeling) {
                    chartAfdeling.resize();
                }
            }

            if (target === '#tab-tahun-tanam') {
                tableTahunTanam.redraw(true);

                if (chartTahunTanam) {
                    chartTahunTanam.resize();
                }
            }

            if (target === '#tab-bibit') {
                tableBibit.redraw(true);

                if (chartBibit) {
                    chartBibit.resize();
                }
            }

            if (target === '#tab-topografi') {
                tableTopografi.redraw(true);

                if (chartTopografi) {
                    chartTopografi.resize();
                }
            }
        }, 150);
    });

    var resizeTimer = null;

    $(window).on('resize', function () {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {
            tableAfdeling.redraw(true);
            tableTahunTanam.redraw(true);
            tableBibit.redraw(true);
            tableTopografi.redraw(true);

            if (chartAfdeling) {
                chartAfdeling.resize();
            }

            if (chartTahunTanam) {
                chartTahunTanam.resize();
            }

            if (chartBibit) {
                chartBibit.resize();
            }

            if (chartTopografi) {
                chartTopografi.resize();
            }
        }, 200);
    });
});
</script>
@endsection
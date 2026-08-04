@extends('dashboard.app')

@section('header-title')
    Dashboard Overview
@endsection

@section('main-content')

<style>
    .overview-page {
        padding-bottom: 25px;
    }

    .overview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 14px;
    }

    .overview-title h1 {
        margin: 0;
        color: #185f2d;
        font-size: 26px;
        font-weight: 700;
    }

    .overview-title p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .overview-filter-panel {
        background: #ffffff;
        border: 1px solid #dfe5ea;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .overview-filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    .overview-filter-group {
        min-width: 180px;
    }

    .overview-filter-group label {
        display: block;
        margin-bottom: 5px;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
    }

    .overview-filter-group .form-control {
        width: 100%;
        height: 34px;
        border-radius: 5px;
    }

    .btn-overview-filter {
        height: 34px;
        padding: 6px 18px;
        border: none;
        border-radius: 5px;
        background: #218838;
        color: #ffffff;
        font-weight: 600;
    }

    .btn-overview-filter:hover,
    .btn-overview-filter:focus {
        background: #176b2b;
        color: #ffffff;
    }

    .overview-info {
        margin-left: auto;
        text-align: right;
    }

    .overview-info-site {
        color: #185f2d;
        font-size: 16px;
        font-weight: 700;
    }

    .overview-info-date {
        margin-top: 2px;
        color: #6b7280;
        font-size: 12px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns:
            minmax(180px, 1fr)
            minmax(180px, 1fr)
            minmax(180px, 1fr)
            minmax(220px, 1.15fr)
            minmax(180px, 1fr);
        gap: 0;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #dfe5ea;
        border-radius: 9px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .kpi-card {
        position: relative;
        min-height: 160px;
        padding: 14px 15px 12px;
        border-right: 1px solid #e4e8ec;
    }

    .kpi-card:last-child {
        border-right: none;
    }

    .kpi-card-title {
        min-height: 17px;
        margin-bottom: 7px;
        color: #1f2937;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
    }

    .kpi-main {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 69px;
        gap: 11px;
    }

    .kpi-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 50%;
        font-size: 25px;
    }

    .kpi-icon-tbs {
        background: #fff4d9;
        color: #dc8b00;
    }

    .kpi-icon-cpo {
        background: #fff3db;
        color: #ee9500;
    }

    .kpi-icon-kernel {
        background: #f4eadf;
        color: #8a5a2b;
    }

    .kpi-icon-ker {
        background: #f3eadf;
        color: #79502c;
    }

    .kpi-value-container {
        white-space: nowrap;
    }

    .kpi-value {
        color: #18752c;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .kpi-value-danger {
        color: #dc2626;
    }

    .kpi-unit {
        margin-left: 4px;
        color: #374151;
        font-size: 12px;
    }

    .kpi-footer {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        color: #4b5563;
        font-size: 11px;
    }

    .kpi-target {
        white-space: nowrap;
    }

    .kpi-achievement {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        white-space: nowrap;
        font-weight: 600;
    }

    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        flex: 0 0 10px;
        border-radius: 50%;
    }

    .status-success {
        background: #35a83b;
    }

    .status-warning {
        background: #f2bd19;
    }

    .status-danger {
        background: #ed2727;
    }

    .oer-card {
        padding-left: 8px;
        padding-right: 8px;
    }

    #oerGauge {
        width: 100%;
        height: 92px;
        margin-top: -5px;
    }

    .oer-footer {
        padding: 0 7px;
        margin-top: -2px;
    }

    .empty-overview {
        margin-top: 15px;
        padding: 14px;
        border: 1px solid #f2c94c;
        border-radius: 6px;
        background: #fff9e8;
        color: #7a5b00;
    }

    @media (max-width: 1250px) {
        .kpi-grid {
            grid-template-columns: repeat(3, minmax(190px, 1fr));
        }

        .kpi-card:nth-child(3) {
            border-right: none;
        }

        .kpi-card:nth-child(-n+3) {
            border-bottom: 1px solid #e4e8ec;
        }
    }

    @media (max-width: 850px) {
        .overview-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .overview-info {
            margin-left: 0;
            text-align: left;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, minmax(180px, 1fr));
        }

        .kpi-card {
            border-right: 1px solid #e4e8ec;
            border-bottom: 1px solid #e4e8ec;
        }

        .kpi-card:nth-child(2n) {
            border-right: none;
        }
    }

    @media (max-width: 520px) {
        .kpi-grid {
            display: block;
        }

        .kpi-card {
            border-right: none;
            border-bottom: 1px solid #e4e8ec;
        }

        .overview-filter-group {
            width: 100%;
        }

        .btn-overview-filter {
            width: 100%;
        }
    }
</style>

@php
    function overviewStatusClass($achievement)
    {
        if ($achievement >= 100) {
            return 'status-success';
        }

        if ($achievement >= 95) {
            return 'status-warning';
        }

        return 'status-danger';
    }

    function overviewValueClass($achievement)
    {
        return $achievement < 95
            ? 'kpi-value-danger'
            : '';
    }
@endphp

<section class="content-header">
    <div class="overview-header">
        <div class="overview-title">
            <h1>
                Dashboard Highlight
            </h1>

            <p>
                Overview operasional harian seluruh PMKS
            </p>
        </div>

        <div class="overview-info">
            <div class="overview-info-site">
                PMKS {{ $overview->PMKS }}
            </div>

            <div class="overview-info-date">
                Data tanggal
                {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}
            </div>
        </div>
    </div>
</section>

<section class="content overview-page">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true"
            >
                ×
            </button>

            <strong>Error:</strong>
            {{ session('error') }}
        </div>
    @endif

    <div class="overview-filter-panel">
        <form
            method="GET"
            action="{{ route('dashboard.home') }}"
            class="overview-filter-form"
        >
            <div class="overview-filter-group">
                <label for="tanggal">
                    Tanggal
                </label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    class="form-control"
                    value="{{ $tanggal }}"
                    required
                >
            </div>

            <div class="overview-filter-group">
                <label for="site_id">
                    PMKS
                </label>

                <select
                    id="site_id"
                    name="site_id"
                    class="form-control"
                >
                    @foreach($sites as $value => $label)
                        <option
                            value="{{ $value }}"
                            {{ (string) $siteId === (string) $value ? 'selected' : '' }}
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="btn btn-overview-filter"
            >
                <i class="fa fa-search"></i>
                Tampilkan
            </button>
        </form>
    </div>

    <div class="kpi-grid">

        {{-- TBS OLAH --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                TBS Diolah
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-tbs">
                    <i class="fa fa-cubes"></i>
                </div>

                <div class="kpi-value-container">
                    <span class="kpi-value">
                        {{ number_format($overview->TBSOLAH, 0, ',', '.') }}
                    </span>

                    <span class="kpi-unit">
                        Ton
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Total olah harian
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot status-success"></span>
                    Aktual
                </span>
            </div>
        </div>

        {{-- CPO --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                CPO Produksi
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-cpo">
                    <i class="fa fa-tint"></i>
                </div>

                <div class="kpi-value-container">
                    <span class="kpi-value {{ overviewValueClass($overview->PENCAPAIANCPO) }}">
                        {{ number_format($overview->PRODUKSICPO, 0, ',', '.') }}
                    </span>

                    <span class="kpi-unit">
                        Ton
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Target
                    {{ number_format($overview->TARGET_CPO_HARIAN, 0, ',', '.') }}
                    Ton
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot {{ overviewStatusClass($overview->PENCAPAIANCPO) }}"></span>

                    {{ number_format($overview->PENCAPAIANCPO, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- KERNEL --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                Kernel Produksi
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-kernel">
                    <i class="fa fa-circle"></i>
                </div>

                <div class="kpi-value-container">
                    <span class="kpi-value {{ overviewValueClass($overview->PENCAPAIANPK) }}">
                        {{ number_format($overview->PRODUKSIPK, 0, ',', '.') }}
                    </span>

                    <span class="kpi-unit">
                        Ton
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Target
                    {{ number_format($overview->TARGET_PK_HARIAN, 0, ',', '.') }}
                    Ton
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot {{ overviewStatusClass($overview->PENCAPAIANPK) }}"></span>

                    {{ number_format($overview->PENCAPAIANPK, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- OER GAUGE --}}
        <div class="kpi-card oer-card">
            <div class="kpi-card-title">
                OER
            </div>

            <div id="oerGauge"></div>

            <div class="kpi-footer oer-footer">
                <span class="kpi-target">
                    Target
                    {{ number_format($overview->TARGET_OER, 2, ',', '.') }}%
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot {{ overviewStatusClass($overview->PENCAPAIAN_OER) }}"></span>

                    {{ number_format($overview->PENCAPAIAN_OER, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- KER --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                KER
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-ker">
                    <i class="fa fa-circle-o"></i>
                </div>

                <div class="kpi-value-container">
                    <span class="kpi-value {{ overviewValueClass($overview->PENCAPAIAN_KER) }}">
                        {{ number_format($overview->KER, 2, ',', '.') }}%
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Target
                    {{ number_format($overview->TARGET_KER, 2, ',', '.') }}%
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot {{ overviewStatusClass($overview->PENCAPAIAN_KER) }}"></span>

                    {{ number_format($overview->PENCAPAIAN_KER, 1, ',', '.') }}%
                </span>
            </div>
        </div>

    </div>

    @if(
        $overview->TBSOLAH == 0 &&
        $overview->PRODUKSICPO == 0 &&
        $overview->PRODUKSIPK == 0
    )
        <div class="empty-overview">
            <i class="fa fa-info-circle"></i>

            Tidak ada data produksi untuk PMKS dan tanggal yang dipilih.
        </div>
    @endif

</section>

{{-- Apache ECharts --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var gaugeElement = document.getElementById('oerGauge');

        if (!gaugeElement || typeof echarts === 'undefined') {
            return;
        }

        var oerValue = Number(@json($overview->OER));
        var targetOer = Number(@json($overview->TARGET_OER));
        var achievementOer = Number(@json($overview->PENCAPAIAN_OER));

        var gaugeMaximum = Math.max(
            30,
            Math.ceil(targetOer * 1.25),
            Math.ceil(oerValue * 1.15)
        );

        var achievementColor;

        if (achievementOer >= 100) {
            achievementColor = '#218838';
        } else if (achievementOer >= 95) {
            achievementColor = '#f2b705';
        } else {
            achievementColor = '#dc2626';
        }

        var oerGauge = echarts.init(gaugeElement);

        var oerGaugeOption = {
            animationDuration: 700,

            series: [
                {
                    type: 'gauge',

                    startAngle: 180,
                    endAngle: 0,

                    center: ['50%', '74%'],
                    radius: '105%',

                    min: 0,
                    max: gaugeMaximum,

                    splitNumber: 6,

                    axisLine: {
                        lineStyle: {
                            width: 9,

                            color: [
                                [0.60, '#dc2626'],
                                [0.80, '#f2b705'],
                                [1.00, '#218838']
                            ]
                        }
                    },

                    pointer: {
                        show: true,
                        length: '55%',
                        width: 4,
                        itemStyle: {
                            color: '#ef8c00'
                        }
                    },

                    anchor: {
                        show: true,
                        showAbove: true,
                        size: 8,
                        itemStyle: {
                            color: '#ef8c00'
                        }
                    },

                    axisTick: {
                        show: false
                    },

                    splitLine: {
                        show: false
                    },

                    axisLabel: {
                        show: false
                    },

                    title: {
                        show: false
                    },

                    detail: {
                        valueAnimation: true,
                        offsetCenter: [0, '13%'],

                        formatter: function (value) {
                            return value
                                .toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + '%';
                        },

                        color: achievementColor,
                        fontSize: 24,
                        fontWeight: 700
                    },

                    data: [
                        {
                            value: oerValue
                        }
                    ]
                }
            ]
        };

        oerGauge.setOption(oerGaugeOption);

        window.addEventListener('resize', function () {
            oerGauge.resize();
        });
    });
</script>

@endsection
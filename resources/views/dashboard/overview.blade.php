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

    .overview-filter-panel {
        margin-bottom: 15px;
        padding: 12px 15px;
        background: #ffffff;
        border: 1px solid #dfe5ea;
        border-radius: 8px;
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

    .overview-filter-description {
        display: flex;
        align-items: center;
        min-height: 34px;
        color: #6b7280;
        font-size: 11px;
    }

    .overview-filter-description i {
        margin-right: 5px;
        color: #249ac7;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
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
    }

    .kpi-icon-image {
        background: transparent;
    }

    .kpi-icon-image img {
        display: block;
        width: 48px;
        height: 48px;
        object-fit: contain;
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
        grid-template-columns: minmax(0, 1fr) auto;
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

    .status-info {
        background: #249ac7;
    }

    .oer-card {
        padding-left: 8px;
        padding-right: 8px;
    }

    .oer-gauge-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        height: 76px;
    }

    #oerGauge {
        width: 100%;
        height: 55px;
        margin: 0;
    }

    .oer-gauge-value {
        margin-top: -2px;
        color: #18752c;
        font-size: 24px;
        font-weight: 700;
        line-height: 24px;
        text-align: center;
    }

    .oer-gauge-value-danger {
        color: #dc2626;
    }

    .oer-footer {
        padding: 0 7px;
        margin-top: -2px;
    }

    .empty-overview {
        margin-top: 15px;
        padding: 14px;
        color: #7a5b00;
        background: #fff9e8;
        border: 1px solid #f2c94c;
        border-radius: 6px;
    }

    @media (max-width: 1500px) {
        .kpi-grid {
            grid-template-columns: repeat(4, minmax(180px, 1fr));
        }

        .kpi-card {
            border-bottom: 1px solid #e4e8ec;
        }

        .kpi-card:nth-child(4n) {
            border-right: none;
        }

        .kpi-card:nth-last-child(-n+3) {
            border-bottom: none;
        }
    }

    @media (max-width: 950px) {
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

        .kpi-card:nth-last-child(-n+3) {
            border-bottom: 1px solid #e4e8ec;
        }

        .kpi-card:last-child {
            border-bottom: none;
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

        .kpi-card:last-child {
            border-bottom: none;
        }

        .overview-filter-group {
            width: 100%;
        }

        .overview-filter-description {
            width: 100%;
        }
    }
</style>

@php
    $statusClass = function ($achievement) {
        $achievement = (float) $achievement;

        if ($achievement >= 100) {
            return 'status-success';
        }

        if ($achievement >= 95) {
            return 'status-warning';
        }

        return 'status-danger';
    };

    $valueClass = function ($achievement) {
        return (float) $achievement < 95
            ? 'kpi-value-danger'
            : '';
    };

    $formatOverviewDecimal = function ($value, $decimals = 1) {
        $formatted = number_format(
            (float) $value,
            $decimals,
            ',',
            '.'
        );

        $formatted = rtrim($formatted, '0');
        $formatted = rtrim($formatted, ',');

        return $formatted;
    };

    $isEmptyOverview =
        (float) $overview->TBSOLAH === 0.0 &&
        (float) $overview->PRODUKSICPO === 0.0 &&
        (float) $overview->PRODUKSIPK === 0.0;
@endphp

<section class="content-header">
    <div class="overview-header">
        <div class="overview-title">
            <h1>Dashboard Highlight</h1>

            <p>
                Overview operasional harian seluruh PMKS
            </p>
        </div>

        <div class="overview-info">
            <div
                id="overviewPmksName"
                class="overview-info-site"
            >
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

    @if(!empty($queryError))
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
            {{ $queryError }}
        </div>
    @endif

    <div class="overview-filter-panel">
        <form
            method="GET"
            action="{{ route('dashboard.home') }}"
            class="overview-filter-form"
            id="overviewFilterForm"
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

            <div class="overview-filter-description">
                <i class="fa fa-info-circle"></i>
                Tanggal mengambil ulang data. PMKS berubah langsung tanpa reload.
            </div>
        </form>
    </div>

    <div class="kpi-grid">

        {{-- TBS OLAH --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                TBS Diolah
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/tbs.png') }}"
                        alt="TBS Diolah"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="tbsValue"
                        class="kpi-value"
                    >
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
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/cpo.png') }}"
                        alt="CPO Produksi"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="cpoValue"
                        class="kpi-value {{ $valueClass($overview->PENCAPAIANCPO) }}"
                    >
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
                    <span id="cpoTarget">
                        {{ number_format($overview->TARGET_CPO_HARIAN, 0, ',', '.') }}
                    </span>
                    Ton
                </span>

                <span class="kpi-achievement">
                    <span
                        id="cpoDot"
                        class="status-dot {{ $statusClass($overview->PENCAPAIANCPO) }}"
                    ></span>

                    <span id="cpoAchievement">
                        {{ number_format($overview->PENCAPAIANCPO, 1, ',', '.') }}
                    </span>%
                </span>
            </div>
        </div>

        {{-- KERNEL --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                Kernel Produksi
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/kernel.png') }}"
                        alt="Kernel Produksi"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="kernelValue"
                        class="kpi-value {{ $valueClass($overview->PENCAPAIANPK) }}"
                    >
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
                    <span id="kernelTarget">
                        {{ number_format($overview->TARGET_PK_HARIAN, 0, ',', '.') }}
                    </span>
                    Ton
                </span>

                <span class="kpi-achievement">
                    <span
                        id="kernelDot"
                        class="status-dot {{ $statusClass($overview->PENCAPAIANPK) }}"
                    ></span>

                    <span id="kernelAchievement">
                        {{ number_format($overview->PENCAPAIANPK, 1, ',', '.') }}
                    </span>%
                </span>
            </div>
        </div>

        {{-- OER --}}
        <div class="kpi-card oer-card">
            <div class="kpi-card-title">
                OER
            </div>

            <div class="oer-gauge-wrap">
                <div id="oerGauge"></div>

                <div
                    id="oerValue"
                    class="
                        oer-gauge-value
                        {{
                            (float) $overview->PENCAPAIAN_OER < 95
                                ? 'oer-gauge-value-danger'
                                : ''
                        }}
                    "
                >
                    {{ number_format($overview->OER, 2, ',', '.') }}%
                </div>
            </div>

            <div class="kpi-footer oer-footer">
                <span class="kpi-target">
                    Target
                    <span id="oerTarget">
                        {{ number_format($overview->TARGET_OER, 2, ',', '.') }}
                    </span>%
                </span>

                <span class="kpi-achievement">
                    <span
                        id="oerDot"
                        class="status-dot {{ $statusClass($overview->PENCAPAIAN_OER) }}"
                    ></span>

                    <span id="oerAchievement">
                        {{ number_format($overview->PENCAPAIAN_OER, 1, ',', '.') }}
                    </span>%
                </span>
            </div>
        </div>

        {{-- KER --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                KER
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/ker.png') }}"
                        alt="KER"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="kerValue"
                        class="kpi-value {{ $valueClass($overview->PENCAPAIAN_KER) }}"
                    >
                        {{ number_format($overview->KER, 2, ',', '.') }}%
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Target
                    <span id="kerTarget">
                        {{ number_format($overview->TARGET_KER, 2, ',', '.') }}
                    </span>%
                </span>

                <span class="kpi-achievement">
                    <span
                        id="kerDot"
                        class="status-dot {{ $statusClass($overview->PENCAPAIAN_KER) }}"
                    ></span>

                    <span id="kerAchievement">
                        {{ number_format($overview->PENCAPAIAN_KER, 1, ',', '.') }}
                    </span>%
                </span>
            </div>
        </div>

        {{-- JAM OPERASI --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                Jam Operasi
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/jam-operasi.png') }}"
                        alt="Jam Operasi"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="jamOlahValue"
                        class="kpi-value"
                    >
                        {{ $formatOverviewDecimal($overview->JAMOLAH, 1) }}
                    </span>

                    <span class="kpi-unit">
                        Jam
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Total jam operasi
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot status-info"></span>
                    Aktual
                </span>
            </div>
        </div>

        {{-- BREAKDOWN --}}
        <div class="kpi-card">
            <div class="kpi-card-title">
                Breakdown
            </div>

            <div class="kpi-main">
                <div class="kpi-icon kpi-icon-image">
                    <img
                        src="{{ asset('assets/dashboard/icons/breakdown.png') }}"
                        alt="Breakdown"
                    >
                </div>

                <div class="kpi-value-container">
                    <span
                        id="breakdownValue"
                        class="kpi-value"
                    >
                        {{ $formatOverviewDecimal($overview->BREAKDOWN, 1) }}
                    </span>

                    <span class="kpi-unit">
                        Jam
                    </span>
                </div>
            </div>

            <div class="kpi-footer">
                <span class="kpi-target">
                    Total downtime
                </span>

                <span class="kpi-achievement">
                    <span class="status-dot status-info"></span>
                    Aktual
                </span>
            </div>
        </div>

    </div>

    <div
        id="emptyOverview"
        class="empty-overview"
        style="{{ $isEmptyOverview ? '' : 'display:none;' }}"
    >
        <i class="fa fa-info-circle"></i>

        Tidak ada data produksi untuk PMKS dan tanggal yang dipilih.
    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var overviewBySite = @json($overviewBySite);

        var siteSelect = document.getElementById('site_id');
        var tanggalInput = document.getElementById('tanggal');
        var overviewForm = document.getElementById('overviewFilterForm');
        var gaugeElement = document.getElementById('oerGauge');

        var oerGauge = null;

        function toNumber(value) {
            var number = Number(value);

            return Number.isFinite(number)
                ? number
                : 0;
        }

        function formatNumber(value, decimals) {
            return toNumber(value).toLocaleString('id-ID', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }

        function formatFlexibleNumber(value, decimals) {
            return toNumber(value).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: decimals
            });
        }

        function updateText(elementId, value) {
            var element = document.getElementById(elementId);

            if (element) {
                element.textContent = value;
            }
        }

        function getStatusClass(achievement) {
            achievement = toNumber(achievement);

            if (achievement >= 100) {
                return 'status-success';
            }

            if (achievement >= 95) {
                return 'status-warning';
            }

            return 'status-danger';
        }

        function updateStatusDot(elementId, achievement) {
            var element = document.getElementById(elementId);

            if (!element) {
                return;
            }

            element.classList.remove(
                'status-success',
                'status-warning',
                'status-danger'
            );

            element.classList.add(
                getStatusClass(achievement)
            );
        }

        function updateValueColor(elementId, achievement) {
            var element = document.getElementById(elementId);

            if (!element) {
                return;
            }

            element.classList.toggle(
                'kpi-value-danger',
                toNumber(achievement) < 95
            );
        }

        function getGaugeMaximum(oerValue, targetOer) {
            return Math.max(
                30,
                Math.ceil(toNumber(targetOer) * 1.25),
                Math.ceil(toNumber(oerValue) * 1.15)
            );
        }

        function getGaugeOption(oerValue, targetOer) {
            return {
                animationDuration: 700,

                series: [
                    {
                        type: 'gauge',

                        startAngle: 180,
                        endAngle: 0,

                        center: ['50%', '90%'],
                        radius: '88%',

                        min: 0,
                        max: getGaugeMaximum(
                            oerValue,
                            targetOer
                        ),

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
                            show: false
                        },

                        data: [
                            {
                                value: toNumber(oerValue)
                            }
                        ]
                    }
                ]
            };
        }

        function updateGauge(data) {
            if (!oerGauge) {
                return;
            }

            oerGauge.setOption({
                series: [
                    {
                        max: getGaugeMaximum(
                            data.OER,
                            data.TARGET_OER
                        ),

                        data: [
                            {
                                value: toNumber(data.OER)
                            }
                        ]
                    }
                ]
            });
        }

        function updateOverview(siteId) {
            siteId = String(siteId);

            var data = overviewBySite[siteId];

            if (!data && overviewBySite['9999']) {
                siteId = '9999';
                data = overviewBySite['9999'];

                if (siteSelect) {
                    siteSelect.value = '9999';
                }
            }

            if (!data) {
                return;
            }

            updateText(
                'overviewPmksName',
                'PMKS ' + (data.PMKS || '-')
            );

            updateText(
                'tbsValue',
                formatNumber(data.TBSOLAH, 0)
            );

            updateText(
                'cpoValue',
                formatNumber(data.PRODUKSICPO, 0)
            );

            updateText(
                'cpoTarget',
                formatNumber(data.TARGET_CPO_HARIAN, 0)
            );

            updateText(
                'cpoAchievement',
                formatNumber(data.PENCAPAIANCPO, 1)
            );

            updateStatusDot(
                'cpoDot',
                data.PENCAPAIANCPO
            );

            updateValueColor(
                'cpoValue',
                data.PENCAPAIANCPO
            );

            updateText(
                'kernelValue',
                formatNumber(data.PRODUKSIPK, 0)
            );

            updateText(
                'kernelTarget',
                formatNumber(data.TARGET_PK_HARIAN, 0)
            );

            updateText(
                'kernelAchievement',
                formatNumber(data.PENCAPAIANPK, 1)
            );

            updateStatusDot(
                'kernelDot',
                data.PENCAPAIANPK
            );

            updateValueColor(
                'kernelValue',
                data.PENCAPAIANPK
            );

            updateText(
                'oerValue',
                formatNumber(data.OER, 2) + '%'
            );

            updateText(
                'oerTarget',
                formatNumber(data.TARGET_OER, 2)
            );

            updateText(
                'oerAchievement',
                formatNumber(data.PENCAPAIAN_OER, 1)
            );

            updateStatusDot(
                'oerDot',
                data.PENCAPAIAN_OER
            );

            var oerValueElement =
                document.getElementById('oerValue');

            if (oerValueElement) {
                oerValueElement.classList.toggle(
                    'oer-gauge-value-danger',
                    toNumber(data.PENCAPAIAN_OER) < 95
                );
            }

            updateGauge(data);

            updateText(
                'kerValue',
                formatNumber(data.KER, 2) + '%'
            );

            updateText(
                'kerTarget',
                formatNumber(data.TARGET_KER, 2)
            );

            updateText(
                'kerAchievement',
                formatNumber(data.PENCAPAIAN_KER, 1)
            );

            updateStatusDot(
                'kerDot',
                data.PENCAPAIAN_KER
            );

            updateValueColor(
                'kerValue',
                data.PENCAPAIAN_KER
            );

            updateText(
                'jamOlahValue',
                formatFlexibleNumber(data.JAMOLAH, 1)
            );

            updateText(
                'breakdownValue',
                formatFlexibleNumber(data.BREAKDOWN, 1)
            );

            var noData =
                toNumber(data.TBSOLAH) === 0 &&
                toNumber(data.PRODUKSICPO) === 0 &&
                toNumber(data.PRODUKSIPK) === 0;

            var emptyOverview =
                document.getElementById('emptyOverview');

            if (emptyOverview) {
                emptyOverview.style.display =
                    noData ? 'block' : 'none';
            }

            var currentUrl = new URL(
                window.location.href
            );

            currentUrl.searchParams.set(
                'site_id',
                siteId
            );

            if (tanggalInput && tanggalInput.value) {
                currentUrl.searchParams.set(
                    'tanggal',
                    tanggalInput.value
                );
            }

            window.history.replaceState(
                {},
                '',
                currentUrl.toString()
            );
        }

        if (
            gaugeElement &&
            typeof echarts !== 'undefined'
        ) {
            var initialSiteId = siteSelect
                ? String(siteSelect.value)
                : '9999';

            var initialData =
                overviewBySite[initialSiteId] ||
                overviewBySite['9999'] ||
                @json($overview);

            oerGauge = echarts.init(gaugeElement);

            oerGauge.setOption(
                getGaugeOption(
                    initialData.OER,
                    initialData.TARGET_OER
                )
            );
        }

        if (siteSelect) {
            siteSelect.addEventListener(
                'change',
                function () {
                    updateOverview(this.value);
                }
            );
        }

        if (tanggalInput && overviewForm) {
            tanggalInput.addEventListener(
                'change',
                function () {
                    overviewForm.submit();
                }
            );
        }

        window.addEventListener(
            'resize',
            function () {
                if (oerGauge) {
                    oerGauge.resize();
                }
            }
        );
    });
</script>

@endsection
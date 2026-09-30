@extends('dashboard.app')

@section('header-title')
    Biaya Kebun
@endsection

@section('main-content')
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">

<style>
    .filter-panel{margin-bottom:15px}.filter-panel .form-group{margin-right:10px;margin-bottom:10px;vertical-align:top}
    .site-dropdown{position:relative;display:inline-block}.site-dropdown-menu{display:none;position:absolute;top:100%;left:0;z-index:1050;width:300px;max-height:340px;overflow-y:auto;padding:10px;margin-top:2px;background:#fff;border:1px solid #d2d6de;border-radius:3px;box-shadow:0 6px 12px rgba(0,0,0,.175)}
    .site-dropdown.open .site-dropdown-menu{display:block}.site-dropdown-menu label{display:block;padding:5px 7px;margin:0;font-weight:normal;cursor:pointer}.site-dropdown-menu label:hover{background:#f4f4f4}.site-dropdown-actions{display:flex;justify-content:space-between;padding-bottom:8px;margin-bottom:5px;border-bottom:1px solid #eee}
    .period-summary{display:flex;align-items:center;flex-wrap:wrap;gap:8px 24px;padding:9px 15px;margin-bottom:10px;font-size:13px;background:#f8f9fa;border:1px solid #d2d6de;border-radius:3px}.period-summary span{white-space:nowrap}.period-summary strong{margin-right:4px}
    .biaya-mode-tabs{margin-bottom:12px}.biaya-mode-tabs>li>a{font-weight:600;cursor:pointer}.biaya-mode-tabs>li.active>a,.biaya-mode-tabs>li.active>a:hover,.biaya-mode-tabs>li.active>a:focus{color:#fff;background:#3c8dbc;border-color:#3c8dbc}
    .table-toolbar{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}.table-toolbar-left,.table-toolbar-right{display:flex;align-items:center;gap:8px}.table-search{width:250px}
    .biaya-produksi-wrapper{display:flex;align-items:flex-start;gap:12px;width:100%}.biaya-column{flex:0 0 auto;min-width:0}.produksi-column{flex:1 1 auto;min-width:0;overflow-x:auto}
    #biaya-table-rpkg,#biaya-table-kgha,#produksi-table-rpkg,#produksi-table-kgha{width:100%;min-height:250px}
    #biaya-table-rpkg .tabulator,#biaya-table-kgha .tabulator,#produksi-table-rpkg .tabulator,#produksi-table-kgha .tabulator{font-size:12px;border:1px solid #d2d6de}
    #biaya-table-rpkg .tabulator-header,#biaya-table-kgha .tabulator-header,#produksi-table-rpkg .tabulator-header,#produksi-table-kgha .tabulator-header{font-weight:600;color:#333;background:#f4f4f4;border-bottom:1px solid #d2d6de}
    #biaya-table-rpkg .tabulator-header .tabulator-col,#biaya-table-kgha .tabulator-header .tabulator-col,#produksi-table-rpkg .tabulator-header .tabulator-col,#produksi-table-kgha .tabulator-header .tabulator-col,#biaya-table-rpkg .tabulator-header .tabulator-col-group,#biaya-table-kgha .tabulator-header .tabulator-col-group,#produksi-table-rpkg .tabulator-header .tabulator-col-group,#produksi-table-kgha .tabulator-header .tabulator-col-group{background:#f4f4f4}
    #biaya-table-rpkg .tabulator-col-title,#biaya-table-kgha .tabulator-col-title,#produksi-table-rpkg .tabulator-col-title,#produksi-table-kgha .tabulator-col-title{white-space:normal;line-height:1.2}
    #biaya-table-rpkg .tabulator-row .tabulator-cell,#biaya-table-kgha .tabulator-row .tabulator-cell,#produksi-table-rpkg .tabulator-row .tabulator-cell,#produksi-table-kgha .tabulator-row .tabulator-cell{border-right:1px solid #ddd}
    #biaya-table-rpkg .tabulator-group,#biaya-table-kgha .tabulator-group{background:#ecf0f5;border-top:1px solid #d2d6de;border-bottom:1px solid #d2d6de;font-weight:600}
    #biaya-table-rpkg .tabulator-calcs-holder,#biaya-table-kgha .tabulator-calcs-holder{font-weight:bold;background:#ecf0f5}
    #biaya-table-rpkg .tabulator-footer .tabulator-calcs-bottom,#biaya-table-kgha .tabulator-footer .tabulator-calcs-bottom{background:#d9edf7!important;font-weight:700;border-top:2px solid #3c8dbc}
    .variance-positive{color:#008000!important;font-weight:600}.variance-negative{color:#d73925!important;font-weight:600}
    @media(max-width:1200px){.biaya-produksi-wrapper{display:block}.produksi-column{margin-top:15px}}
    @media(max-width:767px){.table-toolbar{display:block}.table-toolbar-left,.table-toolbar-right{margin-bottom:8px}.table-search{width:100%}.site-dropdown-menu{width:270px}.period-summary{display:block}.period-summary span{display:block;margin-bottom:5px;white-space:normal}}
</style>

<section class="content-header">
    <h1>Biaya Kebun <small>Laporan Bulanan, Budget dan YTD</small></h1>
</section>

<section class="content">
    <div class="panel panel-default filter-panel">
        <div class="panel-body">
            <form id="filter-form" method="GET" action="{{ route('dev.biaya') }}" class="form-inline">
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2100" value="{{ $tahun }}" style="width:100px;">
                </div>
                <div class="form-group">
                    <label for="bulan">Bulan</label>
                    <select class="form-control" id="bulan" name="bulan" style="width:150px;">
                        @foreach($namaBulan as $nomorBulan => $nama)
                            <option value="{{ $nomorBulan }}" {{ (int)$bulan === (int)$nomorBulan ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kebun</label>
                    <div class="site-dropdown" id="site-dropdown">
                        <button type="button" class="btn btn-default" id="site-dropdown-button" style="min-width:220px;text-align:left;">
                            <span id="site-dropdown-label">Semua Kebun</span>
                            <span class="caret pull-right" style="margin-top:8px;"></span>
                        </button>
                        <div class="site-dropdown-menu">
                            <div class="site-dropdown-actions">
                                <button type="button" class="btn btn-xs btn-primary" id="select-all-sites">Pilih Semua</button>
                                <button type="button" class="btn btn-xs btn-default" id="clear-all-sites">Semua Kebun</button>
                            </div>
                            @foreach($siteOptions as $siteId => $siteName)
                                <label>
                                    <input type="checkbox" name="site_id[]" class="site-checkbox" value="{{ $siteId }}" data-site-name="{{ $siteName }}" {{ in_array((string)$siteId, array_map('strval', $selectedSites), true) ? 'checked' : '' }}>
                                    <strong>{{ $siteName }}</strong> <small class="text-muted">({{ $siteId }})</small>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
                    <a href="{{ route('dev.biaya') }}" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="period-summary">
        <span><strong>Periode:</strong> Bulanan</span>
        <span><strong>Tahun:</strong> {{ $tahun }}</span>
        <span><strong>Bulan:</strong> {{ $namaBulan[$bulan] ?? '' }}</span>
        <span><strong>Kebun:</strong> {{ count($selectedSiteNames) === 0 ? 'Semua Kebun' : implode(', ', $selectedSiteNames) }}</span>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            {{-- TAB DILETAKKAN DI ATAS TOOLBAR --}}
            <ul class="nav nav-tabs biaya-mode-tabs" id="biaya-mode-tabs">
                <li class="active"><a href="#tab-rpkg" data-toggle="tab" data-mode="RPKG">RP/TON TBS</a></li>
                <li><a href="#tab-kgha" data-toggle="tab" data-mode="KGHA">RP/KG HA</a></li>
            </ul>

            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <label for="page-size" style="margin:0;">Tampilkan</label>
                    <select id="page-size" class="form-control input-sm" style="width:85px;">
                        <option value="all" selected>All</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>data</span>
                </div>
                <div class="table-toolbar-right">
                    <button type="button" id="export-excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                    <label for="table-search" style="margin:0;">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="table-search" class="form-control input-sm table-search" placeholder="Cari kebun atau jenis biaya..." autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="tab-rpkg">
                    <div class="biaya-produksi-wrapper">
                        <div class="biaya-column"><div id="biaya-table-rpkg"></div></div>
                        <div class="produksi-column"><div id="produksi-table-rpkg"></div></div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-kgha">
                    <div class="biaya-produksi-wrapper">
                        <div class="biaya-column"><div id="biaya-table-kgha"></div></div>
                        <div class="produksi-column">
                            {{--Produksi--}}
                            <div id="produksi-table-kgha"></div>
                            {{--HA TM--}}
                            <div id="ha-table-kgha" style="margin-top:15px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin:12px 15px 15px 15px;">
            <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#biaya-notes" aria-expanded="false" aria-controls="biaya-notes">
                <i class="fa fa-info-circle"></i> Notes <i class="fa fa-angle-down"></i>
            </button>
            <div id="biaya-notes" class="collapse" style="margin-top:10px;">
                <div class="well well-sm" style="margin-bottom:0;">
                    <div>- Biaya RP/KG TBS = Biaya / Produksi.</div>
                    <div>- VAR YTD Tahun Lalu (%) = Aktual YTD Tahun Ini / YTD Tahun Lalu.</div>
                    <div>- VAR Bulan Ini (%) = Aktual Bulan Ini / Budget Bulan Ini.</div>
                    <div>- VAR YTD (%) = Aktual YTD / Budget YTD.</div>
                    <div>- ACH (%) = Aktual YTD / Budget Full Year.</div>
                    <div style="margin-top:5px;">AKT = AKTUAL, VAR = VARIAN, ACH = ACHIEVEMENT, BUD = BUDGET</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script-content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var tableData = @json($dataBiaya ?? []);
    var produksiData = @json($dataProduksiTBS ?? []);
    var tableDataKgHa = @json($dataBiaya2 ?? []);
    var produksiDataKgHa = @json($dataProduksiTBS ?? []);
    var haData = @json($dataHaYtd ?? []);

    var activeMode = "RPKG";
    var searchKeyword = "";
    var selectedPageSize = "all";

    var siteNames = {
        "2200":"TELDA", "2300":"KALSA", "2400":"KALDA", "2500":"KOKAR",
        "3200":"RICKO", "4200":"MUARA", "5200":"PASER", "6200":"LANGGAI"
    };

    var numberFormatter = new Intl.NumberFormat("id-ID", {minimumFractionDigits:0, maximumFractionDigits:0});
    var percentageFormatter = new Intl.NumberFormat("id-ID", {minimumFractionDigits:2, maximumFractionDigits:2});

    function toNumber(value){var n=Number(value);return isNaN(n)?0:n;}
    function sumField(data,field){return data.reduce(function(total,row){return total+toNumber(row[field]);},0);}
    function calculateVariance(actualField,comparisonField){return function(values,data){var actual=sumField(data,actualField),comparison=sumField(data,comparisonField);return comparison===0?0:(actual/comparison)*100;};}
    function calculateAchievement(actualField,budgetField){return function(values,data){var actual=sumField(data,actualField),budget=sumField(data,budgetField);return budget===0?0:(actual/budget)*100;};}
    function numberCellFormatter(cell){return numberFormatter.format(toNumber(cell.getValue()));}
    var numberFormatter2Decimal = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    function numberCellFormatter2Decimal(cell) {
        return numberFormatter2Decimal.format(
            toNumber(cell.getValue())
        );
    }
    var tonFormatter = new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    function produksiNumberFormatter(cell) {
        var kg = toNumber(
            cell.getValue()
        );

        var ton =
            kg / 1000;

        return tonFormatter.format(
            ton
        );
    }
    function percentageCellFormatter(cell){var value=toNumber(cell.getValue()),el=cell.getElement();el.classList.remove("variance-positive","variance-negative");el.classList.add(value>=95?"variance-positive":"variance-negative");return percentageFormatter.format(value)+"%";}
    function achievementFormatter(cell){return percentageCellFormatter(cell);}
    function exportNumberAccessor(value){return toNumber(value);}
    function exportProduksiTonAccessor(value) {
        return (
            toNumber(value)
            / 1000
        );
    }
    function exportPercentageAccessor(value){return percentageFormatter.format(toNumber(value))+"%";}

    function createBiayaColumns(){
        return [
            {title:"NO",field:"NO",visible:false},
            {title:"YTD TAHUN LALU<br>RP/TON TBS",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"YTD_TAHUN_LALU",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_YTD_TAHUN_LALU",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_YTD","YTD_TAHUN_LALU"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"BIAYA",field:"BIAYA",frozen:true,headerHozAlign:"center",bottomCalc:function(){return "SUBTOTAL";}},
            {title:"BULAN INI RP/TON TBS",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"AKTUAL_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"BUD",field:"BUDGET_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_BULAN_INI","BUDGET_BULAN_INI"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"SAMPAI DENGAN (YTD) RP/TON TBS",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"AKTUAL_YTD",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"BUD",field:"BUDGET_YTD",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_YTD",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_YTD","BUDGET_YTD"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"BUD FULL YEAR<br>RP/TON TBS",headerHozAlign:"center",columns:[
                {title:"BUD",field:"BUDGET_ALL",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"ACH (%)",field:"ACH",hozAlign:"right",headerHozAlign:"center",formatter:achievementFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateAchievement("AKTUAL_YTD","BUDGET_ALL"),bottomCalcFormatter:achievementFormatter}
            ]}
        ];
    }

    function createBiayaKgHaColumns(){
        return [
            {title:"NO",field:"NO",visible:false},
            {title:"YTD TAHUN LALU<br>RP/TON HA",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"YTD_TAHUN_LALU",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_YTD_TAHUN_LALU",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_YTD","YTD_TAHUN_LALU"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"BIAYA",field:"BIAYA",frozen:true,headerHozAlign:"center",bottomCalc:function(){return "SUBTOTAL";}},
            {title:"BULAN INI RP/TON HA",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"AKTUAL_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"BUD",field:"BUDGET_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_BULAN_INI","BUDGET_BULAN_INI"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"SAMPAI DENGAN (YTD) RP/TON HA",headerHozAlign:"center",columns:[
                {title:"AKTUAL",field:"AKTUAL_YTD",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"BUD",field:"BUDGET_YTD",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"VAR (%)",field:"VAR_YTD",hozAlign:"right",headerHozAlign:"center",formatter:percentageCellFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateVariance("AKTUAL_YTD","BUDGET_YTD"),bottomCalcFormatter:percentageCellFormatter}
            ]},
            {title:"BUD FULL YEAR<br>RP/TON HA",headerHozAlign:"center",columns:[
                {title:"BUD",field:"BUDGET_ALL",hozAlign:"right",headerHozAlign:"center",formatter:numberCellFormatter2Decimal,accessorDownload:exportNumberAccessor,bottomCalc:"sum",bottomCalcFormatter:numberCellFormatter2Decimal},
                {title:"ACH (%)",field:"ACH",hozAlign:"right",headerHozAlign:"center",formatter:achievementFormatter,accessorDownload:exportPercentageAccessor,bottomCalc:calculateAchievement("AKTUAL_YTD","BUDGET_ALL"),bottomCalcFormatter:achievementFormatter}
            ]}
        ];
    }

    function createProduksiColumns(){
        return [
            {title:"YTD<br>TAHUN LALU",headerHozAlign:"center",columns:[
                {title:"AKT",field:"YTD_TAHUN_LALU",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor},
            ]},
            {title:"KEBUN",field:"KEBUN",frozen:true,headerHozAlign:"center"},
            {title:"PRODUKSI TAHUN INI",headerHozAlign:"center",columns:[
                {title:"BULAN INI", headerHozAlign:"center", columns:[
                    {title:"AKT",field:"AKT_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor},
                    {title:"BUD",field:"BUD_BULAN_INI",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor},
                ]},
                {title:"SAMPAI DENGAN YTD", headerHozAlign:"center", columns:[
                    {title:"YTD",field:"YTD_TAHUN_INI",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor},
                    {title:"BUD<br>YTD",field:"BUD_YTD_TAHUN_INI",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor},
                ]},
                {title:"BUD<br>TAHUN<br>INI",field:"BUD_TAHUNAN",hozAlign:"right",headerHozAlign:"center",formatter:produksiNumberFormatter,accessorDownload:exportProduksiTonAccessor}
            ]}
        ];
    }

    var selectedYear = {{ (int)$tahun }};
    var previousYear = selectedYear - 1;

    function createHaColumns() {
        return [
            {
                title: "HA TM LALU",
                field: "HA_TAHUN_LALU",
                hozAlign: "right",
                headerHozAlign: "center",
                formatter: numberCellFormatter,
                accessorDownload: exportNumberAccessor
            },
            {
                title: "KEBUN",
                field: "KEBUN",
                frozen: true,
                headerHozAlign: "center"
            },
            {
                title: "HA TM INI",
                field: "HA_TAHUN_INI",
                hozAlign: "right",
                headerHozAlign: "center",
                formatter: numberCellFormatter,
                accessorDownload: exportNumberAccessor
            }
        ];
    }

    function createBiayaTable(selector){
        return new Tabulator(selector, {
            data:tableData, layout:"fitData", height:"560px", placeholder:"Data biaya tidak ditemukan.",
            columnDefaults:{minWidth:80,headerSort:false}, pagination:"local", paginationSize:tableData.length>0?tableData.length:1,
            paginationCounter:"rows", movableColumns:false, resizableColumns:true, columnHeaderVertAlign:"middle",
            columnCalcs:"both", groupBy:"SITE_ID", groupStartOpen:true, groupClosedShowCalcs:true,
            groupHeader:function(value,count){var nama=siteNames[String(value)]||String(value);return "<strong>"+nama+"</strong> <span style='color:#999;font-weight:normal;'>("+value+") - "+count+" jenis biaya</span>";},
            downloadConfig:{columnHeaders:true,columnGroups:true,rowGroups:true,columnCalcs:true,dataTree:false},
            columns:createBiayaColumns()
        });
    }

    function createProduksiTable(selector){
        return new Tabulator(selector, {
            data:produksiData, layout:"fitData", placeholder:"Data produksi tidak ditemukan.",
            columnDefaults:{minWidth:80,headerSort:false}, movableColumns:false, resizableColumns:true,
            columnHeaderVertAlign:"middle", downloadConfig:{columnHeaders:true,columnGroups:true,rowGroups:false,columnCalcs:false,dataTree:false},
            columns:createProduksiColumns()
        });
    }

    function createBiayaTableKgHa(selector){
        return new Tabulator(selector, {
            data:tableDataKgHa, layout:"fitData", height:"560px", placeholder:"Data biaya tidak ditemukan.",
            columnDefaults:{minWidth:50,headerSort:false}, pagination:"local", paginationSize:tableData.length>0?tableData.length:1,
            paginationCounter:"rows", movableColumns:false, resizableColumns:true, columnHeaderVertAlign:"middle",
            columnCalcs:"both", groupBy:"SITE_ID", groupStartOpen:true, groupClosedShowCalcs:true,
            groupHeader:function(value,count){var nama=siteNames[String(value)]||String(value);return "<strong>"+nama+"</strong> <span style='color:#999;font-weight:normal;'>("+value+") - "+count+" jenis biaya</span>";},
            downloadConfig:{columnHeaders:true,columnGroups:true,rowGroups:true,columnCalcs:true,dataTree:false},
            columns:createBiayaKgHaColumns()
        });
    }

    function createProduksiTableKgHa(selector){
        return new Tabulator(selector, {
            data:produksiDataKgHa, layout:"fitData", placeholder:"Data produksi tidak ditemukan.",
            columnDefaults:{minWidth:50,headerSort:false}, movableColumns:false, resizableColumns:true,
            columnHeaderVertAlign:"middle", downloadConfig:{columnHeaders:true,columnGroups:true,rowGroups:false,columnCalcs:false,dataTree:false},
            columns:createProduksiColumns()
        });
    }

    function createHaTable(selector) {
        return new Tabulator(selector, {
            data: haData,
            layout: "fitData",
            placeholder: "Data HA tidak ditemukan.",
            columnDefaults: {
                minWidth: 80,
                headerSort: false
            },
            movableColumns: false,
            resizableColumns: true,
            columnHeaderVertAlign: "middle",
            columns: createHaColumns()
        });
    }

    var tableRpKg=createBiayaTable("#biaya-table-rpkg");
    var produksiTableRpKg=createProduksiTable("#produksi-table-rpkg");
    var tableKgHa=createBiayaTableKgHa("#biaya-table-kgha");
    var produksiTableKgHa=createProduksiTable("#produksi-table-kgha");
    var haTableKgHa = createHaTable("#ha-table-kgha");

    function getActiveBiayaTable(){return activeMode==="KGHA"?tableKgHa:tableRpKg;}
    function getActiveProduksiSelector(){return activeMode==="KGHA"?"#produksi-table-kgha":"#produksi-table-rpkg";}
    function getActiveBiayaSelector(){return activeMode==="KGHA"?"#biaya-table-kgha":"#biaya-table-rpkg";}

    function updateCalculationLabels(selector){
        var el=document.querySelector(selector);if(!el)return;
        el.querySelectorAll(".tabulator-footer .tabulator-calcs-bottom").forEach(function(row){
            row.querySelectorAll(".tabulator-cell").forEach(function(cell){if(cell.textContent.trim()==="SUBTOTAL")cell.textContent="GRAND TOTAL";});
        });
    }

    function attachCalcEvents(tabulator,selector){
        ["tableBuilt","dataProcessed","renderComplete","pageLoaded"].forEach(function(evt){tabulator.on(evt,function(){setTimeout(function(){updateCalculationLabels(selector);},0);});});
        setTimeout(function(){updateCalculationLabels(selector);},50);
    }

    attachCalcEvents(tableRpKg,"#biaya-table-rpkg");
    attachCalcEvents(tableKgHa,"#biaya-table-kgha");

    function applyTableFilter(){
        var table=getActiveBiayaTable(),keyword=searchKeyword.toLowerCase();
        if(keyword===""){table.clearFilter(true);}else{
            table.setFilter(function(data){
                var text=[data.NO,data.SITE_ID,siteNames[String(data.SITE_ID)]||"",data.BIAYA].join(" ").toLowerCase();
                return text.indexOf(keyword)!==-1;
            });
        }
        applyPageSize();
    }

    function applyPageSize(){
        var table=getActiveBiayaTable();
        if(selectedPageSize==="all"){
            var count=table.getDataCount("active");
            table.setPageSize(count>0?count:1);
        }else{table.setPageSize(parseInt(selectedPageSize,10));}
        table.setPage(1);
    }

    document.getElementById("table-search").addEventListener("input",function(){searchKeyword=this.value.trim();applyTableFilter();});
    document.getElementById("page-size").addEventListener("change",function(){selectedPageSize=this.value;applyPageSize();});

    $('#biaya-mode-tabs a[data-toggle="tab"]').on('shown.bs.tab',function(event){
        activeMode=$(event.target).data('mode')||'RPKG';
        if(activeMode==='KGHA'){
            tableKgHa.redraw(true);produksiTableKgHa.redraw(true);
        }else{
            tableRpKg.redraw(true);produksiTableRpKg.redraw(true);
        }
        applyTableFilter();
        setTimeout(function(){updateCalculationLabels(getActiveBiayaSelector());},0);
    });

    var siteDropdown=document.getElementById("site-dropdown");
    var siteDropdownButton=document.getElementById("site-dropdown-button");
    var siteCheckboxes=document.querySelectorAll(".site-checkbox");
    var siteDropdownLabel=document.getElementById("site-dropdown-label");

    function updateSiteDropdownLabel(){
        var checked=Array.prototype.slice.call(siteCheckboxes).filter(function(cb){return cb.checked;});
        if(checked.length===0){siteDropdownLabel.textContent="Semua Kebun";}
        else if(checked.length===1){siteDropdownLabel.textContent=checked[0].getAttribute("data-site-name");}
        else{siteDropdownLabel.textContent=checked.length+" kebun dipilih";}
    }

    siteDropdownButton.addEventListener("click",function(e){e.stopPropagation();siteDropdown.classList.toggle("open");});
    document.querySelector(".site-dropdown-menu").addEventListener("click",function(e){e.stopPropagation();});
    document.addEventListener("click",function(){siteDropdown.classList.remove("open");});
    siteCheckboxes.forEach(function(cb){cb.addEventListener("change",updateSiteDropdownLabel);});
    document.getElementById("select-all-sites").addEventListener("click",function(){siteCheckboxes.forEach(function(cb){cb.checked=true;});updateSiteDropdownLabel();});
    document.getElementById("clear-all-sites").addEventListener("click",function(){siteCheckboxes.forEach(function(cb){cb.checked=false;});updateSiteDropdownLabel();});
    updateSiteDropdownLabel();

    function getSelectedKebunText(){
        var checked=Array.prototype.slice.call(siteCheckboxes).filter(function(cb){return cb.checked;});
        if(checked.length===0)return "Semua Kebun";
        return checked.map(function(cb){return cb.getAttribute("data-site-name");}).join(", ");
    }

    function shiftWorksheetDown(worksheet,rowCount){
        var cells=[];
        Object.keys(worksheet).forEach(function(address){if(address.charAt(0)!=="!")cells.push({address:address,cell:worksheet[address]});});
        cells.forEach(function(item){delete worksheet[item.address];});
        cells.forEach(function(item){var decoded=XLSX.utils.decode_cell(item.address);decoded.r+=rowCount;worksheet[XLSX.utils.encode_cell(decoded)]=item.cell;});
        if(worksheet["!merges"]){worksheet["!merges"].forEach(function(merge){merge.s.r+=rowCount;merge.e.r+=rowCount;});}
        if(worksheet["!ref"]){var range=XLSX.utils.decode_range(worksheet["!ref"]);range.s.r+=rowCount;range.e.r+=rowCount;worksheet["!ref"]=XLSX.utils.encode_range(range);}
    }

    function addSheetHeader(workbook,sheetName,title,tampilan){
        var ws=workbook.Sheets[sheetName];if(!ws)return;
        shiftWorksheetDown(ws,8);
        XLSX.utils.sheet_add_aoa(ws,[
            [title],["Periode","Bulanan"],["Tahun","{{ $tahun }}"],["Bulan","{{ $namaBulan[$bulan] ?? $bulan }}"],["Kebun",getSelectedKebunText()],["Tampilan",tampilan],[""],[""]
        ],{origin:"A1"});
        if(!ws["!merges"])ws["!merges"]=[];
        ws["!merges"].push({s:{r:0,c:0},e:{r:0,c:5}});
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL - 1 SHEET PER TAB
    |--------------------------------------------------------------------------
    */

    function getSelectedKebunText() {
        var checked = Array.prototype.slice
            .call(siteCheckboxes)
            .filter(function (checkbox) {
                return checkbox.checked;
            });

        if (checked.length === 0) {
            return "Semua Kebun";
        }

        return checked
            .map(function (checkbox) {
                return checkbox.getAttribute("data-site-name");
            })
            .join(", ");
    }


    /*
    |--------------------------------------------------------------------------
    | Convert data Biaya ke Array of Array
    |--------------------------------------------------------------------------
    */

    function buildBiayaExportData(data) {
        var rows = [];

        rows.push([
            "BIAYA",
            "YTD TAHUN LALU - AKTUAL",
            "YTD TAHUN LALU - VAR (%)",
            "BULAN INI - AKTUAL",
            "BULAN INI - BUD",
            "BULAN INI - VAR (%)",
            "YTD - AKTUAL",
            "YTD - BUD",
            "YTD - VAR (%)",
            "BUD FULL YEAR",
            "ACH (%)"
        ]);

        var grouped = {};

        data.forEach(function (row) {
            var siteId = String(row.SITE_ID || "");

            if (!grouped[siteId]) {
                grouped[siteId] = [];
            }

            grouped[siteId].push(row);
        });


        Object.keys(grouped).forEach(function (siteId) {
            var groupRows = grouped[siteId];

            var namaKebun =
                siteNames[siteId]
                || siteId;


            /*
            |--------------------------------------------------------------------------
            | Group Header
            |--------------------------------------------------------------------------
            */

            rows.push([
                namaKebun
                + " ("
                + siteId
                + ")"
            ]);


            /*
            |--------------------------------------------------------------------------
            | Detail
            |--------------------------------------------------------------------------
            */

            groupRows.forEach(function (row) {
                rows.push([
                    row.BIAYA,
                    toNumber(row.YTD_TAHUN_LALU),
                    toNumber(row.VAR_YTD_TAHUN_LALU) / 100,
                    toNumber(row.AKTUAL_BULAN_INI),
                    toNumber(row.BUDGET_BULAN_INI),
                    toNumber(row.VAR_BULAN_INI) / 100,
                    toNumber(row.AKTUAL_YTD),
                    toNumber(row.BUDGET_YTD),
                    toNumber(row.VAR_YTD) / 100,
                    toNumber(row.BUDGET_ALL),
                    toNumber(row.ACH) / 100
                ]);
            });


            /*
            |--------------------------------------------------------------------------
            | Subtotal
            |--------------------------------------------------------------------------
            */

            var sumYtdTahunLalu =
                sumField(
                    groupRows,
                    "YTD_TAHUN_LALU"
                );

            var sumAktualBulan =
                sumField(
                    groupRows,
                    "AKTUAL_BULAN_INI"
                );

            var sumBudgetBulan =
                sumField(
                    groupRows,
                    "BUDGET_BULAN_INI"
                );

            var sumAktualYtd =
                sumField(
                    groupRows,
                    "AKTUAL_YTD"
                );

            var sumBudgetYtd =
                sumField(
                    groupRows,
                    "BUDGET_YTD"
                );

            var sumBudgetAll =
                sumField(
                    groupRows,
                    "BUDGET_ALL"
                );


            rows.push([
                "SUBTOTAL",

                sumYtdTahunLalu,

                sumYtdTahunLalu === 0
                    ? 0
                    : (
                        sumAktualYtd
                        / sumYtdTahunLalu
                    ),

                sumAktualBulan,

                sumBudgetBulan,

                sumBudgetBulan === 0
                    ? 0
                    : (
                        sumAktualBulan
                        / sumBudgetBulan
                    ),

                sumAktualYtd,

                sumBudgetYtd,

                sumBudgetYtd === 0
                    ? 0
                    : (
                        sumAktualYtd
                        / sumBudgetYtd
                    ),

                sumBudgetAll,

                sumBudgetAll === 0
                    ? 0
                    : (
                        sumAktualYtd
                        / sumBudgetAll
                    )
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        var totalYtdTahunLalu =
            sumField(
                data,
                "YTD_TAHUN_LALU"
            );

        var totalAktualBulan =
            sumField(
                data,
                "AKTUAL_BULAN_INI"
            );

        var totalBudgetBulan =
            sumField(
                data,
                "BUDGET_BULAN_INI"
            );

        var totalAktualYtd =
            sumField(
                data,
                "AKTUAL_YTD"
            );

        var totalBudgetYtd =
            sumField(
                data,
                "BUDGET_YTD"
            );

        var totalBudgetAll =
            sumField(
                data,
                "BUDGET_ALL"
            );


        rows.push([
            "GRAND TOTAL",

            totalYtdTahunLalu,

            totalYtdTahunLalu === 0
                ? 0
                : (
                    totalAktualYtd
                    / totalYtdTahunLalu
                ),

            totalAktualBulan,

            totalBudgetBulan,

            totalBudgetBulan === 0
                ? 0
                : (
                    totalAktualBulan
                    / totalBudgetBulan
                ),

            totalAktualYtd,

            totalBudgetYtd,

            totalBudgetYtd === 0
                ? 0
                : (
                    totalAktualYtd
                    / totalBudgetYtd
                ),

            totalBudgetAll,

            totalBudgetAll === 0
                ? 0
                : (
                    totalAktualYtd
                    / totalBudgetAll
                )
        ]);


        return rows;
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Produksi ke Array of Array
    |--------------------------------------------------------------------------
    */

    function buildProduksiExportData(data) {
        var rows = [];

        rows.push([
            "KEBUN",
            "YTD TAHUN LALU",
            "AKT BULAN INI",
            "YTD TAHUN INI",
            "BUD BULAN INI",
            "BUD YTD TAHUN INI",
            "BUD TAHUNAN"
        ]);

        data.forEach(function (row) {
            rows.push([
                row.KEBUN,
                toNumber(row.YTD_TAHUN_LALU),
                toNumber(row.AKT_BULAN_INI),
                toNumber(row.YTD_TAHUN_INI),
                toNumber(row.BUD_BULAN_INI),
                toNumber(row.BUD_YTD_TAHUN_INI),
                toNumber(row.BUD_TAHUNAN)
            ]);
        });

        return rows;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate 1 sheet
    |--------------------------------------------------------------------------
    */

    function createReportSheet(
        tabTitle,
        biayaData,
        produksiData,
        extraTableData
    ) {
        var sheetData = [
            [
                "LAPORAN BIAYA KEBUN - "
                + tabTitle
            ],
            [
                "Periode",
                "Bulanan"
            ],
            [
                "Tahun",
                "{{ $tahun }}"
            ],
            [
                "Bulan",
                "{{ $namaBulan[$bulan] ?? $bulan }}"
            ],
            [
                "Kebun",
                getSelectedKebunText()
            ],
            [""]
        ];


        /*
        |--------------------------------------------------------------------------
        | BIAYA
        |--------------------------------------------------------------------------
        */

        sheetData.push([
            "BIAYA KEBUN"
        ]);

        var biayaRows =
            buildBiayaExportData(
                biayaData
            );

        biayaRows.forEach(function (row) {
            sheetData.push(row);
        });


        /*
        |--------------------------------------------------------------------------
        | Blank Row
        |--------------------------------------------------------------------------
        */

        sheetData.push([]);
        sheetData.push([]);


        /*
        |--------------------------------------------------------------------------
        | PRODUKSI
        |--------------------------------------------------------------------------
        */

        sheetData.push([
            "PRODUKSI"
        ]);

        var produksiRows =
            buildProduksiExportData(
                produksiData
            );

        produksiRows.forEach(function (row) {
            sheetData.push(row);
        });


        /*
        |--------------------------------------------------------------------------
        | Optional Table tambahan
        |--------------------------------------------------------------------------
        |
        | Nanti bisa dipakai untuk tabel HA pada Tab KG/HA.
        |
        */

        if (
            extraTableData
            && extraTableData.length > 0
        ) {
            sheetData.push([]);
            sheetData.push([]);

            sheetData.push([
                "LUASAN HA"
            ]);

            extraTableData.forEach(function (row) {
                sheetData.push(row);
            });
        }


        var worksheet =
            XLSX.utils.aoa_to_sheet(
                sheetData
            );


        /*
        |--------------------------------------------------------------------------
        | Format percentage columns
        |--------------------------------------------------------------------------
        */

        var range =
            XLSX.utils.decode_range(
                worksheet["!ref"]
            );

        for (
            var rowIndex = 0;
            rowIndex <= range.e.r;
            rowIndex++
        ) {
            /*
            * Pada tabel Biaya:
            * C = VAR YTD TL
            * F = VAR BULAN
            * I = VAR YTD
            * K = ACH
            */

            [
                2,
                5,
                8,
                10
            ].forEach(function (columnIndex) {

                var address =
                    XLSX.utils.encode_cell({
                        r:
                            rowIndex,

                        c:
                            columnIndex
                    });

                var cell =
                    worksheet[
                        address
                    ];

                if (
                    cell
                    && typeof cell.v
                        === "number"
                    && cell.v >= 0
                    && cell.v < 10
                ) {
                    cell.z =
                        "0.00%";
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Column Width
        |--------------------------------------------------------------------------
        */

        worksheet["!cols"] = [
            {wch:24},
            {wch:18},
            {wch:15},
            {wch:18},
            {wch:16},
            {wch:15},
            {wch:18},
            {wch:16},
            {wch:15},
            {wch:18},
            {wch:15}
        ];


        /*
        |--------------------------------------------------------------------------
        | Merge Title
        |--------------------------------------------------------------------------
        */

        worksheet["!merges"] =
            worksheet["!merges"]
            || [];

        worksheet[
            "!merges"
        ].push({
            s: {
                r:0,
                c:0
            },

            e: {
                r:0,
                c:6
            }
        });


        return worksheet;
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT BUTTON
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            "export-excel"
        )
        .addEventListener(
            "click",
            function () {

                /*
                |--------------------------------------------------------------------------
                | Semua data TAB 1
                |--------------------------------------------------------------------------
                */

                var biayaRpKgData =
                    tableRpKg
                        .getData(
                            "active"
                        );

                var produksiRpKgData =
                    produksiTableRpKg
                        .getData(
                            "active"
                        );


                /*
                |--------------------------------------------------------------------------
                | Semua data TAB 2
                |--------------------------------------------------------------------------
                |
                | Untuk sementara datanya memang sama,
                | tapi tetap ambil dari instance Tab 2.
                |
                */

                var biayaKgHaData =
                    tableKgHa
                        .getData(
                            "active"
                        );

                var produksiKgHaData =
                    produksiTableKgHa
                        .getData(
                            "active"
                        );


                /*
                |--------------------------------------------------------------------------
                | DATA HA UNTUK EXPORT SHEET RP/KG HA
                |--------------------------------------------------------------------------
                */

                var haExportRaw =
                    haTableKgHa.getData(
                        "active"
                    );

                var dataHaExport = [
                    [
                        "KEBUN",
                        "HA TM" + selectedYear,
                        "HA TM" + previousYear
                    ]
                ];

                haExportRaw.forEach(
                    function (row) {
                        dataHaExport.push([
                            row.KEBUN,
                            toNumber(
                                row.HA_TAHUN_INI
                            ),
                            toNumber(
                                row.HA_TAHUN_LALU
                            )
                        ]);
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Workbook
                |--------------------------------------------------------------------------
                */

                var workbook =
                    XLSX.utils.book_new();


                /*
                |--------------------------------------------------------------------------
                | SHEET 1 = RP/KG TBS
                |--------------------------------------------------------------------------
                */

                var sheetRpKg =
                    createReportSheet(
                        "RP/KG TBS",
                        biayaRpKgData,
                        produksiRpKgData,
                        []
                    );


                XLSX.utils.book_append_sheet(
                    workbook,
                    sheetRpKg,
                    "RP-KG TBS"
                );


                /*
                |--------------------------------------------------------------------------
                | SHEET 2 = RP/KG HA
                |--------------------------------------------------------------------------
                */

                var sheetKgHa =
                    createReportSheet(
                        "RP/KG HA",
                        biayaKgHaData,
                        produksiKgHaData,
                        dataHaExport
                    );


                XLSX.utils.book_append_sheet(
                    workbook,
                    sheetKgHa,
                    "RP-KG HA"
                );


                /*
                |--------------------------------------------------------------------------
                | Download
                |--------------------------------------------------------------------------
                */

                var fileName =
                    "Biaya_Kebun_"
                    + "{{ $tahun }}"
                    + "_"
                    + "{{ $namaBulan[$bulan] ?? $bulan }}"
                    + ".xlsx";


                XLSX.writeFile(
                    workbook,
                    fileName
                );
            }
        );

    applyTableFilter();
});
</script>
@endsection

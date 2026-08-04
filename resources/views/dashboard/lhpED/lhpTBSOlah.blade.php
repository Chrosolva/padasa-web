@extends('dashboard.app')

@section('header-title')
    TBS Olah
@endsection

@section('main-content')
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">
<style>
    #table-data{width:100%}
    .tabulator{border:1px solid #d2d6de;background:#fff;font-size:12px}
    .tabulator .tabulator-header{background:#f4f4f4;border-bottom:2px solid #d2d6de;font-weight:bold}
    .tabulator .tabulator-header .tabulator-col{background:#f4f4f4;border-right:1px solid #d2d6de}
    .tabulator .tabulator-header .tabulator-col-content{padding:7px 5px}
    .tabulator .tabulator-header .tabulator-col-title{white-space:normal;line-height:16px;text-align:center}
    .tabulator .tabulator-row{min-height:30px;border-bottom:1px solid #eee}
    .tabulator .tabulator-row:nth-child(even){background:#f9f9f9}
    .tabulator .tabulator-row:hover{background:#eef6ff}
    .tabulator .tabulator-row .tabulator-cell{padding:6px 7px;border-right:1px solid #eee;line-height:17px}
    .tabulator .tabulator-calcs-holder{background:#eaf2ff;border-top:2px solid #3c8dbc}
    .tabulator .tabulator-calcs-holder .tabulator-row,
    .tabulator .tabulator-calcs-holder .tabulator-cell{background:#eaf2ff!important;font-weight:bold}
    .tabulator .tabulator-footer{padding:7px;background:#fff;border-top:1px solid #d2d6de}
    .tabulator .tabulator-footer .tabulator-page{margin:2px;padding:4px 9px;border:1px solid #d2d6de;border-radius:3px;background:#fff}
    .tabulator .tabulator-footer .tabulator-page.active{color:#fff;background:#3c8dbc;border-color:#367fa9}
    .table-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:10px}
    .table-toolbar-left{display:flex;align-items:center;flex-wrap:wrap;gap:6px}
    .table-search{width:260px;max-width:100%}
    @media(max-width:767px){
        .table-toolbar{display:block}
        .table-toolbar-left{margin-bottom:8px}
        .table-search{width:100%}
    }

    .tabulator .tabulator-header .tabulator-col.tabulator-col-group{
        border-right:1px solid #999;
        background:#f4f4f4;
    }

    .tabulator .tabulator-header .tabulator-col-group-cols{
        border-top:1px solid #999;
    }

    .tabulator .tabulator-header .tabulator-col-group-cols .tabulator-col{
        background:#fff;
    }

    .tabulator .tabulator-header .tabulator-col-title{
        text-align:center;
        white-space:normal;
    }

    .tabulator .tabulator-header .tabulator-frozen{
        background:#f4f4f4!important;
    }
</style>

<section class="content-header">
    <h1>TBS Olah</h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpTBSOlah') }}">
                <div class="row">
                    <div class="form-group">
                        <label for="dari_tanggal">Dari Tanggal : </label>
                        <div class="input-group date input-inline" style="width:175px">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampai_tanggal">Sampai Tanggal : </label>
                        <div class="input-group date input-inline" style="width:175px">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <button type="submit" class="form-control btn btn-primary">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">TBS Masuk, TBS Olah dan Restan Per PMKS</h3>
                </div>
                <div class="box-body">
                    <div class="table-toolbar">
                        <div class="table-toolbar-left">
                            <div class="input-group table-search">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input type="text" id="search-table" class="form-control" placeholder="Cari semua kolom...">
                            </div>
                            <button type="button" id="reset-table" class="btn btn-default">
                                <i class="fa fa-times"></i> Reset
                            </button>
                        </div>
                        {{-- <small class="text-muted">Kolom tanggal tetap terlihat saat tabel digeser</small> --}}
                    </div>
                    <div id="table-data"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script-content')
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>
<script type="text/javascript">
    setValidationRangeDatePicker('dari_tanggal','sampai_tanggal');

    var rawData = @json($lhp_TBSOlah ?? []);

    function toNumber(value){
        var number=parseFloat(value);
        return isNaN(number)?0:number;
    }

    function formatInteger(value){
        var number=parseFloat(value);
        if(isNaN(number)) return '-';
        return number.toLocaleString('id-ID',{
            minimumFractionDigits:0,
            maximumFractionDigits:0
        });
    }

    function numberFormatter(cell){
        var value=cell.getValue();
        if(value===null||value===undefined||value==='') return '-';
        return formatInteger(value);
    }

    function bottomNumberFormatter(cell){
        return formatInteger(cell.getValue());
    }

    function parseDateValue(value){
        if(!value) return 0;
        var timestamp=new Date(value).getTime();
        return isNaN(timestamp)?0:timestamp;
    }

    function dateFormatter(cell){
        var value=cell.getValue();
        if(!value) return '';
        var date=new Date(value);
        if(isNaN(date.getTime())) return value;
        var day=String(date.getDate()).padStart(2,'0');
        var month=String(date.getMonth()+1).padStart(2,'0');
        var year=String(date.getFullYear()).slice(-2);
        return day+'/'+month+'/'+year;
    }

    var tableData=rawData.map(function(row){
        return{
            Tanggal:row.Tanggal||null,
            PMKSTELDA_TBSMASUK:toNumber(row.PMKSTELDA_TBSMASUK),
            PMKSTELDA_TBSOLAH:toNumber(row.PMKSTELDA_TBSOLAH),
            PMKSTELDA_RESTAN:toNumber(row.PMKSTELDA_RESTAN),
            PMKSKALSA_TBSMASUK:toNumber(row.PMKSKALSA_TBSMASUK),
            PMKSKALSA_TBSOLAH:toNumber(row.PMKSKALSA_TBSOLAH),
            PMKSKALSA_RESTAN:toNumber(row.PMKSKALSA_RESTAN),
            PMKSKALDA_TBSMASUK:toNumber(row.PMKSKALDA_TBSMASUK),
            PMKSKALDA_TBSOLAH:toNumber(row.PMKSKALDA_TBSOLAH),
            PMKSKALDA_RESTAN:toNumber(row.PMKSKALDA_RESTAN),
            PMKSKOKAR_TBSMASUK:toNumber(row.PMKSKOKAR_TBSMASUK),
            PMKSKOKAR_TBSOLAH:toNumber(row.PMKSKOKAR_TBSOLAH),
            PMKSKOKAR_RESTAN:toNumber(row.PMKSKOKAR_RESTAN),
            PMKSRICKO_TBSMASUK:toNumber(row.PMKSRICKO_TBSMASUK),
            PMKSRICKO_TBSOLAH:toNumber(row.PMKSRICKO_TBSOLAH),
            PMKSRICKO_RESTAN:toNumber(row.PMKSRICKO_RESTAN),
            PMKSPASER_TBSMASUK:toNumber(row.PMKSPASER_TBSMASUK),
            PMKSPASER_TBSOLAH:toNumber(row.PMKSPASER_TBSOLAH),
            PMKSPASER_RESTAN:toNumber(row.PMKSPASER_RESTAN)
        };
    });

    function numericColumn(title,field){
        return{
            title:title,
            field:field,
            width:105,
            minWidth:90,
            hozAlign:"right",
            headerHozAlign:"center",
            sorter:"number",
            formatter:numberFormatter,
            bottomCalc:"sum",
            bottomCalcFormatter:bottomNumberFormatter
        };
    }

    var table=new Tabulator('#table-data',{
        data:tableData,
        layout:'fitData',
        height:'60vh',
        placeholder:'Tidak ada data yang tersedia',
        responsiveLayout:false,
        movableColumns:false,
        resizableColumns:true,
        pagination:'local',
        paginationSize:25,
        paginationSizeSelector:[10,25,50,100,true],
        paginationCounter:'rows',
        initialSort:[
            {column:'Tanggal',dir:'asc'}
        ],
        columnDefaults:{
            vertAlign:'middle',
            headerSort:true,
            resizable:true
        },
        columns:[
            {
                title:"TGL",
                field:"Tanggal",
                frozen:true,
                width:95,
                minWidth:95,
                hozAlign:"center",
                headerHozAlign:"center",
                sorter:function(a,b){
                    return parseDateValue(a)-parseDateValue(b);
                },
                formatter:dateFormatter,
                bottomCalc:function(){
                    return "TOTAL";
                }
            },
            {
                title:"TELDA",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSTELDA_TBSMASUK"),
                    numericColumn("OLAH","PMKSTELDA_TBSOLAH"),
                    numericColumn("RESTAN","PMKSTELDA_RESTAN")
                ]
            },
            {
                title:"KALSA",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSKALSA_TBSMASUK"),
                    numericColumn("OLAH","PMKSKALSA_TBSOLAH"),
                    numericColumn("RESTAN","PMKSKALSA_RESTAN")
                ]
            },
            {
                title:"KALDA",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSKALDA_TBSMASUK"),
                    numericColumn("OLAH","PMKSKALDA_TBSOLAH"),
                    numericColumn("RESTAN","PMKSKALDA_RESTAN")
                ]
            },
            {
                title:"KOKAR",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSKOKAR_TBSMASUK"),
                    numericColumn("OLAH","PMKSKOKAR_TBSOLAH"),
                    numericColumn("RESTAN","PMKSKOKAR_RESTAN")
                ]
            },
            {
                title:"RICKO",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSRICKO_TBSMASUK"),
                    numericColumn("OLAH","PMKSRICKO_TBSOLAH"),
                    numericColumn("RESTAN","PMKSRICKO_RESTAN")
                ]
            },
            {
                title:"PASER",
                headerHozAlign:"center",
                columns:[
                    numericColumn("MASUK","PMKSPASER_TBSMASUK"),
                    numericColumn("OLAH","PMKSPASER_TBSOLAH"),
                    numericColumn("RESTAN","PMKSPASER_RESTAN")
                ]
            }
        ],
        langs:{
            'id-id':{
                data:{
                    loading:'Memuat data...',
                    error:'Terjadi kesalahan'
                },
                pagination:{
                    page_size:'Jumlah baris',
                    first:'Awal',
                    first_title:'Halaman pertama',
                    last:'Akhir',
                    last_title:'Halaman terakhir',
                    prev:'Sebelumnya',
                    prev_title:'Halaman sebelumnya',
                    next:'Berikutnya',
                    next_title:'Halaman berikutnya',
                    all:'Semua',
                    counter:{
                        showing:'Menampilkan',
                        of:'dari',
                        rows:'baris',
                        pages:'halaman'
                    }
                }
            }
        },
        locale:'id-id'
    });

    function applyGlobalSearch(keyword){
        keyword=String(keyword||'').trim().toLowerCase();
        if(keyword===''){
            table.clearFilter(true);
            return;
        }
        table.setFilter(function(rowData){
            return Object.keys(rowData).some(function(field){
                var value=rowData[field];
                var searchableValue=field==='Tanggal'
                    ?dateFormatter({getValue:function(){return value;}})
                    :formatInteger(value);
                return String(searchableValue).toLowerCase().indexOf(keyword)!==-1;
            });
        });
    }

    var searchTimer=null;

    $('#search-table').on('input',function(){
        var keyword=this.value;
        clearTimeout(searchTimer);
        searchTimer=setTimeout(function(){
            applyGlobalSearch(keyword);
        },200);
    });

    $('#reset-table').on('click',function(){
        $('#search-table').val('');
        table.clearFilter(true);
        table.setSort('Tanggal','asc');
        table.setPage(1);
    });

    var resizeTimer=null;

    $(window).on('resize',function(){
        clearTimeout(resizeTimer);
        resizeTimer=setTimeout(function(){
            table.redraw(true);
        },200);
    });
</script>
@endsection
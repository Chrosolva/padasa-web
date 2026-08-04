@extends('dashboard.app')

@section('header-title')
    Dead Stock
@endsection

@section('main-content')
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.2/dist/css/tabulator.min.css">

<style>
    #table-data{width:100%}
    .tabulator{border:1px solid #d2d6de;background:#fff;font-size:12px}
    .tabulator .tabulator-header{background:#f4f4f4;border-bottom:2px solid #d2d6de;font-weight:bold}
    .tabulator .tabulator-header .tabulator-col{background:#f4f4f4;border-right:1px solid #d2d6de}
    .tabulator .tabulator-header .tabulator-col.tabulator-col-group{border-right:1px solid #aaa}
    .tabulator .tabulator-header .tabulator-col-group-cols{border-top:1px solid #d2d6de}
    .tabulator .tabulator-header .tabulator-col-content{padding:7px 6px}
    .tabulator .tabulator-header .tabulator-col-title{white-space:normal;overflow:visible;text-overflow:clip;line-height:16px;text-align:center}
    .tabulator .tabulator-row{min-height:31px;border-bottom:1px solid #eee}
    .tabulator .tabulator-row:nth-child(even){background:#f9f9f9}
    .tabulator .tabulator-row:hover{background:#eef6ff}
    .tabulator .tabulator-row .tabulator-cell{padding:6px 8px;border-right:1px solid #eee;line-height:18px}
    .tabulator .tabulator-frozen{background:#fff!important}
    .tabulator .tabulator-row:nth-child(even) .tabulator-frozen{background:#f9f9f9!important}
    .tabulator .tabulator-row:hover .tabulator-frozen{background:#eef6ff!important}
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
</style>

<section class="content-header">
    <h1>Dead Stock</h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/inventory/Dead-Stock') }}">
                <div class="form-group">
                    <label for="tahun">Tahun : </label>
                    <div class="input-group date input-inline" style="width:175px">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2100" value="{{ Request::get('tahun') ?: date('Y', strtotime('-7 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bulan">Bulan : </label>
                    <div class="input-group date input-inline" style="width:175px">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="number" class="form-control" id="bulan" name="bulan" min="1" max="12" value="{{ Request::get('bulan') ?: date('m', strtotime('-1 month')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectkebun">Kebun : </label>
                    <select class="form-control" id="selectkebun" name="selectkebun">
                        <option value="2200" {{ request('selectkebun','2200') == '2200' ? 'selected' : '' }}>TELDA</option>
                        <option value="2300" {{ request('selectkebun','2200') == '2300' ? 'selected' : '' }}>KALSA</option>
                        <option value="2400" {{ request('selectkebun','2200') == '2400' ? 'selected' : '' }}>KALDA</option>
                        <option value="2500" {{ request('selectkebun','2200') == '2500' ? 'selected' : '' }}>KOKAR</option>
                        <option value="3200" {{ request('selectkebun','2200') == '3200' ? 'selected' : '' }}>RICKO</option>
                        <option value="4200" {{ request('selectkebun','2200') == '4200' ? 'selected' : '' }}>MUARA</option>
                        <option value="5200" {{ request('selectkebun','2200') == '5200' ? 'selected' : '' }}>PASER</option>
                        <option value="6200" {{ request('selectkebun','2200') == '6200' ? 'selected' : '' }}>LANGGAI</option>
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

    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Dead Stock</h3>
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

                        <small class="text-muted">
                            Kolom jenis persediaan tetap terlihat saat digeser
                        </small>
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
    var rawData = @json($Dead_Stock ?? []);

    function toNumber(value){
        var number=parseFloat(value);
        return isNaN(number)?0:number;
    }

    function formatInteger(value){
        var number=parseFloat(value);
        if(isNaN(number)) return "-";

        return number.toLocaleString("id-ID",{
            minimumFractionDigits:0,
            maximumFractionDigits:0
        });
    }

    function numberFormatter(cell){
        var value=cell.getValue();

        if(value===null||value===undefined||value===""){
            return "-";
        }

        return formatInteger(value);
    }

    function bottomNumberFormatter(cell){
        return formatInteger(cell.getValue());
    }

    function parseDate(value){
        if(!value) return null;

        var date=new Date(value);

        if(!isNaN(date.getTime())){
            return date;
        }

        var text=String(value).substring(0,10);
        var parts=text.split("-");

        if(parts.length===3){
            date=new Date(
                parseInt(parts[0],10),
                parseInt(parts[1],10)-1,
                parseInt(parts[2],10)
            );

            if(!isNaN(date.getTime())){
                return date;
            }
        }

        return null;
    }

    function dateFormatter(cell){
        var date=parseDate(cell.getValue());

        if(!date){
            return "";
        }

        var day=String(date.getDate()).padStart(2,"0");
        var month=String(date.getMonth()+1).padStart(2,"0");
        var year=String(date.getFullYear()).slice(-2);

        return day+"/"+month+"/"+year;
    }

    function dateSorter(a,b){
        var dateA=parseDate(a);
        var dateB=parseDate(b);
        var timeA=dateA?dateA.getTime():0;
        var timeB=dateB?dateB.getTime():0;

        return timeA-timeB;
    }

    var tableData=rawData.map(function(row){
        return{
            GROUPDESCRIPTION:row.GROUPDESCRIPTION||"",
            SALDO_AKHIR_BULAN:toNumber(row.SALDO_AKHIR_BULAN),
            TOTAL_RUPIAH:toNumber(row.TOTAL_RUPIAH),
            LAST_DATE_MOVING:row.LAST_DATE_MOVING||null,
            LAMA_STOCK_BULAN:toNumber(row.LAMA_STOCK_BULAN)
        };
    });

    var table=new Tabulator("#table-data",{
        data:tableData,
        layout:"fitData",
        height:"60vh",
        placeholder:"Tidak ada data yang tersedia",
        responsiveLayout:false,
        movableColumns:false,
        resizableColumns:true,
        pagination:"local",
        paginationSize:25,
        paginationSizeSelector:[10,25,50,100,true],
        paginationCounter:"rows",
        initialSort:[
            {column:"GROUPDESCRIPTION",dir:"asc"}
        ],
        columnDefaults:{
            vertAlign:"middle",
            headerSort:true,
            resizable:true
        },
        columns:[
            {
                title:"JENIS PERSEDIAAN",
                field:"GROUPDESCRIPTION",
                frozen:true,
                minWidth:190,
                hozAlign:"left",
                headerHozAlign:"center",
                sorter:"string",
                bottomCalc:function(){
                    return "TOTAL";
                }
            },
            {
                title:"SALDO PERSEDIAAN",
                headerHozAlign:"center",
                columns:[
                    {
                        title:"[QTY]",
                        field:"SALDO_AKHIR_BULAN",
                        minWidth:110,
                        hozAlign:"right",
                        headerHozAlign:"center",
                        sorter:"number",
                        formatter:numberFormatter
                    }
                ]
            },
            {
                title:"NILAI PERSEDIAAN",
                headerHozAlign:"center",
                columns:[
                    {
                        title:"[Rp]",
                        field:"TOTAL_RUPIAH",
                        minWidth:130,
                        hozAlign:"right",
                        headerHozAlign:"center",
                        sorter:"number",
                        formatter:numberFormatter,
                        bottomCalc:"sum",
                        bottomCalcFormatter:bottomNumberFormatter
                    }
                ]
            },
            {
                title:"TGL TERAKHIR<br>BERGERAK",
                field:"LAST_DATE_MOVING",
                minWidth:135,
                hozAlign:"center",
                headerHozAlign:"center",
                sorter:dateSorter,
                formatter:dateFormatter
            },
            {
                title:"TIDAK BERGERAK<br>(BULAN)",
                field:"LAMA_STOCK_BULAN",
                minWidth:135,
                hozAlign:"right",
                headerHozAlign:"center",
                sorter:"number",
                formatter:numberFormatter
            }
        ],
        langs:{
            "id-id":{
                data:{
                    loading:"Memuat data...",
                    error:"Terjadi kesalahan"
                },
                pagination:{
                    page_size:"Jumlah baris",
                    first:"Awal",
                    first_title:"Halaman pertama",
                    last:"Akhir",
                    last_title:"Halaman terakhir",
                    prev:"Sebelumnya",
                    prev_title:"Halaman sebelumnya",
                    next:"Berikutnya",
                    next_title:"Halaman berikutnya",
                    all:"Semua",
                    counter:{
                        showing:"Menampilkan",
                        of:"dari",
                        rows:"baris",
                        pages:"halaman"
                    }
                }
            }
        },
        locale:"id-id"
    });

    function applyGlobalSearch(keyword){
        keyword=String(keyword||"").trim().toLowerCase();

        if(keyword===""){
            table.clearFilter(true);
            return;
        }

        table.setFilter(function(rowData){
            var values=[
                rowData.GROUPDESCRIPTION,
                formatInteger(rowData.SALDO_AKHIR_BULAN),
                formatInteger(rowData.TOTAL_RUPIAH),
                dateFormatter({
                    getValue:function(){
                        return rowData.LAST_DATE_MOVING;
                    }
                }),
                formatInteger(rowData.LAMA_STOCK_BULAN)
            ];

            return values.some(function(value){
                return String(value||"")
                    .toLowerCase()
                    .indexOf(keyword)!==-1;
            });
        });
    }

    var searchTimer=null;

    $("#search-table").on("input",function(){
        var keyword=this.value;

        clearTimeout(searchTimer);

        searchTimer=setTimeout(function(){
            applyGlobalSearch(keyword);
        },200);
    });

    $("#reset-table").on("click",function(){
        $("#search-table").val("");
        table.clearFilter(true);
        table.setSort("GROUPDESCRIPTION","asc");
        table.setPage(1);
    });

    var resizeTimer=null;

    $(window).on("resize",function(){
        clearTimeout(resizeTimer);

        resizeTimer=setTimeout(function(){
            table.redraw(true);
        },200);
    });
</script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Mutasi Pupuk
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Mutasi Pupuk
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/AnalisaPupuk') }}">
                    <div class="form-group">
                        <label for="bulan">Bulan : </label>
                        <div class="input-group date input-inline" style="width: 175px;">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="number" class="form-control" id="bulan" name="bulan" value="{{ Request::get('bulan') ?: date('m', strtotime('-7 days')) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tahun">Tahun : </label>
                        <div class="input-group date input-inline" style="width: 175px;">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="{{ Request::get('tahun') ?: date('Y', strtotime('-7 days')) }}">
                            {{-- <input type="number" class="form-control" id="tahun" name="tahun" value="2022"> --}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selectkebun">Kebun : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            <option value="2200">TELDA</option>
                            <option value="2300">KALSA</option>
                            <option value="2400">KALDA</option>
                            <option value="2500">KOKAR</option>
                            <option value="2600">MITRA KOKAR</option>
                            <option value="3200">RICKO</option>
                            <option value="4200">MUARA</option>
                            <option value="5200">PASER</option>
                            <option value="6200">LANGGAI</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive" style="overflow-x:auto;">
                            <?php
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200';
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;display:none;" rowspan = "2">KODE NKB</th>
                                        <th class="sticky-col" style="font-size: 12px;" rowspan="2">JENIS PUPUK</th>
                                        <th style="font-size: 12px;" rowspan = "2">SATUAN</th>
                                        <th style="font-size: 12px; text-align:center;" colspan = "5">REALISASI PEMBELIAN PUPUK</th>
                                        <th style="font-size: 12px; text-align:center;" colspan = "5">RKAP PEMAKAIAN PUPUK</th>
                                        <th style="font-size: 12px; text-align:center;" colspan = "5">REALISASI PEMAKAIAN PUPUK</th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">Qty</th>
                                        <th style="font-size: 12px;">Harga / Item</th>
                                        <th style="font-size: 12px;">Nilai [RP.]</th>
                                        <th style="font-size: 12px;">YTD [RP.]</th>
                                        <th style="font-size: 12px;">YTD Qty</th>
                                        <th style="font-size: 12px;">Qty</th>
                                        <th style="font-size: 12px;">Harga / Item</th>
                                        <th style="font-size: 12px;">Nilai [RP.]</th>
                                        <th style="font-size: 12px;">YTD [RP.]</th>
                                        <th style="font-size: 12px;">YTD Qty </th>
                                        <th style="font-size: 12px;">Qty</th>
                                        <th style="font-size: 12px;">Harga / Item</th>
                                        <th style="font-size: 12px;">Nilai [RP.]</th>
                                        <th style="font-size: 12px;">YTD [RP.]</th>
                                        <th style="font-size: 12px;">YTD Qty zxcv bn</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php  $i = 0; 
                                           $totalnilaiA = 0;
                                           $totalytdA = 0;
                                           $totalytdQtyA = 0;
                                           $totalnilaiB = 0;
                                           $totalytdB = 0;
                                           $totalytdQtyB = 0;
                                           $totalnilaiC = 0;
                                           $totalytdC = 0;
                                           $totalytdQtyC = 0;
                                    ?>
                                    @foreach ($Analisa_Pupuk as $row)
                                        <tr>
                                            <td style="display:none;">{{$row->ITEMCODE}}</td>
                                            <td class="sticky-col">{{$row->ITEMDESCRIPTION}}</td>
                                            <td>{{$row->UOMCODE}}</td>
                                            @if ($row->Qty_GRN == (int)$row->Qty_GRN) 
                                                <td style="text-align: right;">{{number_format($row->Qty_GRN,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Qty_GRN,0,',','.')}}</td>
                                            @endif
                                            @if ($row->Harga_GRN == (int)$row->Harga_GRN) 
                                                <td style="text-align: right;">{{number_format($row->Harga_GRN,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Harga_GRN,0,',','.')}}</td>
                                            @endif
                                            @if ($row->Nilai_GRN == (int)$row->Nilai_GRN) 
                                                <td style="text-align: right;">{{number_format($row->Nilai_GRN,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Nilai_GRN,0,',','.')}}</td>
                                            @endif
                                            @if ($grn[$i] == (int)$grn[$i]) 
                                                <td style="text-align: right;">{{number_format($grn[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($grn[$i],0,',','.')}}</td>
                                            @endif

                                            @if ($grn2[$i] == (int)$grn2[$i]) 
                                                <td style="text-align: right;">{{number_format($grn2[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($grn2[$i],0,',','.')}}</td>
                                            @endif
                                            
                                            @if ($row->Qty_Budget == (int)$row->Qty_Budget) 
                                                <td style="text-align: right;">{{number_format($row->Qty_Budget,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Qty_Budget,0,',','.')}}</td>
                                            @endif
                                            @if ($row->Harga_Budget == (int)$row->Harga_Budget) 
                                                <td style="text-align: right;">{{number_format($row->Harga_Budget,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Harga_Budget,0,',','.')}}</td>
                                            @endif

                                            @if ($row->Nilai_Budget == (int)$row->Nilai_Budget) 
                                                <td style="text-align: right;">{{number_format($row->Nilai_Budget,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Nilai_Budget,0,',','.')}}</td>
                                            @endif
                                            @if ($budget[$i] == (int)$budget[$i]) 
                                                <td style="text-align: right;">{{number_format($budget[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($budget[$i],0,',','.')}}</td>
                                            @endif
                                            
                                            @if ($budget2[$i] == (int)$budget2[$i]) 
                                                <td style="text-align: right;">{{number_format($budget2[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($budget2[$i],0,',','.')}}</td>
                                            @endif

                                            @if ($row->Qty_SIV == (int)$row->Qty_SIV) 
                                                <td style="text-align: right;">{{number_format($row->Qty_SIV,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Qty_SIV,0,',','.')}}</td>
                                            @endif
                                            @if ($row->Harga_SIV == (int)$row->Harga_SIV) 
                                                <td style="text-align: right;">{{number_format($row->Harga_SIV,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Harga_SIV,0,',','.')}}</td>
                                            @endif
                                            @if ($row->Nilai_SIV == (int)$row->Nilai_SIV) 
                                                <td style="text-align: right;">{{number_format($row->Nilai_SIV,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($row->Nilai_SIV,0,',','.')}}</td>
                                            @endif
                                            @if ($siv[$i] == (int)$siv[$i]) 
                                                <td style="text-align: right;">{{number_format($siv[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($siv[$i],0,',','.')}}</td>
                                            @endif 

                                            @if ($siv2[$i] == (int)$siv2[$i]) 
                                                <td style="text-align: right;">{{number_format($siv2[$i],0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">{{number_format($siv2[$i],0,',','.')}}</td>
                                            @endif 
                                        </tr>
                                        <?php
                                            // inside the foreach
                                            $totalnilaiA += (float)($row->Nilai_GRN ?? 0);
                                            $totalytdA   += (float)($grn[$i]    ?? 0);
                                            $totalytdQtyA   += (float)($grn2[$i]    ?? 0);

                                            $totalnilaiB += (float)($row->Nilai_Budget ?? 0);
                                            $totalytdB   += (float)($budget[$i]        ?? 0);
                                            $totalytdQtyB   += (float)($budget2[$i]        ?? 0);

                                            $totalnilaiC += (float)($row->Nilai_SIV ?? 0);
                                            $totalytdC   += (float)($siv[$i]       ?? 0);
                                            $totalytdQtyC   += (float)($siv2[$i]       ?? 0);

                                            $i++;
                                        ?>
                                    @endforeach

                                    <tr>
                                            <td style="display: none;"><strong>TOTAL</strong></td>
                                            <td class="sticky-col"><strong>TOTAL</strong></td>
                                            <td>-</td>
                                            <td style="text-align: right;">-</td>
                                            <td style="text-align: right;">-</td>
                                            @if ($totalnilaiA == (int)$totalnilaiA) 
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiA,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiA,0,',','.')}}</strong></td>
                                            @endif
                                            @if ($totalytdA == (int)$totalytdA) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdA,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdA,0,',','.')}}</strong></td>
                                            @endif

                                            @if ($totalytdQtyA == (int)$totalytdQtyA) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyA,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyA,0,',','.')}}</strong></td>
                                            @endif
                                            
                                            <td style="text-align: right;">-</td>
                                            <td style="text-align: right;">-</td>
                                            @if ($totalnilaiB == (int)$totalnilaiB) 
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiB,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiB,0,',','.')}}</strong></td>
                                            @endif
                                            @if ($totalytdB == (int)$totalytdB) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdB,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdB,0,',','.')}}</strong></td>
                                            @endif

                                            @if ($totalytdQtyB == (int)$totalytdQtyB) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyB,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyB,0,',','.')}}</strong></td>
                                            @endif

                                            <td style="text-align: right;">-</td>
                                            <td style="text-align: right;">-</td>
                                            @if ($totalnilaiC == (int)$totalnilaiC) 
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiC,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalnilaiC,0,',','.')}}</strong></td>
                                            @endif
                                            @if ($totalytdC == (int)$totalytdC) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdC,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdC,0,',','.')}}</strong></td>
                                            @endif

                                            @if ($totalytdQtyC == (int)$totalytdQtyC) 
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyC,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: right;"><strong>{{number_format($totalytdQtyC,0,',','.')}}</strong></td>
                                            @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<style>
    #table-data {
        min-width: 1800px;
    }

    #table-data .sticky-col,
    .dataTables_scrollHead .sticky-col {
        position: sticky !important;
        left: 0 !important;
        background: #fff !important;
        z-index: 10 !important;
        min-width: 250px;
    }

    .dataTables_scrollHead .sticky-col {
        background: #f4f4f4 !important;
        z-index: 20 !important;
    }

    #table-data tbody .sticky-col {
        background: #fff !important;
    }
</style>

@endsection

@section('script-content')
    <script type="text/javascript">
        // setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        makeDataTableResponsiveFixed('table-data', 0, 'asc', -1, 1);
        
    </script>
@endsection
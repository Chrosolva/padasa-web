@extends('dashboard.app')

@section('header-title')
    Restan Panen
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Restan Panen
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpRestanPanenBlmAngkut') }}">
                    <div class="row">
                        {{-- <div class="form-group">
                            <label for="pilih_tanggal">Pilih Tanggal : </label>
                            <div class="input-group date input-inline">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="pilih_tanggal" name="pilih_tanggal" value="{{ Request::get('pilih_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="dari_tanggal">Dari Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sampai_tanggal">Sampai Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"  value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selectkebun">Kebun : </label>
                            <select class="form-control filter" id="selectkebun" name="selectkebun">
                                <option value="NULL">SEMUA</option>
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
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                        {{-- <div class="form-group form-inline">
                            <a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenExport') }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}

                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <?php 
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  '2200'; 
                                    // echo $selectkebun;  
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatablefilter" >
                                <thead>
                                    <tr>
                                        {{-- @if($selectkebun == 'SEMUA')
                                            <th style="display: none;">SITE ID</th>
                                        @endif --}}
                                        <th style="display: none;">SITE ID</th>
                                        <th>KEBUN</th>
                                        <th style="font-size: 12px;">TGL</th>
                                        {{-- @if($selectkebun == 'SEMUA')
                                            <th style="font-size: 12px;">KEBUN </th>
                                        @endif --}}
                                        <th style="font-size: 12px;">TBS PRODUKSI</th>
                                        <th style="font-size: 12px;">R0 (TANDAN)</th>
                                        <th style="font-size: 12px;">R1 (TANDAN)</th>
                                        <th style="font-size: 12px;">R2 (TANDAN)</th>
                                        <th style="font-size: 12px;">R3 (TANDAN)</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_RestanPanenBlmAngkut as $row)
                                        <tr>
                                            {{-- @if($selectkebun == 'SEMUA')
                                                <td style="display: none;">SITE ID</td>
                                            @endif --}}
                                            <td style="display: none;">SITE ID</td>
                                            <td>{{$row->KEBUN}}</td>
                                            <?php 
                                                $date = date_create($row->Tanggal);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            {{-- @if($selectkebun == 'SEMUA')
                                                <td>{{$row->KEBUN}}</td>
                                            @endif --}}
                                            <td style="text-align: right;">{{number_format($row->TBSPRODUKSI,0,',','.')}}</td>
                                            @if($row->SITE_ID < '3200' && $row->R0 > (0.02 * $row->TBSPRODUKSI))
                                                <td class="bg-red" style="text-align: right;">{{number_format($row->R0,0,',','.')}}</td>
                                            @elseif ($row->SITE_ID > '3100' && $row->R0 > (0.02 * $row->TBSPRODUKSI))
                                                <td class="bg-red" style="text-align: right;">{{number_format($row->R0,0,',','.')}}</td>
                                            @else 
                                                <td style="text-align: right;">{{number_format($row->R0,0,',','.')}}</td>
                                            @endif
                                            <td style="text-align: right;">{{number_format($row->R1,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->R2,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->R3,0,',','.')}}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h5> Restan TBS Max Kebun Sumatera adalah 2% dari Janjang Panen. </h5>
                        <h5> Restan TBS Max Kebun Kaltim adalah 5% dari Janjang Panen. </h5> 
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
    let kebun = $('#selectkebun').val();
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        // var table = $(document).ready(function() {
        //     $('#table-data').DataTable({
        //         paging: true,
        //         lengthChange: true,
        //         searching: true,
        //         scrollY:'55vh',
        //         scrollX:true,
        //         scrollCollapse: true,
        //         ordering: true,
        //         info: true,
        //         autoWidth: true,
        //         responsive: true,
        //         bDestroy: true,
        //         order: [[0, 'asc']],
        //         lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
        //         iDisplayLength: -1,
        //         ajax: {
        //             url: "{{ '/dashboard/lhpexecutive/lhpRestanPanenBlmAngkut' }}",
        //             data: function (d) {
        //                 d.SITE_ID = $('#selectkebun').val()
        //             }
        //         }
        //         ajax: {
        //             url: "{{ '/dashboard/lhpexecutive/lhpRestanPanenBlmAngkut' }}",
        //             data: function (d) {
        //                     d.SITE_ID = $('#selectkebun').val()
        //                 }
        //             },
        //             columns: [
        //                 {data: 'TANGGAL', name: 'TANGGAL'},
        //                 {data: 'SITE_ID', name: 'SITE_ID'},
        //                 {data: 'TBSPRODUKSIR0', name: 'TBSPRODUKSIR0'},
        //                 {data: 'R0', name: 'R0'},
        //                 {data: 'TBSPRODUKSIR1', name: 'TBSPRODUKSIR1'},
        //                 {data: 'R1', name: 'R1'},
        //                 {data: 'TBSPRODUKSIR2', name: 'TBSPRODUKSIR2'},
        //                 {data: 'R2', name: 'R2'},
        //                 {data: 'TBSPRODUKSIR3', name: 'TBSPRODUKSIR3'},
        //                 {data: 'R3', name: 'R3'},
        //                 {data: 'TBSPRODUKSIR4', name: 'TBSPRODUKSIR4'},
        //                 {data: 'R4', name: 'R4'},
        //                 {data: 'TBSPRODUKSIR5', name: 'TBSPRODUKSIR5'},
        //                 {data: 'R5', name: 'R5'},
        //                 {data: 'KEBUN', name: 'KEBUN'},
        //             ]
        //     });
        // });

        // document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        // $('.filter').on('change', function(){
        //     kebun = $('#selectkebun').val();
        //     console.log(kebun);
        //     table.ajax.reload()
        // })
    </script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Status Batch EPLANT
@endsection


@section('main-content')
    <section class="content-header">
        <h1>
            Status Batch EPLANT [IN DEVELOPMENT]
            <!-- <small>( dalam Ribuan Rupiah )</small> -->
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/biofertilizer/StatusbatchEplant') }}">

                    <div class="form-group">
                        <label for="selectkebun">Kebun : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            <option value="2200">TELDA</option>
                            <option value="2300">KALSA</option>
                            <option value="2400">KALDA</option>
                            <option value="2500">KOKAR</option>
                            <option value="3200">RICKO</option>
                            <option value="5200">PASER</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="selectjenis">Jenis : </label>
                        <select class="form-control" id="selectjenis" name="selectjenis">
                            <option value="SSB">SSB</option>
                            <option value="SSC">SSC</option>
                            <option value="SSK">SSK</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="selectstatus">Status : </label>
                        <select class="form-control" id="selectstatus" name="selectstatus">
                            <option value="SEMUA">SEMUA</option>
                            <option value="CLOSED">CLOSED</option>
                            <option value="OPEN">OPEN</option>
                            <option value="AVAILABLE">AVAILABLE</option>
                            <!-- <option value="IN USED">IN USED</option> -->
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">

            <?php 
                $dom = new DOMDocument();
                $dom->loadHtml("Index.php");
                $kebun = Request::get('selectkebun') ?: '2200';
                $total = 0;
            ?>

            <div class="col-md-12">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive fixedcol">
                            <?php
                                    
                                    // $tahun = Request::get('tahun') ?: date('Y', strtotime('-7 days'));
                                    // $bulan = Request::get('bulan') ?: date('M', strtotime('0 days'));
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>

                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="display:none;">FLAG</th>
                                        <th style="display:none;">SITE ID</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">COMPOST BATCH ID</th>
                                        <th style="font-size: 12px;">BATCH NAME</th>
                                        <th style="font-size: 12px;">CAPACITY</th>
                                        <th style="font-size: 12px;">UOM</th>
                                        <th style="font-size: 12px;">COMPOST TYPE</th>
                                        <th style="font-size: 12px;">TERIMA JJK</th>
                                        <th style="font-size: 12px;">EST COMPOS</th>
                                        <th style="font-size: 12px;">KELUAR</th>
                                        <th style="font-size: 12px;">SALDO</th>
                                        <th style="font-size: 12px;">STATUS</th>
                                        <th style="font-size: 12px;">START COMPOST</th>
                                        <th style="font-size: 12px;">UMUR [WEEK]</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($Status_Batch as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->STARTCOMPOS);
                                                $flag = $row->FLAG;
                                            ?>
                                            <td style ="display: none;">{{$row->FLAG}}</td>
                                            <td style ="display: none;">{{$row->SITE_ID}}</td>
                                            @IF($flag == 'T') 
                                                <td><strong>TOTAL</strong></td>
                                                <td><strong>-</strong></td>
                                            @else 
                                                <td>{{$row->KEBUN}}</td>
                                                <td>{{$row->COMPOST_BATCH_ID}}</td>
                                            @endif
                                            <td>{{$row->BATCHNAME}}</td>
                                            <td>{{number_format($row->CAPACITY,0,',','.')}}</td>
                                            <td>{{$row->UOM}}</td>
                                            <td>{{$row->COMPOSTTYPE}}</td>
                                            <td>{{number_format($row->TERIMA_JJK,0,',','.')}}</td>
                                            <td>{{number_format($row->EST_COMPOST,0,',','.')}}</td>
                                            <td>{{number_format($row->KELUAR,0,',','.')}}</td>
                                            <td>{{number_format($row->SALDO,0,',','.')}}</td>
                                            <td>{{$row->STATUS}}</td>
                                            @if($row->STARTCOMPOS == NULL)
                                                <td></td>
                                            @else
                                                <td>{{date_format($date, 'd/m/y')}}</td>
                                            @endif
                                            @if($row->UMUR_WEEK == NULL)
                                                <td></td>
                                            @else
                                                @if($row->UMUR_WEEK >= 7 && $row->UMUR_WEEK <=10) 
                                                    <td class = "bg-warning">{{number_format($row->UMUR_WEEK,0,',','.')}}</td>
                                                @elseif ($row->UMUR_WEEK > 10) 
                                                    <td class = "bg-red">{{number_format($row->UMUR_WEEK,0,',','.')}}</td>
                                                @else
                                                    <td>{{number_format($row->UMUR_WEEK,0,',','.')}}</td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach       
                                </tbody>
                            </table>
                        </div>
                        <h5>UMUR 7 - 10 MINGGU = WARNA KUNING</h5>
                        <h5>UMUR > 10 MINGGU = WARNA MERAH</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">  
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('selectjenis').value = "<?php echo isset($_GET['selectjenis']) ? $_GET['selectjenis'] : 'SSB'; ?>";
        document.getElementById('selectstatus').value = "<?php echo isset($_GET['selectstatus']) ? $_GET['selectstatus'] : 'SEMUA'; ?>";
    </script>
@endsection

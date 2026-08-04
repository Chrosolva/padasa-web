@extends('dashboard.app')

@section('header-title')
    Biaya Pupuk / Pemupukan Per Pokok
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Biaya Pupuk / Pemupukan Per Pokok
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/produksi/AnalisaPupukPerPokok') }}">

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
            <div class="col-md-6">
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
                                    $jumlahbaris = 0;
                                    $total_total = 0;
                                    $total_jumlahpokok = 0;
                                    $total_rata2 = 0;

                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="display:none;">JUMLAH BARIS</th>
                                        <th style="font-size: 12px;text-align:center;">BULAN</th>
                                        <th style="font-size: 12px;text-align:center;">BIAYA PEMUPUKAN</th>
                                        <th style="font-size: 12px; text-align:center;">JLH POKOK</th>
                                        <th style="font-size: 12px; text-align:center;">BIAYA PER POKOK (RP/PKK)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($Analisa_PupukPerPokok as $row)
                                        <tr>
                                            <td style="display:none;">{{number_format($jumlahbaris,0,',','.')}}</td>
                                            <td style="text-align: center;">{{number_format($row->BULAN,0,',','.')}}</td>
                                            <td style="text-align: center;">{{number_format($row->TOTAL,0,',','.')}}</td>
                                            <td style="text-align: center;">{{number_format($row->JLH_POKOK,0,',','.')}}</td>
                                            <td style="text-align: center;">{{number_format($row->RATA_BIAYA_PER_POKOK,0,',','.')}}</td>
                                        </tr>
                                        <?php 
                                            if($row->RATA_BIAYA_PER_POKOK > 0) {
                                                $jumlahbaris += 1;
                                            }
                                            $total_total += $row->TOTAL;
                                            $total_jumlahpokok += $row->JLH_POKOK; 
                                            $total_rata2 += $row->RATA_BIAYA_PER_POKOK;
                                        ?>
                                    @endforeach

                                    <tr>
                                        <td style="display:none;">{{number_format($jumlahbaris,0,',','.')}}</td>
                                        <td style="text-align: center;"><strong>TOTAL</strong></td>
                                        <td style="text-align: center;" title="TOTAL SEMUA BIAYA"><strong>{{number_format($total_total,0,',','.')}}</strong></td>
                                        <td style="text-align: center;">{{number_format($total_jumlahpokok,0,',','.')}}</td>
                                        <?php 
                                            if($jumlahbaris == 0) {
                                                $total_rata2 = 0;
                                            }
                                            else {
                                                $total_rata2 = $total_rata2 / $jumlahbaris;
                                            }
                                        ?>
                                        <td style="text-align: center;" title="RATA RATA"><strong>{{number_format($total_rata2,0,',','.')}}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5> * Total diperoleh dari: 81.02.70.02, 81.02.75.01, 81.02.75.02, 81.02.75.04, 81.02.75.05, 81.02.80.06, 81.02.80.09, 81.02.80.10 </h5>
                        <h5> * Jumlah Pokok diperoleh dari data Penerimaan Barang (SIV) dikaitkan dengan normaloriginaltress </h5> 
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">
        // setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('tahun').value = "<?php echo isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('-7 days')); ?>";
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        
    </script>
@endsection

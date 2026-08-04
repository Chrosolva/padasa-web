@extends('dashboard.app')

@section('header-title')
    Analisa Mutasi Pupuk [WAITING USER CONFIRMATION]
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Analisa Mutasi Pupuk [WAITING USER CONFIRMATION]
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/AnalisaMutasiPupuk') }}">
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
                        <div class="box-body table-responsive">
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
                                        <!-- hilangkan NKB 19 02 2025; -->
                                        <th style="font-size: 12px;display:none;">KODE NKB</th>
                                        <th style="font-size: 12px;">NAMA BARANG</th>
                                        <th style="font-size: 12px;">UOM</th>
                                        <th style="font-size: 12px;">OPENING</th>
                                        <th style="font-size: 12px;">MASUK</th>
                                        <th style="font-size: 12px;">KELUAR</th>
                                        <th style="font-size: 12px;">ADJUST</th>
                                        <th style="font-size: 12px;">CLOSING</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($Analisa_Pupuk as $row)
                                        <tr>
                                            <td style="display:none;">{{$row->ITEMCODE}}</td>
                                            <td>{{$row->ITEMDESCRIPTION}}</td>
                                            <td>{{$row->UOMCODE}}</td>
                                            <td style="text-align: right;">{{number_format($row->OPENING,2,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MASUK,2,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->KELUAR,2,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->ADJUST,2,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->CLOSING,2,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        
    </script>
@endsection

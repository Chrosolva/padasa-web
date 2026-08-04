@extends('dashboard.app')

@section('header-title')
    Harga Rata Rata Beli TBS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
        Harga Rata Rata Beli TBS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/rata2HargaBeliTBS') }}">
                    <div class="form-group">
                        <label for="dari_tanggal">Dari Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ Request::get('dari_tanggal') ?: date('d/m/Y', strtotime('-7 days')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampai_tanggal">Sampai Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"  value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selectkebun">Kebun : </label>
                        <select class="form-control" id="selectkebun" name="selectkebun">
                            <option value="SEMUA">SEMUA</option>
                            <option value="TELDA">TELDA</option>
                            <option value="KALSA">KALSA</option>
                            <option value="KALDA">KALDA</option>
                            <option value="KOKAR">KOKAR</option>
                            <option value="RICKO">RICKO</option>
                            <option value="PASER">PASER</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
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
                                        <th style="display: none;">BARIS</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">BULAN</th>
                                        <th style="font-size: 12px;">TAHUN</th>
                                        <th style="font-size: 12px;">RATA2 HARGA BELI</th>
                                        <th style="font-size: 12px;">RATA2 HARGA IDEAL</th>
                                        <th style="font-size: 12px;">RATA2 HARGA IDEAL ZM</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($harga_rata2beliTBS as $row)
                                        <tr>
                                            <td style="display: none;">{{$row->BARIS}}</td>
                                            <td>{{$row->KEBUN}}</td>
                                            <td>{{number_format($row->BULAN,0,',','.')}}</td>
                                            <td>{{$row->TAHUN}}</td>
                                            <td style="text-align: right;">{{number_format($row->AVERAGE_HARGA_BELI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->AVERAGE_HARGA_IDEAL,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->AVERAGE_HARGA_IDEAL_ZM,0,',','.')}}</td>
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
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
        makeDataTableResponsive('table-data', 0, 'asc', ALL);
    </script>
@endsection

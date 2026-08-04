@extends('dashboard.app')

@section('header-title')
    Rekap Pembelian TBS Pihak 3 
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Rekap Pembelian TBS Pihak 3 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/pembelian/RekapPembelianTBSP3') }}">
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
                            <option value="2200">TELDA</option>
                            <option value="2300">KALSA</option>
                            <option value="2400">KALDA</option>
                            <option value="2500">KOKAR</option>
                            <option value="2600">MITRA KOKAR</option>
                            <option value="3200">RICKO</option>
                            <option value="5200">PASER</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <?php
                                    $dom = new DOMDocument();
                                    $dom->loadHtml("Index.php");
                                    
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                <tr>
                                        <th style="font-size: 12px; text-align:center;">TAHUN</th>
                                        <th style="font-size: 12px; text-align:center;">BULAN</th>
                                        <th style="font-size: 12px; text-align:center;">HARGA RATA-RATA [RP]</th>
                                        <th style="font-size: 12px; text-align:center;">TONASE [KG]</th>
                                        <th style="font-size: 12px; text-align:center;">TOTAL REALISASI [RP]</th>                                        
                                    </tr>

                                </thead>

                                <tbody>
                                    @foreach ($RekapPembelian_TBSP3 as $row)
                                        <tr>
                                            @if($row->BULAN == '13')
                                                <td style="text-align: center;"><strong>{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td style="text-align: center;"><strong>TOTAL</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($row->HARGA_BELI_AVERAGE,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($row->TBSTERIMA,0,',','.')}}</strong></td>
                                                <td style="text-align: center;"><strong>{{number_format($row->TOTAL,0,',','.')}}</strong></td>
                                            @else
                                                <td style="text-align: center;">{{number_format($row->TAHUN,0,'','')}}</td>
                                                <td style="text-align: center;">{{number_format($row->BULAN,0,'','')}}</td>
                                                <td style="text-align: center;">{{number_format($row->HARGA_BELI_AVERAGE,0,',','.')}}</td>
                                                <td style="text-align: center;">{{number_format($row->TBSTERIMA,0,',','.')}}</td>
                                                <td style="text-align: center;">{{number_format($row->TOTAL,0,',','.')}}</td>
                                            @endif
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
        makeDataTableResponsive('table-data', 0, 'asc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>
@endsection

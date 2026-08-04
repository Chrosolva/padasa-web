@extends('dashboard.app')

@section('header-title')
    Persediaan Produk Sampingan Semua PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Persediaan Produk Sampingan Semua PMKS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpByProductBulanan') }}">
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
                            <label for="selectproduct">Produk : </label>
                            <select class="form-control" id="selectproduct" name="selectproduct">
                                <option value="SEMUA">SEMUA</option>
                                <option value="PALM ACID OIL">PALM ACID OIL</option>
                                <option value="Cangkang">Cangkang</option>
                                <option value="Fiber">Fiber</option>
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
            <div class="col-md-12">
                <div class="box box-primary">
                    {{-- <div class="box-header with-border">
                    </div> --}}
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="display:none;">BARIS</th>
                                        <th style="font-size: 12px;">TGL</th>
                                        <th style="font-size: 12px;">KEBUN</th>
                                        <th style="font-size: 12px;">PRODUK</th>
                                        <th style="font-size: 12px;">SALDO AWAL (KG)</th>
                                        <th style="font-size: 12px;">PRODUKSI (KG)</th>
                                        <th style="font-size: 12px;">PENGIRIMAN (KG)</th>
                                        <th style="font-size: 12px;">DIPAKAI (KG)</th>
                                        <th style="font-size: 12px;">SALDO AKHIR (KG)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProdukSampinganBulanan as $row)
                                        <tr>
                                            <td style ="display: none;">{{$row->BARIS}}</td>
                                            <?php 
                                                $date = date_create($row->TGL);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                            <td>{{$row->NAMA_KEBUN}}</td>
                                            <td>{{$row->PRODUK}}</td>
                                            @if($row->PRODUK == 'PALM ACID OIL' && $row->PRODUKSI > $row->TONASE_MAX) 
                                                <td class="bg-red">{{number_format($row->SALDOAWAL,0,',','.')}}</td>
                                                <td class="bg-red">{{number_format($row->PRODUKSI,0,',','.')}}</td>
                                                <td class="bg-red">{{number_format($row->PENGIRIMAN,0,',','.')}}</td>
                                                <td class="bg-red">{{number_format($row->DIPAKAI,0,',','.')}}</td>
                                                <td class="bg-red">{{number_format($row->SALDOAKHIR,0,',','.')}}</td>
                                            @else 
                                                <td>{{number_format($row->SALDOAWAL,0,',','.')}}</td>
                                                <td>{{number_format($row->PRODUKSI,0,',','.')}}</td>
                                                <td>{{number_format($row->PENGIRIMAN,0,',','.')}}</td>
                                                <td>{{number_format($row->DIPAKAI,0,',','.')}}</td>
                                                <td>{{number_format($row->SALDOAKHIR,0,',','.')}}</td>
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
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'PALM ACID OIL'; ?>";
    </script>
@endsection
@extends('dashboard.app')

@section('header-title')
    Analsis Stok
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Analisis Stok 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/analisisstok') }}">
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
                        <label for="selectkebun">PMKS : </label>
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="table-data" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="display: none;">TAHUN</th>
                                    <th>BULAN</th>
                                    <th>PRODUKSI MS</th>
                                    <th>PENGIRIMAN MS</th>
                                    <th>SISA MS</th> 
                                    <th>PRODUKSI IS</th>
                                    <th>PENGIRIMAN IS</th>
                                    <th>SISA IS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($analisa_produksi); $i++)
                                    <tr>
                                        <td style="text-align:right;display:none;">{{number_format($analisa_produksi[$i]->TAHUN,0,',','') }}</td>
                                        <td style="text-align:right;">{{number_format($analisa_produksi[$i]->BULAN,0,',','') }} - {{number_format($analisa_produksi[$i]->TAHUN,0,',','') }}</td>
                                        <td style="text-align:right;">{{number_format($analisa_produksi[$i]->PRODUKSI_MS,0,',','.') }}</td>
                                        <td style="text-align:right;">{{number_format($analisa_produksi[$i]->PENGIRIMAN_MS,0,',','.') }}</td>
                                        @if($i >= count($analisa_stok)) 
                                            <td style="text-align:right;">-</td>
                                        @else
                                            <td style = "text-align:right;">{{number_format($analisa_stok[$i]->SISA_MS,0,',','.')}}</td>
                                        @endif
                                        <td style="text-align:right;">{{number_format($analisa_produksi[$i]->PRODUKSI_IS,0,',','.') }}</td>
                                        <td style="text-align:right;">{{number_format($analisa_produksi[$i]->PENGIRIMAN_IS,0,',','.') }}</td>
                                        @if($i >= count($analisa_stokIS)) 
                                            <td style="text-align:right;">-</td>
                                        @else
                                            <td style = "text-align:right;">{{number_format($analisa_stokIS[$i]->SISA_IS,0,',','.')}}</td>
                                        @endif
                                    </tr>
                                @endfor
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script-content')
    <script type="text/javascript">
        // setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
    </script>
@endsection
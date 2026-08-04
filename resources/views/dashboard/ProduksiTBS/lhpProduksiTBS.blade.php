@extends('dashboard.app')

@section('header-title')
    Produksi TBS [Kg]
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Produksi TBS [Kg]
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/lhpProduksiTBS') }}">
                    <div class="row">
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
                                <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ Request::get('sampai_tanggal') ?: date('d/m/Y', strtotime('-1 days')) }}">
                            </div>
                        </div>

                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">

            {{-- <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="chart">
                        <canvas id="lineChart_1" style="height:300px"></canvas>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-12">
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
                                        <th style="font-size: 12px;display:none;">FLAG</th>
                                        <th style="font-size: 12px;display:none;">TAHUN</th>
                                        <th style="font-size: 12px;">BULAN</th>
                                        <th style="font-size: 12px;">TELDA</th>
                                        <th style="font-size: 12px;">KALSA</th>
                                        <th style="font-size: 12px;">KALDA</th>
                                        <th style="font-size: 12px;">MITRA KALDA</th>
                                        <th style="font-size: 12px;">KOKAR</th>
                                        <th style="font-size: 12px;">MITRA KOKAR</th>
                                        <th style="font-size: 12px;">RICKO</th>
                                        <th style="font-size: 12px;">MITRA RICKO</th>
                                        <th style="font-size: 12px;">PASER</th>
                                        <th style="font-size: 12px;">MITRA PASER</th>
                                        <th style="font-size: 12px;">MUARA</th>
                                        <th style="font-size: 12px;">MITRA MUARA</th>
                                        <th style="font-size: 12px;">LANGGAI</th>
                                        <th style="font-size: 12px;">MITRA LANGGAI</th>
                                        <th style="font-size: 12px;">TOTAL</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_ProduksiTBS as $row)
                                        <tr>
                                            <td style="text-align: right;display:none;">{{$row->FLAG}}</td>
                                            <td style="text-align: right;display:none;">{{$row->TAHUN}}</td>
                                            <td style="text-align: right;">{{$row->BULAN}} - {{$row->TAHUN}}</td>
                                            <td style="text-align: right;">{{number_format($row->TELDA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->KALSA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->KALDA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_KALDA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->KOKAR,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_KOKAR,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->RICKO,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_RICKO,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->PASER,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_PASER,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MUARA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_MUARA,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->LANGGAI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->MITRA_LANGGAI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TOTAL,0,',','.')}}</td>
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
    </script>
@endsection

@extends('dashboard.app')

@section('header-title')
        Rendemen MS Semua PMKS
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Rendemen MS Semua PMKS
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/lhpexecutive/lhpEDMain') }}">
                    <div class="row">
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
                        <div class="form-group" style="display: none;">
                            <label for="selecttoleransi">Toleransi : </label>
                            <input type='number' step='0.01' value='0.35' placeholder='0.00' id = 'toleransi' name = 'toleransi'/>
                        </div>
                        <div class="form-group form-inline">
                            <button type="submit" class="form-control btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        </div>
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
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">TGL</th>
                                        <th style="font-size: 12px;">TELDA IDEAL</th>
                                        <th style="font-size: 12px;">TELDA REALISASI</th>
                                        <th style="font-size: 12px;">KALSA IDEAL</th>
                                        <th style="font-size: 12px;">KALSA REALISASI</th>
                                        <th style="font-size: 12px;">KALDA IDEAL</th>
                                        <th style="font-size: 12px;">KALDA REALISASI</th>
                                        <th style="font-size: 12px;">KOKAR IDEAL</th>
                                        <th style="font-size: 12px;">KOKAR REALISASI</th>
                                        <th style="font-size: 12px;">RICKO IDEAL</th>
                                        <th style="font-size: 12px;">RICKO REALISASI</th>
                                        <th style="font-size: 12px;">PASER IDEAL</th>
                                        <th style="font-size: 12px;">PASER REALISASI</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($lhp_EDMain as $row)
                                        <tr>
                                            <?php 
                                                $date = date_create($row->TANGGAL);
                                            ?>
                                            <td>{{date_format($date, 'd/m/y')}}</td> 
                                            @if ( round($row->TELDA_REALISASI - $row->TELDA_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->TELDA_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->TELDA_REALISASI,2,'.',',') }}</td>
                                            @else
                                                @if ($row->TELDA_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->TELDA_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->TELDA_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->TELDA_REALISASI == 0)
                                                    <td class="bg-red hoverTD" style="text-align: right;"  id = "{{$row->TANGGAL}}" >{{number_format($row->TELDA_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;" class="hoverTD" id = "{{$row->TANGGAL}}" > {{number_format($row->TELDA_REALISASI,2,'.',',')}} </td>
                                                @endif
                                            @endif
                                            @if ( round($row->KALSA_REALISASI - $row->KALSA_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KALSA_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KALSA_REALISASI,2,'.',',')}}</td>
                                            @else
                                                @if ($row->KALSA_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KALSA_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KALSA_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->KALSA_REALISASI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KALSA_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KALSA_REALISASI,2,'.',',')}}</td>
                                                @endif
                                            @endif
                                            @if ( round($row->KALDA_REALISASI - $row->KALDA_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KALDA_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KALDA_REALISASI,2,'.',',')}}</td>
                                            @else
                                                @if ($row->KALDA_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KALDA_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KALDA_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->KALDA_REALISASI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KALDA_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KALDA_REALISASI,2,'.',',')}}</td>
                                                @endif
                                            @endif
                                            @if ( round($row->KOKAR_REALISASI - $row->KOKAR_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KOKAR_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->KOKAR_REALISASI,2,'.',',')}}</td>
                                            @else
                                                @if ($row->KOKAR_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KOKAR_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KOKAR_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->KOKAR_REALISASI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->KOKAR_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->KOKAR_REALISASI,2,'.',',')}}</td>
                                                @endif
                                            @endif
                                            @if ( round($row->RICKO_REALISASI - $row->RICKO_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->RICKO_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->RICKO_REALISASI,2,'.',',')}}</td>
                                            @else
                                                @if ($row->RICKO_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->RICKO_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->RICKO_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->RICKO_REALISASI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->RICKO_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->RICKO_REALISASI,2,'.',',')}}</td>
                                                @endif
                                            @endif
                                            @if ( round($row->PASER_REALISASI - $row->PASER_IDEAL,2) < -0.35 )
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->PASER_IDEAL,2,'.',',')}}</td>
                                                <td class ="bg-red" style="text-align: right;">{{number_format($row->PASER_REALISASI,2,'.',',')}}</td>
                                            @else
                                                @if ($row->PASER_IDEAL == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->PASER_IDEAL,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->PASER_IDEAL,2,'.',',')}}</td>
                                                @endif
                                                @if ($row->PASER_REALISASI == 0)
                                                    <td class="bg-red" style="text-align: right;">{{number_format($row->PASER_REALISASI,2,'.',',')}}</td>
                                                @else
                                                    <td style="text-align: right;">{{number_format($row->PASER_REALISASI,2,'.',',')}}</td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>Keterangan: </p>
                        <p>Ideal = Rendemen Gabungan RKAP CPO dari Total Produksi TBS </p>
                        <p>Realisasi = Rendemen Gabungan Realisasi CPO dari Total Produksi TBS</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        // var lhpEDMain = <?php echo json_encode($lhp_EDMain); ?>;
        // console.log(lhpEDMain);
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('toleransi').value = "<?php echo isset($_GET['toleransi']) ? $_GET['toleransi'] : '0.35'; ?>";

    </script>
@endsection
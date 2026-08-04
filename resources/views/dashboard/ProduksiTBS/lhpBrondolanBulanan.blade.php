@extends('dashboard.app')

@section('header-title')
    Brondolan
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Brondolan
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline mb-2" method="GET" action="{{ url('/dashboard/produksi/lhpBrondolanBulanan') }}">
                    <div class="row">
                        <div class="form-group">
                            <label for="dari_tanggal">Dari Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="dari_tanggal" name="dari_tanggal"
                                value="{{ request('dari_tanggal', \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sampai_tanggal">Sampai Tanggal : </label>
                            <div class="input-group date input-inline" style="width: 175px;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="sampai_tanggal" name="sampai_tanggal"
                                value="{{ request('sampai_tanggal', \Carbon\Carbon::now()->endOfMonth()->format('d/m/Y')) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selectkebun">Kebun : </label>
                            <select class="form-control" id="selectkebun" name="selectkebun">
                                <option value="SEMUA">SEMUA</option>
                                <option value="2200">TELDA</option>
                                <option value="2300">KALSA</option>
                                <option value="2400">KALDA</option>
                                <option value="2500">KOKAR</option>
                                <option value="3200">RICKO</option>
                                <option value="5200">PASER</option>
                            </select>
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
                                    $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'SEMUA';
                                    // echo $selectkebun;
                                    // echo $selecttype;
                            ?>
                            <table id="table-data" class="table table-bordered table-striped table-hover datatable" >
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;display:none;" rowspan = 2 >DETAIl</th>
                                        <th style="font-size: 12px; text-align: center;" rowspan = 2 >BULAN</th>
                                        <th style="font-size: 12px; text-align: center;display:none;" rowspan = 2 >TAHUN</th>
                                        <th style="font-size: 12px; text-align: center;"rowspan = 2 >SUPPLIER</th>
                                        <th style="font-size: 12px; text-align: center;">TOTAL TBS & BRD SEBELUM SORTASI [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">TOTAL TBS & BRD SETELAH SORTASI [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">TOTAL POTONGAN TBS & BRD [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">Potongan TBS & BRD thd Sortasi [%]</th>
                                        <th style="font-size: 12px; text-align: center;">BRD SEBELUM SORTASI [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">BRD SETELAH SORTASI [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">POTONGAN BRD [KG]</th>
                                        <th style="font-size: 12px; text-align: center;">PERSEN BRD [%]</th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px; text-align: center;">A</th>
                                        <th style="font-size: 12px; text-align: center;">B</th>
                                        <th style="font-size: 12px; text-align: center;">C = A-B </th>
                                        <th style="font-size: 12px; text-align: center;">D = C/A </th>
                                        <th style="font-size: 12px; text-align: center;">E</th>
                                        <th style="font-size: 12px; text-align: center;">F</th>
                                        <th style="font-size: 12px; text-align: center;">G = E-F</th>
                                        <th style="font-size: 12px; text-align: center;">H = F/B %</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lhp_brondolan as $row)
                                        <tr>
                                            <td style="text-align: left;display:none;">{{$row->DETAIL}}</td>
                                            @if($row->SUPPLIERNAME == "TOTAL")
                                                <td style="text-align: left;"><strong>-</strong></td>
                                                <td style="text-align: left;display:none;"><strong>-</strong></td>
                                                <td style="text-align: left;"><strong>{{$row->SUPPLIERNAME}}</strong></td>
                                            @else 
                                                <td>{{$row->BULAN}} - {{$row->TAHUN}}</td>
                                                <td style="display:none;">{{$row->TAHUN}}</td>
                                                <td style="text-align: left;">{{$row->SUPPLIERNAME}}</td>
                                            @endif
                                            <td style="text-align: right;">{{number_format($row->TBS_BRD_SBLM_SORTASI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->TBS_BRD_STLH_SORTASI,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->POTONGAN_TBS_BRD,0,',','.')}}</td>
                                            <td style="text-align: right;">{{number_format($row->PERSEN_POTONGAN_TBS_BRD,2,',','.')}} %</td>
                                            @if ($row->BRD_SBLM_SORTASI != null)
                                                <td style="text-align: right;">{{number_format($row->BRD_SBLM_SORTASI,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">-</td>
                                            @endif
                                            @if ($row->BRD_STLH_SORTASI != null)
                                                <td style="text-align: right;">{{number_format($row->BRD_STLH_SORTASI,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">-</td>
                                            @endif
                                            @if ($row->BRD_POTONGAN != null)
                                                <td style="text-align: right;">{{number_format($row->BRD_POTONGAN,0,',','.')}}</td>
                                            @else
                                                <td style="text-align: right;">-</td>
                                            @endif
                                            @if ($row->PERSEN_BRONDOLAN != null)
                                                <td style="text-align: right;">{{number_format($row->PERSEN_BRONDOLAN,2,',','.')}} %</td>
                                            @else
                                                <td style="text-align: right;">-</td>
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
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : 'SEMUA'; ?>";
    </script>
@endsection

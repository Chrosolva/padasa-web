@extends('dashboard.app')

@section('header-title')
    Shipment
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Shipment 
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/pengiriman') }}">
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
                        <label for="selectkebun">MILL : </label>
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

                    <div class="form-group">
                        <label for="selectproduct">Produk : </label>
                        <select class="form-control" id="selectproduct" name="selectproduct">
                            <option value="SEMUA">SEMUA</option>
                            <option value="MINYAK SAWIT">MINYAK SAWIT</option>
                            <option value="INTI SAWIT">INTI SAWIT</option>
                            <option value="CRUDE PALM OIL (CP1)">CRUDE PALM OIL (CP1)</option>
                            <option value="CANGKANG">CANGKANG</option>
                            <option value="ABU BOILER">ABU BOILER</option>
                            <option value="JANJANGAN KOSONG">JANJANGAN KOSONG</option>
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
                            <?php
                                        $dom = new DOMDocument();
                                        $dom->loadHtml("Index.php");
                                        $selectkebun = isset($_REQUEST['selectkebun']) ? $_REQUEST['selectkebun'] :  'TELDA';
                                        $selectproduk = isset($_REQUEST['selectproduct']) ? $_REQUEST['selectproduct'] :  'CPO';
                                        $total_tonase_terima = 0;
                                        $total_tonase_kirim = 0;
                                        $total_jumlah_nilai = 0;
                                        $total_tonase_retur = 0;
                                        // echo $selectkebun;
                                        // echo $selecttype;
                                ?>
                            <thead>
                                <tr>
                                    <th>BULAN</th>
                                    <th>TAHUN</th>
                                    <th>PMKS</th>
                                    <th>TGL KONTRAK</th>
                                    <th>NO KONTRAK</th>
                                    <th>NAMA PEMBELI</th>
                                    <th>PRODUK</th>
                                    <th>TONASE KONTRAK [KG]</th>
                                    <th>HARGA/KG [RP]</th>
                                    <th data-toggle="tooltip" data-placement="left" title="TONASE YANG KELUAR DARI PMKS PENJUAL">TONASE TERIMA [KG]</th>
                                    <th data-toggle="tooltip" data-placement="left" title="TONASE YANG DITERIMA OLEH CUSTOMER">TONASE KIRIM [KG]</th>
                                    <th>JUMLAH NILAI [RP]</th>
                                    <th>TONASE RETUR [KG]</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengiriman as $row)
                                    <tr>
                                        <td>{{$row->BULAN}}</td>
                                        <?php 
                                            $date = date_create($row->TGLKONTRAK);
                                        ?>
                                        <td>{{$row->TAHUN}}</td>
                                        <td>{{$row->MILL_SOURCE}}</td>
                                        @if($row->MILL_SOURCE == 'TOTAL' OR $row->MILL_SOURCE == NULL) 
                                            <td></td>
                                        @else
                                            <td>{{date_format($date, 'd/m/y')}}</td>
                                        @endif
                                        <td>{{$row->AGREEMENTCODE}}</td>
                                        <td>{{$row->NAMACUSTOMER}}</td> 
                                        <td>{{$row->NAMAPRODUK}}</td> 
                                        @if($row->MILL_SOURCE == 'TOTAL') 
                                            <td></td>
                                        @else
                                            <td style="text-align: right;">{{number_format($row->QTYKONTRAK,0,',','.')}}</td>
                                        @endif
                                        <td style="text-align: right;">{{number_format($row->UNITPRICE,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->TONASEKIRIM,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->TONASETERIMA,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->TOTAL,0,',','.')}}</td>
                                        <td style="text-align: right;">{{number_format($row->TONASERETUR,0,',','.')}}</td>

                                        <?php 
                                            $total_tonase_terima += $row->TONASEKIRIM;
                                            $total_tonase_kirim += $row->TONASETERIMA;
                                            $total_jumlah_nilai += $row->TOTAL;
                                            $total_tonase_retur += $row->TONASERETUR; 
                                        ?>  
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p>Data di atas merupakan data shipment penjualan</p>
                        <table id="table-data2" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>BULAN</th>
                                    <th>TAHUN</th>
                                    <th>PMKS</th>
                                    <th>TGL KONTRAK</th>
                                    <th>NO KONTRAK</th>
                                    <th>NAMA PEMBELI</th>
                                    <th>PRODUK</th>
                                    <th>TOTAL TONASE KONTRAK [KG]</th>
                                    <th>AVG HARGA/KG [RP]</th>
                                    <th data-toggle="tooltip" data-placement="left" title="TONASE YANG KELUAR DARI PMKS PENJUAL">TOTAL TONASE TERIMA [KG]</th>
                                    <th data-toggle="tooltip" data-placement="left" title="TONASE YANG DITERIMA OLEH CUSTOMER">TOTAL TONASE KIRIM [KG]</th>
                                    <th>TOTAL JUMLAH NILAI [RP]</th>
                                    <th>TOTAL TONASE RETUR [KG]</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TOTAL</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td> 
                                    <td>-</td> 
                                    <td style="text-align: right;">-</td>
                                    <td style="text-align: right;">{{number_format($total_jumlah_nilai / $total_tonase_terima,0,',','.')}}</td>
                                    <td style="text-align: right;">{{number_format($total_tonase_terima,0,',','.')}}</td>
                                    <td style="text-align: right;">{{number_format($total_tonase_kirim,0,',','.')}}</td>
                                    <td style="text-align: right;">{{number_format($total_jumlah_nilai,0,',','.')}}</td>
                                    <td style="text-align: right;">{{number_format($total_tonase_retur,0,',','.')}}</td>
                                </tr>
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
        setValidationRangeDatePicker('dari_tanggal', 'sampai_tanggal');
        makeDataTableResponsive('table-data', 0, 'desc', -1);
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        document.getElementById('selectproduct').value = "<?php echo isset($_GET['selectproduct']) ? $_GET['selectproduct'] : 'MINYAK SAWIT'; ?>";
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
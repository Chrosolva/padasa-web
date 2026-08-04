@extends('dashboard.app')

@section('header-title')
    Harga Tender Final LTC [Waiting User Confirmation]
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Harga Tender Final LTC [Waiting User Confirmation]
        <small>Dengan Kontrak Jual</small>
    </h1>
</section>

<section class="content">
    <div class="panel">
        <div class="panel-body">
            <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/penjualan/harga-tender-final-ltc') }}">

                <div class="form-group">
                    <label for="startdate">Dari Tanggal : </label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" id="startdate" name="startdate"
                               value="{{ Request::get('startdate') ?: date('Y-m-d', strtotime('-7 days')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="enddate">Sampai Tanggal : </label>
                    <div class="input-group date input-inline" style="width: 175px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" id="enddate" name="enddate"
                               value="{{ Request::get('enddate') ?: date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_id">Site : </label>
                    <select class="form-control" id="site_id" name="site_id">
                        <option value="2100">2100</option>
                        <option value="3100">3100</option>
                        <option value="5100">5100</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pmks">PMKS : </label>
                    <select class="form-control" id="pmks" name="pmks">
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
                    <label for="produk">Produk : </label>
                    <select class="form-control" id="produk" name="produk">
                        <option value="CPO">CPO</option>
                        <option value="PK">PK</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenistender">Jenis Tender : </label>
                    <select class="form-control" id="jenistender" name="jenistender">
                        <option value="AVG">AVG</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body table-responsive">
                    <table id="table-data" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>PMKS</th>
                                <th>TGL TENDER</th>
                                <th>TGL DATA AWAL</th>
                                <th>TGL DATA AKHIR</th>
                                <th>NAMA</th>
                                <th>HRG AVRG</th>
                                <th>OA</th>
                                <th>PREMI</th>
                                <th>HARGA TENDER FINAL</th>
                                <th>QTY KONTRAK</th>
                                <th>TGL KONTRAK</th>
                                <th>AGREEMENT CODE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $row->PMKS }}</td>
                                    <td>{{ $row->TGLTENDER ? date('d/m/Y', strtotime($row->TGLTENDER)) : '' }}</td>
                                    <td>{{ $row->TGL_DATA_AWAL ? date('d/m/Y', strtotime($row->TGL_DATA_AWAL)) : '' }}</td>
                                    <td>{{ $row->TGL_DATA_AKHIR ? date('d/m/Y', strtotime($row->TGL_DATA_AKHIR)) : '' }}</td>
                                    <td>{{ $row->NAMA }}</td>
                                    <td style="text-align:right;">{{ number_format($row->HRG_AVRG, 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($row->OA, 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($row->PREMI, 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($row->HARGATENDERFINAL, 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($row->QTYKONTRAK, 0, ',', '.') }}</td>
                                    <td>{{ $row->TGLKONTRAK ? date('d/m/Y', strtotime($row->TGLKONTRAK)) : '' }}</td>
                                    <td>{{ $row->AGREEMENTCODE }}</td>
                                </tr>
                            @endforeach
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
    makeDataTableResponsive('table-data', 0, 'asc', -1);

    document.getElementById('site_id').value = "{{ Request::get('site_id') ?: '2100' }}";
    document.getElementById('pmks').value = "{{ Request::get('pmks') ?: 'TELDA' }}";
    document.getElementById('produk').value = "{{ Request::get('produk') ?: 'CPO' }}";
    document.getElementById('jenistender').value = "{{ Request::get('jenistender') ?: 'AVG' }}";
</script>
@endsection
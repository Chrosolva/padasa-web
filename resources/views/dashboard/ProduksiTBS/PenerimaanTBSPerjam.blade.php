@extends('dashboard.app')

@section('header-title')
    Penerimaan TBS Per Jam
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Penerimaan TBS Per Jam
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="panel">
            <div class="panel-body">
                <form role="form" class="form-inline" method="GET" action="{{ url('/dashboard/produksi/PenerimaanTBSPerJam') }}">
                    <div class="form-group">
                        <label for="per_tanggal">Tanggal : </label>
                        <div class="input-group date input-inline">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="per_tanggal" name="per_tanggal" value="{{ Request::get('per_tanggal') ?: date('d/m/Y', strtotime('0 days')) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selectkebun">Kebun : </label>
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
            {{-- PENERIMAAN TBS PER JAM [PTPJ] --}}
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Penerimaan TBS Per Jam</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_PTPJ" style="height:400px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script-content')
    <script type="text/javascript">
        setValidationRangeDatePicker('per_tanggal');
        document.getElementById('selectkebun').value = "<?php echo isset($_GET['selectkebun']) ? $_GET['selectkebun'] : '2200'; ?>";
        // Penerimaan TBS Per Jam
        generateLineChartJSCustom2('lineChart_PTPJ',
            [
                'Jam 00',
                'Jam 01',
                'Jam 02',
                'Jam 03',
                'Jam 04',
                'Jam 05',
                'Jam 06',
                'Jam 07',
                'Jam 08',
                'Jam 09',
                'Jam 10',
                'Jam 11',
                'Jam 12',
                'Jam 13',
                'Jam 14',
                'Jam 15',
                'Jam 16',
                'Jam 17',
                'Jam 18',
                'Jam 19',
                'Jam 20',
                'Jam 21',
                'Jam 22',
                'Jam 23',
                'Jam 24'
            ],
            [
                @foreach($Penerimaan_TBSPerJam as $row)
                    {
                        label :  '{{$row->SUPPLIERNAME}}',
                        data : [
                            {{$row->JAM00}},
                            {{$row->JAM01}},
                            {{$row->JAM02}},
                            {{$row->JAM03}},
                            {{$row->JAM04}},
                            {{$row->JAM05}},
                            {{$row->JAM06}},
                            {{$row->JAM07}},
                            {{$row->JAM08}},
                            {{$row->JAM09}},
                            {{$row->JAM10}},
                            {{$row->JAM11}},
                            {{$row->JAM12}},
                            {{$row->JAM13}},
                            {{$row->JAM14}},
                            {{$row->JAM15}},
                            {{$row->JAM16}},
                            {{$row->JAM17}},
                            {{$row->JAM18}},
                            {{$row->JAM19}},
                            {{$row->JAM20}},
                            {{$row->JAM21}},
                            {{$row->JAM22}},
                            {{$row->JAM23}},
                            {{$row->JAM24}},
                        ],
                        ticks: {
                            stepSize: 5
                        }
                    }, 
                @endforeach
            ],
            [
                @foreach($Penerimaan_TBSPerJam as $row)
                    {
                        label :  '{{$row->SUPPLIERNAME}}',
                        category: 'TRIP',
                        data2 : [
                            {{$row->TRIP00}},
                            {{$row->TRIP01}},
                            {{$row->TRIP02}},
                            {{$row->TRIP03}},
                            {{$row->TRIP04}},
                            {{$row->TRIP05}},
                            {{$row->TRIP06}},
                            {{$row->TRIP07}},
                            {{$row->TRIP08}},
                            {{$row->TRIP09}},
                            {{$row->TRIP10}},
                            {{$row->TRIP11}},
                            {{$row->TRIP12}},
                            {{$row->TRIP13}},
                            {{$row->TRIP14}},
                            {{$row->TRIP15}},
                            {{$row->TRIP16}},
                            {{$row->TRIP17}},
                            {{$row->TRIP18}},
                            {{$row->TRIP19}},
                            {{$row->TRIP20}},
                            {{$row->TRIP21}},
                            {{$row->TRIP22}},
                            {{$row->TRIP23}},
                            {{$row->TRIP24}},
                        ],
                        category2: 'AVERAGE', 
                        data3 : [
                            {{$row->AVRG00}},
                            {{$row->AVRG01}},
                            {{$row->AVRG02}},
                            {{$row->AVRG03}},
                            {{$row->AVRG04}},
                            {{$row->AVRG05}},
                            {{$row->AVRG06}},
                            {{$row->AVRG07}},
                            {{$row->AVRG08}},
                            {{$row->AVRG09}},
                            {{$row->AVRG10}},
                            {{$row->AVRG11}},
                            {{$row->AVRG12}},
                            {{$row->AVRG13}},
                            {{$row->AVRG14}},
                            {{$row->AVRG15}},
                            {{$row->AVRG16}},
                            {{$row->AVRG17}},
                            {{$row->AVRG18}},
                            {{$row->AVRG19}},
                            {{$row->AVRG20}},
                            {{$row->AVRG21}},
                            {{$row->AVRG22}},
                            {{$row->AVRG23}},
                            {{$row->AVRG24}},
                        ]
                    }, 
                @endforeach
            ]
        );
    </script>
@endsection

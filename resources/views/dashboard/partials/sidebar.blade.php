<style>
    /* Hide disabled menu */
    .sidebar-menu li.disabled {
        display: none !important;
    }

    /* Status submenu */
    .sidebar-menu .menu-unconfirmed {
        color: #f39c12 !important;
    }

    .sidebar-menu .treeview-menu > li > a > .fa {
        width: 20px;
        text-align: center;
    }
</style>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="{{ url('dashboard/home') }}"><i class="fa fa-home"></i> <span>Home</span></a>
            </li>
            {{-- <li class="treeview {{ Auth::user()->canAccessByHakAkses('Dashboard', 'Personalia') ? '' : 'disabled' }}">
                <a href="{{ url('dashboard/personalia') }}"><i class="fa fa-users"></i> <span>Personalia</span></a>
            </li> --}}
            <li class="treeview" style="display:none;">
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>Timbangan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('Timbangan', 'Pengolahan TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/timbangan/pengolahan-tbs') }}"><i class="fa fa-circle-o"></i> Pengolahan TBS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Timbangan', 'Produksi Gabungan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/timbangan/produksi-gabungan') }}"><i class="fa fa-circle-o"></i> Produksi Gabungan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Timbangan', 'Rendemen Gabungan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/timbangan/rendemen-gabungan') }}"><i class="fa fa-circle-o"></i> Rendemen Gabungan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Timbangan', 'Hasil Timbang') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/timbangan/hasil-timbang') }}"><i class="fa fa-circle-o"></i> Hasil Timbang</a></li> --}}
                    {{-- fortesting
                    {{-- <li>{{ Auth::user()->canAccessByHakAkses('Timbangan', 'Pengolahan TBS') ? 'aaa' : 'disabled' }}</li>
                    <li>testing</li> --}}
                </ul>
            </li>

            <li class="{{ Request::is('dashboard/home') ? 'active' : '' }}">
                <a href="{{ route('dashboard.home') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Overview</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Areal Statement</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('Areal Statement', 'BreakDown Luasan Per Wilayah dan PT') ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/arealstatement/breakdown-luasan-wilayah-pt') }}" class="sidebar-wrap-link">
                            {{-- <i class="fa fa-exclamation-circle menu-unconfirmed" title="Belum dikonfirmasi"></i>--}}
                            <span class="sidebar-wrap-text">Luasan Per Wilayah dan PT</span>
                        </a>
                    </li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Areal Statement', 'Luasan Wilayah Per Kebun') ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/arealstatement/luasan-wilayah-per-kebun') }}" class="sidebar-wrap-link">
                            {{-- <i class="fa fa-exclamation-circle menu-unconfirmed" title="Belum dikonfirmasi"></i>--}}
                            <span class="sidebar-wrap-text">Luasan Wilayah</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tint"></i>
                    <span>Produksi MS IS</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP main') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpEDMain') }}"><i class="fa fa-circle-o"></i> Rend MS Semua PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP detail') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpEDDetail') }}"><i class="fa fa-circle-o"></i> Rend MS Per PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP main Inti') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpEDMainInti') }}"><i class="fa fa-circle-o"></i> Rend IS Semua PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP detail Inti') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpEDDetailInti') }}"><i class="fa fa-circle-o"></i> Rend IS Per PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP ffa PMKS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/ReportFFAallPMKS') }}"><i class="fa fa-circle-o"></i> ALB MS Semua PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP report alb') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/ReportALB') }}"><i class="fa fa-circle-o"></i> ALB MS Per PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP ffa PMKS Inti') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/ReportFFAallPMKSInti') }}"><i class="fa fa-circle-o"></i> ALB IS Semua PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP report alb Inti') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/ReportALBInti') }}"><i class="fa fa-circle-o"></i> ALB IS Per PMKS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Kadar Air MS/IS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/KadarAirMSIS') }}"><i class="fa fa-circle-o"></i> Kadar Air MS/IS (PMKS)</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Kadar Kotoran MS/IS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/KadarKotoranMSIS') }}"><i class="fa fa-circle-o"></i> Kadar Kotoran MS/IS (PMKS)</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRealisasiVsTarget') }}"><i class="fa fa-circle-o"></i> Realisasi Rend MS Vs Target</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpHitunganP3') }}"><i class="fa fa-circle-o"></i> Hitungan Pihak 3</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Proporsi Produksi P3 Harian') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/proporsiProduksiP3Harian') }}"><i class="fa fa-circle-o"></i>Proporsi Produksi P3 Harian</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Real Vs Target Inti') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRealisasiVsTargetInti') }}"><i class="fa fa-circle-o"></i> Realisasi Rend Inti Vs Target</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP TBS Olah') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpTBSOlah') }}"><i class="fa fa-circle-o"></i> TBS Olah</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP TBS Tersedia') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpTBSTersedia') }}"><i class="fa fa-circle-o"></i> TBS Tersedia</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Persediaan Produk Sampingan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpByProduct') }}"><i class="fa fa-circle-o"></i> Persediaan Produk Sampingan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Waktu Proses') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpWaktuProsesLHP') }}"><i class="fa fa-circle-o"></i> Waktu Proses LHP</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Mutasi CI') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/MutasiCI') }}"><i class="fa fa-circle-o"></i> Mutasi Persediaan CPO INTI</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Get MS IS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/MSISPerGrup') }}"><i class="fa fa-circle-o"></i>Produksi MS IS Per Grup</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Get Pengeluaran MS IS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/PengeluaranMSISPerGrup') }}"><i class="fa fa-circle-o"></i>Pengeluaran MS IS Per Grup</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Mutasi TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/MutasiTBS') }}"><i class="fa fa-circle-o"></i> Mutasi TBS</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanen') }}"><i class="fa fa-circle-o"></i> Restan Panen Di Angkut</a></li>  --}}
                </ul>
                {{-- <a href="{{ url('/dashboard/lhpexecutive/lhpexecutiveDirector') }}"><i class="fa fa-chart-pie"></i> <span>LHP Executive Director</span></a> --}}
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tree"></i>
                    <span>Produksi TBS</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Produksi TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpProduksiTBS') }}"><i class="fa fa-circle-o"></i>Produksi TBS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpProduksiAngkutTBS') }}"><i class="fa fa-circle-o"></i> Produksi & Pengangkutan TBS</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi per 2 Jam') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpProduksiTBS2Jam') }}"><i class="fa fa-circle-o"></i> Produksi TBS Per 2 Jam</a></li>
                    <!-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Pencapaian Produksi') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpPencapaianProduksiTBS') }}"><i class="fa fa-circle-o"></i> Pencapaian Produksi TBS KS</a></li> -->
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Pencapaian Produksi Main') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpPencapaianProduksiMain') }}"><i class="fa fa-circle-o"></i> Pencapaian Produksi TBS</a></li>
                    <!-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Inventory TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/inventoryTBS') }}"><i class="fa fa-circle-o"></i> Inventory TBS [Developing]</a></li> -->
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Produksi TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpProduksiTBS') }}"><i class="fa fa-circle-o"></i> Produksi TBS</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen Blm Angkut') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenBlmAngkut') }}"><i class="fa fa-circle-o"></i> Restan Panen</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Restan Panen BJR') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenBJR') }}"><i class="fa fa-circle-o"></i> Restan Panen BJR</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Restan Panen Bulanan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpRestanPanenBulanan') }}"><i class="fa fa-circle-o"></i> Restan Panen Blnan [Developing]</a></li>   --}}
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Persediaan Produk Sampingan Bulanan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpByProductBulanan') }}"><i class="fa fa-circle-o"></i>Produk Sampingan Semua PMKS</a></li>  --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Curah Hujan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpCurahHujan') }}"><i class="fa fa-circle-o"></i> Curah Hujan Eplant</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Curah Hujan V2') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/lhpexecutive/lhpCurahHujanV2') }}"><i class="fa fa-circle-o"></i> Curah Hujan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Analisa Pupuk Per Pokok') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/AnalisaPupukPerPokok') }}"><i class="fa fa-circle-o"></i>Biaya Pemupukan Per Pokok</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'Penerimaan TBS Per Jam') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/PenerimaanTBSPerJam') }}"><i class="fa fa-circle-o"></i>Penerimaan TBS Per Jam</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Brondolan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpBrondolan') }}"><i class="fa fa-circle-o"></i> Brondolan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'LHP Brondolan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/produksi/lhpBrondolanBulanan') }}"><i class="fa fa-circle-o"></i> Brondolan Bulanan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('LHP', 'RKAP VS REAL TBS HARIAN') ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/produksi/rkap-vs-real-tbs-harian') }}">
                            <i class="fa fa-circle-o"></i> RKAP VS REAL TBS HARIAN
                        </a>
                    </li>
                </ul>
                {{-- <a href="{{ url('/dashboard/lhpexecutive/lhpexecutiveDirector') }}"><i class="fa fa-chart-pie"></i> <span>LHP Executive Director</span></a> --}}
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-leaf"></i>
                    <span>BioFertilizer</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('BioFertilizer', 'Status Batch') ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/biofertilizer/Statusbatch') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>Status Batch</span>
                        </a>
                    </li>

                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('BioFertilizer', 'Status Batch EPLANT') ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/biofertilizer/StatusbatchEplant') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>Status Batch EPLANT</span>
                        </a>
                    </li> --}}

                    <li class="{{ Auth::user()->canAccessByHakAkses(
                        'BioFertilizer',
                        'Analisa Mutasi Pupuk Compost PerBulan'
                    ) ? '' : 'disabled' }}">
                        <a href="{{ url('/dashboard/biofertilizer/AnalisaMutasiPupukPerBulan') }}"
                           class="submenu-with-status">

                            <i class="fa fa-exclamation-circle menu-unconfirmed"
                               title="Belum dikonfirmasi"></i>

                            <span class="submenu-text">
                                Analisa Mutasi Pupuk (Per Bulan)
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>Penjualan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Kontrak Penjualan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/kontrak-penjualan') }}"><i class="fa fa-circle-o"></i> Kontrak Penjualan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Tender') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/harga-tender') }}"><i class="fa fa-circle-o"></i>Harga Tender</a></li> --}}
                    <!-- <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Kontrak vs Harga Tender') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/harga-kontrak-vs-harga-tender') }}"><i class="fa fa-circle-o"></i> Harga Kontrak vs Harga Tender</a></li> -->
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Pengiriman DO') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/pengiriman-DO') }}"><i class="fa fa-circle-o"></i> Pengiriman DO DEVELOPING</a></li> --}}
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Kontrak Penjualan V2') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/kontrak_jualV2') }}"><i class="fa fa-circle-o"></i> Kontrak Penjualan DEVELOPING</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Outstanding') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/outstanding') }}"><i class="fa fa-circle-o"></i> OutStanding Per Kontrak</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Outstanding Per Customer') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/outstandingpercust') }}"><i class="fa fa-circle-o"></i> OutStanding Per Customer</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Pengiriman') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/pengiriman') }}"><i class="fa fa-circle-o"></i> Shipment</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Analisis Stok') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/analisisstok') }}"><i class="fa fa-circle-o"></i> Analisis Stok</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Rekap Shipment') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/RekapShipment') }}"><i class="fa fa-circle-o"></i> Rekap Shipment</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Rekap Shipment Tahunan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/RekapShipmentTahunan') }}"><i class="fa fa-circle-o"></i> Rekap Shipment Tahunan</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Tender Final LTC') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/harga-tender-final-ltc') }}"><i class="fa fa-circle-o"></i>Harga Final TENDER LTC</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Rekomendasi Harga Tender Harian PDP') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/rekomendasi-harga-tender-harian-pdp') }}"><i class="fa fa-circle-o"></i>Rekomendasi Harga Tender Harian</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Penjualan', 'Harga Rata Rata Tender') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/penjualan/harga-rata-rata-tender') }}"><i class="fa fa-circle-o"></i>Harga Rata Rata Tender</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Pembelian</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/harga-idealNew') }}"><i class="fa fa-circle-o"></i> Harga Ideal</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Ideal TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/hargaIdealTBS') }}"><i class="fa fa-circle-o"></i> Harga Beli TBS P-3</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Rata2 Beli TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/rata2HargaBeliTBS') }}"><i class="fa fa-circle-o"></i> Harga Rata2 Beli TBS</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Analisa Pupuk') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/AnalisaPupuk') }}"><i class="fa fa-circle-o"></i>Mutasi Pupuk</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Analisa Pupuk') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/AnalisaMutasiPupuk') }}"><i class="fa fa-circle-o"></i>Analisa Mutasi Pupuk</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian TBS P3') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/PembelianTBSP3') }}"><i class="fa fa-circle-o"></i>Pembelian TBS P3</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Harga Beli TBS') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/hargaBeliTBS') }}"><i class="fa fa-circle-o"></i> Harga Beli TBS</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/RekapitulasiPembelianSolar') }}"><i class="fa fa-circle-o"></i>Rekap Penerimaan Solar [GRN]</a></li> 
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/RekapitulasiPembelianBeras') }}"><i class="fa fa-circle-o"></i>Rekap Penerimaan Beras [GRN]</a></li> 
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/RekapitulasiPembelianSolarPO') }}"><i class="fa fa-circle-o"></i>Rekap Pembelian Solar [PO]</a></li> 
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Pembelian Rekapitulasi Pembelian Solar') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/RekapitulasiPembelianBerasPO') }}"><i class="fa fa-circle-o"></i>Rekap Pembelian Beras [PO]</a></li> 
                    <li class="{{ Auth::user()->canAccessByHakAkses('Pembelian', 'Rekap Pembelian TBS P3') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/pembelian/RekapPembelianTBSP3') }}"><i class="fa fa-circle-o"></i>Rekap Pembelian TBS P3</a></li>
                    <li class="{{ Auth::user()->canAccessByHakAkses('Inventory', 'Dead Stock') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/inventory/Dead-Stock') }}"><i class="fa fa-circle-o"></i>Dead Stock</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Personalia</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Auth::user()->canAccessByHakAkses('Personalia', 'Dinas') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/personalia/dinas') }}"><i class="fa fa-circle-o"></i> Dinas</a></li>
                    {{-- <li class="{{ Auth::user()->canAccessByHakAkses('Personalia', 'Manager Kebun') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/personalia/daftar_manager') }}"><i class="fa fa-circle-o"></i> Daftar Manager</a></li> --}}
                    <li class="{{ Auth::user()->canAccessByHakAkses('Personalia', 'Daftar Karyawan') ? '' : 'disabled' }}"><a href="{{ url('/dashboard/personalia/daftarkaryawan') }}"><i class="fa fa-circle-o"></i> Daftar Karyawan</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bug"></i>
                    <span>Hama &amp; Penyakit Tumbuhan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/dashboard/hpt/Rekap-HPT') }}">
                        {{-- <a href="{{ route('hpt.rekap') }}"> --}}
                            <i class="fa fa-exclamation-circle menu-unconfirmed" title="Belum dikonfirmasi"></i>Rekap HPT
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/dashboard/hpt/Detail-HPT') }}">
                            {{-- <i class="fa fa-circle-o"></i>
                            Detail HPT --}}
                            <i class="fa fa-exclamation-circle menu-unconfirmed" title="Belum dikonfirmasi"></i>Detail HPT
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-question"></i>
                    <span>Bantuan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/dashboard/bantuan/kamus') }}"><i class="fa fa-circle-o"></i> Kamus Istilah</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>

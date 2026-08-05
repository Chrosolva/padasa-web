<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgronomiController extends Controller
{
    public function getProduksiTBS(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Agronomi', 'Produksi TBS') == false) {
            abort('403-dashboard');
        }
        $tahun = (int) $request->get('tahun', date('Y'));
        $bulan = (int) $request->get('bulan', date('n'));

        /*
         * site_id dikirim dalam bentuk array:
         * site_id[]=2200
         * site_id[]=2300
         *
         * Jika tidak ada site yang dipilih, parameter dikirim sebagai SQL NULL
         * sehingga stored procedure mengembalikan semua data.
         */
        $selectedSites = $request->get('site_id', []);

        if (!is_array($selectedSites)) {
            $selectedSites = explode(',', $selectedSites);
        }

        $selectedSites = array_values(array_filter(
            array_map('trim', $selectedSites),
            function ($siteId) {
                return $siteId !== '' &&
                    strtoupper($siteId) !== 'NULL' &&
                    is_numeric($siteId);
            }
        ));

        $siteParameter = count($selectedSites) > 0
            ? implode(',', $selectedSites)
            : null;

        $data = DB::select(
            'SET NOCOUNT ON; EXEC PUBDB.Produksi.LaporanProduksiTBS_Bulanan_Budget_YTD_DASHBOARD
                @tahun = ?,
                @bulan = ?,
                @site_id = ?',
            [
                $tahun,
                $bulan,
                $siteParameter
            ]
        );

        $data = collect($data)->map(function ($row) {
            return [
                'INDEX' => (int) ($row->INDEX ?? 0),
                'COMP_ID' => $row->COMP_ID ?? null,
                'SITE_ID' => $row->SITE_ID ?? null,
                'ORGID' => $row->ORGID ?? null,
                'DIVISIONCODE' => $row->DIVISIONCODE ?? '',
                'DIVISIONNAME' => $row->DIVISIONNAME ?? '',
                'KET_KEBUN' => $this->formatKeteranganKebun(
                    $row->DIVISIONNAME ?? '',
                    $row->DIVISIONCODE ?? ''
                ),
                'GRUP' => strtoupper(trim($row->GRUP ?? '')),

                'PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU' =>
                    (float) ($row->PRODUKSI_TBS_SELECTED_BULAN_TAHUNLALU ?? 0),

                'VARIAN_TAHUNLALU' =>
                    (float) ($row->VARIAN_TAHUNLALU ?? 0),

                'PRODUKSI_TBS_AKTUAL_BULAN_INI' =>
                    (float) ($row->PRODUKSI_TBS_AKTUAL_BULAN_INI ?? 0),

                'MONTHLYBUDGET' =>
                    (float) ($row->MONTHLYBUDGET ?? 0),

                'VARIAN_TAHUN_INI' =>
                    (float) ($row->VARIAN_TAHUN_INI ?? 0),

                'PRODUKSI_TBS_AKTUAL_YTD' =>
                    (float) ($row->PRODUKSI_TBS_AKTUAL_YTD ?? 0),

                'BUDGETYTD' =>
                    (float) ($row->BUDGETYTD ?? 0),

                'VARIAN_YTD' =>
                    (float) ($row->VARIAN_YTD ?? 0),

                'ANUALBUDGET' =>
                    (float) ($row->ANUALBUDGET ?? 0),

                'VARIAN_TOTAL' =>
                    (float) ($row->VARIAN_TOTAL ?? 0),
            ];
        })->sortBy('INDEX')->values();

        /*
         * Daftar site dapat dipindahkan ke tabel master apabila nantinya
         * sudah tersedia tabel master kebun.
         */
        $siteOptions = [
            '2200' => 'TELDA / TD',
            '2300' => 'KALSA / K1',
            '2400' => 'KALDA / K2',
            '2500' => 'KOKAR / KK',
            '2600' => 'MITRA KOKAR',
            '3200' => 'RICKO / RK',
            '4200' => 'MUARA / MR',
            '5200' => 'PASER / PS',
            '6200' => 'LANGGAI / LG',
        ];

        $namaBulan = [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];

        return view('dashboard.agronomi.ProduksiTBS', [
            'dataProduksi' => $data,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $namaBulan,
            'siteOptions' => $siteOptions,
            'selectedSites' => $selectedSites,
        ]);
    }

    /**
     * Mengubah DIVISIONNAME menjadi format singkat.
     *
     * Contoh:
     * PEU - TELDA Afdeling 01             => TD AFD 01
     * PEU - MITRA KALIANTA RAYON A        => MTR K2 RYN A
     * PEU - Mitra Kokar Rayon A           => MTR KK RYN A
     * APMR - RICKO MITRA RAYON A          => MTR RK RYN A
     */
    private function formatKeteranganKebun($divisionName, $divisionCode = '')
    {
        $name = strtoupper(trim($divisionName));

        /*
         * Hilangkan nama company di depan tanda "-".
         *
         * PEU - TELDA AFDELING 01
         * menjadi:
         * TELDA AFDELING 01
         */
        if (strpos($name, '-') !== false) {
            $parts = explode('-', $name, 2);
            $name = trim($parts[1]);
        }

        $kodeKebun = '';

        $mappingKebun = [
            'TELDA' => 'TD',
            'KALSA' => 'K1',
            'KALDA' => 'K2',
            'KALIANTA' => 'K2',
            'KOKAR' => 'KK',
            'RICKO' => 'RK',
            'MUARA' => 'MR',
            'PASER' => 'PS',
            'LANGGAI' => 'LG',
        ];

        foreach ($mappingKebun as $namaPanjang => $namaSingkat) {
            if (preg_match('/\b' . preg_quote($namaPanjang, '/') . '\b/i', $name)) {
                $kodeKebun = $namaSingkat;
                break;
            }
        }

        /*
         * Format Afdeling:
         * TELDA AFDELING 01 => TD AFD 01
         */
        if (preg_match('/\bAFDELING\s*([0-9A-Z]+)/i', $name, $matches)) {
            $nomorAfdeling = strtoupper($matches[1]);

            return trim($kodeKebun . ' AFD ' . $nomorAfdeling);
        }

        /*
         * Format Mitra Rayon:
         * MITRA KALIANTA RAYON A => MTR K2 RYN A
         * RICKO MITRA RAYON A    => MTR RK RYN A
         */
        if (
            preg_match('/\bMITRA\b/i', $name) ||
            preg_match('/\bMTR\b/i', $name)
        ) {
            $rayon = '';

            if (preg_match('/\bRAYON\s*([0-9A-Z]+)/i', $name, $matches)) {
                $rayon = strtoupper($matches[1]);
            }

            $result = 'MTR';

            if ($kodeKebun !== '') {
                $result .= ' ' . $kodeKebun;
            }

            if ($rayon !== '') {
                $result .= ' RYN ' . $rayon;
            }

            return trim($result);
        }

        /*
         * Fallback apabila pola DIVISIONNAME berbeda dari pola normal.
         */
        $name = preg_replace('/\bAFDELING\b/i', 'AFD', $name);
        $name = preg_replace('/\bMITRA\b/i', 'MTR', $name);
        $name = preg_replace('/\bRAYON\b/i', 'RYN', $name);

        foreach ($mappingKebun as $namaPanjang => $namaSingkat) {
            $name = preg_replace(
                '/\b' . preg_quote($namaPanjang, '/') . '\b/i',
                $namaSingkat,
                $name
            );
        }

        $name = preg_replace('/\s+/', ' ', $name);

        if ($name !== '') {
            return trim($name);
        }

        return strtoupper(trim($divisionCode));
    }
}
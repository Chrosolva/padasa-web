<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArealStatementController extends Controller
{
    public function getBreakdownLuasanWilayahPT(Request $request)
    {
        if (Auth::user()->canAccessByHakAkses('Areal Statement', 'BreakDown Luasan Per Wilayah dan PT') == false) {
            abort('403-dashboard');
        }

        $lastMonth = strtotime('first day of last month');

        $tahun = isset($_GET['tahun'])
            ? $_GET['tahun']
            : date('Y', $lastMonth);

        $bulan = isset($_GET['bulan'])
            ? $_GET['bulan']
            : date('n', $lastMonth);

        $comp_id = null;
        $site_id = null;

        $wilayah = DB::select(
            "SET NOCOUNT ON;
             EXEC PUBDB.Tanaman.ArealStatement_1_PDP
                @comp_id = ?,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?",
            [$comp_id, $site_id, $tahun, $bulan]
        );

        $pt = DB::select(
            "SET NOCOUNT ON;
             EXEC PUBDB.Tanaman.ArealStatement_2_PT
                @comp_id = ?,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?",
            [$comp_id, $site_id, $tahun, $bulan]
        );

        $dataUmur = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_8_KELOMPOK_UMUR_TANAMAN_V2
                @kategori = ?,
                @comp_id = null,
                @site_id = null,
                @tahun = ?,
                @bulan = ?
        ", [
            'HA',
            $tahun,
            $bulan
        ]);

        $wilayah = $this->addTotalHaAndSubtotal($wilayah, 'REGION');
        $pt = $this->addTotalHaAndSubtotal($pt, 'NAMA');

        $noUrutUmur = 1;

        foreach ($dataUmur as $row) {
            $row->NOURUT = $noUrutUmur++;

            $row->TBM = (float) ($row->TBM ?? 0);
            $row->MUDA = (float) ($row->MUDA ?? 0);
            $row->REMAJA = (float) ($row->REMAJA ?? 0);
            $row->DEWASA = (float) ($row->DEWASA ?? 0);
            $row->TUA = (float) ($row->TUA ?? 0);
            $row->REPLANTING = (float) ($row->REPLANTING ?? 0);

            $row->TOTAL_HA =
                $row->TBM +
                $row->MUDA +
                $row->REMAJA +
                $row->DEWASA +
                $row->TUA +
                $row->REPLANTING;
        }

        return view('dashboard.arealstatement.BreakdownLuasanWilayahPT')->with([
            'wilayah' => $wilayah,
            'pt' => $pt,
            'dataUmur' => $dataUmur,
            'tahun' => $tahun,
            'bulan' => $bulan
        ]);
    }

    private function addTotalHaAndSubtotal($rows, $nameColumn)
    {
        $result = [];

        $sum = [
            'HA_TM' => 0,
            'PPK_TM' => 0,
            'HA_TBM' => 0,
            'PKK_TBM' => 0,
            'HA_TB' => 0,
            'PKK_TB' => 0,
            'HA_LAIN' => 0,
            'TOTAL_HA' => 0,
            'TOTAL_PKK' => 0       // Tambahan
        ];

        foreach ($rows as $row) {

            $row->TOTAL_HA =
                (float)($row->HA_TM ?? 0) +
                (float)($row->HA_TBM ?? 0) +
                (float)($row->HA_TB ?? 0) +
                (float)($row->HA_LAIN ?? 0);

            $row->TOTAL_PKK =
                (float)($row->PPK_TM ?? 0) +
                (float)($row->PKK_TBM ?? 0) +
                (float)($row->PKK_TB ?? 0);

            $sum['HA_TM'] += (float)($row->HA_TM ?? 0);
            $sum['PPK_TM'] += (float)($row->PPK_TM ?? 0);
            $sum['HA_TBM'] += (float)($row->HA_TBM ?? 0);
            $sum['PKK_TBM'] += (float)($row->PKK_TBM ?? 0);
            $sum['HA_TB'] += (float)($row->HA_TB ?? 0);
            $sum['PKK_TB'] += (float)($row->PKK_TB ?? 0);
            $sum['HA_LAIN'] += (float)($row->HA_LAIN ?? 0);
            $sum['TOTAL_HA'] += (float)($row->TOTAL_HA ?? 0);
            $sum['TOTAL_PKK'] += (float)($row->TOTAL_PKK ?? 0); // Tambahan

            $result[] = $row;
        }

        $subtotal = new \stdClass();
        $subtotal->{$nameColumn} = 'SUB TOTAL';
        $subtotal->NOURUT = 999999;
        $subtotal->NoUrut = 999999;

        $subtotal->HA_TM = $sum['HA_TM'];
        $subtotal->PPK_TM = $sum['PPK_TM'];
        $subtotal->HA_TBM = $sum['HA_TBM'];
        $subtotal->PKK_TBM = $sum['PKK_TBM'];
        $subtotal->HA_TB = $sum['HA_TB'];
        $subtotal->PKK_TB = $sum['PKK_TB'];
        $subtotal->HA_LAIN = $sum['HA_LAIN'];

        $subtotal->TOTAL_HA = $sum['TOTAL_HA'];
        $subtotal->TOTAL_PKK = $sum['TOTAL_PKK']; // Tambahan

        $subtotal->IS_TOTAL = true;

        $result[] = $subtotal;

        return $result;
    }

    public function getLuasanWilayahPerKebun(Request $request)
    {
        if (
            Auth::user()->canAccessByHakAkses(
                'Areal Statement',
                'Luasan Wilayah Per Kebun'
            ) == false
        ) {
            abort('403-dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $tahun = $request->get('tahun', date('Y'));

        $bulan = $request->get(
            'bulan',
            date('m', strtotime('first day of last month'))
        );

        $site_id = $request->get('site_id', '2200');

        /*
        |--------------------------------------------------------------------------
        | Query Per Afdeling
        |--------------------------------------------------------------------------
        */

        $dataAfdeling = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_3_PT_KEBUN_AFDELING
                @comp_id = null,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?
        ", [
            $site_id,
            $tahun,
            $bulan
        ]);

        /*
        |--------------------------------------------------------------------------
        | Query Per Tahun Tanam
        |--------------------------------------------------------------------------
        */

        $dataTahunTanam = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_5_PER_KEBUN_PER_TAHUN_TANAM_V2
                @comp_id = null,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?
        ", [
            $site_id,
            $tahun,
            $bulan
        ]);

        /*
        |--------------------------------------------------------------------------
        | Query Per Bibit
        |--------------------------------------------------------------------------
        */

        $dataBibit = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_6_PER_KEBUN_PER_BIBIT_V2
                @comp_id = null,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?
        ", [
            $site_id,
            $tahun,
            $bulan
        ]);

        /*
        |--------------------------------------------------------------------------
        | Query Per Topografi
        |--------------------------------------------------------------------------
        */

        $dataTopografi = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_7_PER_KEBUN_PER_TOPOGRAFI_V2
                @comp_id = null,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?
        ", [
            $site_id,
            $tahun,
            $bulan
        ]);

        /*
        |--------------------------------------------------------------------------
        | Query Per Umur Tanaman
        |--------------------------------------------------------------------------
        */

        $dataUmur = DB::select("
            SET NOCOUNT ON;

            EXEC PUBDB.Tanaman.ArealStatement_8_KELOMPOK_UMUR_TANAMAN_AFDELING
                @kategori = ?,
                @comp_id = null,
                @site_id = ?,
                @tahun = ?,
                @bulan = ?
        ", [
            'HA',
            $site_id,
            $tahun,
            $bulan
        ]);

        /*
        |--------------------------------------------------------------------------
        | Format Data Per Afdeling
        |--------------------------------------------------------------------------
        |
        | Kolom PKK tidak dipakai pada tampilan.
        |
        */

        $noUrut = 1;

        foreach ($dataAfdeling as $row) {
            $row->NOURUT = $noUrut++;

            $row->HA_TM = (float) ($row->HA_TM ?? 0);
            $row->HA_TBM = (float) ($row->HA_TBM ?? 0);
            $row->HA_TB = (float) ($row->HA_TB ?? 0);
            $row->HA_LAIN = (float) ($row->HA_LAIN ?? 0);

            $row->TOTAL_HA =
                $row->HA_TM +
                $row->HA_TBM +
                $row->HA_TB +
                $row->HA_LAIN;

            /*
            * Hapus field PKK agar tidak ikut dikirim ke Blade.
            */
            unset($row->PKK_TM);
            unset($row->PKK_TBM);
            unset($row->PKK_TB);
        }

        /*
        |--------------------------------------------------------------------------
        | Format Data Per Umur
        |--------------------------------------------------------------------------
        */

        $noUrutUmur = 1;

        foreach ($dataUmur as $row) {
            $row->NOURUT = $noUrutUmur++;

            $row->TBM = (float) ($row->TBM ?? 0);
            $row->MUDA = (float) ($row->MUDA ?? 0);
            $row->REMAJA = (float) ($row->REMAJA ?? 0);
            $row->DEWASA = (float) ($row->DEWASA ?? 0);
            $row->TUA = (float) ($row->TUA ?? 0);
            $row->REPLANTING = (float) ($row->REPLANTING ?? 0);

            $row->TOTAL_HA =
                $row->TBM +
                $row->MUDA +
                $row->REMAJA +
                $row->DEWASA +
                $row->TUA +
                $row->REPLANTING;
        }

        /*
        |--------------------------------------------------------------------------
        | Fungsi Membersihkan Kolom PKK
        |--------------------------------------------------------------------------
        |
        | Stored procedure Tahun Tanam, Bibit, dan Topografi menghasilkan nama
        | kolom dinamis seperti:
        |
        | HA PEU - TELDA Afdeling 01
        | PKK PEU - TELDA Afdeling 01
        |
        | Hanya kolom HA yang dipertahankan.
        |
        */

        $removePkkColumns = function ($rows) {
            $result = [];

            foreach ($rows as $row) {
                $newRow = [];

                foreach ((array) $row as $columnName => $value) {
                    $columnUpper = strtoupper(trim($columnName));

                    /*
                    * Abaikan semua kolom yang dimulai dengan PKK.
                    */
                    if (strpos($columnUpper, 'PKK ') === 0) {
                        continue;
                    }

                    /*
                    * Konversi kolom HA menjadi angka.
                    */
                    if (strpos($columnUpper, 'HA ') === 0) {
                        $newRow[$columnName] = (float) ($value ?? 0);
                    } else {
                        $newRow[$columnName] = $value;
                    }
                }

                $result[] = $newRow;
            }

            return $result;
        };

        $dataTahunTanam = $removePkkColumns($dataTahunTanam);
        $dataBibit = $removePkkColumns($dataBibit);
        $dataTopografi = $removePkkColumns($dataTopografi);

        /*
        |--------------------------------------------------------------------------
        | Ambil Daftar Kolom Dinamis
        |--------------------------------------------------------------------------
        */

        $getColumns = function ($rows) {
            if (count($rows) === 0) {
                return [];
            }

            return array_keys($rows[0]);
        };

        $columnsTahunTanam = $getColumns($dataTahunTanam);
        $columnsBibit = $getColumns($dataBibit);
        $columnsTopografi = $getColumns($dataTopografi);

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('dashboard.arealstatement.LuasanWilayahPerKebun')->with([
            'dataAfdeling' => $dataAfdeling,
            'dataTahunTanam' => $dataTahunTanam,
            'dataBibit' => $dataBibit,
            'dataTopografi' => $dataTopografi,
            'dataUmur' => $dataUmur,

            'columnsTahunTanam' => $columnsTahunTanam,
            'columnsBibit' => $columnsBibit,
            'columnsTopografi' => $columnsTopografi,

            'tahun' => $tahun,
            'bulan' => $bulan,
            'site_id' => $site_id
        ]);
    }
}
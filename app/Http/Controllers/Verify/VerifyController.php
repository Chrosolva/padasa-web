<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class VerifyController extends Controller
{
    public function check($id)
    {
        $data = DB::select("SET NOCOUNT ON; exec PUBDB.MM.[tblspk_verify] @id = '?'", [$id]);
        if (count($data) > 0) {
            $start_date = Carbon::parse($data[0]->start_date);
            $end_date = Carbon::parse($data[0]->end_date);
            return view('Verify.ok')->with([
                'data' => $data[0],
                'detail' => DB::select("SET NOCOUNT ON; exec PUBDB.MM.[tblspk_d1_teknis_r] @code = '?'", [$data[0]->ca]),
                'ca_date' => Carbon::parse($data[0]->ca_date)->isoFormat('D MMMM YYYY'),
                'start_date' => $start_date->isoFormat('D MMMM YYYY'),
                'end_date' => $end_date->isoFormat('D MMMM YYYY'),
                'diff' => $end_date->diffInDays($start_date),
            ]);
        }
        return view('Verify.no');
    }

    public static function toRomawi($integer)
    {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        // Create a lookup array that contains all of the Roman numerals.
        $lookup = array(
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1);

        foreach ($lookup as $roman => $value) {
            $matches = intval($integer / $value);
            $result .= str_repeat($roman, $matches);
            $integer = $integer % $value;
        }
        return $result;
    }

    public function checkPb($id)
    {
        $data = DB::select("SET NOCOUNT ON; exec PUBDB.web.[pb_verify] @id = ?", [$id]);
        if (count($data) > 0) {
            $nopb = $data[0]->NoPB;
            $kodesite = $data[0]->KodeSite;
            $tglpb = Carbon::parse($data[0]->TglPB);
            return view('Verify.pbok')->with([
                'data' => $data[0],
                'month' => VerifyController::toRomawi($tglpb->month),
                'rincian' => DB::select("SET NOCOUNT ON; exec PUBDB.web.[pb_rincian] @nopb = ?, @kodesite = ?", [$nopb, $kodesite]),
                'rincian_pembayaran' => DB::select("SET NOCOUNT ON; exec PUBDB.web.[pb_rincian_pembayaran] @nopb = ?, @kodesite = ?", [$nopb, $kodesite]),
            ]);
        }

        return view('Verify.no');
    }
}

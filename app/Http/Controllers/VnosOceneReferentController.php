<?php namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\IzpitniRok;
use App\Models\StudentProgram;
use App\Models\StudijskiProgram;
use App\Models\VrstaVpisa;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Nosilec;
use App\Models\Predmet;
use App\Models\StudentPredmet;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\ExportHelper;


class VnosOceneReferentController extends Controller
{
    public function index()
    {
        //
    }

    public function vnesiOceno($id_studenta)
    {
        $student = Student::find($id_studenta);
        return view('vnosOceneReferent', ['student'=>$student]);
    }

    public function obdelajObrazecOcena(Request $request)
    {
        $student = Student::where('vpisna','=',$request['vpisna'])->first();
        if ($request['predmet'] == 0)
        {
            return Redirect::back()->withErrors(['Izberi predmet.']);
        }
        else
        {
            $predmet = Predmet::find($request['predmet']);
            $ocena = $request['ocena'];
            if (is_numeric($ocena) && $ocena > 0 && $ocena < 11) {
                $sp = StudentPredmet::where('id_studenta', '=', $student->id)->where('id_predmeta', '=', $predmet->id)->orderBy('id', 'desc')->first();
                $sp->ocena = $ocena;
                //Če je vezano na kak datum, zapišem še tja:
                $datum = $request['datum'];
                if ($datum == "brez_prijave")
                {
                    if (date('Y-m-d', strtotime($request['datum_vnosa'])) > date ('Y-m-d'))
                    {
                        return Redirect::back()->withErrors(['Datum vnosa ocene ne sme biti večji od današnjega.']);
                    }
                    else
                    {
                        $sp->datum_vnosa_ocene = date('Y-m-d', strtotime($request['datum_vnosa']));
                    }

                }
                else
                {
                    $sp->datum_vnosa_ocene = date('Y-m-d');
                }
                $sp->save();

                if ($datum != "brez_prijave")
                {

                    $datum = date('Y-m-d',strtotime($datum));
                    $izpitni_rok = IzpitniRok::where('id_predmeta','=',$predmet->id)->where('datum','=',$datum)->first();
                    $st_izp = \DB::table('student_izpit')->where('id_studenta','=',$student->id)->where('id_izpitnega_roka','=',$izpitni_rok->id)->update(array('ocena'=>$ocena));
                    $st_izp = \DB::table('student_izpit')->where('id_studenta','=',$student->id)->where('id_izpitnega_roka','=',$izpitni_rok->id)->update(array('datum_vnosa_ocene'=>date ('Y-m-d')));

                }
            }
            else
            {
                return Redirect::back()->withErrors(['Neveljavna ocena.']);
            }
        }



        return Redirect(action('StudentController@searchForm'));
    }
}
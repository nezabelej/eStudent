<?php namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\MailHelper;

class Student extends Model {

    protected $table = 'student';
    protected $fillable = ['vpisna', 'ime', 'priimek', 'email', 'geslo', 'emso', 'naslov', 'obcina', 'posta', 'drzava', 'naslovZacasni', 'obcinaZacasni', 'postaZacasni', 'drzavaZacasni', 'datum_rojstva', 'posiljanje', 'obcina_rojstva', 'drzava_rojstva', 'davcna', 'drzavljanstvo', 'spol', 'telefon'];
    protected $guarded = ['id', 'ponastavitev_gesla', 'novo_geslo'];

    public $timestamps = false;

    public function studijskiProgrami()
    {
        return $this->belongsToMany('App\Models\StudijskiProgram', 'student_program', 'id_studenta','id_programa');
    }

    public function izpitniRoki()
    {
        return $this->belongsToMany('App\Models\IzpitniRok', 'student_izpit',  'id_studenta', 'id_izpitnega_roka');
    }

    public function Predmeti()
    {
        return $this->belongsToMany('App\Models\Predmet', 'student_predmet', 'id_studenta','id_predmeta')->withPivot('ocena','semester','letnik','studijsko_leto');
    }

    public function predmetiVProgramu($studijsko_leto)
    {
        $predmeti = \DB::table('student_predmet')->where('id_studenta','=',$this->id)->where('studijsko_leto','=',$studijsko_leto)->lists('id_predmeta');
        return $predmeti;
    }
    public function predmetiVLetniku($letnik)
    {
        $predmeti = \DB::table('student_predmet')->where('id_studenta','=',$this->id)->where('letnik','=',$letnik)->lists('id_predmeta');
        return $predmeti;
    }

    public function razpisaniRoki($from='',$to='')
    {
        if(date('m') >= 10){
            $trenutno_leto = date('Y').'/'.date('Y',strtotime('+1 year'));
        }else{
            $trenutno_leto = date('Y',strtotime('-1 year')).'/'.date('Y');
        }
        $predmeti = $this->Predmeti()->wherePivot('studijsko_leto','=',$trenutno_leto)->with('izpitni_roki')->get();
        $izpitni_roki = new Collection();
        foreach($predmeti as $predmet){
            if(!empty($from)){
                $roki = $predmet->izpitni_roki()->with('predmet')->where('datum','>=',$from)->get();
            }else{
                $roki = $predmet->izpitni_roki()->with('predmet')->get();
            }
            foreach($roki as $rok){
                $rok->letnik = $predmet->pivot->letnik;
                $izpitni_roki->add($rok);
            }
        }
        return $izpitni_roki;
        //dd($izpitni_roki);
    }

    public function polaganja()
    {
        return $this->belongsToMany('App\Models\IzpitniRok', 'student_izpit', 'id_studenta', 'id_izpitnega_roka' )->withPivot('ocena', 'tocke_izpita', 'datum_prijave', 'datum_odjave','odjavitelj', 'placilo_izpita');
    }

    public function sklepi()
    {
        return $this->hasMany('App\Models\Sklep', 'id_studenta');
    }

    public function studentProgram()
    {
        return $this->hasMany('App\Models\StudentProgram', 'id_studenta')->orderBy('studijsko_leto','desc');

    }

    public function trenutniProgram()
    {
        if(date('m') >= 10){
            $trenutno_leto = date('Y').'/'.date('Y',strtotime('+1 year'));
        }else{
            $trenutno_leto = date('Y',strtotime('-1 year')).'/'.date('Y');
        }

        return $this->studijskiProgrami()->withPivot('letnik', 'studijsko_leto', 'vrsta_vpisa', 'nacin_studija', 'datum_vpisa','vloga_potrjena')->wherePivot('studijsko_leto','=',$trenutno_leto)->first();
    }

    public function trenutniPredmeti($studijsko_leto='')
    {
        if(empty($studijsko_leto)){
            $studijsko_leto = date('Y',strtotime('-1 year')).'/'.date('y');
        }
        return $this->Predmeti()->withPivot('letnik','semester','studijsko_leto','ocena')->wherePivot('studijsko_leto','=',$studijsko_leto);
    }

    public function passwordReset(){
        $new_password = str_random(15);
        $new_hash = \Hash::make($new_password);
        $this->novo_geslo = $new_hash;
        $this->ponastavitev_gesla = str_random(40);
        $ponastavitveni_link = $this->id.'-'.$this->ponastavitev_gesla;
        $this->save();
        $msg = '<h3> Ponastavitev gesla</h3><br>';
        $msg .= 'Za ponastavitev gesla kliknite <a href="'. action('LoginController@passwordResetPotrditev',['koda'=>$ponastavitveni_link]).'">TUKAJ</a>';
        $msg .= '<br><br>';
        $msg .= 'Po ponastavitivi bo vaše novo geslo: <b>'.$new_password.'</b>';
        $msg .= '<br><br> Lp, ekipa TPO6';
        $mailer = new MailHelper();
        $mailer->send('Referat FRI <noreply@tpo6.si>', $this->email, 'Ponastavitev gesla', $msg);
        return true;
    }

    public function passwordResetPotrditev($potrditev){
        if($this->ponastavitev_gesla == $potrditev){
            $this->geslo = $this->novo_geslo;
            $this->novo_geslo = null;
            $this->ponastavitev_gesla = null;
            $this->save();
            return true;
        }
        else return false;
    }
}


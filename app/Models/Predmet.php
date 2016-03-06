<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Predmet extends Model {

    use SoftDeletes;
    protected $table = 'predmet';
    protected $fillable = ['sifra','naziv','opis','id_nosilca','id_nosilca2', 'id_nosilca3','KT'];
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function studijski_programi()
    {
        return $this->belongsToMany('App\Models\StudijskiProgram', 'program_predmet', 'id_predmeta','id_programa');
    }

    public function predmet_nosilec()
    {
        return $this->hasMany('App\Models\PredmetNosilec', 'id_predmeta', 'id');
    }

    public function nosilec()
    {
        return $this->belongsTo('App\Models\Nosilec','id_nosilca');
    }

    public function nosilec2()
    {
        return $this->belongsTo('App\Models\Nosilec','id_nosilca2');
    }
    public function nosilec3()
    {
        return $this->belongsTo('App\Models\Nosilec','id_nosilca3');
    }

    public function izpitni_roki()
    {
        return $this->hasMany('App\Models\IzpitniRok','id_predmeta');
    }

    public function studenti()
    {
        return $this->belongsToMany('App\Models\Student', 'student_predmet', 'id_studenta', 'id_predmeta');
    }

    public function studentovaPolaganja(Student $student)
    {
        $polaganja = $student->polaganja()->where('id_predmeta','=', $this->id)->get();
        return $polaganja;
    }

    public function predmetNosilec($studijsko_leto)
    {
        $studentPredmet = PredmetNosilec::where('id_predmeta','=',$this->id)->where('studijsko_leto','=',$studijsko_leto)->first();
        return $studentPredmet;
    }
    /*public function nosilec($studijsko_leto)
    {
        $studentPredmet = PredmetNosilec::where('id_predmeta','=',$this->id)->where('studijsko_leto','=',$studijsko_leto)->first();
        return $studentPredmet->nosilec;
    }
    public function nosilec2($studijsko_leto)
    {
        $studentPredmet = PredmetNosilec::where('id_predmeta','=',$this->id)->where('studijsko_leto','=',$studijsko_leto)->first();
        return $studentPredmet->nosilec2;
    }
    public function nosilec3($studijsko_leto)
    {
        $studentPredmet = PredmetNosilec::where('id_predmeta','=',$this->id)->where('studijsko_leto','=',$studijsko_leto)->first();
        return $studentPredmet->nosilec3;
    }*/

}

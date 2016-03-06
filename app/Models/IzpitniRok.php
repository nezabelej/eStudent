<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzpitniRok extends Model {

    protected $table = 'izpitni_rok';
    protected $fillable = ['izpitni_rok', 'datum', 'studijsko_leto', 'ura_izpita', 'predavalnice','id_predmeta', 'id_nosilca', 'letnik'];
    protected $guarded = [];


    public $timestamps = false;

    public function predmet()
    {
        return $this->belongsTo('App\Models\Predmet','id_predmeta');
    }

    public function studenti()
    {
        return $this->belongsToMany('App\Models\Student','student_izpit','id_studenta','id_izpita');
    }
}

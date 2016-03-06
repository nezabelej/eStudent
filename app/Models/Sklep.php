<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sklep extends Model {

    use SoftDeletes;
    protected $table = 'sklep';
    protected $fillable = ['id', 'id_studenta', 'id_organa', 'datum_izdaje','datum_veljavnosti', 'vsebina'];
    protected $dates = ['deleted_at', 'updated_at'];
    public $timestamps = false;

    public function student()
    {
        return $this->hasMany('App\Models\Student', 'id_studenta');
    }

    public function organ()
    {
        return $this->belongsTo('App\Models\Organ', 'id_organa');
    }

}

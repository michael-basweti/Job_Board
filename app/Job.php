<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'expected_income', 'description', 'delivery_date', 'start_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
// @codeCoverageIgnoreStart
    // Relationships
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function applications(){
        return $this->hasMany('App\Application');
    }
    // @codeCoverageIgnoreEnd
}

?>

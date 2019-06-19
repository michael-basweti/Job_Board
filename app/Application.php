<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id', 'description', 'job_id', 'user_name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function job(){
        return $this->belongsTo('App\Job');
    }

}

?>

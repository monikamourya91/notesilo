<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autoresponder_list extends Model
{
    
	protected $table = 'autoresponder_list';

	protected $guarded = [];

	public function details(){
		return $this->hasOne('App\Autoresponder','autoresponder_list_id');
	}
}

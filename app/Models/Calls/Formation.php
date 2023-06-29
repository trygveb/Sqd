<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Formation
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|StartEndFormation[] $start_end_formations
 *
 * @package App\Models
 */
class Formation extends Model
{
	protected $table = 'formation';
	public $timestamps = true;
   protected $connection = 'calls';
   
	protected $fillable = [
		'name'
	];

	public function start_end_formations()
	{
		return $this->hasMany(StartEndFormation::class, 'end_formation_id');
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FragmentType
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Fragment[] $fragments
 *
 * @package App\Models
 */
class FragmentType extends Model
{
	protected $table = 'fragment_type';
	public $timestamps = true;
   protected $connection = 'calls';
   
	protected $fillable = [
		'name'
	];

	public function fragments()
	{
		return $this->hasMany(Fragment::class, 'type_id');
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SdCall
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Definition[] $definitions
 *
 * @package App\Models
 */
class CallGroup extends Model
{
	protected $table = 'call_group';
	public $timestamps = true;
   protected $connection = 'calls';
  
	protected $fillable = [
		'name'
	];

	public function calls()
	{
		return $this->hasMany(SdCall::class, 'call_group_id');
	}


        
        }

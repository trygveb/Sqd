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
class SdCall extends Model
{
	protected $table = 'sd_call';
	public $timestamps = true;
   protected $connection = 'calls';
   
	protected $fillable = [
		'name'
	];

	public function definitions()
	{
		return $this->hasMany(Definition::class, 'call_id');
	}


        
        }

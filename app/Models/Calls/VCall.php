<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VCall
 * 
 * @property int $definition_id
 * @property int $program_id
 * @property string|null $program_name
 * @property int $call_id
 * @property string|null $call_name
 * @property int|null $start_end_formation_id
 * @property int|null $start_formation_id
 * @property int|null $end_formation_id
 * @property string|null $start_formation_name
 * @property string|null $end_formation_name
 *
 * @package App\Models
 */
class VCall extends Model
{
	protected $table = 'v_call';
	public $incrementing = false;
	public $timestamps = false;
   protected $connection = 'calls';
   
	protected $casts = [
		'definition_id' => 'int',
		'program_id' => 'int',
		'call_id' => 'int',
		'start_end_formation_id' => 'int',
		'start_formation_id' => 'int',
		'end_formation_id' => 'int'
	];

	protected $fillable = [
		'definition_id',
		'program_id',
		'program_name',
		'call_id',
		'call_name',
		'start_end_formation_id',
		'start_formation_id',
		'end_formation_id',
		'start_formation_name',
		'end_formation_name'
	];
}

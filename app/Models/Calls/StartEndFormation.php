<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StartEndFormation
 * 
 * @property int $id
 * @property int|null $start_formation_id
 * @property int|null $end_formation_id
 * 
 * @property Formation|null $formation
 * @property Collection|Definition[] $definitions
 *
 * @package App\Models
 */
class StartEndFormation extends Model
{
	protected $table = 'start_end_formation';
	public $timestamps = false;
   protected $connection = 'calls';
   
	protected $casts = [
		'start_formation_id' => 'int',
		'end_formation_id' => 'int'
	];

	protected $fillable = [
		'start_formation_id',
		'end_formation_id'
	];
        public $additional_attributes = ['name'];

	public function endFormation()
	{
		return $this->belongsTo(Formation::class, 'end_formation_id');
	}
	public function startFormation()
	{
		return $this->belongsTo(Formation::class, 'start_formation_id');
	}

	public function definitions()
	{
		return $this->hasMany(Definition::class);
	}
        public function getNameAttribute() {
            $txt= $this->startFormation->name;
            if (! is_null($this->endFormation)) {
               $txt .= ' - ' . $this->endFormation->name;
            }
            return ltrim($txt);
        }

}

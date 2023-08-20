<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DefinitionFragment
 * 
 * @property int $id
 * @property int $definition_id
 * @property int $fragment_id
 * @property int $seq_no
 * 
 * @property Fragment $fragment
 * @property Definition $definition
 *
 * @package App\Models
 */
class DefinitionFragment extends Model
{
	protected $table = 'definition_fragments';
	public $timestamps = false;
   protected $connection = 'calls';
   
	protected $casts = [
		'definition_id' => 'int',
		'fragment_id' => 'int',
		'seq_no' => 'int'
	];

	protected $fillable = [
		'definition_id',
		'fragment_id',
		'seq_no',
      'fragment_separator'
	];

	public function fragment()
	{
		return $this->belongsTo(Fragment::class);
	}

	public function definition()
	{
		return $this->belongsTo(Definition::class);
	}
}

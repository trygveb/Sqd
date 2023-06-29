<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VDefinitionFragment
 * 
 * @property int $definition_fragments_id
 * @property int $definition_id
 * @property int $fragment_id
 * @property int $seq_no
 * @property int|null $type_id
 * @property string|null $text
 *
 * @package App\Models
 */
class VDefinitionFragment extends Model
{
	protected $table = 'v_definition_fragments';
	public $incrementing = false;
	public $timestamps = false;
   protected $connection = 'calls';
   
	protected $casts = [
		'definition_fragments_id' => 'int',
		'definition_id' => 'int',
		'fragment_id' => 'int',
		'seq_no' => 'int',
		'type_id' => 'int'
	];

	protected $fillable = [
		'definition_fragments_id',
		'definition_id',
		'fragment_id',
		'seq_no',
		'type_id',
		'text'
	];
}

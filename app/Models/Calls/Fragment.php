<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fragment
 * 
 * @property int $id
 * @property int $type_id
 * @property string $text
 * 
 * @property FragmentType $fragment_type
 * @property Collection|Definition[] $definitions
 *
 * @package App\Models
 */
class Fragment extends Model {

   protected $table = 'fragment';
   public $timestamps = true;
   protected $connection = 'calls';
   protected $casts = [
       'type_id' => 'int'
   ];
   protected $fillable = [
       'type_id',
       'text'
   ];

   // Set the first character of attribute text to Uppercase, and the rest lowercase
   protected function text(): Attribute {
      return Attribute::make(
                      set: fn(string $value) => ucfirst(strtolower($value)),
      );
   }

   public function fragment_type() {
      return $this->belongsTo(FragmentType::class, 'type_id');
   }

   public function definitions() {
      return $this->belongsToMany(Definition::class, 'definition_fragments')
                      ->withPivot('id', 'seq_no');
   }

}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Program
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Definition[] $definitions
 *
 * @package App\Models
 */
class Program extends Model {

   protected $table = 'program';
   public $timestamps = true;
   protected $connection = 'calls';
   protected $fillable = [
       'name'
   ];

   public function definitions() {
      return $this->hasMany(Definition::class);
   }

   public function definitionsForProgram() {
      return $this->belongsToMany(Definition::class)
                      ->wherePivot('program_id', $this->id);
   }

}

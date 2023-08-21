<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pause
 * 
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property int $time
 * 
 * @package App\Models\Calls
 */
class Pause extends Model {

   protected $table = 'pauses';
   public $timestamps = false;
   protected $connection = 'calls';
   protected $fillable = [
       'name'
   ];

}

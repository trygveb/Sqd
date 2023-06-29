<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Definition
 * 
 * @property int $id
 * @property int $program_id
 * @property int $call_id
 * @property int|null $start_end_formation_id
 * 
 * @property Program $program
 * @property SdCall $sd_call
 * @property StartEndFormation|null $start_end_formation
 * @property Collection|Fragment[] $fragments
 *
 * @package App\Models
 */
class Definition extends Model {

   protected $table = 'definition';
   public $timestamps = true;
   protected $connection = 'calls';
   protected $casts = [
       'program_id' => 'int',
       'call_id' => 'int',
       'start_end_formation_id' => 'int'
   ];
   protected $fillable = [
       'program_id',
       'call_id',
       'start_end_formation_id'
   ];
   public $additional_attributes = ['all_fragment_texts'];

   public function program() {
      return $this->belongsTo(Program::class);
   }

   public function sd_call() {
      return $this->belongsTo(SdCall::class, 'call_id');
   }

   public function start_end_formation() {
      return $this->belongsTo(StartEndFormation::class);
   }

   public function fragments() {
      return $this->belongsToMany(Fragment::class, 'definition_fragments')
                      ->withPivot('id', 'seq_no', 'fragment_id', 'part_no');
   }

   public function getAllFragmentTextsAttribute() {
      $txt = '';
      foreach ($this->fragments as $fragment) {
         $txt .= ' ' . $fragment->pivot->part_no . ' ' . $fragment->text;
      }
      return ltrim($txt);
   }

}

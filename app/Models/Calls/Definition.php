<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Calls;
use App\Classes\Utility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

   public static function newDefinition($callName, $programId) {
      DB::beginTransaction();
      try {
         $sdCall = SdCall::firstOrCreate([
                     'name' => $callName
         ]);
         $definition = new Definition();
         $definition->call_id = $sdCall->id;
         $definition->program_id = $programId;
         $definition->start_formation_id = 1;
         $definition->end_formation_id = 1;
         $definition->save();
         $definitionFragment= new DefinitionFragment();
         $definitionFragment->definition_id=$definition->id;
         $definitionFragment->fragment_id=1;
         $definitionFragment->fragment_type_id= 1;
         $definitionFragment->save();
         
      } catch (\Illuminate\Database\QueryException $ex) {
         DB::rollback();
         Utility::Logg('CallsController', 'Database error=' . $ex->getMessage());
         return 0;
      }
      return $definition->id;;
   }

   public function program() {
      return $this->belongsTo(Program::class);
   }

   public function sd_call() {
      return $this->belongsTo(SdCall::class, 'call_id');
   }

//
//   public function start_end_formation() {
//      return $this->belongsTo(StartEndFormation::class);
//   }
   public function endFormation() {
      return $this->belongsTo(Formation::class, 'end_formation_id');
   }

   public function startFormation() {
      return $this->belongsTo(Formation::class, 'start_formation_id');
   }

   public function fragments() {
      return $this->belongsToMany(Fragment::class, 'definition_fragments')
                      ->withPivot('id', 'seq_no', 'fragment_id', 'fragment_type_id', 'part_no');
   }

   public function getAllFragmentTextsAttribute() {
      $txt = '';
      foreach ($this->fragments as $fragment) {
         $txt .= ' ' . $fragment->pivot->part_no . ' ' . $fragment->text;
      }
      return ltrim($txt);
   }

}

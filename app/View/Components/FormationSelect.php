<?php

namespace App\View\Components;
use App\Classes\SdCallsUtility;
use App\Models\User;
use Illuminate\View\Component;

class FormationSelect extends Component {

   public $mode;
   public $user;
   public $formationId;
   public $formationList;
   public $startEnd;
   public $selectName;


   public function __construct($mode, $formationId, $startEnd) {
      $this->mode = $mode;
      $this->formationId = $formationId;
      $this->startEnd = $startEnd;
      if ($startEnd ==="Start") {
         $this->selectName= 'start_formation_id';
      } else {
         $this->selectName= 'end_formation_id';
      }
      $this->formationList = SdCallsUtility::GetFormationList();
      $this->user = User::find(auth()->id());
   }

   /**
    * Get the view / contents that represent the component.
    *
    * @return \Illuminate\Contracts\View\View|\Closure|string
    */
   public function render() {
      return view('components.formation-select');
   }

}
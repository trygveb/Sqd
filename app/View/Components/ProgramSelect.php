<?php

namespace App\View\Components;
use App\Classes\SdCallsUtility;
use App\Models\User;
use Illuminate\View\Component;

class ProgramSelect extends Component {

   public $mode;
   public $user;
   public $programList;

   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
   public function __construct($mode) {
      $this->mode = $mode;
      $this->programList = SdCallsUtility::GetProgramList();
      $this->user = User::find(auth()->id());
   }

   /**
    * Get the view / contents that represent the component.
    *
    * @return \Illuminate\Contracts\View\View|\Closure|string
    */
   public function render() {
      return view('components.program-select');
   }

}

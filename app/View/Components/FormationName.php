<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Classes\SdCallsUtility;

class FormationName extends Component
{
   public $mode;
   public $formationName;
   public $formationId;
   public $formationList;
   
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($mode, $formationName, $formationId)
    {
        $this->mode= $mode;
        $this->formationName= $formationName;
        $this->formationId= $formationId;
        $this->formationList = SdCallsUtility::GetFormationNames();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.formation-name');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Classes\SdCallsUtility;

class CallName extends Component
{
   public $mode;
   public $callName;
   public $callId;
   public $definitionId;
   public $callList;
   
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($mode, $callName, $callId, $definitionId)
    {
        $this->mode= $mode;
        $this->callName= $callName;
        $this->callId= $callId;
        $this->definitionId= $definitionId;
        $this->callList = SdCallsUtility::GetCallNames();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.call-name');
    }
}

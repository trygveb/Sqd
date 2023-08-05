<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CallName extends Component
{
   public $mode;
   public $callName;
   public $callId;
   public $definitionId;
   
   
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

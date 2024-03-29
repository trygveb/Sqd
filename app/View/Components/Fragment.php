<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fragment extends Component
{
   public $seqNo;
   public $buttonName;
   public $divName;
   public $selectName;
   public $fragmentList;
   public $visible;
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($seqNo, $fragmentList, $visible)
    {
        $this->seqNo= $seqNo;
        $this->fragmentList= $fragmentList;
        $this->buttonName= sprintf('button_id_%d', $seqNo);
        $this->divName= sprintf('div_id_%d', $seqNo);
        $this->selectName= sprintf('fragment_id_%d', $seqNo);
        $this->visible= $visible;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.fragment');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fragment extends Component
{
   public $seqNo;
   public $htmlName;
   public $fragmentList;
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($seqNo, $fragmentList)
    {
        $this->seqNo= $seqNo;
        $this->fragmentList= $fragmentList;
        $this->htmlName= sprintf('fragment_id_%d', $seqNo);
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

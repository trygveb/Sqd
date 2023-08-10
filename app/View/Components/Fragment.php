<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fragment extends Component
{
   public $mode;
   public $count;
   public $seqNo;
   public $plusButtonName;
   public $minusButtonName;
   public $checkbox1Name;
   public $divName;
   public $selectName;
   public $fragmentList;
   public $fragmentId;
   public $visible;
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($mode, $seqNo, $count, $fragmentList, $visible, $fragmentId=0)
    {
        $this->mode= $mode;
        $this->seqNo= $seqNo;
        $this->count= $count;
        $this->visible= $visible;
        $this->fragmentList= $fragmentList;
        $this->fragmentId= $fragmentId;
        $this->plusButtonName= sprintf('plus_button_id_%d', $seqNo);
        $this->minusButtonName= sprintf('minus_button_id_%d', $seqNo);
        $this->checkbox1Name= sprintf('checkbox1_id_%d', $seqNo);
        $this->divName= sprintf('div_id_%d', $seqNo);
        $this->selectName= sprintf('fragment_id_%d', $seqNo);
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

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fragment extends Component
{
   public $mode;
   public $count;
   public $seqNo;
   
   // Html element names
   public $plusButtonName;
   public $minusButtonName;
   public $checkbox1Name;
   public $divName;
   public $selectName;
   public $selectPauseName;
   
   public $pauseId;
   public $fragmentList;
   public $pausesList;
   public $fragmentId;
   public $definitionId;  // Needed for cancel button
   public $fragmentTypeId;
   public $visible;
   
    public static function getSelectPauseName($seqNo) {
       return sprintf('separator_id_%d', $seqNo);
    }
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($mode, $seqNo, $count, $fragmentList, $pausesList, $visible, $fragmentTypeId, $definitionId=0, $fragmentId=0, $pauseId=0)
    {
        $this->mode= $mode;
        $this->seqNo= $seqNo;
        $this->count= $count;
        $this->visible= $visible;
        $this->fragmentList= $fragmentList;
        $this->fragmentId= $fragmentId;
        $this->definitionId= $definitionId;
        $this->fragmentTypeId= $fragmentTypeId;
        $this->plusButtonName= sprintf('plus_button_id_%d', $seqNo);
        $this->minusButtonName= sprintf('minus_button_id_%d', $seqNo);
        $this->checkbox1Name= sprintf('checkbox1_id_%d', $seqNo);
        $this->divName= sprintf('div_id_%d', $seqNo);
        $this->selectName= sprintf('fragment_id_%d', $seqNo);
        $this->selectPauseName= self::getSelectPauseName($seqNo);
        $this->pausesList= $pausesList;
        $this->pauseId= $pauseId;
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

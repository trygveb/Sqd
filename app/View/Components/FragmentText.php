<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Classes\SdCallsUtility;

class FragmentText extends Component
{
   public $mode;
   public $fragmentText;
   public $fragmentId;
   public $fragmentList;
   
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($mode, $fragmentText, $fragmentId)
    {
        $this->mode= $mode;
        $this->fragmentText= $fragmentText;
        $this->fragmentId= $fragmentId;
        $this->fragmentList = SdCallsUtility::GetFragmentList();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.fragment-text');
    }
}

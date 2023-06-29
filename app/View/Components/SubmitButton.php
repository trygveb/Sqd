<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SubmitButton extends Component
{
   public $submitText;
   public $cancelText;
   public $cancelUrl;
   public $onclickFunction;
   public $myId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($submitText, $cancelText, $cancelUrl, $myId="", $onclickFunction="")
    {
         $this->submitText = $submitText;
         $this->cancelText= $cancelText;
         $this->cancelUrl= $cancelUrl;
         $this->myId= $myId;
         $this->onclickFunction= $onclickFunction;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.submit-button');
    }
}
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
   public $routeRoot;
   
   /**
    * Create a new component instance
    * @param type $subApp         (sqd.se, sdCalls, schedule)
    */
    public function __construct($routeRoot)
    {
        $this->routeRoot= $routeRoot;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footer');
    }
}

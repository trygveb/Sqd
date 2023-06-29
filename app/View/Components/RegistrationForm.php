<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RegistrationForm extends Component
{
   public $isAdmin;
   public $names;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($names, $isAdmin)
    {
        $this->names= $names;
        $this->isAdmin= $isAdmin;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.registration-form');
    }
}

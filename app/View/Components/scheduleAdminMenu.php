<?php

namespace App\View\Components;
use App\Models\User;

use Illuminate\View\Component;

class scheduleAdminMenu extends Component
{
      public $scheduleId;
      public $manageMembers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($scheduleId, $manageMembers)
    {
        $this->scheduleId= $scheduleId;
        $this->manageMembers= $manageMembers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.schedule-admin-menu');
    }
}

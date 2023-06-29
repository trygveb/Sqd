   <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   {{__('Schedule administration')}}
   </a>
   <div class="dropdown-menu" aria-labelledby="navbarDropdown">
   <a class="dropdown-item" href="{{route('schedule.showComments',['scheduleId' => $scheduleId])}}">{{__('Manage comments')}}</a>
   <a class="dropdown-item" href="{{route('schedule.showAddRemoveDates',['scheduleId' => $scheduleId])}}">{{__('Manage dates')}}</a>
   @if ($manageMembers)
   <a class="dropdown-item" href="{{route('schedule.showMembers',['scheduleId' => $scheduleId])}}">{{__('Manage members')}}</a>
   @endif
   </div>

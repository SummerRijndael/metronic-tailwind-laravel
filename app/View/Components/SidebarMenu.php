<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarMenu extends Component
{
    public $items;

   public function __construct($items = null)
    {
         $this->items = $items ?? config('sidebar', []); // default to empty array
    }

    public function render()
    {
        return view('components.sidebar-menu');
    }
}

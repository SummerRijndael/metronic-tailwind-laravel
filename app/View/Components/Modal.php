<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($id = 'modal', $title = 'Modal Title')
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.modal');
    }
}

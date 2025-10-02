<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $title;
    public $autoShow; // Add this line
    /**
     * Create a new component instance.
     */
    public function __construct($id = 'modal', $title = 'Modal Title', $autoShow = false) // Add $autoShow parameter
    {
        $this->id = $id;
        $this->title = $title;
        $this->autoShow = $autoShow; // Assign the value
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.modal');
    }
}

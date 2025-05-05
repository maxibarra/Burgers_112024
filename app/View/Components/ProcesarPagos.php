<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProcesarPagos extends Component
{
    public $total;

    public function __construct($total = null)
    {
        $this->total = $total;
    }

    public function render()
    {
        return view('components.procesar-pagos');
    }
}

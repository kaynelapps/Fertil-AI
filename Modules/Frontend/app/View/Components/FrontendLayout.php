<?php

namespace Modules\Frontend\View\Components;

use Illuminate\View\Component;
use App\Models\AppSetting;
use App\Models\CalculatorTool;

class FrontendLayout extends Component
{
    public $assets;
    public $app_settings;
    public $calculator;

    public function __construct($assets = [])
    {
        $this->assets = $assets;
        $this->app_settings = AppSetting::first(); 
        $this->calculator = CalculatorTool::where('status','1')->orderBy('id', 'desc')->get();
    }
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('frontend::frontend.components.frontend-layout');
    }
}

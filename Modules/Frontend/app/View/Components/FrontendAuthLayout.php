<?php

namespace Modules\Frontend\View\Components;

use Illuminate\View\Component;
use App\Models\AppSetting;

class FrontendAuthLayout extends Component
{
    public $assets;
    public $app_settings;

    public function __construct($assets = [])
    {
        $this->assets = $assets;
        $this->app_settings = AppSetting::first(); 
    }
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('frontend::layouts.auth');
    }
}

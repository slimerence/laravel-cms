<?php

namespace App\Models\Widget;

use App\Models\Contract\IWidget;

class Sidebar extends BaseWidget implements IWidget
{
    public static $LEFT  = 1;   // Locate at left side
    public static $RIGHT = 2;   // Locate at right side

    public function outputHtml()
    {
        // TODO: Implement outputHtml() method.
    }
}

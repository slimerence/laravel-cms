<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/3/18
 * Time: 5:31 PM
 */

namespace App\Models\Contract;

interface ISupportWidget
{
    public function locateWidgets();

    public function rebuildContent();
}
<?php
namespace ikhlas\seller;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'ikhlas\seller\controllers';

    public function init()
    {
        $this->layout = 'left-menu.php';
        parent::init();

        // custom initialization code goes here
    }
}

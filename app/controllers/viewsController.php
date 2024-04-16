<?php

namespace app\controllers;

use app\models\viewsModel;

class viewsController extends viewsModel
{

    public function getViewsController($view)
    {
        if ($view != "") {
            $response = $this->getViewsModels($view);
        } else {
            $response = "login";
        }
        return $response;
    }
}

<?php

namespace app\models;

class viewsModel
{

    protected function getViewsModels($view)
    {
        $listWhite = ["dashboard","userNew","userList","userSearch",
        "userUpdate","logOut","teacherNew","teacherList","teacherSearch",
        "studentNew","studentList","studentSearch","curseNew","curseList"];

        if (in_array($view, $listWhite)) {
            if (is_file("./app/views/content/" . $view . "-view.php")) {
                $content = "./app/views/content/" . $view . "-view.php";
            } else {
                $content = "404";
            }
        } elseif ($view == "login" || $view == "index") {
            $content = "login";
        } else {
            $content = "404";
        }
        return $content;
    }
}

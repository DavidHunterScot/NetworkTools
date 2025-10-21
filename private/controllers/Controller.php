<?php

class Controller
{
    protected function view( $name, $params = array() )
    {
        $views_dir_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
        $view_file_path = $views_dir_path . DIRECTORY_SEPARATOR . $name . '.view.php';

        if( ! is_file( $view_file_path ) )
            return;

        include $view_file_path;
    }

    protected function model( $name )
    {
        $models_dir_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models';
        $model_file_path = $models_dir_path . DIRECTORY_SEPARATOR . $name . '.php';

        if( ! is_file( $model_file_path ) )
            return;

        include $model_file_path;

        if( ! class_exists( $name ) )
            return;

        $model = new $name;

        return $model;
    }
}
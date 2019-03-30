<?php
    namespace App\Base;

    use App\Core;

    class View extends Core
    {
        public static function render($template, $args=[])
        {
            static $twig = null;

            if($twig == null)
            {
                $loader = new \Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/src/Views');
                $twig = new \Twig_Environment($loader);
            }
            
            echo $twig->render($template, $args);
        }
    }

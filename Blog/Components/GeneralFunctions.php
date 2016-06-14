<?php namespace App\Modules\Blog\Components;
/**
 * This class is used for common. all comman methods are written in this.
 * By: Dhara
 * Date: 2-6-2016
 */

class GeneralFunctions
{

    /**
     * Description: Return dynamic catefory for bind menu
     * By: Dhara
     * Date: 2-6-2016
     * @return type
     */
    public static function getCategoryMenu()
    {
        $cat = \App\Modules\Blog\Models\Category::where('status', 'y')->paginate(15);

        return $cat;
    }

    public static function curPageURL()
    {
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
    * Description: Get http protocol from url
    * By: Dhara
    * Date: -6-2016
    * @return string
    */
   public static function getHtttp()
   {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        {
            $protocol = 'https://';
        }
        else {
            $protocol = 'http://';
        }

        return $protocol;
   }
}

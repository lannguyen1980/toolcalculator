<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals
{
    private static $lstFoods = null;
    private static $initialized = false;

    private static function initialize()
    {
        if (self::$initialized)
            return;

        self::$lstFoods = null;
        self::$initialized = true;
    }

    public static function setListFoods($lst)
    {
        self::initialize();
        self::$lstFoods = $lst;
    }


    public static function getListFoods()
    {
        self::initialize();
        return self::$lstFoods;
    }
}
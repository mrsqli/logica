<?php

/**
 * kernel class, 
 * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Kernel {

    /**
     * Static function for suggestions by Module :
     * Module can be : member, project..etc 
     * 
     * Start point
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @parm $model
     * @return $array 
     */
    static $_model_prefix = App_Tag_Suggest_;

    public static function run($model) {
        $folder = self::$_model_prefix . $model;
        return $folder::suggest($model);
    }

}


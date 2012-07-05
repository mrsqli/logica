<?php

/**
 * App hashtag, this class is for system hash tag
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Manage_Tags {

    protected $_groupProfil = 1;
    protected $_groupProject = 2;
    static $_blacklist = array('oui', 'non');

    /**
     * Load config
     *
     * @return Zend_Config_Ini
     */
    protected function getConfig() {
        return new Zend_Config_Ini(APPLICATION_PATH . '/configs/hash_tagging_defaults.ini', 'defaults');
    }

    /**
     *
     * @return Zend_Config_Xml
     */
    protected function getBlacklist() {

        var_dump($this->_blacklist);
        die;
        // return new Zend_Config_Xml(APPLICATION_PATH . '/configs/hash_tagging_blacklist.xml', 'fr');
    }

    /**
     * Extract tags
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param <type> $text
     * @param <type> $oneWord
     * @return <type>
     */
    public function Extract_Tags($text, $oneWord = false) {

        if ($oneWord)
            return (App_Utilities::generateSlug($text));

        $text = strtolower($text);
        $text = preg_replace('!\s!', '-', $text);
        $text = preg_replace('![^0-9a-z-_]!', '', $text);
        $text = preg_replace('!(-)+!', '-', $text);

        return explode('-', $text);
    }

    protected static function fetch_post_tags($post, $prefix = '#') {

        $result = array();
        $index = 0;
        $post = preg_replace('!\s!', '-', $post);
        $tags = explode('-', $post);

        foreach ($tags as $tag) {
         
            if (ereg("^#[a-zA-Z]*[a-zA-Z]$", $tag, $tmp)) {
                $result[$index++]= substr($tmp[0],1); //enlever le caractére #
            }
        }
        

        return $result;
    }

}

?>

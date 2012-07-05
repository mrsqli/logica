<?php

/**
 * App utilitiles, this class has all function static
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Utilities {

    /**
     * Generate password
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param int  $size 
     * @return string $password

     */
    public static function Genere_Password($size) {
        // Initialisation des caractères utilisables
        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

        for ($i = 0; $i < $size; $i++) {
            $password .= ($i % 2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }

        return $password;
    }

    /**
     * Generate Slug by exluding special chars
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param string  $phrase
     * @return string $result

     */
    public static function generateSlug($phrase) {

        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
            'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
            'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
            'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
            'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
            'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
            'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
            'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
            'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
            'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
            'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
            'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
            'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
            'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
            'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
            'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
            'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
            'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
            's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
            'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
            'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        $phrase = str_replace($a, $b, $phrase);

        $result = strtolower($phrase);

        $result = preg_replace("/[^a-z0-9\s-\/.]/", "", $result);
        $result = trim(preg_replace("/[\s]+/", " ", $result));
        $result = preg_replace("/\s/", "-", $result);

        return $result;
    }

    /**
     * switch language between french and english
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param string  $lang
     * @return string $urlprevious

     */
    public static function switchLang($lang) {

        $member = new Zend_Session_Namespace('Member');
        $member->choosedLanguage = $lang;
        return $member->urlprevious;
    }

    /**
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return $member

     */
    public static function getMember_Registry() {

        if (!Zend_Registry::isRegistered('member')) {
            return self::setMember_Registry();
        }

        return Zend_Registry::get('member');
    }

    /**
     * set Member in Registry
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param  <empty>
     * @return string $Member

     */
    public static function setMember_Registry() {

        $member = new Member();
        $member = $member->getMember($member->getIdMemberFromUser());
        Zend_Registry::set('member', $member);
        return $member;

//        $membertag=new MemberTag();
//        $membertag=$membertag->getMemberTag($id);
//       Zend_Registry::set('memberTag', $membertag);  
    }

    /** get id Member from registry
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param  <empty>
     * @return string $idMember

     */
    public static function getIdMember() {
        $member = self::getMember_Registry();
        return $member->member_id;
    }

    /**
     *  stay in same page when user click on language
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param  
     * @return 

     */
    public static function stayInSamePageWhenChangeLang() {
// see App_Frontend_Controller :: Action_preDispatch()
    }

    /**
     * convert to array
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param $array,$key
     * @return string $Member
     */
    public static function convert_toArray($array, $key = 'member_id') {

        $member['item_id'] = array();
        if (!empty($array)) {
            foreach ($array as $a) {
                $member['item_id'][] = $a->$key;
            }
        }
        return $member;
    }

    /**
     * add Array
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return string $Member
     */
    public static function Concat_Result(&$array1, $array2) {
        $indice = count($array1['item_id']);

        foreach ($array2 as $value) {
            foreach ($value as $key1 => $value1) {
                $array1['item_id'][$indice++] = $value1;
            }
        }
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param array $array_member
     * @param type $array
     * @param type $key 
     */
    public static function Convert_and_Concat(&$array_member, $array, $key) {

        $array = App_Utilities::convert_toArray($array, $key);
        App_Utilities::Concat_Result($array_member, $array);
        $array_member['limit']-=count($array['item_id']);

//        var_dump($array_member);
    }

    /**
     * function to apply to strings before sending to DB
     * @todo add filters if required
     * 
     * @author : Reda Menhouch, reda@fornetmaroc.com 
     * @param type $str
     * @param bool $keepTags (case of tinyMce)
     * @return type F
     */
    public static function _f($str, $keepTags = false) {

        if (is_array($str) && !empty($str)) {
            foreach ($str as $k => $v) {
                $str[$k] = self::_f($v);
            }
            return $str;
        }
        return (is_numeric($str)) ? (int) $str : ($keepTags) ? htmlspecialchars($str) : htmlspecialchars(strip_tags($str));
    }

    /**
     * order array multidimention by column
     * 
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param array $array multidimention to sort
     * @param string colone name to sort 
     */
    public static function aasort($array, $key, $order = 'desc') {

        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter, $order);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
        return $array;
    }

}
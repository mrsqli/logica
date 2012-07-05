<?php

/* update code by s.elguennuni@gmail.com         */

/**
 * Default Model_UrlAlias
 * 
 * @uses Core_Model_Acl_Abstract
 * @category Default
 * @package Model
 */
class UrlAlias extends App_Model {

    protected $_primary = 'urlAliasId';
    protected $_name = 'url_alias';

    /**
     * Return a model item from primary criteria
     * 
     * @param int $criteria
     * @return Core_Model_Item_Abstract|null
     * @author houmir ayoub ayoub@fornetmaroc.com
     */
    public function find($criteria) {
        if (is_numeric($criteria)) {
            $data = $this->getDao()->find($criteria)->current();
        }
        if (null === $data) {
            return null;
        }
        return $this->create($data);
    }

    /**
     * Generate Uri from url using baseUrl
     *
     * @param  <string>  $url
     * @param  <string>  $baseUrl
     * @return <string>  $url
     */
    public function getUriFromUrl($url, $baseUrl = '') {

        $pos = strpos($url, $baseUrl . '/');
        if ($pos !== false) {
            return substr_replace($url, '', $pos, strlen($baseUrl . '/'));
        }
        return $url;
    }

    /**
     * Return the alias attached to the source URI if exists
     *
     * @param String $source
     * @return String $alias
     * @author Reda Menhouch - reda@fornetmaroc.com
     */
    public function sourceHasAlias($source) {

        $select = $this->select();
        $select->where('source = "' . $source . '"');
        $item = $this->fetchRow($select);

        return (is_numeric($item->urlAliasId) && $item->urlAliasId > 0);
    }

    /**
     * Return the alias attached to the source URI if exists
     * 
     * @param String $source 
     * @return String $alias
     * @author kamal zairig - kamal@fornetmaroc.com
     */
    public function getAliasBySource($source, $lang = 'fr') {


        $select = $this->select();
        $select->where('source = "' . $source . '"');
        $item = $this->fetchRow($select);

        if (is_numeric($item->urlAliasId) && $item->urlAliasId > 0) {
            return $item->alias; //. '_' . $lang;
        }
        return $source;
    }

    /**
     * Return item from alias
     *
     * @param  <string> $alias
     * @return <string>
     * @author kamal zairig - kamal@fornetmaroc.com
     */
    public function getByAlias($alias) {
        $select = $this->select();
        $select->where('alias = "' . $alias . '"');
        $item = $this->fetchRow($select);
// if (is_numeric($item->urlAliasId) && $item->urlAliasId > 0) {
        if (is_numeric($item['urlAliasId']) && $item['urlAliasId'] > 0) {
            return $item;
        }
        return false;
    }

    /**
     * @param type $alias 
     * @param array $params  ex: array {"module":"default","controller":"category","action":"productsbycategory","id":7}
     * @author houmir ayoub ayoub@fornetmaroc.com
     */
    public function saveAlias($alias, array $aParams, $isthumb = false, $returnAlias = false) {

        //$arrLang = array('fr', 'en');
// clear the cache
//        $this->getCached()->getCache()
//                ->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('alias')
//        );

        $jParams = json_encode($aParams);
        $link = "";

        foreach ($aParams as $key => $value) {
            if (!in_array($key, array("module"))) {
                if (in_array($key, array("controller", "action"))) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . '/' . $value;
                }
            }
        }
        $params = $jParams;

        $select = $this->select()
                ->where('source = (?)', $link);

        $urlAliasSet = $this->fetchAll($select);

        $select2 = $this->select()
                ->where('alias = (?)', App_Utilities::generateSlug($alias));

        $aliasSet = $this->fetchAll($select2);

        /**
         * source not exist URL
         */
        if ($aliasSet->count() < 1) {
            /**
             * if alias axist
             */
            if ($urlAliasSet->count() > 0) {
                $urlAliasItem = $urlAliasSet->current();
                /**
                 * Boucle sur langues ($arrLang) =>
                 */
                $urlAliasItem->alias = $this->generateSlug($alias);
                $urlAliasItem->params = $params;
                $this->save($urlAliasItem);
                if ($returnAlias) {
                    return $urlAliasItem->alias;
                }
            } else {
                $data['source'] = $link;
                $data['alias'] = App_Utilities::generateSlug($alias);
                $data['params'] = $params;
                $this->save($data);
                if ($returnAlias) {
                    return $data['alias'];
                }
            }
        } else {
            if ($isthumb == FALSE) {
                $hasInsert = false;
                $i = 0;
                $aliasOne = $alias;
                while (!$hasInsert) {
                    $i++;
                    $alias = $aliasOne;
                    $alias .= '-' . $i;

                    $select2 = $this->select()
                            ->where('alias = (?)', App_Utilities::generateSlug($alias));

                    $aliasSet = $this->fetchAll($select2);
                    if ($aliasSet->count() < 1) {
                        if ($urlAliasSet->count() > 0) {
                            $hasInsert = true;
                            $urlAliasItem = $urlAliasSet->current();
                            $urlAliasItem->alias = App_Utilities::generateSlug($alias);
                            $urlAliasItem->params = $params;
                            $this->save($urlAliasItem);
                            if ($returnAlias) {
                                return $urlAliasItem->alias;
                            }
                        } else {
                            $hasInsert = true;
                            $data['source'] = $link;
                            $data['alias'] = App_Utilities::generateSlug($alias);
                            $data['params'] = $params;
                            $this->save($data);
                            if ($returnAlias) {
                                return $data['alias'];
                            }
                        }
                    }
                }
            }
        }
    }

    public function findAll($page = 1) {
        $paginator = $this->fetchAll();

        $paginator = Zend_Paginator::factory($paginator);

        return $this->fetchAll();
    }

    public function saveUserAlias($userId) {
        //die;
        $user = new Member();
        $user = $user->fetchRow("member_id='" . $userId . "'");
        // var_dump($login);die;
        $urlAliasModel = new UrlAlias();

        $paramsAlias = array("module" => "frontend", "controller" => "profile", "action" => "index", "id" => $user->member_id);

        if (isset($user->first_name) && isset($user->last_name)) {

            $profileAlias = $user->first_name . '.' . $user->last_name;
            if ($urlAliasModel->getByAlias($profileAlias)) {
                $randInt = 1;
                while ($urlAliasModel->getByAlias($profileAlias . $randInt)) {
                    $randInt++;
                }
                $profileAlias = $profileAlias . $randInt;
            }
            $urlAliasModel->saveAlias('profile/' . $profileAlias, $paramsAlias, false, true);
        }
    }

}


<?php

/* Exemple  :
 * 
 * $moduleREST = new module_clientREST();
 * $moduleREST->definirUrl("http://www...");
 * $data = array('name' => value);
 * $resultat = $moduleREST->postRequest($data);
 * if($resultat){
 *  echo $resultat['content'];
 * }
 */

class modules_clientREST {

    private $_urlDuSite;

    public function definirUrl($votreUrl) {
        $this->_urlDuSite = $votreUrl;
        return $this;
    }

    public function getRequest($vosParametres = array()) {
        return $this->_Creation($this->_creationURL($vosParametres), $this->_CreationContexte('GET'));
    }

    public function postRequest($parametresPost = array(), $parametresGet = array()) {
        return $this->_Creation($this->_creationURL($parametresGet), $this->_CreationContexte('POST', $parametresPost));
    }

    public function putRequest($leContenu = null, $parametresGet = array()) {
        return $this->_Creation($this->_creationURL($parametresGet), $this->_CreationContexte('PUT', $leContenu));
    }

    public function deleteRequest($leContenu = null, $parametresGet = array()) {
        return $this->_Creation($this->_creationURL($parametresGet), $this->_CreationContexte('DELETE', $leContenu));
    }

    protected function _CreationContexte($laMethode, $leContenu = null) {
        $options = array(
            'http' => array(
                'method' => $laMethode,
                'header' => 'Content-type: application/x-www-form-urlencoded',
            )
        );
        if ($leContenu !== null) {
            if (is_array($leContenu)) {
                $leContenu = http_build_query($leContenu);
            }
            $options['http']['content'] = $leContenu;
        }
        return stream_context_create($options);
    }

    protected function _creationURL($pParams) {
        return $this->_urlDuSite
                . (strpos($this->_urlDuSite, '?') ? '' : '?')
                . http_build_query($pParams);
    }

    protected function _Creation($votreURL, $leContexte) {
        if (($stream = fopen($votreURL, 'r', false, $leContexte)) !== false) {
            $content = stream_get_contents($stream);
            $header = stream_get_meta_data($stream);
            fclose($stream);
            return array('content' => $content, 'header' => $header);
        } else {
            return false;
        }
    }

}

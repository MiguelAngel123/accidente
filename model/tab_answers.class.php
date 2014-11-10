<?php

/**
 * tab_answers.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class tab_answers extends db2 {

    var $aid;
    var $uid;
    var $qid;
    var $value;

    function __construct() {
        parent::__construct();
    }

    function getAid() {
        return $this->aid;
    }

    function setAid($aid) {
        $this->aid = $aid;
    }

    function getUid() {
        return $this->uid;
    }

    function setUid($uid) {
        $this->uid = $uid;
    }

    function getQid() {
        return $this->qid;
    }

    function setQid($qid) {
        $this->qid = $qid;
    }

    function getValue() {
        return $this->value;
    }

    function setValue($value) {
        $this->value = $value;
    }


}

?>
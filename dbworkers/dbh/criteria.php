<?php
class DBHCriteria {
       public $condition;
       public $order;
       public $limit;
       public $offset;
       public $conditionArr;
       public function __construct(  $condition=null,Array $conditionArr=null,$order=null, $limit=null, $offset=null) {
            $this->condition=str_replace(array("<?","<=?",">?",">=?","=?"),array("< ?","<= ?","> ?",">= ?","= ?"),$condition);
            $this->conditionArr=$conditionArr?$conditionArr:array();
            $this->order=$order;
            $this->limit=$limit;
            $this->offset=$offset;
       }
   } 
<?php
class DBHRel   {
       public $type;
       public $t1,$t2,$alias;
       public function __construct($type,$alias,$t1,$t2) {
            $this->type=$type;
            $this->t1=$t1;
            $this->t2=$t2;
            $this->alias=$alias;
       } 
   }
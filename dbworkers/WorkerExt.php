<?php
  abstract class WorkerExt {
    protected $DBH;
      public function __construct($rels = []) {
          $connection = ConfigLoader::getDbConnection();
          $this->DBH = new DBH($connection['string'],$connection['user'],$connection['pass'],$rels);
      }  
      
      public function getLastQueryString() {
          return $this->DBH->queryStatement;
      }
      
      public function getLastQueryArgs() {
          return $this->DBH->queryArgs;
      }
      
      public function findByAttr(DBHCriteria $c, $ops = []) {
          return $this->DBH->findByAttr($c, $ops);
      }
      
      public function findByAttrRel($relalias,DBHCriteria $c, $ops = []) {
          return $this->DBH->findByAttrRel($relalias,$c, $ops);
      }
      
      public function updateByAttrRel($relalias, $c, $ops = []) {
          return $this->DBH->updateByAttrRel($relalias,$c, $ops);
      }
      
      public function insert($keys,$values,$ops = [], $id = false) {
          return $this->DBH->insert($keys,$values,$ops,$id);
      }
      
      public function delByAttr($c,$t) {
          return $this->DBH->deleteByAttr($c,$t);
      }
      
       public function delByAttrRel($a,$c,$t) {
          return $this->DBH->deleteByAttrRel($a,$c,$t);
      }
      
      public function findBySql($sql, $arr= [], $one = false) {
          return $this->DBH-findBySql($sql,$arr, $one);
      }
      
      public function doBySql($sql, $arr= []) {
          $this->DBH->findBySql($sql,$arr);
      }
      
      public function normalize($string) {
          $string = htmlspecialchars($string);
          return $string;
      }
  }
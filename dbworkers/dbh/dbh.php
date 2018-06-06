<?php
class DBH  {
       public $crnt;
       public $joinType="LEFT JOIN";
       public $dbh;
       public $rels;
       public $query=null;
       public $queryStatement;
       public $queryArgs;
       public $queryArr=array();
       public function __construct($conn, $user,$pass,$rels=array()) {
            $this->dbh=new PDO($conn,$user,$pass);
            $this->rels=array();
            foreach($rels as $r) $this->rels[]=$r;
            $this->dbh->exec('SET CHARACTER SET utf8');
       }
       private function getRelByAlias($alias) {
           $re=null;
           foreach($this->rels as $rel) 
             if($rel->alias==$alias) { $re=$rel; break; }  
             elseif($rel->alias."_rev"==$alias) { 
                 $re=$rel; 
                 $re->type=@split("[_]",$re->type)[1]."_".@split("[_]",$re->type)[0]; 
                 if($re->type=="MANY_MANY") $re->type="MANY_MANY2";
                 break;     
             }
           return $re;         
       }
       public function findByAttr(DBHCriteria $c=null,$ops=array('table'=>null,'one'=>false,'distinct'=>false)) {
           $s=""; $s2=array();
           if($c!=null) {
                 if(!is_null($c->condition) && $c->condition!="") $s=" WHERE ".$c->condition; else $s="";
                 foreach($c->conditionArr as $ar) $s2[]=$ar;
                 if(!is_null($c->order)) $s.=" ORDER BY ".$c->order;
                 if(!is_null($c->limit) ){ 
                     $s.=" LIMIT ".$c->limit;
                     if(!is_null($c->offset)) $s.=" OFFSET ".$c->offset;
                 }
           }
           $this->queryStatement = "SELECT ".($ops['distinct']?"DISTINCT ":"")." * FROM ".($t=$ops['table']==null?$this->crnt:$ops['table']).$s;
           $this->queryArgs = $s2;
           $sth=$this->dbh->prepare($this->queryStatement);
           $sth->execute($this->queryArgs);
           return !$ops['one']?$sth->fetchAll():$sth->fetch();
       }
       public function findByAttrRel($relalias, DBHCriteria $c=null,$ops=array('table'=>null,'one'=>false,'joinType'=>'LEFT JOIN','distinct'=>false)) {
           $s=""; $s2=array();
           $cols="";$re=null;
           if(is_a($relalias,'DBHRel')) $re = $relalias;
           else foreach($this->rels as $rel)  if($rel->alias==$relalias) { $re=$rel; break; }
               if($c!=null) {  
                 if(!is_null($c->condition) && $c->condition!="") $s=" AND ".$c->condition; else $s="";
                 foreach($c->conditionArr as $ar) $s2[]=$ar;
                 if(!is_null($c->order)) $s.=" ORDER BY ".$c->order;
                 if(!is_null($c->limit) ){ 
                     $s.=" LIMIT ".$c->limit;
                     if(!is_null($c->offset)) $s.=" OFFSET ".$c->offset;
                 }
               }
               else $s="";
           switch ($re->type) {
               case "ONE_MANY":
               foreach($re->t1['fields'] as $col) $cols.=$re->t1['name'].".".$col.",";
               foreach($re->t2['fields'] as $col) $cols.=$re->t2['name'].".".$col." AS ".$re->t2['name']."_".$col.",";
               if(count($re->t2['fields'])>0 || count($re->t1['fields'])>0) $cols=substr($cols,0,strlen($cols)-1);
               if(strlen($cols)<=0)  $cols=" * ";
               $from=" FROM ".$re->t1['name']." ".(is_null($ops['joinType'])?$this->joinType:$ops['joinType'])." ".$re->t2['name']." ON ".$re->t1['name'].".".$re->t1[$re->t1['name']]."=".$re->t2['name'].".".$re->t2[$re->t2['name']];
               //if(true) echo $s."-==<br/>"; 
               $q="SELECT ".($ops['distinct']?"DISTINCT ":"").$cols.$from.(strstr($s," AND ")==null?$s:" WHERE ".substr_replace($s," ",strpos($s," AND "),5)).";";
               //echo $q;
               $this->queryStatement = $q;
               $this->queryArgs = $s2;
               $sth=$this->dbh->prepare($this->queryStatement);
               $sth->execute($this->queryArgs);  
               return !$ops['one']?$sth->fetchAll():$sth->fetch();
               
               case "MANY_MANY":
               foreach($re->t1['fields'] as $col) $cols.=$re->t1['name'].".".$col.",";
               foreach($re->t2['fields'] as $col) $cols.=$re->t2['name'].".".$col." AS ".$re->t2['name']."_".$col.",";
               if(count($re->t2['fields'])>0 || count($re->t1['fields'])>0) $cols=substr($cols,0,strlen($cols)-1);
               if(strlen($cols)<=0)  $cols=" * ";
               $from=" FROM ".$re->t1['name'].", ".$re->t2['name'];
               $select2=" SELECT ";
               $select2.=$re->t1['through'][0].'.'.$re->t1['through'][1].' FROM '.$re->t1['through'][0].' WHERE '.$re->t1['through'][0].'.'.$re->t1['through'][2].'='.$re->t2['name'].'.'.$re->t2[$re->t2['name']];
               $select2=" WHERE ".$re->t1['name'].".".$re->t1[$re->t1['name']]." IN (".$select2." )";
               
               $q="SELECT ".($ops['distinct']?"DISTINCT ":"").$cols.$from.$select2." ".$s.";";
               //echo $q;
               //print_r($s2);
               $sth=$this->dbh->prepare($q);
               $sth->execute($s2);  
               return !$ops['one']?$sth->fetchAll():$sth->fetch();
           }  
      } 
       public function insert($keys,$values,$ops=array(),$id=false) {
           $c=count($keys);
           $k="(".implode(",",$keys).")";
           $i=0; $v="";$arr=array();
           foreach($values as $val)
             {
               $i=0;
               $b="";
               for($i=0;$i<$c;++$i) { $b.="?,"; $arr[]=$val[$i]; }
               $b=substr($b,0,strlen($b)-1);
               $v.="(".$b."),";
             }
            if($v!="") $v=substr($v,0,strlen($v)-1); 
            //print_r($arr);
            $this->queryStatement = "INSERT INTO ".($ops['table']?$ops['table']:$this->crnt).$k." VALUES".$v.";";
            $sth=$this->dbh->prepare($this->queryStatement);
            $this->queryArgs = $arr;
            $sth->execute($this->queryArgs);
            if($id) {
               $rs= $this->dbh->prepare("SELECT MAX(".$id.") AS max FROM ".($ops['table']?$ops['table']:$this->crnt).";");
               $rs->execute(array());
               $rs2=$rs->fetch();
               return $rs2['max'];
            }
       }
       public function updateByAttr(DBHCriteria $c=null,$values=array(),$t=null) {
            $table=$t?$t:$this->crnt;
            $set=" SET "; $ar=array();
            foreach($values as $k=>$v) {   $set.=$k."=?,"; $ar[]=$v; }
            if(strlen($set)>5) $set=substr($set,0,strlen($set)-1); else $set="";
            $where=$c!=null?" WHERE ".$c->condition:"";
            //echo "UPDATE ".$table.$set.$where.";";
            $sth=$this->dbh->prepare("UPDATE ".$table.$set.$where.";");
            $sth->execute(array_merge($ar,$c!=null?$c->conditionArr:array()));
       }
       public function updateByAttrRel($alias, $c=null, $values=array(), $exec=true) {
            //echo "<br/>---78t677tguy-----<br/>"; 
            $set=" SET "; $ar=array();
            //return true;
            if(empty($c)) $c = new DBHCriteria("",[0],"");
            //return true;
            foreach($values as $k=>$v) {   $set.=$k."=?,"; $ar[]=$v; }
            if(strlen($set)>5) $set=substr($set,0,strlen($set)-1); else $set="";
            $re=null; 
            if(is_object($alias)) $re = $alias;
            else foreach($this->rels as $rel) 
             if($rel->alias==$alias) { $re=$rel; break; }  
             elseif($rel->alias."_rev"==$alias) { 
                 $re=$rel; 
                 $re->type=@split("[_]",$re->type)[1]."_".@split("[_]",$re->type)[0]; 
                 if($re->type=="MANY_MANY") $re->type="MANY_MANY2";
                 break;     
             }
          //echo "<br/>----re----<br/>";
           //print_r($re);
           $table=$re->t1['name'];  
           //return true;
           if($c!=null) {
              $t1="";$t2=""; 
              $ar1=array();$ar2=array();
              $k=0;
              foreach(@split("[A][N][D][ ]",$c->condition) as $condition) {
                 //echo "<br/>---condition---".$condition."---<br/>---re[t1]---".$re->t1['name']."---<br/>";
                  $s=trim(@split("\.",$condition)[0]);
                  $s=count(@split("[(]",$s))>1?@split("[(]",$s)[1]:$s;
                  if($s==$re->t1['name']) { 
                      $t1.=$condition." AND ";
                      if(stripos($condition,"?")!=false) $ar1[]=$c->conditionArr[$k++];   
                  } 
                  elseif($s==$re->t2['name']) {
                      $t2.=$condition." AND ";
                      if(stripos($condition,"?")!=false) $ar2[]=$c->conditionArr[$k++];
                  }
              } 
              //return true;
              if($t1!="") $t1=substr($t1,0,strlen($t1)-4); if($t2!="") $t2=substr($t2,0,strlen($t2)-4);
              //echo "<br/>---t1---<br/>".$t1."<br/>---ar1---<br/>"; print_r($ar1);
              //echo "<br/>---t2---<br/>".$t2."<br/>---ar2---<br/>"; print_r($ar2);
              switch($re->type){
                  case "ONE_MANY":
                    $sel="SELECT ".$re->t2[$re->t2['name']]." FROM ".$re->t2['name'].($t2!=""?" WHERE ".$t2:"");
                    $query="UPDATE ".$re->t1['name'].$set.($t1!=""?" WHERE ".$t1." AND ":" WHERE ").$re->t1['name'].".".$re->t1[$re->t1['name']]." IN (".$sel.");";
                    $ar=array_merge(array_merge($ar,$ar1),$ar2);
                    //$this->dbh->prepare($query)->execute($ar);
                    //echo "<br/>---query---<br/>";
                    $this->query=$query; $this->queryArr=$ar;
                    $this->queryStatement = $query;
                    $this->queryArgs = $ar;
                    if($exec)$this->dbh->prepare($query)->execute($ar);
                   // echo $query;
                    //print_r($ar);
                  break;
                  case "MANY_ONE":
                    //echo "<br/>MANY_ONE<br/>";
                    $sel="SELECT ".$re->t1[$re->t1['name']]." FROM ".$re->t1['name'].($t1!=""?" WHERE ".$t1:"");
                    $query="UPDATE ".$re->t2['name'].$set.($t2!=""?" WHERE ".$t2." AND ":" WHERE ").$re->t2['name'].".".$re->t2[$re->t2['name']]." IN (".$sel.");";
                    $ar=array_merge(array_merge($ar,$ar2),$ar1);
                    $this->query=$query; $this->queryArr=$ar;
                    if($exec)$this->dbh->prepare($query)->execute($ar);
                    //echo "<br/>---query---<br/>";
                    //echo $query;
                    //print_r($ar);
                  break;
                  case "MANY_MANY":
                    //echo "<br/>MANY_MANY<br/>";
                    $sub="SELECT ".$re->t2[$re->t2['name']]." FROM ".$re->t2['name'].($t2!=""?" WHERE ".$t2:"");
                    $sel="SELECT ".$re->t1['through'][1]." FROM ".$re->t1['through'][0]." WHERE ".$re->t1['through'][2]." IN (".$sub.")";
                    $query="UPDATE ".$re->t1['name'].$set.($t1!=""?" WHERE ".$t1." AND ":" WHERE ").$re->t1['name'].".".$re->t1[$re->t1['name']]." IN (".$sel.");";
                    $ar=array_merge(array_merge($ar,$ar1),$ar2);
                    //echo "<br/>---query---<br/>";
                    //echo $query;
                    //print_r($ar);
                    $this->query=$query; $this->queryArr=$ar;
                    if($exec)$this->dbh->prepare($query)->execute($ar);
                  break;
                  case "MANY_MANY2":
                    $sub="SELECT ".$re->t1[$re->t1['name']]." FROM ".$re->t1['name'].($t1!=""?" WHERE ".$t1:"");
                    $sel="SELECT ".$re->t1['through'][2]." FROM ".$re->t1['through'][0]." WHERE ".$re->t1['through'][1]." IN (".$sub.")";
                    $query="UPDATE ".$re->t2['name'].$set.($t2!=""?" WHERE ".$t2." AND ":" WHERE ").$re->t2['name'].".".$re->t2[$re->t2['name']]." IN (".$sel.");";
                    $ar=array_merge(array_merge($ar,$ar2),$ar1);
                    //echo "<br/>---query---<br/>";
                   // echo $query;
                    //print_r($ar);
                    $this->query=$query; $this->queryArr=$ar;
                    if($exec)$this->dbh->prepare($query)->execute($ar);
                  break; 
              }
           }
       }
       public function deleteByAttr(DBHCriteria $c=null,$t=null) {
           $table=$t?$t:$this->crnt;
           $this->queryStatement = "DELETE FROM ".$table.($c?$c->condition!=""?" WHERE ".$c->condition:";":";");
           $this->queryArgs = $c?$c->conditionArr?$c->conditionArr:array():array();
           $this->dbh->prepare($this->queryStatement)->execute($this->queryArgs);
       }
       public function deleteByAttrRel($alias,DBHCriteria $c=null,$exec=true) {
          $this->updateByAttrRel($alias,$c,array("a"=>"a"),false); 
          // echo "<br/>---query---<br/>".$this->query."<br/>---ar---<br/>";
           $this->query=str_replace("UPDATE ","DELETE FROM ",@split("SET[ ]+a[=][?]",$this->query)[0]).@split("SET[ ]+a[=][?]",$this->query)[1];
           array_shift($this->queryArr);
           //echo "<br/>---query2---<br/>".$this->query."<br/>";
           //print_r($this->queryArr);
           $this->queryStatement = $this->query;
           $this->queryArgs = $this->queryArr;
           if($exec)$this->dbh->prepare($this->query)->execute($this->queryArr);
       }
       public function findBySql($sql,$arr=array(),$one=false) {
           $res=$this->dbh->prepare($sql);
           $res->execute($arr);
           if($one) return $res->fetch(); else return $res->fetchAll();
       }
       public function doBySql($sql,$arr=array()) { $this->dbh->prepare($sql)->execute($arr);     }
   }
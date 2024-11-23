<?php

class Mitteilung{
    private $link;
    //-----------------------------------------
    public function __construct($dbLinkA=null){
        $this->link = $dbLinkA;
    }
    //-----------------------------------------
    public function setzenDBLink($dbLinkA=null):void{
        $this->link = (isset($dbLinkA))?$dbLinkA:null;        
    }
    //-----------------------------------------
    public function erhaltenList():array{
        $stnh = $this->link->prepare("select * from ".DB_PREFIX."MITTEILUNGEN");
        $stnh->execute();
        $res = $stnh->fetchAll();
        return $res;    
    }
    //-----------------------------------------
    public function erhaltenDBLink(){
        return $this->link;
    }
    //-----------------------------------------
    public function erhaltenListLange():int{
        $stnh = $this->link->prepare("select count(*) from ".DB_PREFIX."MITTEILUNGEN");
        $stnh->execute();
        $res = $stnh->fetchAll();
        return intval($res[0][0]);    
    }
    //-----------------------------------------
    public function loschenMitteilung($mID){
        $sql = "delete from ";
        $sql .= DB_PREFIX;
        $sql .= "MITTEILUNGEN where ID=:mid";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":mid",$mID);
        return $stnh->execute();
    }
    //-----------------------------------------
    public function erhaltenPerID($mID):array{
        $sql = "select * from ";
        $sql .= DB_PREFIX;
        $sql .= "MITTEILUNGEN where ID=:mid";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":mid",$mID);
        $stnh->execute();
        $res = $stnh->fetch(PDO::FETCH_ASSOC);
        return $res;    
    }
    //-----------------------------------------
    public function erhaltenPerEtikett($tgA):array{
        $sql = "select * from ";
        $sql .= DB_PREFIX;
        $sql .= "MITTEILUNGEN where ";
        $sql .= "VORNAME like \"%$tgA%\" or ";
        $sql .= "NACHNAME like \"%$tgA%\" or ";
        $sql .= "TELEFON like \"%$tgA%\" or ";
        $sql .= "EMAIL like \"%$tgA%\" or ";
        $sql .= "MITTEILUNG like \"%$tgA%\"";
        $stnh = $this->link->prepare($sql);
        $stnh->execute();        
        $res = $stnh->fetchAll();
        return $res;    
    }
    //-----------------------------------------
    public function einfugen($vornameA,$nachnameA,$telA,$emailA,$mA):bool{
        if(!$this->prufenMitteilung($vornameA,$nachnameA,$telA,$emailA,$mA)){
            $sql = "insert into ";
            $sql .= DB_PREFIX;
            $sql .= "MITTEILUNGEN(VORNAME,NACHNAME,TELEFON,EMAIL,MITTEILUNG) ";
            $sql .= "values(:fn,:ln,:ph,:em,:mg)";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":fn",$vornameA);
            $stnh->bindParam(":ln",$nachnameA);
            $stnh->bindParam(":ph",$telA);
            $stnh->bindParam(":em",$emailA);
            $stnh->bindParam(":mg",$mA);
            return $stnh->execute();
        }
        return false;
    }
    //-----------------------------------------
    private function prufenMitteilung($vornameA,$nachnameA,$telA,$emailA,$mA):bool{
        $sql = "select id from ";
        $sql .= DB_PREFIX;
        $sql .= "MITTEILUNGEN where ";
        $sql .= "VORNAME=:fn and ";
        $sql .= "NACHNAME=:ln and ";
        $sql .= "TELEFON=:ph and ";
        $sql .= "EMAIL=:em and ";
        $sql .= "MITTEILUNG=:mg";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":fn",$vornameA);
        $stnh->bindParam(":ln",$nachnameA);
        $stnh->bindParam(":ph",$telA);
        $stnh->bindParam(":em",$emailA);
        $stnh->bindParam(":mg",$mA);
        $stnh->execute();
        return is_array($stnh->fetch(PDO::FETCH_ASSOC));
    }
}

?>
<?php

class Token{
    private $id;
    private $daten;
    private $link;
    //-----------------------------------------
    public function __construct($dbLinkA=null){
        $this->loschenDaten();
        $this->link = $dbLinkA;
    }
    //-----------------------------------------
    public function loschenDaten():void{
        $this->id = null;
        $this->daten = array(
            "benutzer"=>"",
            "angemeldet"=>"",
            "lezteaktion"=>"",
            "token"=>"",
            "mitteilungen"=>0
        );
    }
    //-----------------------------------------
    private function tokenExistiert($tokenA):bool{
        $sql = "select id from ".DB_PREFIX."TOKENS where TOKEN=:tk";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":tk",$tokenA);
        $stnh->execute();
        return is_array($stnh->fetch(PDO::FETCH_ASSOC));
    }
    //-----------------------------------------
    public function benutzerExistiert($userA):bool{
        $sql = "select ID from ".DB_PREFIX."TOKENS where BENUTZER=:bid";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":bid",$userA);
        $stnh->execute();
        return is_array($stmt->fetch(PDO::FETCH_ASSOC));
    }
    //-----------------------------------------
    public function loschenToken($idA):bool{
        if(isset($idA)
        && $this->getById($idA)){
            $stnh = $this->link->prepare("delete from ".DB_PREFIX."tokens where ID=:bid");
            $stnh->bindParam(":bid",$idA);
            return $stnh->execute();
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerId($idA):bool{
        if(isset($idA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."TOKENS where ID=:bid";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":bid",$idA);
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->id = $idA;
                if(isset($res['benutzer'])){
                    $this->daten["benutzer"] = $res['BENUTZER'];
                }
                if(isset($res['angemeldet'])){
                    $this->daten["angemeldet"] = $res['ANGEMELDET'];
                }
                if(isset($res['lezteaktion'])){
                    $this->daten["lezteaktion"] = $res['LETZTEAKTION'];
                }
                if(isset($res['token'])){
                    $this->daten["token"] = $res['TOKEN'];
                }
                if(isset($res['mitteilungen'])){
                    $this->daten["mitteilungen"] = $res['MITTEILUNG'];
                }
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerToken($tokenA):bool{
        if(isset($tokenA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."TOKENS where TOKEN=:tk";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":tk",$tokenA);
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->daten["token"] = $tokenA;
                if(isset($res['ID'])){
                    $this->id = $res['ID'];
                }
                if(isset($res['benutzer'])){
                    $this->daten["benutzer"] = $res['BENUTZER'];
                }
                if(isset($res['angemeldet'])){
                    $this->daten["angemeldet"] = $res['ANGEMELDET'];
                }
                if(isset($res['lezteaktion'])){
                    $this->daten["lezteaktion"] = $res['LETZTEAKTION'];
                }
                if(isset($res['mitteilungen'])){
                    $this->daten["mitteilungen"] = $res['MITTEILUNG'];
                }               
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerBenutzer($benutzeridA):bool{
        if(isset($benutzeridA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."TOKENS where BENUTZER=:bid";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":bid",$benutzeridA);
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->daten["benutzer"] = $benutzeridA;
                if(isset($res['ID'])){
                    $this->id = $res['ID'];
                }
                if(isset($res['token'])){
                    $this->daten["token"] = $res['TOKEN'];
                }
                if(isset($res['angemeldet'])){
                    $this->daten["angemeldet"] = $res['ANGEMELDET'];
                }
                if(isset($res['lezteaktion'])){
                    $this->daten["lezteaktion"] = $res['LETZTEAKTION'];
                }
                if(isset($res['mitteilungen'])){
                    $this->daten["mitteilungen"] = $res['MITTEILUNG'];
                }               
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenDaten($feldA):string{
        if($feldA=="id"){
            return strval($this->id);
        }
        else if(array_key_exists($feldA,$this->daten)){
            return $this->daten[$feldA];
        }
        return "";
    }
    //-----------------------------------------
    public function erhaltenDBLink(){
        return $this->link;
    }
    //-----------------------------------------
    public function erhaltenList():array{
        if(isset($this->link)){
            $stnh = $this->link->prepare("select * from ".DB_PREFIX."TOKENS");
            $stnh->execute();
            return $stnh->fetchAll();
        }
        return array();
    }
    //-----------------------------------------
    public function rehaltenListLange():int{
        $stnh = $this->link->prepare("select count(*) from ".DB_PREFIX."TOKENS");
        $stnh->execute();
        $res = $stnh->fetchAll();
        return intval($res[0][0]);
    }
    //-----------------------------------------
    public function erhaltenToken():string{
        return hash("sha256",Date("sdYimh",Time()).$this->daten["benutzer"].$this->daten["angemeldet"]);
    }
    //-----------------------------------------
    public function speichernToken():bool{
        if($this->id){
            //aktualisieren benutzer
            $sql = "update ".DB_PREFIX."TOKENS set ";
            $sql .= "benutzer = :uid,";
            $sql .= "angemeldet = :ulog,";
            $sql .= "lezteaktion = :ulast,";
            $sql .= "mitteilungen = :umsg ";
            $sql .= "where TOKEN=:utk";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":uid",$this->daten['BENUTZER']);
            $stnh->bindParam(":ulog",$this->daten['ANGEMELDET']);
            $stnh->bindParam(":ulast",$this->daten['LEZTEAKTION']);
            $stnh->bindParam(":umsg",$this->daten['MITTEILUNGEN']);
            $stnh->bindParam(":utk",$this->daten['TOKEN']);
            return $stnh->execute();            
        }
        else if(!$this->tokenExistiert($this->daten['token'])
        && !$this->benutzerExistiert($this->daten['benutzer'])){
            //erstellen neu benutzer
            $sql = "insert into ".DB_PREFIX."TOKENS(BENUTZER,TOKEN)";
            $sql .= "values(:uid,:utk)";
            $stnh = $this->link->prepare($sql);
            $tk = $this->erhaltenToken();
            $stnh->bindParam(":uid",$this->daten['benutzer']);
            $stnh->bindParam(":utk",$tk);
            return $stnh->execute();
        }
        return false;
    }
    //-----------------------------------------
    public function setzenData($feldA,$wertA):void{
        if($feldA==="id"){
            $this->id = $wertA;
        }
        if(array_key_exists($feldA,$this->daten)){
            $this->daten[$feldA] = $wertA;
        }
    }
    //-----------------------------------------
    public function setzenDBLink($dbLinkA=null):void{
        $this->link = (isset($dbLinkA))?$dbLinkA:null;        
    }

}

?>
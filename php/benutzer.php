<?php

class Benutzer{
    //Benutzer privilegien
    const KEINER = 0;
    const LESEN = 2;
    const AKTUALISIEREN = 4;
    const ERSTELLEN = 8;
    const LOSCHEN = 16;
    //Benutzer zone
    const KUNDE = 0;
    const MITARBEITER = 2;
    const ADMIN = 4;
    //Benutzerdatenvariablen
    private $id;
    private $daten;
    private $link;
    //-----------------------------------------
    public function __construct($dbLinkA=null){
        $this->id = null;
        $this->daten = array(
            "name"=>"",
            "passwort"=>"",
            "email"=>"",
            "privilegien"=>0,
            "zone"=>0
        );
        $this->link = $dbLinkA;
    }
    //-----------------------------------------
    public function erhaltenDBLink():void{
        return $this->link;
    }
    //-----------------------------------------
    public function setzenDBLink($dbLinkA=null):void{
        $this->link = (isset($dbLinkA))?$dbLinkA:null;        
    }
    //-----------------------------------------
    public function setzenDaten($feldA,$wertA):void{
        if($feldA==="id"){
            $this->id = intval($wertA);
        }
        else if($feldA==="name"||$feldA==="passwort"){
            $this->daten[$feldA] = hash("sha256",$wertA);
        }
        else if(array_key_exists($feldA,$this->daten)){
            $this->daten[$feldA] = $wertA;
        }
    }
    //-----------------------------------------
    public function erhaltenDaten($feldA):string{
        if($feldA=="id"){
            return strval($this->id);
        }
        else if(array_key_exists($feldA,$this->daten)){
            return strval($this->daten[$feldA]);
        }
        return "";
    }
    //-----------------------------------------
    private function benutzernameExistiert($nameA):bool{
        $sql = "select ID from ".DB_PREFIX."BENUTZER where BENUTZERNAME=:uname";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":uname",hash("sha256",$nameA));
        $stnh->execute();
        return is_array($stnh->fetch(PDO::FETCH_ASSOC));
    }
    //-----------------------------------------
    private function emailExistiert($emailA):bool{
        $sql = "select id from ".DB_PREFIX."BENUTZER where EMAIL=:mail";
        $stnh = $this->link->prepare($sql);
        $stnh->bindParam(":mail",$emailA);
        $stnh->execute();
        return is_array($stnh->fetch(PDO::FETCH_ASSOC));
    }
    //-----------------------------------------
    public function erhaltenListe():array{
        if(isset($this->link)){
            $stnh = $this->link->prepare("select ID,BENUTZERNAME,EMAIL,PRIVILEGIEN from ".DB_PREFIX."BENUTZER");
            $stnh->execute();
            return $stnh->fetchAll();
        }
        return array();
    }
    //-----------------------------------------
    public function erhaltenListenlange():int{
        $stmt = $this->link->prepare("select count(*) from ".DB_PREFIX."BENUTZER");
        $stmt->execute();
        $res = $stmt->fetchAll();
        return intval($res[0][0]);
    }
    //-----------------------------------------
    public function loschenBenutzer($idA):bool{
        if(isset($idA)
        && $this->erhaltenPerID($idA)){
            $stnh = $this->link->prepare("delete from ".DB_PREFIX."BENUTZER where ID=:uid");
            $stnh->bindParam(":uid",$idA);
            return $stnh->execute();
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerID($idA):bool{
        if(isset($idA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."BENUTZER where ID=:uid";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":uid",$idA);
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->id = intval($idA);
                if(isset($res['name'])){
                    $this->daten["name"] = strval($res['BENUTZERNAME']);
                }
                if(isset($res['passwort'])){
                    $this->daten["passwort"] = strval($res['PASSWORT']);
                }
                if(isset($res['email'])){
                    $this->daten["email"] = strval($res['EMAIL']);
                }
                if(isset($res['privilegien'])){
                    $this->daten["privilegien"] = intval($res['PRIVILEGIEN']);
                }
                if(isset($res['zone'])){
                    $this->daten["zone"] = intval($res['BZONE']);
                }
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerEmail($emailA):bool{
        if(isset($emailA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."BENUTZER where EMAIL=:umail";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":umail",$emailA);
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->daten["email"] = strval($emailA);
                if(isset($res['ID'])){
                    $this->id = intval($res['ID']);
                }
                if(isset($res['passwort'])){
                    $this->daten["passwort"] = strval($res['PASSWORT']);
                }
                if(isset($res['name'])){
                    $this->daten["name"] = strval($res['BENUTZERNAME']);
                }
                if(isset($res['privilegien'])){
                    $this->daten["privilegien"] = intval($res['PRIVILEGIEN']);
                }
                if(isset($res['zone'])){
                    $this->daten["zone"] = intval($res['BZONE']);
                }
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------
    public function erhaltenPerName($nameA){
        if(isset($nameA)
        && isset($this->link)){
            $sql = "select * from ".DB_PREFIX."BENUTZER where BENUTZERNAME=:uname";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":uname",hash("sha256",$nameA));
            $stnh->execute();
            $res = $stnh->fetch(PDO::FETCH_ASSOC);
            if(is_array($res)){
                $this->daten["name"] = hash("sha256",$nameA);
                if(isset($res['ID'])){
                    $this->id = intval($res['ID']);
                }
                if(isset($res['passwort'])){
                    $this->daten["passwort"] = strval($res['PASSWORT']);
                }
                if(isset($res['email'])){
                    $this->daten["email"] = strval($res['EMAIL']);
                }
                if(isset($res['privilegien'])){
                    $this->daten["privilegien"] = intval($res['PRIVILEGIEN']);
                }
                if(isset($res['zone'])){
                    $this->daten["zone"] = intval($res['BZONE']);
                }
                return true;
            }
        }
        return false;
    }
    //-------------------------------
    public function speichernBenutzer():bool{
        if(isset($this->id)){
            //aktualisieren benutzer
            $sql = "update ".DB_PREFIX."BENUTZER set ";
            $sql .= "BENUTZERNAME = :uname,";
            $sql .= "PASSWORT = :upass,";
            $sql .= "EMAIL = :umail ";
            $sql .= "PRIVILEGIEN = :priv,";
            $sql .= "BZONE = :bzne";
            $sql .= "where id=:uid";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":uname",$this->daten['name']);
            $stnh->bindParam(":upass",$this->daten['passwort']);
            $stnh->bindParam(":umail",$this->daten['email']);
            $stnh->bindParam(":priv",$this->daten['privilegien']);
            $stnh->bindParam(":bzne",$this->daten['zone']);
            $stnh->bindParam(":uid",$this->id);
            return $stnh->execute();
        }
        else if(!$this->benutzernameExistiert($this->daten['name'])
        && !$this->emailExistiert($this->daten['email'])){
            //erstallen neu benutzer
            $sql = "insert into ".DB_PREFIX."BENUTZER(BENUTZERNAME,PASSWORT,EMAIL,PRIVILEGIEN,BZONE)";
            $sql .= "values(:uname,:upass,:umail,:priv,:bzne)";
            $stnh = $this->link->prepare($sql);
            $stnh->bindParam(":uname",$this->daten['name']);
            $stnh->bindParam(":upass",$this->data['passwort']);
            $stnh->bindParam(":umail",$this->data['email']);
            $stnh->bindParam(":priv",$this->data['privilegien']);
            $stnh->bindParam(":bzne",$this->daten['zone']);
            return $stnh->execute();
        }
        return false;
    }
}

?>








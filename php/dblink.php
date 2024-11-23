<?php 

class DBLink{
    private $dblink;
    private $verbDaten;
    //-------------------------------------
    public function __construct($hostA=null,$dbA=null,$benutzerA=null,$pswrtA=null){
        $this->dblink = null;
        $this->verbDaten = array(
            "host"=>$hostA,
            "db"=>$dbA,
            "user"=>$benutzerA,
            "pswrt"=>$pswrtA
        );
    }
    //-------------------------------------
    private function erhaltenVerbParam():string{
        $res = "mysql:host=";
        $res.= $this->verbDaten["host"];
        $res.= ";dbname=";
        $res.= $this->verbDaten["db"];
        $res.=";";
        return $res;
    }
    //-------------------------------------
    public function verbinden():bool{
        try{
            $this->dblink = new PDO($this->erhaltenVerbParam(),$this->verbDaten["user"],$this->verbDaten["pswrt"]);
            $this->dblink->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $this->dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }
        catch(PDOException $e){
            $this->dblink = null;
        }
        return false;
    }
    //-------------------------------------
    public function trennen():void{
        $this->dblink = null;
    }
    //-------------------------------------
    public function erhaltenDaten($feldA):string{
        if(array_key_exists($feldA,$this->verbDaten)){
            return strval($this->verbDaten[$feldA]);
        }
        return "";
    }
    //-------------------------------------
    public function erhaltenLink(){
        return $this->dblink;
    }
    //-------------------------------------
    public function setzenDaten($feldA,$wertA):void{
        if(array_key_exists($feldA,$this->verbDaten)){
            $this->verbDaten[$feldA] = strval($wertA);
        }
    }
}



?>

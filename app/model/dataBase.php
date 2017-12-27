<?php

namespace app\model;

class dataBase
{
    public $db;
    public $setLog = 0;
    
    static private $instance = null;
    
    static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function __construct($param)
    {
        include $_SERVER['DOCUMENT_ROOT'] ."/vendor/DbSimple/Generic.php";
        $this->db = \DbSimple_Generic::connect("mysqli://{$param['user']}:{$param['pass']}@{$param['host']}/{$param['table']}");
        $this->db->query("SET NAMES UTF8");
    }
    
    function setLogger($set=1)
    {
        if($set){
           $this->db->setLogger([$this,'logger']); 
        }else{
            $this->db->setLogger('');
        }
    }
    
    function logger($db, $sql)
    {
        $this->setLogger(0);
        $caller = $this->db->findLibraryCaller();
        $this->insert("log",array(
            'name'=>"sql call at {$caller['file']} line {$caller['line']}",
            'log'=>$sql
        ));
        
        $this->setLogger(1);
    }
    
    function select()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'select'], $args);
    }
    
    function selectRow()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectRow'], $args);
    }
    
    function selectCell()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectCell'], $args);
    }
    
    function selectCol()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectCol'], $args);
    }
    
    function query()
    {
        $args = func_get_args();
        $res = call_user_func_array([$this->db,'query'], $args);
        return $res;
    }
    
    function insert($table,$row)
    {
        return $this->db->query('insert into ?# (?#) values (?a)',$table,array_keys($row),array_values($row));
    }
    
    function update($table,$row,$id)
    {
        return $this->db->query('update ?# set ?a where id=?d',$table,$row,$id);
    }
    
    function updateArr($table, $list)
    {
        $args = array_keys($list[0]);
        $sql = "update ?# set ";
        foreach($args as $arg){
            if($arg == 'id'){
                continue;
            }
            $ids = [];
            $sql .= " `{$arg}` = CASE id";
            foreach($list as $item){
                $ids[] = $item['id'];
                $sql .= " when {$item['id']} then '{$item[$arg]}' ";
            }
            $sql .= "end,";
        }
        $sql = substr($sql,0, -1);
        $sql .= " where id in(?a)";
        $this->db->query($sql,$table,$ids);
    }
    
    public function getError()
    {
        return $this->db->getErrors();
    }
    
    public function checkError()
    {
        $error = $this->getError();
        if($error['error']){
            throw new \Exception($error['error']);
        }
    }
    
    public function escape($str)
    {
        return $this->db->link->escape_string($str);
    }
}

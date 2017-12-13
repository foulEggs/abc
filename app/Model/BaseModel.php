<?php

namespace app\Model;
use DB;

class BaseModel{
	
	protected function getTable ($tablename) {
		return DB::table($tablename);
	}
	
	protected function switchWhere($db, $where){
		
		foreach($where as $k=>$v){
			if(is_array($v)){
				if($v[0] == 'between'){
					$db->whereBetween($k, $v[1]);
				}else if($v[0] == 'in'){
					$db->whereIn($k, $v[1]);
				}else{
					$db->where($k, $v[0], $v[1]);
				}
			}else{
				$db->where($k, $v);
			}
		}
		
		return $db;
	}
	
	public function startTransaction () {
		DB::beginTransaction();
	}
	
	public function commit () {
		DB::commit();
	}
	
	public function rollBack () {
		DB::rollBack();
	}
	
}


<?php

namespace app\Model;

use Illuminate\Support\Facades\DB;
use App\Model\BaseModel;

class SystemConfig extends BaseModel{
	
	public function acquire($tablename, $where, $fields = []){
		
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->first();
	}
	
	public function acquireList($tablename, $page, $limit, $where, $fields = []){
		
		$offset = ($page - 1)*$limit;
		
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->offset($offset)->limit($limit)->get();
	}
	
	public function acquireKeyVlaueList($tablename, $where, $key, $value){
		
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->pluck($value, $key);
	}
	
	public function acquireListJoin($table, $page, $limit, $where, $join, $fields = [], $group = ''){
		
		$offset = ($page - 1)*$limit;
		
		$db = $this->getTable($table);
		
		$this->switchWhere($db, $where);
		
		if(!empty($join)){
			foreach($join as $k=>$v){
				$db->leftJoin($k,$v[0],'=',$v[1]);
			}
		}
		
		if($group){
			$db->groupBy($group);
		}
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->offset($offset)->limit($limit)->get();
	}
	
	public function acquireListCount($tablename, $where){
		
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->count();
	}
	
	public function add($tablename, $data, $getId = true){
		
		$action = $getId ? 'insertGetId' : 'insert';
		
		return $this->getTable($tablename)->$action($data);
	}
	
	public function modify($tablename, $where, $data){
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->update($data);
	}
	
	public function remove($tablename, $where){
		$db = $this->getTable($tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->delete();
	}
	
}


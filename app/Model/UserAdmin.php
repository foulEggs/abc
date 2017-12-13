<?php

namespace app\Model;

use Illuminate\Support\Facades\DB;
use App\Model\BaseModel;

class UserAdmin extends BaseModel{
	protected $tablename = 'users_admin';
	
	public function acquire($where, $fields = ''){
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->first();
	}
	
	public function acquireList($page, $limit, $where, $fields = ''){
		
		$offset = ($page - 1)*$limit;
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->offset($offset)->limit($limit)->get();
	}
	
	public function acquireListCount($where){
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->count();
	}
	
	public function add($data, $getId = true){
		
		$action = $getId ? 'insertGetId' : 'insert';
		
		return $this->getTable($this->tablename)->$action($data);
	}
	
	public function modify($where, $data){
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->update($data);
	}
	
	public function remove($where){
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->delete();
	}
	
}


<?php

namespace app\Model;

use App\Model\BaseModel;

class Terminal extends BaseModel{
	protected $tablename = 'terminal';
	
	protected $tablename1 = 'terminal_staff';
	
	public function acquire($where, $fields = ''){
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->first();
	}
	
	public function acquireAggregate($where , $action, $field){
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		return $db->$action($field);
	}
	
	public function acquireList($page, $limit, $where, $fields = [], $order = ['order_by'=>'id', 'order_way'=>'desc']){
		
		$offset = ($page - 1)*$limit;
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		if($fields){
			$db->select($fields);
		}
		
		return $db->offset($offset)->limit($limit)->orderBy($order['order_by'], $order['order_way'])->get();
	}
	
	public function acquireListJoin($page, $limit, $where, $join, $fields = [], $group = '', $order = ['order_by'=>'orders.id', 'order_way'=>'desc']){
		
		$offset = ($page - 1)*$limit;
		
		$db = $this->getTable($this->tablename);
		
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
		
		return $db->offset($offset)->limit($limit)->orderBy($order['order_by'], $order['order_way'])->distinct()->get();
	}
	
	public function acquireListJoinCount($where, $join){
		
		$db = $this->getTable($this->tablename);
		
		$this->switchWhere($db, $where);
		
		if(!empty($join)){
			foreach($join as $k=>$v){
				$db->leftJoin($k,$v[0],'=',$v[1]);
			}
		}
		
		return $db->distinct()->count('orders.sys_order_num');
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
	
	public function acquireTerminalStaffList($where){
		
		$db = $this->getTable($this->tablename1);
		
		$this->switchWhere($db, $where);
		
		return $db->get();
	}
	
	public function addTerminalStaff($data, $getId = true){
		
		$action = $getId ? 'insertGetId' : 'insert';
		
		return $this->getTable($this->tablename1)->$action($data);
	}
	
	public function removeTerminalStaff($where){
		$db = $this->getTable($this->tablename1);
		
		$this->switchWhere($db, $where);
		
		return $db->delete();
	}
	
}


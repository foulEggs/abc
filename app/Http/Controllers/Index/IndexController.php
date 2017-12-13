<?php

namespace app\Http\controllers\Index;
use App\Http\Controllers\Controller;
use App\Repositories\MenuRepository;

class IndexController extends Controller{
	
	public function __construct(){
		$this->middleware('auth');
	}

	public function index(MenuRepository $MenuRepository){
		$menu_list = $MenuRepository->scopeQuery(function($query){
			return $query->where('status', 1);
		})->orderBy('order', 'asc')->all(); 

		$data = $this->combineMenus($menu_list);

		return view('Index.index', ['menus' => $data]);
	}

	protected function combineMenus ($data, $id = 0) {
		$result = [];

		foreach ($data as $key => $value) {
			if($value->parent_id == $id) {
				unset($data[$key]);
				
				if(!empty($data)) {
					$child = $this->combineMenus($data, $value->id);

					if(!empty($child)) {
						$value['_child'] = $child;
					}
				}

				$result[] = $value;
			}
		}

		return $result;
	}

	public function welcome(){
		return view('Index.welcome');
	}
	
	public function wait(){
		return view('Index.wait');
	}
}


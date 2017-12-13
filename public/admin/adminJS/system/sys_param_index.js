(function (window) {	

	var load_opts = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};

	var Paging = null;
	
	var submit_flag = false;
	
	var line_arr = [10,15];
	
	var page_objs = [];

	var main_addr = '/system/sys_param';
	
	/* 域变量容器 */
	var scope_vars = [];
	
	var form_validate = $(".data-form").validate();

	//detepicker
	   if($.isFunction($.fn.datepicker))
		{
			$(".datepicker").each(function(i, el)
			{
				var $this = $(el),
					opts = {
						format: attrDefault($this, 'format', 'yyyy-mm-dd'),
						autoclose: attrDefault($this, 'autoclose', true),
						startDate: attrDefault($this, 'startDate', ''),
						endDate: attrDefault($this, 'endDate', ''),
						daysOfWeekDisabled: attrDefault($this, 'disabledDays', ''),
						startView: attrDefault($this, 'startView', 0),
						//rtl: rtl()
					},
					$n = $this.next(),
					$p = $this.prev();
								
				$this.datepicker(opts);
				
				if($n.is('.input-group-addon') && $n.has('a'))
				{
					$n.on('click', function(ev)
					{
						ev.preventDefault();
						
						$this.datepicker('show');
					});
				}
				
				if($p.is('.input-group-addon') && $p.has('a'))
				{
					$p.on('click', function(ev)
					{
						ev.preventDefault();
						
						$this.datepicker('show');
					});
				}
			});
		}
	
	/* 解析模板标签 */
	function analysis_template (data, base_template) {
		
		var regx = null;
		
		for (var k in data) {
			regx = new RegExp('\\{'+k+'\\}', 'gi');
			base_template = base_template.replace(regx, data[k]);
		}
		
		regx = new RegExp('\\{[^\\}]*\\}', 'gi');
		base_template = base_template.replace(regx, '-');
		
		return base_template;
	}
	
	function get_list (currentpage, currentline, follow) {
		
		page_objs['index'] = currentpage;
		page_objs['line']  = currentline;
		
		var template_str = $('#table_template').html();
		
		var search = $('#form-search').serialize();
		
		if (submit_flag === false) {
			
			submit_flag = true;
			
			if (follow !== undefined) {
				if (follow === true){
					if (Paging.GetCurrentPage() == Paging.GetTotalPages() && Paging.DataPagecontrol.GetDataSource().length - 1 == Paging.GetPageLines()) { 
						currentpage = Paging.GetTotalPages()+1;
					} else if (Paging.DataPagecontrol.GetDataSource().length - 1 == Paging.GetPageLines()) {
						currentpage = Paging.GetTotalPages();
					}
				}
			}
			
			$.ajax({
				url     :main_addr+'?page='+currentpage+'&limit='+currentline,
				type    :'get',
				data    :search,
				dataType:'json',
				success:function (result) {
					
					$('#datatable').find('tbody').html('');
					$('ul.pagination').html('');
					
					var datas = result['data'];
					var count = datas.length;
										
					
					if (count > 0) {
						for (var i = 0; i < count; i++) {
							$('#datatable').find('tbody').append(analysis_template(datas[i], template_str));
						}
						
						$('button.btn-detail').on('click', function () {
						
							pop_modal.call(this, 'edit-detail','','view');
							get_data($(this).attr('dataid'));
						});

						$('button.btn-edit').on('click', function () {
						
							pop_modal.call(this, 'edit-detail','','edit');
							get_data($(this).attr('dataid'));
						});
						
						$('button.btn-delete').on('click', function () {
							pop_modal.call(this, 'delete-info', 'fade');
							console.log($('button.btn-delete-sure'));
							$('button.btn-delete-sure').prop('_id', $(this).attr('dataid'));
						});												
					}
					
					Paging.SetCurrentPage(currentpage);
					Paging.SetPageLines(currentline);
					Paging.DataPagecontrol.SetDataTotal(result['total']);
					Paging.DataPagecontrol.SetDataSource([''].concat(result['datas']));
					Paging.DataPagecontrol.Refresh();
					
					submit_flag = false;
					
				}
			});
		}
		
	}
		
	/* 获得详情数据 */
	function get_data (dataid) {
		
		if (submit_flag === false) {
			
			submit_flag = true;
			
			var modal_pop = $('#edit-detail');
			
			$.ajax({
				url     :main_addr+'/'+dataid,
				type    :'get',
				dataType:'json',
				success:function (result) {
					var data = result['data'];
					for(var k in data){
						modal_pop.find('*[name='+k+']').val(data[k]);
						
						if(k == "districts" && data[k].length > 0){
							
							for(var key in data[k]){
								//console.log(result[k][key]['district_id']);
								var node = Tree.getNodesByParam('id',data[k][key])[0];

								Tree.checkNode(node,true);
								Tree.expandNode(node,true,true,true);
							}
						}
					}

					submit_flag = false;
					
				}
			});
			
		}
	}
	
	/* 保存/添加数据 */
	function save_data () {
		console.log(Tree.getCheckedNodes(true));
		if (submit_flag === false) {
			
			if (form_validate.form()) {
				
				submit_flag = true;
			
				var modal_pop = $('#edit-detail');
				
				var data_form = modal_pop.find('.data-form');

				var id = $("#id").val();

				var _url = id>0?'/'+id:'';

				var ids=[];
				for(var k in Tree.getCheckedNodes(true)){
					ids.push(Tree.getCheckedNodes(true)[k]['id']);
				}
				
				var data = data_form.serializeArray();
				data.push({'name':'districts_ids','value':ids.join(',')});
				//console.log(data);
				
				$.ajax({
					url :main_addr+_url,
					type: (id>0?'put':'post'),
					data: data,
					dataType: 'json',
					success:function (result) {
						
						if (result['error'] == undefined) {
							
							toastr.success(result.message, "", load_opts);

							modal_pop.modal('hide');
							
							submit_flag = false;
							
							get_list(page_objs['index'], page_objs['line'], (data_form.find('input[name="id"]').val()==0?true:false));
							
						} else {
							toastr.error(result.message, "", load_opts);
						}
						
					},
					error:function () {
						toastr.error('Save Data Fail, Please Try Again!', "", load_opts);
					},
					complete:function () {
						submit_flag = false;
					}
				});
				
			}else{
				toastr.error('请填写完整信息', "", load_opts);
			}
		}
	}
	
	/* 删除数据 */
	function del_data () {
		if (submit_flag === false) {
			
			submit_flag = true;
			
			load_opts['timeOut'] = 5000;
			
			var modal_tips = $('#delete-info');
			
			$.ajax({
				
				url : main_addr+'/'+$('button.btn-delete-sure').prop('_id'),
				type: 'delete',
				dataType: 'json',
				success:function (result) {
					
					if (result['deleted'] == true) {
						load_opts['timeOut'] = 2000;
						toastr.success(result.message, "", load_opts);
						
						modal_tips.modal('hide');
						
						submit_flag = false;
						
						$('button.information-delete-btn').prop('_id', null);
						
						if (Paging.GetTotalPages() > 1 && Paging.GetCurrentPage() == Paging.GetTotalPages() && Paging.DataPagecontrol.GetDataSource().length - 2 == 0) {
							--page_objs['index'];
						}
						get_list(page_objs['index'], page_objs['line']);
						
					} else {
						toastr.error('Delete Data Fail, Please Try Again!', "", load_opts);
					}
					
				},
				error:function () {
					toastr.error('Delete Data Fail, Please Try Again!', "", load_opts);
				},
				complete:function () {
					submit_flag = false;
				}
			});
				
		}
	}
	
	

	/* 设置分页 */
	function set_page () {
		Paging = new AiTPagecontrol('AiGrid').RegisterService();
		Paging.SetPageControlMode(1);
		
		//设置数据显示长度
		Paging.SetPageLineArr(line_arr);
		Paging.SetPageLines(line_arr[0]);
		//设置页数显示长度
		Paging.SetTotalPagesLength(5);
		//设置点击分页回调函数
		Paging.DataPagecontrol.OnPageChange(function (_pageindex, _pageline) {
			get_list(_pageindex, _pageline);
		});
		
		//设置切换显示数回调函数
		Paging.DataPagecontrol.OnPageLinesChange(function (_pageline, _pageindex) {
			get_list(_pageindex, _pageline);
		});
		//分页样式
		Paging.SetPageStyle({
			'tagType':'span',
			'first':'<<',
			'prev':'<',
			'next':'>',
			'last':'>>',

		});
		
	}
	
	/* 弹出POP */
	function pop_modal (pop_id, backdrop,action) {
		
		if (backdrop === undefined) {
			backdrop = 'static';
		}
		
		if ($('#modal_container').find('#'+pop_id).get(0) === undefined && $('#modal_container').append($('#'+pop_id)))
		
		var modal_pop = $('#'+pop_id);
		
		if(action == "view"){
			var title = "详情";
			modal_pop.find('*[name]').attr('disabled',true);
			modal_pop.find('.btn-save').hide();
		}else{
			var title = "修改";
			modal_pop.find('*[name]').attr('disabled',false);
			modal_pop.find('.btn-save').show();
		}

		modal_pop.find('.modal-title').text(title);
				
		modal_pop.modal({'backdrop': backdrop, 'keyboard':false});

		modal_pop.find('input[type="text"][name],input[type="hidden"][name="id"],textarea[name]').val('');
		
		modal_pop.find('select[name]').each(function () {
			this.selectedIndex = 0;
		});

		Tree.checkAllNodes(false);
		//Tree.expandAll(false);

		modal_pop.on('hidden.bs.modal', function () {
			form_validate.resetForm();
			form_validate.reset();
			submit_flag = false;
			$(this).off('hidden.bs.modal');
			Tree.expandAll(false);
		});
		
	}
	
	/*绑定查找事件*/
	$('.search-btn').click(function(){
		get_list(1,Paging.GetPageLines());
	});
	
	
	$('#information-pop').on('shown.bs.modal', function () {
		// if (map === null) {
			// initMap();
		// } else {
			// mapLocation();
		// }
		
		if (editor === null) {
			initUeditor();
		}

	});
	
	$('#information-pop').on('hide.bs.modal', function () {
		if (editor !== null) {
			editor.destroy();
			editor = null;
		}
				
	});

	//$('#welfare-pop .close').click( function () {
		
		//if (editor !== null) {
			//editor.destroy();
			//editor = null;
		//}
				
	//});
	
	$('button.btn-add').on('click', function () {
		pop_modal.call(this, 'edit-detail');
	});
	
	$('#information-pop').find('select[name="type"]').change(function(){
		var type = $(this).val();
		
		if(type==1){
			$('#information-pop').find('.link').show();
			$('#information-pop').find('.contents').hide();
		}else{
			$('#information-pop').find('.link').hide();
			$('#information-pop').find('.contents').show();
		}
	});

	$('button.btn-delete-sure').on('click', function () {
		del_data();
	});
	
	$('button.btn-save').on('click', function () {
		
		save_data();
	});
	
	set_page();
	
	get_list(1, Paging.GetPageLines());
		
})(window);
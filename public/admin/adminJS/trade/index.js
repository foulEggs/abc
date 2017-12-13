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

	var main_addr = '/trades/';
	
	/* 域变量容器 */
	var scope_vars = [];
	
	//var form_validate = $(".data-form").validate();

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
								
				var input_name = $this.context.name;
						
				$this.datepicker(opts).on('changeDate', function(e) {
					
					if(input_name.split('-')[0] == 'start') {

						var start_val = $('.datepicker[name="'+input_name+'"]').val();
						var end_val = $('.datepicker[name="end-'+input_name.split('-')[1]+'"]').val();
						
						if(end_val != "" && (start_val > end_val)) {
							$('.datepicker[name="'+input_name+'"]').val("");
							toastr.error('开始时间不能大于结束时间', "", load_opts);
						}
					} else {
						var end_val = $('.datepicker[name="'+input_name+'"]').val();
						var start_val = $('.datepicker[name="start-'+input_name.split('-')[1]+'"]').val();
						if(start_val != "" && start_val > end_val) {
							$('.datepicker[name="'+input_name+'"]').val("");
							toastr.error('开始时间不能大于结束时间', "", load_opts);
						}
					}
				});
				
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
		
		var modal = $('#form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if((start_time && !end_time) || (!start_time && end_time)){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}

		var form_search = modal.serialize();

		var search_arr = form_search.split('&');

		var search = [], searchFields = [], date_search = "";

		for(var key in search_arr){
			var item = search_arr[key].split('=');
			console.log(modal.find("*[name="+item[0]+"]").attr('condition'));

			if(item[1]){

				var condition = modal.find("*[name="+item[0]+"]").attr('condition');

				var table = modal.find("*[name="+item[0]+"]").attr('table');
				if( table !== undefined ) item[0] = table+"."+item[0];

				if(condition == "start"){
					var _item = item[0].split('-');
					date_search = _item[1]+":"+item[1]+","; 
				} else if (condition == "end") {
					var _item = item[0].split('-');
					date_search += date_search == ""  ? _item[1]+":,"+item[1] : item[1]; 
				} else {
					search.push(item[0]+':'+item[1]);
					searchFields.push(item[0]+':=');
				}
			}
		}
		console.log(date_search)
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
				url     :main_addr
				+'detail_index?page='+currentpage
				+'&limit='+currentline
				+'&search='+search.join(';')
				+'&searchFields='+searchFields.join(';')
				+'&date_search='+date_search
				+'&searchJoin=and',
				type    :'get',
				dataType:'json',
				success:function (result) {
					
					$('#datatable').find('tbody').html('');
					$('ul.pagination').html('');
					
					var datas = result['datas'];
					var count = datas.length;
										
					
					if (count > 0) {
						for (var i = 0; i < count; i++) {
							$('#datatable').find('tbody').append(analysis_template(datas[i], template_str));
						}
						
						$('button.view-detail').on('click', function () {
						
							pop_modal.call(this, 'view-detail');
							get_data($(this).attr('dataid'));
						});
						
						$('button.information-delete').on('click', function () {
							pop_modal.call(this, 'information-tips', 'fade');
							$('button.information-delete-btn').prop('_id', $(this).attr('dataid'));
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
			
			var modal_pop = $('#view-detail');
			
			modal_pop.find('input[type="text"][name]').val('');
			
			modal_pop.find('textarea').val('');
			
			var template_str = $('#detail_table_template').html();

			$.ajax({
				url     :main_addr+'serial_index'+'?search=to_sys_order_num:'+dataid+'&searchFields=to_sys_order_num:=',
				type    :'get',
				dataType:'json',
				success:function (result) {
					$('#detail-datatable').find('tbody').html('');

					for (var i = 0; i < result.length; i++) {
						$('#detail-datatable').find('tbody').append(analysis_template(result[i], template_str));
					}
					
					submit_flag = false;
					
				}
			});
			
		}
	}
	
	/* 保存/添加数据 */
	function save_data () {
		
		if (submit_flag === false) {
			
			if (form_validate.form()) {
				
				submit_flag = true;
			
				var modal_pop = $('#information-pop');
				
				var data_form = modal_pop.find('.data-form');
				
				load_opts['timeOut'] = 5000;
				
				$.ajax({
					url : '/admin/Information/ajax_save?psubmit=true',
					type: (data_form.find('input[name="id"]').val()>0?'put':'post'),
					data: data_form.serialize(),
					dataType: 'json',
					success:function (result) {
						
						if (result['code'] == 200) {
							
							load_opts['timeOut'] = 2000;
							
							toastr.success('Save Success!', "", load_opts);
							
							modal_pop.modal('hide');
							
							submit_flag = false;
							
							get_list(page_objs['index'], page_objs['line'], (data_form.find('input[name="id"]').val()==0?true:false));
							
						} else {
							toastr.error('Save Data Fail, Please Try Again!', "", load_opts);
						}
						
					},
					error:function () {
						toastr.error('Save Data Fail, Please Try Again!', "", load_opts);
					},
					complete:function () {
						submit_flag = false;
					}
				});
				
			}
		}
	}
	
	/* 删除数据 */
	function del_data () {
		if (submit_flag === false) {
			
			submit_flag = true;
			
			load_opts['timeOut'] = 5000;
			
			var modal_tips = $('#information-tips');
			
			$.ajax({
				url : '/admin/Information/ajax_del?psubmit=true',
				type: 'delete',
				data: {'id':$('button.information-delete-btn').prop('_id')},
				dataType: 'json',
				success:function (result) {
					
					if (result['code'] == 200) {
						load_opts['timeOut'] = 2000;
						toastr.success('Delete Success!', "", load_opts);
						
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
	function pop_modal (pop_id, backdrop) {
		
		if (backdrop === undefined) {
			backdrop = 'static';
		}
		
		if ($('#modal_container').find('#'+pop_id).get(0) === undefined && $('#modal_container').append($('#'+pop_id)))
		
		var title = $(this).attr('modal-title');
		
		var describe = $(this).attr('modal-describe');
		
		var modal_pop = $('#'+pop_id);
				
		modal_pop.modal({'backdrop': backdrop, 'keyboard':false});
		
		modal_pop.on('hidden.bs.modal', function () {
			//form_validate.resetForm();
			//form_validate.reset();
			submit_flag = false;
			$(this).off('hidden.bs.modal');
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
	
	$('button.information-add-btn').on('click', function () {
		pop_modal.call(this, 'information-pop');
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

	$('button.information-delete-btn').on('click', function () {
		del_data();
	});
	
	$('button.save-btn').on('click', function () {
		save_data();
	});
	
	set_page();
	
	get_list(1, Paging.GetPageLines());
		
})(window);
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

	var main_addr = '/statement/';
	
	/* 域变量容器 */
	var scope_vars = [];
	
	//var form_validate = $(".data-form").validate();

	//detepicker
	function init_datepicker(time_size){
	   if($.isFunction($.fn.datepicker))
		{
			if (time_size == 'day') {
				var format = 'yyyy-mm-dd';
				var startView = 0;
			} else if (time_size == 'month') {
				var format = 'yyyy-mm';
				var startView = 1;
			} else if (time_size == 'year') {
				var format = 'yyyy';
				var startView = 2;
			}

			$(".datepicker").each(function(i, el)
			{
				
				var $this = $(el),
					opts = {
						format: attrDefault($this, 'format', format),
						autoclose: attrDefault($this, 'autoclose', true),
						startDate: attrDefault($this, 'startDate', ''),
						endDate: attrDefault($this, 'endDate', ''),
						daysOfWeekDisabled: attrDefault($this, 'disabledDays', ''),
						//startView: attrDefault($this, 'startView', 1),
						startView: startView, 
						maxViewMode: startView,
						minViewMode: startView,
						clearBtn: true
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
	}

	init_datepicker('day');
	//console.log($('#form-search').find('select[name="time_size"]'));
	$('#form-search').find('select[name="date_type"]').change(function(){
		var date_type = $(this).val();
		$(".datepicker").each(function(i, el)
		{
			$(el).datepicker('remove');
			$(el).val('');
		});
		init_datepicker(date_type);
	});

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
	
	function get_list () {
		
		var template_str = $('#table_template').html();
		
		var modal = $('#form-search');

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

				if(condition != ''){
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
		}
		
		$.ajax({
			url     :main_addr
			+'trade_statement'
			+'?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&action=preview',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {

				var datatable = "datatable";
				
				$('#'+datatable).find('tbody').html('');
				
				var datas = result;
				var count = datas.length;
									
				
				if (count > 0) {
					for (var i = 0; i < count; i++) {
						$('#'+datatable).find('tbody').append(analysis_template(datas[i], template_str));
					}
															
				}
			}
		});
	}

	function export_list () {
		
		var template_str = $('#table_template').html();
		
		var modal = $('#form-search');

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

				if(condition != ''){
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
		}
		
		var url = main_addr
			+'trade_statement'
			+'?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and&'
			+form_search;
		//console.log(url)
		window.location = url;
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
			get_list(_pageindex, _pageline, 1);
			get_list(_pageindex, _pageline, 2);
		});
		
		//设置切换显示数回调函数
		Paging.DataPagecontrol.OnPageLinesChange(function (_pageline, _pageindex) {
			get_list(_pageindex, _pageline, 1);
			get_list(_pageindex, _pageline, 2);
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
		var modal = $('#form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if(!start_time || !end_time){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}
		
		get_list();
	});

	/*绑定导出事件*/
	$('.export').click(function(){
		var modal = $('#form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if(!start_time || !end_time){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}
		
		export_list();
	});
	
})(window);
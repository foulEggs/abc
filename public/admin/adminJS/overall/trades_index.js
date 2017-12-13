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

	/*echarts instance*/
	var acceptChart,paymentChart,topAcceptChart,topPaymentChart,topDistrictChart;	
	//var form_validate = $(".data-form").validate();

	//detepicker
	   if($.isFunction($.fn.datepicker))
		{
			$(".datepicker").each(function(i, el)
			{
				var $this = $(el),
					opts = {
						format: attrDefault($this, 'format', 'yyyy-mm'),
						autoclose: attrDefault($this, 'autoclose', true),
						startDate: attrDefault($this, 'startDate', ''),
						endDate: attrDefault($this, 'endDate', ''),
						daysOfWeekDisabled: attrDefault($this, 'disabledDays', ''),
						//startView: attrDefault($this, 'startView', 0),
						startView: 1, 
						maxViewMode: 1,
						minViewMode:1,
						//rtl: rtl()
					},
					$n = $this.next(),
					$p = $this.prev();
								
				
				var modal = $this.parent().parent().parent();
				var input_name = $this.context.name;
						
				$this.datepicker(opts).on('changeDate', function(e) {
					
					if(input_name.split('-')[0] == 'start') {

						var start_val = modal.find('.datepicker[name="'+input_name+'"]').val();
						var end_val = modal.find('.datepicker[name="end-'+input_name.split('-')[1]+'"]').val();
						
						if(end_val != "" && (start_val > end_val)) {
							modal.find('.datepicker[name="'+input_name+'"]').val("");
							toastr.error('开始时间不能大于结束时间', "", load_opts);
						}
					} else {
						var end_val = modal.find('.datepicker[name="'+input_name+'"]').val();
						var start_val = modal.find('.datepicker[name="start-'+input_name.split('-')[1]+'"]').val();
						if(start_val != "" && start_val > end_val) {
							modal.find('.datepicker[name="'+input_name+'"]').val("");
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
	
	function get_list () {
		
		var template_str = $('#table_template').html();
		
		if (submit_flag === false) {
			
			submit_flag = true;
			
			$.ajax({
				url     :main_addr+'detail_index?page=1&limit=10&search=&searchFields=',
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
					
					submit_flag = false;
					
				}
			});
		}
		
	}

	function get_total(){
	
			$.ajax({
				url     :main_addr+'overall_total',
				type    :'get',
				data    : {},
				dataType:'json',
				success:function (result) {
					//console.log(result);
					for(var k in result){
						$("#"+k).text(result[k]);
					}
				}
			});
		
	}

	function count_by_accept(){
		var modal = $('#accept-form-search');

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

				if(condition){
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
		console.log(date_search)
		
		$.ajax({
			url     :main_addr
			+'count_orders_by_condition?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&date_type=by_month',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {
				
				acceptChart.setOption({
                    xAxis: {
                        data: result['date'],
                        name : "日期"
                    },
                    yAxis: [{
				        splitLine: {show: false},
				        name : "单位："+(modal.find("select[name='type']").val() == "count" ? "笔" : "元")
				    }],
                    series: [{
				        type: 'line',
				        name: modal.find("select[name='type']").val() == "count" ? '交易笔数' : '交易金额',
				        showSymbol: false,
				        lineStyle: {normal:{
				        	color: "#2A3F54"
				        }},
				        data: result['count']
				    }]
                });

			}
		});
	}

	function sort_rate_by_accept(){
		var modal = $('#accept-form-search');

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

				if(condition){
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
		console.log(date_search)
		
		$.ajax({
			url     :main_addr
			+'sort_rate_by_accept?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&date_type=by_month'
			+'&top_num=4&name_field=trade_channel_name&group_by_field=trade_channel',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {
				
				topAcceptChart.setOption(set_pie_charts_option("单位："+(modal.find("select[name='type']").val() == "count" ? "笔" : "元"), result.name, result.data));

			}
		});
	}

	function sort_rate_by_payment(){
		var modal = $('#payment-form-search');

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

				if(condition){
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
		console.log(date_search)
		
		$.ajax({
			url     :main_addr
			+'sort_rate_by_payment?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&date_type=by_month'
			+'&top_num=4&name_field=payment_channel_name&group_by_field=payment_channel',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {
				
				topPaymentChart.setOption(set_pie_charts_option("单位："+(modal.find("select[name='type']").val() == "count" ? "笔" : "元"), result.name, result.data));

			}
		});
	}

	function sort_rate_by_district(){
		var modal = $('#district-form-search');

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

				if(condition){
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
		console.log(date_search)
		
		$.ajax({
			url     :main_addr
			+'sort_rate_by_district?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&date_type=by_month'
			+'&top_num=5&name_field=city_name&group_by_field=city'
			+'&type=count',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {
				
				//topDistrictChart.setOption(set_pie_charts_option("单位：元", result.name, result.data));
				topDistrictChart.setOption({
					title : {
				        subtext: "单位：元",
				        x:'right'
				    },
				    tooltip : {
				        trigger: 'item',
				        formatter: "{a} <br/>{b} : {c} ({d}%)"
				    },
				    legend: {
				        orient: 'vertical',
				        left: 'left',
				        data: result.name
				    },
				    series : [
					        {
					            type: 'pie',
					            radius : '50%',
					            center: ['50%', '30%'],
					            data:result.data,
					            itemStyle: {
					                emphasis: {
					                    shadowBlur: 10,
					                    shadowOffsetX: 0,
					                    shadowColor: 'rgba(0, 0, 0, 0.5)'
					                },
					                normal:{ 
					                    label:{ 
					                        show: true, 
					                        formatter: '{b} : {c} ({d}%)' 
					                    }, 
					                    labelLine :{show:true} 
					                }
					            },
					            label:{normal:{
					                show:true,
					               
					            }}
					        }
					    ]
				});

			}
		});
	}

	function count_by_payment(){
		var modal = $('#payment-form-search');

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

				if(condition){
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
		console.log(date_search)
		
		$.ajax({
			url     :main_addr
			+'count_orderserial_by_condition?search='+search.join(';')
			+'&searchFields='+searchFields.join(';')
			+'&date_search='+date_search
			+'&searchJoin=and'
			+'&date_type=by_month',
			type    :'get',
			data    :form_search,
			dataType:'json',
			success:function (result) {
				
				paymentChart.setOption({
                    xAxis: {
                        data: result['date'],
                        name : "日期"
                    },
                    yAxis: [{
				        splitLine: {show: false},
				        name : "单位："+(modal.find("select[name='type']").val() == "count" ? "笔" : "元")
				    }],
                    series: [{
				        type: 'line',
				        name: modal.find("select[name='type']").val() == "count" ? '交易笔数' : '交易金额',
				        showSymbol: false,
				        lineStyle: {normal:{
				        	color: "#2A3F54"
				        }},
				        data: result['count']
				    }]
                });
			}
		});		
	}
	

	var _interval = setInterval(function(){
		get_total();
		get_list();
	},10000);
	
	localStorage.setItem("interval", _interval);
	console.log(localStorage.getItem("interval"));
	
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

	function init_charts(){
		acceptChart = echarts.init(document.getElementById('accept-main'));
	
	    // 指定图表的配置项和数据
	    acceptChart.setOption(set_line_charts_option('受理渠道统计'));

	    paymentChart = echarts.init(document.getElementById('payment-main'));
	
	    // 指定图表的配置项和数据
	    paymentChart.setOption(set_line_charts_option('支付渠道统计'));

	    topAcceptChart = echarts.init(document.getElementById('topAccept-main'));

	    //topAcceptChart.setOption(set_pie_charts_option('',[],[]));

	    topPaymentChart = echarts.init(document.getElementById('topPayment-main'));

	    //topPaymentChart.setOption(set_pie_charts_option('',[],[]));

	    topDistrictChart = echarts.init(document.getElementById('topDistrict-main'));

	    //topDistrictChart.setOption(set_pie_charts_option('',[],[]));
	}

	function set_line_charts_option(text){
		var option = {

		    // Make gradient line here
		    visualMap: [{
		        show: false,
		        type: 'continuous',
		        seriesIndex: 0,
		        min: 0,
		        max: 400
		    }],


		    title: [{
		        left: 'center',
		        text: text
		    }],
		    tooltip: {
		        trigger: 'axis'
		    },
		    xAxis: [{
		        data: []
		    }],
		    yAxis: [{
		        splitLine: {show: false}
		    }],
		    
		    series: [{
		        type: 'line',
		        showSymbol: false,
		        data: []
		    }]
		};

		return option;
	}

	function set_pie_charts_option(subtext, legend_data, series_data){
		var option = {
				title : {
			        subtext: subtext,
			        x:'right'
			    },
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        orient: 'vertical',
			        bottom: 'bottom',
			        data: legend_data
			    },
			    series : [
			        {
			            type: 'pie',
			            radius : '70%',
			            center: ['50%', '45%'],
			            data:series_data,
			            itemStyle: {
			                emphasis: {
			                    shadowBlur: 10,
			                    shadowOffsetX: 0,
			                    shadowColor: 'rgba(0, 0, 0, 0.5)'
			                },
			                normal:{ 
			                    label:{ 
			                        show: true, 
			                        formatter: '{b} : {c} ({d}%)' 
			                    }, 
			                    labelLine :{show:true} 
			                }
			            },
			            label:{normal:{
			                show:true,
			               
			            }}
			        }
			    ]
			};

		return option;
	}
	
	$("#accept-form-search").find('.search-btn').click(function(){
		var modal = $('#accept-form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if((start_time && !end_time) || (!start_time && end_time)){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}

		count_by_accept();
		sort_rate_by_accept();
	});

	$("#payment-form-search").find('.search-btn').click(function(){
		var modal = $('#payment-form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if((start_time && !end_time) || (!start_time && end_time)){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}

		count_by_payment();
		sort_rate_by_payment();
	});

	$("#district-form-search").find('.search-btn').click(function(){
		var modal = $('#district-form-search');

		var start_time = modal.find('input[name="start-create_time"]').val();

		var end_time = modal.find('input[name="end-create_time"]').val();
		
		if((start_time && !end_time) || (!start_time && end_time)){
			toastr.error('请同时填写时间区间', "", load_opts);
			return false;
		}

		sort_rate_by_district();
	});

	function init_page(){
		init_charts();//初始化图表

		count_by_accept();

		sort_rate_by_accept();

		sort_rate_by_payment();

		sort_rate_by_district();

		count_by_payment();

		get_list();

		get_total();
	}

	init_page();

})(window);
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
	
	

	function get_list () {
		
		var modal = $('#form-search');

		var start_time = modal.find('input[name="start-charge_time"]').val();

		var end_time = modal.find('input[name="end-charge_time"]').val();
		
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
		submit_flag = false;
		if (submit_flag === false) {
			
			submit_flag = true;
			
			$.ajax({
				url     :main_addr
				+'count_orderserial_by_condition?search='+search.join(';')
				+'&searchFields='+searchFields.join(';')
				+'&date_search='+date_search
				+'&searchJoin=and',
				type    :'get',
				data    :form_search,
				dataType:'json',
				success:function (result) {
					
					myChart.setOption({
                        xAxis: {
                            data: result['date'],
                            name : "日期"
                        },
                        yAxis: [{
					        splitLine: {show: false},
					        name : "单位："+($("select[name='type']").val() == "count" ? "笔" : "元")
					    }],
                        series: [{
					        type: 'line',
					        name: $("select[name='type']").val() == "count" ? '交易笔数' : '交易金额',
					        showSymbol: false,
					        lineStyle: {normal:{
					        	color: "#2A3F54"
					        }},
					        data: result['count']
					    }]
                    });

					submit_flag = false;
					
				}
			});
		}
		
	}
		
	var myChart = echarts.init(document.getElementById('main'));
	console.log(myChart);
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
	        text: '按受理渠道统计'
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
	
    // 指定图表的配置项和数据
    myChart.setOption(option);
	/*绑定查找事件*/
	$('.search-btn').click(function(){

		get_list();
	});
	
	get_list();
		
})(window);
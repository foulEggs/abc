
		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>结算报表<small>可选时间内全部结算</small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
				  
				  <!-- search start -->
					<form id="form-search" class="x_panelNew">
					
					<div class="btn-group focus-btn-group">
						<label for="date_type">时间粒度
						  <select name="date_type" class="form-control form-controlNew" condition="">
							<option value='day'>日</option>
							<option value='month'>月</option>
						  </select>
						</label>
					</div>

					<div class="btn-group focus-btn-group" style="width:215px;">
						
					<label for="start_time">开始时间
						<input type="text" class="form-control datepicker form-controlNew" name="start-charge_time" placeholder="" condition="start">
					</label>
						
					  </div>
					<div class="btn-group focus-btn-group" style="width:215px;">
					<label for="start_time">结束时间
						<input type="text" class="form-control datepicker form-controlNew" name="end-charge_time" placeholder="" condition="end">
					</label>
					</div>

					<div class="btn-group focus-btn-group">
						<label for="clear_type">结算方
						  <select name="clear_type" class="form-control form-controlNew" condition="=">
						  	@foreach($clear_type as $k=>$v)
							<option value='{{$k}}'>{{$v}}</option>
							@endforeach
						  </select>
						</label>
					</div>

					<div class="btn-group focus-btn-group district">
						<label for="city">城市
							  	<select name="city" class="form-control form-controlNew" condition="">
																			
								</select>
						</label>
						
					</div>
					<div class="btn-group focus-btn-group district">
						<label for="district">区、县
							  	<select name="district" class="form-control form-controlNew" condition="">
									<option value="">请选择</option>
																			
								</select>
						</label>
						
					</div>
					<div class="btn-group focus-btn-group district">
						<label for="team">营业厅
							  	<select name="team" class="form-control form-controlNew" condition="">
									<option value="">请选择</option>
																			
								</select>
						</label>
						
					</div>

					<div class="btn-group focus-btn-group salesman">
						<label for="salesman">收费员
							  	<select name="salesman" class="form-control form-controlNew" condition="">
									<option value="">请选择</option>
									@foreach($salesman as $v)
									<option value="{{$v->id}}">{{$v->username}}</option>
									@endforeach										
								</select>
						</label>
						
					</div>

					<div class="btn-group focus-btn-group">
						<label for="fullname">预览
						<button type="button" class="form-control btn btn-primary btn-xs search-btn"><i class="fa fa-eye"></i></button>
						</label>
					</div>
					<div class="btn-group focus-btn-group">
						<label for="fullname">导出
						<button type="button" class="form-control btn btn-primary btn-xs export"><i class="fa fa-file-excel-o"></i></button>
						</label>
					</div>
					</form>
					<!-- search end -->
                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>日期</th>
                          <th>结算笔数</th>
                          <th>结算金额（元）</th>
						  <!-- <th>结算流水</th> -->
                        </tr>
                      </thead>
						<!-- ajax加载 -->
                      <tbody>
						
                      </tbody>
                    </table>
					<div id="AiGrid"><!-- 分页插件 --></div>
					
					<!-- 外部结算列表HTML模板 -->
					<script type="text/template" id="table_template">
					<tr>
						<td>{date}</td>
						<td>{clear_count}</td>
						<td>{clear_money}</td>
						<!-- <td>
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs view-detail">查看流水</button>
						</td> -->
					</tr>
					</script>
                  </div>				
                </div>
              </div>
            </div>
<script>
	var city = <?php echo $city; ?>,
	district = <?php echo $district; ?>,
	team = <?php echo $team; ?>
	
	var modal = $('#form-search');
	console.log(city);console.log(district);console.log(team);
	var city_opt = "";
	
	for(var k in city){
		city_opt+="<option value='"+k+"'>"+city[k]['name']+"</option>";
	}
	
	modal.find('select[name="city"]').html(city_opt);
	
	modal.find('select[name="city"]').change(function(){
		
		var city = $(this).val();
		
		var district_opt = "<option value=''>请选择</option>";
		
		for(var k in district){
			if(district[k]['parent_id'] == city){
				district_opt+="<option value='"+k+"'>"+district[k]['name']+"</option>";
			}
		}
		
		modal.find('select[name="district"]').html(district_opt);
		modal.find('select[name="team"]').html("<option value=''>请选择</option>");
		
		modal.find('select[name="district"]').change(function(){
			
			var district = $(this).val();
			
			var team_opt = "<option value=''>请选择</option>";
			
			for(var k in team){
				if(team[k]['parent_id'] == district){
					team_opt+="<option value='"+k+"'>"+team[k]['name']+"</option>";
				}
			}
			
			modal.find('select[name="team"]').html(team_opt);
			
		});
	});
	
	modal.find('select[name="city"]').trigger('change');
</script>
<script src="/admin/adminJS/statement/clearing_statement.js"></script>   	

       

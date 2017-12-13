<script src="/admin/adminJS/echarts.js"></script>
		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>按区域统计<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
				  
				  <!-- search start -->
					<form id="form-search" class="x_panelNew">
					<div class="btn-group focus-btn-group">
						<label for="city">城市
							  	<select name="city" class="form-control form-controlNew" condition="=">
									<option value="">请选择</option>
																			
								</select>
						</label>
						
					</div>
					<div class="btn-group focus-btn-group">
						<label for="district">区、县
							  	<select name="district" class="form-control form-controlNew" condition="=">
									<option value="">请选择</option>
																			
								</select>
						</label>
						
					</div>
					<div class="btn-group focus-btn-group">
						<label for="team">营业厅
							  	<select name="team" class="form-control form-controlNew" condition="=">
									<option value="">请选择</option>
																			
								</select>
						</label>
						
					</div>
					
					<div class="btn-group focus-btn-group" style="width:215px;">
						
					<label for="start_time">开始时间
						<input type="text" class="form-control datepicker form-controlNew" name="start-create_time" placeholder="" condition="start">
					</label>
						
					  </div>
					<div class="btn-group focus-btn-group" style="width:215px;">
					<label for="start_time">结束时间
						<input type="text" class="form-control datepicker form-controlNew" name="end-create_time" placeholder="" condition="end">
					</label>
					</div>
					<div class="btn-group focus-btn-group">
						<label for="type">统计类型
						  <select name="type" class="form-control form-controlNew" condition="">
							<option value='count'>交易笔数</option>
							
							<option value='sum'>交易金额</option>
							
						  </select>
						</label>
					</div>
					
					<div class="btn-group focus-btn-group">
						<label for="fullname">查找
						<button type="button" class="form-control btn btn-primary btn-sm search-btn"><i class="fa fa-search"></i></button>
						</label>
					</div>
					</form>
					<!-- search end -->
                  <div class="x_content">
                    <div id="main" style="width:100%;height:600px;"></div>
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
	var city_opt = "<option value=''>请选择</option>";
	
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
<script src="/admin/adminJS/trade/count_by_district.js"></script>   	

       

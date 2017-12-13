<link href="/admin/vendors/searchableSelect/jquery.searchableSelect.css" rel="stylesheet">
			<div class="row">
			
            </div>
               <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>收费员管理</h2>
					
                    <div class="clearfix"></div>
					<button type="button" class="btn btn-success btn-xs btn-add">添加</button>
                  </div>
				  
				  <!-- search start -->
				  <!--
					<form id="form-search">
					<div class="btn-group focus-btn-group">
						<label for="sys_order_num">订单号
							  <input type="text" id="sys_order_num" class="form-control form-controlNew" name="filter[sys_order_num]"/>
						</label>
						
					</div>
					
					<div class="btn-group focus-btn-group">
						<label for="fullname">查找
						<button type="button" class="form-control form-controlNew btn btn-primary btn-sm search-btn"><i class="fa fa-search"></i></button>
						</label>
					</div>
					</form>-->
					<!-- search end -->
                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>收费员名称</th>
                          <th>终端ID</th>
                          <th>收费员账号</th>
                          <th>归属地</th>
						  <th>类型</th>
                          <th>结算费率</th>
						  <th>创建时间</th>						  
						  <th>操作</th>
                        </tr>
                      </thead>
						<!-- ajax加载 -->
                      <tbody>
						
                      </tbody>
                    </table>
					<div id="AiGrid"><!-- 分页插件 --></div>
					
					<!-- 订单列表HTML模板 -->
					<script type="text/template" id="table_template">
					<tr>
						<td>{id}</td>
						<td>{name}</td>
						<td>{terminal_key}</td>
						<td>{username}</td>
						<td>{city_name}/{district_name}/{team_name}</td>
						<td>{charge_staff_type_str}</td>
						<td>{clear_money_rate}</td>
						<td>{created_at_str}</td>											
						<td>
							
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs btn-detail"><i class="fa fa-folder">详情</i></button>							
							<button type="button" dataid="{id}" class="btn btn-info btn-xs btn-edit"><i class="fa fa-pencil">修改</i></button>
							<button type="button" dataid="{id}" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o">删除</i></button>
						</td>
					</tr>
					</script>
                  </div>
				  
				  <!-- 删除 modal -->
				  <div id="delete-info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog"  style="width:30%">
						<div class="modal-content">
													
							<div class="modal-body">
								确定要删除该信息么？
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default antoclose2" data-dismiss="modal">取消</button>
								<button type="button" _id="" class="btn btn-primary btn-delete-sure">确定</button>
							 </div>
							 
						</div>
					</div>
				  </div>
				  
				  <!-- 修改详情 modal -->
				  <div id="edit-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog"  style="width:30%">
					<div class="modal-content">

					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel2"></h4>
					  </div>
					  <div class="modal-body">
						
					
						<div id="testmodal2" style="padding: 5px 20px;">
							<input type="hidden" class="form-control form-controlNew" id="id" name="id" value="">
							
							<form id="antoform2" class="form-horizontal data-form" role="form">
							<!-- CSRF TOKEN -->
							
							<div class="form-group">
							  
								<div class="col-sm-6">
									<label class="control-label">收费员名称<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="name" data-rule-required="true" data-msg-required="请填写名称">
								
								</div>
								<div class="col-sm-6">
									<label class="control-label">终端<span class="required">*</span></label>
									<select name="terminal_id" class="form-control form-controlNew">
										@foreach($terminal_list as $vo)
										<option value='{{$vo->id}}'>{{$vo->terminal_key}}</option>
										@endforeach
									  </select>
								</div>
							 
							</div>
							
							<div class="form-group">
							  
								<div class="col-sm-6">
									<label class="control-label">收费员账号<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="username" data-rule-required="true" data-msg-required="请填写账号">

								</div>
								<div class="col-sm-6">
									<label class="control-label">收费员密码</label>
									<input type="text" class="form-control form-controlNew" name="pwd" placeholder="">

								</div>								
							 
							</div>
							
							<div class="form-group qrcode">
							
								<div class="col-sm-6">
									<label class="control-label">二维码</label>
									<img src="">
								</div>
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-4">
									<label class="control-label">归属市</label>
									<select name="city" class="form-control form-controlNew">
										<option value="">请选择</option>
																				
									</select>
									<input type="hidden" class="form-control form-controlNew" name="city_name" value="">
								</div>
								<div class="col-sm-4">
									<label class="control-label">归属区县</label>
									<select name="district" class="form-control form-controlNew">
										<option value="">请选择</option>
																				
									</select>
									<input type="hidden" class="form-control form-controlNew" name="district_name" value="">
								</div>
								<div class="col-sm-4">
									<label class="control-label">归属营业厅</label>
									<select name="team" class="form-control form-controlNew">
										<option value="">请选择</option>
																				
									</select>
									<input type="hidden" class="form-control form-controlNew" name="team_name" value="">
								</div>
								
							</div>
							<!--
							<div class="form-group">
								<div class="col-sm-4">
									<label class="control-label">开始时间</label>
									<input type="text" class="form-control form-controlNew datepicker" name="start_time" placeholder="">
								</div>
								<div class="col-sm-4">
									<label class="control-label">结束时间</label>
									<input type="text" class="form-control form-controlNew datepicker" name="end_time" placeholder="">
								</div>
							</div>-->
							
							<div class="form-group">
							
								<div class="col-sm-4">
									<label class="control-label">结算费率</label>
									<input type="text" class="form-control form-controlNew" name="clear_money_rate" placeholder="">

								</div>
								
								<div class="col-sm-4">
									<label class="control-label">收费员类型<span class="required">*</span></label>
									<select name="charge_staff_type" class="form-control form-controlNew">
										@foreach($staff_type_list as $k=>$vo)
										<option value='{{$k}}'>{{$vo}}</option>
										@endforeach
									  </select>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">状态</label>
									<select name="status" class="form-control form-controlNew">
										<option value="1">正常</option>
										<option value="2">休假</option>
										<option value="3">离职</option>										
									</select>
								</div>
								
							</div>

						  </form>
						</div>
						
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default antoclose2" data-dismiss="modal">关闭</button>
						<button type="button" class="btn btn-primary btn-save">保存</button>
					  </div>
					</div>
				  </div>
				</div>				
                </div>
              </div>
            </div>
			
<script>
	var city = <?php echo $city; ?>,
	district = <?php echo $district; ?>,
	team = <?php echo $team; ?>
	
	var modal = $('#edit-detail');
	console.log(city);console.log(district);console.log(team);
	var city_opt = "";
	
	for(var k in city){
		city_opt+="<option value='"+k+"'>"+city[k]['name']+"</option>";
	}
	
	modal.find('select[name="city"]').html(city_opt);
	
	modal.find('select[name="city"]').change(function(){
		
		modal.find('input[name="city_name"]').val($(this).find('option:selected').text());
		
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
			
			modal.find('input[name="district_name"]').val($(this).find('option:selected').text());
			
			var district = $(this).val();
			
			var team_opt = "<option value=''>请选择</option>";
			
			for(var k in team){
				if(team[k]['parent_id'] == district){
					team_opt+="<option value='"+k+"'>"+team[k]['name']+"</option>";
				}
			}
			
			modal.find('select[name="team"]').html(team_opt);
			
			modal.find('select[name="team"]').change(function(){
				modal.find('input[name="team_name"]').val($(this).find('option:selected').text());
			});
		
		});
	});
	
	modal.find('select[name="city"]').trigger('change');
</script>
<script src="/admin/vendors/searchableSelect/jquery.searchableSelect.js"></script>
<script src="/admin/adminJS/system/charge_staffs_index.js"></script> 
		
  	

       

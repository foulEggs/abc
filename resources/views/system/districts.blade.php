
			<div class="row">
			
            </div>
               <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>区域管理</h2>
					
                    <div class="clearfix"></div>
					<button type="button" class="btn btn-success btn-xs btn-add">添加</button>
					<button type="button" parent_id="0" level="1" class="btn btn-info btn-xs btn-back" style="display:none;"><i class="fa fa-arrow-left"></i>返回</button>
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
                          <th>区域ID</th>
                          <th>区域名</th>                        
                          <th>级别</th>
                          <th>结算费率(%)</th>                        
						  <th>创建时间</th>
						  <th>状态</th>
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
						<td>{level}</td>
						<td>{clear_money_rate}</td>						
						<td>{create_time_str}</td>
						<td>{status_str}</td>						
						<td>
							
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs btn-detail"><i class="fa fa-folder">详情</i></button>							
							<button type="button" dataid="{id}" class="btn btn-info btn-xs btn-edit"><i class="fa fa-pencil">修改</i></button>
							<button type="button" dataid="{id}" parent_id="{parent_id}" class="btn btn-warning btn-xs btn-child">子区域<i class="fa fa-arrow-right"></i></button>
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
							<input type="hidden" class="form-control form-controlNew" id='id' name="id" value="">
							<form id="antoform2" class="form-horizontal data-form" role="form">
							<!-- CSRF TOKEN -->
							
							
							
							<input type="hidden" class="form-control form-controlNew" name="parent_id" value="0">
							<input type="hidden" class="form-control form-controlNew" name="level" value="1">
							
							<div class="form-group">
							  
								<div class="col-sm-6">
									<label class="control-label">区域名<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="name" data-rule-required="true" data-msg-required="请填写区域名">
								
								</div>
								
							</div>
							
							<div class="form-group">
							  
								<div class="col-sm-6">
									<label class="control-label">结算费率</label>
									<input type="text" class="form-control form-controlNew" name="clear_money_rate" data-rule-required="true" data-rule-max="100" data-msg-max="请输入小于100的数" data-msg-required="请填写结算费率">

								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-4">
									<label class="control-label">状态</label>
									<select name="status" class="form-control form-controlNew">
										<option value="1">正常</option>
										<option value="2">禁用</option>										
									</select>
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
			

<script src="/admin/adminJS/system/districts_index.js"></script>   	

       

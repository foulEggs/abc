
			<div class="row">
			
            </div>
               <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>终端列表</h2>
					
                    <div class="clearfix"></div>
					<button type="button" class="btn btn-success btn-xs btn-add">添加</button>
                  </div>
				  
				  <!-- search start -->
				  
					<form id="form-search" class="x_panelNew">
					<div class="btn-group focus-btn-group">
						<label for="sys_order_num">状态
							<select name="status" class="form-control form-controlNew">
							<option value="">不限</option>
							@foreach($status_list as $k=>$vo)
								<option value="{{$k}}">{{$vo}}</option>										
							@endforeach										
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
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>终端ID</th>
                          <th>押金</th>                         
                          <th>出库时间</th>
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
						<td>{terminal_key}</td>
						<td>{cash_pledge}</td>
						<td>{delivery_time_str}</td>
						<td>{status_str}</td>
															
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
							<input type="hidden" class="form-control" id="id" name="id" value="">
							
							<form id="antoform2" class="form-horizontal data-form" role="form">
							<!-- CSRF TOKEN -->
							
							<div class="form-group">
							  
								<div class="col-sm-6">
									<label class="control-label">终端ID<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="terminal_key" data-rule-required="true" data-msg-required="请填写终端">
								
								</div>
								<div class="col-sm-6">
									<label class="control-label">押金<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="cash_pledge" data-rule-required="true" data-msg-required="请填写押金">
								
								</div>								
								
							</div>
					
							<!--
							<div class="form-group">
								<div class="col-sm-4">
									<label class="control-label">开始时间</label>
									<input type="text" class="form-control datepicker" name="start_time" placeholder="">
								</div>
								<div class="col-sm-4">
									<label class="control-label">结束时间</label>
									<input type="text" class="form-control datepicker" name="end_time" placeholder="">
								</div>
							</div>-->
							
							<div class="form-group">
							
								<div class="col-sm-6">
									<label class="control-label">出库时间</label>
									<input type="text" class="form-control datepicker form-controlNew" name="delivery_time" placeholder="">
								</div>		
							
								<div class="col-sm-6">
									<label class="control-label">状态</label>
									<select name="status" class="form-control form-controlNew">
									@foreach($status_list as $k=>$vo)
										<option value="{{$k}}">{{$vo}}</option>										
									@endforeach										
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
			

<script src="/admin/adminJS/terminal/index.js"></script>   	

       

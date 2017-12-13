
<link href="/admin/vendors/zTree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet">
<script src="/admin/vendors/zTree/js/jquery.ztree.core.js"></script>
<script src="/admin/vendors/zTree/js/jquery.ztree.excheck.js"></script>
			<div class="row">
			
            </div>
               <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>参数管理</h2>
					
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
                          <th>参数ID</th>
                          <th>参数名</th>
                          <th>参数值</th>
                          <th>地区</th>
                          <th>受理渠道</th>
                          <th>支付渠道</th>
						  <th>BOSS账户类型</th>
						  <th>过期时间</th>
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
						<td>{value}</td>
						<td>{districts_ids_str}</td>
						<td>{accept_channel_str}</td>
						<td>{payment_channel_str}</td>
						<td>{BOSS_account}</td>
						<td>{delay_time_str}</td>
						<td><button type="button" dataid="{id}" class="btn {btn} btn-xs">{btn_name}</button></td>
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
							  
								<div class="col-sm-4">
									<label class="control-label">参数ID<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="key" data-rule-required="true" data-msg-required="请填写ID">
								
								</div>
								<div class="col-sm-4">
									<label class="control-label">参数名称<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="name" data-rule-required="true" data-msg-required="请填写标识">
								
								</div>
								<div class="col-sm-4">
									<label class="control-label">参数值<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="value" data-rule-required="true" data-msg-required="请填写值">
								
								</div>
							 
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-6">
									<label class="control-label">受理渠道</label>
									<select name="accept_channel" class="form-control form-controlNew">
										<option value="0">ALL</option>
										@foreach($accept_channel as $vo)
											<option value="{{$vo->id}}">{{$vo->name}}</option>
										@endforeach									
									</select>
								</div>
								<div class="col-sm-6">
									<label class="control-label">支付渠道</label>
									<select name="payment_channel" class="form-control form-controlNew">
										<option value="0">ALL</option>
										@foreach($payment_channel as $vo)
											<option value="{{$vo->id}}">{{$vo->name}}</option>
										@endforeach									
									</select>
								</div>
								
							</div>
							
							<div class="form-group">
								
								<div class="col-sm-12">
									<label class="control-label">适用区域</label>
									<ul id="treeDemo" class="ztree"></ul>
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
									<label class="control-label">BOSS账户类型<span class="required">*</span></label>
									<input type="text" class="form-control form-controlNew" name="BOSS_account" data-rule-required="true" data-msg-required="请填写BOSS账户类型">
								
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">过期时间<span class="required">*</span></label>
									<input type="text" class="form-control datepicker form-controlNew" name="delay_time" data-rule-required="true" data-msg-required="请选择过期时间">
								
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">状态</label>
									<select name="status" class="form-control form-controlNew">
										<option value="1">正常</option>
										<option value="2">禁用</option>										
									</select>
								</div>
								<!--
								<div class="col-sm-4">
									<label class="control-label">是否流水号唯一</label>
									<select name="only_serial" class="form-control form-controlNew">
										<option value="1">是</option>
										<option value="2">否</option>										
									</select>
								</div>-->
								
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

	var Tree = null;
	
	var setting = {
		check: {
			enable: true
		},
		data: {
			simpleData: {
				enable: true,
				pIdKey: "parent_id",
			}
		},
		chkboxType:{
			"Y":'ps',
			"N":'ps'
		}
	};
	
	var zNodes = <?php echo $districts;?>;
	
	for(var k in zNodes){
		zNodes[k]['icon'] = "/admin/vendors/zTree/css/zTreeStyle/img/diy/1_close.png";
	}
	
	$(document).ready(function(){
		Tree = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
		
	});
	
	
</script>
<script src="/admin/adminJS/system/sys_param_index.js"></script>   	

       


		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>外部结算<small>可选时间内全部结算</small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
				  
				  <!-- search start -->
					<form id="form-search" class="x_panelNew">
					<div class="btn-group focus-btn-group">
						<label for="sys_order_num">订单号
							  <input type="text" id="to_order_id" class="form-control form-controlNew" name="to_order_id" condition="="/>
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
						<label for="fullname">查找
						<button type="button" class="form-control btn btn-primary btn-sm search-btn"><i class="fa fa-search"></i></button>
						</label>
					</div>
					</form>
					<!-- search end -->
                  <div class="x_content">
                    <table id="outside-datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>订单号</th>
                          <th>受理渠道</th>
                          <th>支付渠道</th>
                          <th>支付方式</th>
                          <th>交易金额（元）</th>
						  <th>结算流水</th>
                        </tr>
                      </thead>
						<!-- ajax加载 -->
                      <tbody>
						
                      </tbody>
                    </table>
					<div id="AiGrid"><!-- 分页插件 --></div>
					
					<!-- 外部结算列表HTML模板 -->
					<script type="text/template" id="outside_table_template">
					<tr>
						<td>{to_order_id}</td>
						<td>{trade_channel_name}</td>
						<td>{payment_channel_name}</td>
						<td>{charge_type_name}</td>
						<td>{charge_money}</td>
						<td>
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs view-detail">查看流水</button>
						</td>
					</tr>
					</script>
                  </div>
					
				<div class="x_title">
                    <h2>内部结算<small>可选时间内全部结算</small></h2>
                  
                    <div class="clearfix"></div>
              	</div>
                  <div class="x_content">
                    <table id="inside-datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>订单号</th>
                          <th>受理渠道</th>
                          <th>支付渠道</th>
                          <th>支付方式</th>
                          <th>收入（元）</th>
						  <th>结算流水</th>
                        </tr>
                      </thead>
						<!-- ajax加载 -->
                      <tbody>
						
                      </tbody>
                    </table>
					<div id="AiGrid"><!-- 分页插件 --></div>
					
					<!-- 内部结算列表HTML模板 -->
					<script type="text/template" id="inside_table_template">
					<tr>
						<td>{to_order_id}</td>
						<td>{trade_channel_name}</td>
						<td>{payment_channel_name}</td>
						<td>{charge_type_name}</td>
						<td>{charge_money}</td>
						<td>
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs view-detail">查看流水</button>
						</td>
					</tr>
					</script>
                  </div>
				  
				  
				  <!-- 结算详情 modal -->
				  <div id="view-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog"  style="width:50%">
					<div class="modal-content">

					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel2">流水详情</h4>
					  </div>
					  <div class="modal-body">
						<table id="detail-datatable" class="table table-striped table-bordered">
						  <thead>
							<tr>
							  <th>结算方</th>
							  <th>结算费率（%）</th>
							  <th>结算金额（元）</th>
							</tr>
						  </thead>

						  <tbody>
							<!-- ajax加载 -->
						  </tbody>
						</table>
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default antoclose2" data-dismiss="modal">关闭</button>
					  </div>
					</div>
				  </div>
				</div>
				<!-- 结算列表HTML模板 -->
				<script type="text/template" id="detail_table_template">
				<tr>
					<td>{clear_relate_name}</td>
					<td>{clear_money_rate}%</td>
					<td>{clear_money}</td>					
				</tr>
				</script>
                </div>
              </div>
            </div>
<script src="/admin/adminJS/clearing/clearing_detail.js"></script>   	

       

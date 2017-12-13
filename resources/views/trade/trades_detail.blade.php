
		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>交易明细<small>可选时间内全部交易流水</small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
				  
				  <!-- search start -->
					<form id="form-search" class="x_panelNew">
					<div class="btn-group focus-btn-group">
						<label for="sys_order_num">订单号
							  <input type="text" id="sys_order_num" class="form-control form-controlNew" name="sys_order_num" condition="="/>
						</label>
						
					</div>
					<div class="btn-group focus-btn-group">
					<label for="user_no">用户ID
						  <input type="text" id="user_no" class="form-control form-controlNew" name="user_no" condition="="/>
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
						<label for="user_no">受理渠道
						  <select name="trade_channel" class="form-control form-controlNew" condition="=">
							<option value=''>不限</option>
							@foreach($accept_channel_list as $vo)
							<option value='{{$vo->sign}}'>{{$vo->name}}</option>
							@endforeach
						  </select>
						</label>
					</div>
					<div class="btn-group focus-btn-group">
						<label for="user_no">支付渠道
						  <select name="payment_channel" table="orderSerial" class="form-control form-controlNew" condition="=">
							<option value=''>不限</option>
							@foreach($payment_channel as $k=>$vo)
							<option value='{{$k}}'>{{$vo}}</option>
							@endforeach
						  </select>
						</label>
					</div>
					
					<div class="btn-group focus-btn-group">
						<label for="user_no">支付方式
						  <select name="charge_type" table="orderSerial" class="form-control form-controlNew" condition="=">
							
						  </select>
						</label>
					</div>
					<div class="btn-group focus-btn-group">
						<label for="user_no">交易状态
						  <select name="trade_status"  class="form-control form-controlNew" condition="=">
							<option value=''>不限</option>
							@foreach($trade_status as $k=>$vo)
							<option value='{{$k}}'>{{$vo}}</option>
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
                          <th>订单号</th>
                          <th>用户ID</th>
                          <th>交易金额</th>
                          <th>交易时间</th>
                          <th>受理渠道</th>
                          <th>交易状态</th>
						  <th>流水</th>
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
						<td>{sys_order_num}</td>
						<td>{user_no}</td>
						<td>{total_money}</td>
						<td>{create_time}</td>
						<td>{trade_channel_name}</td>
						<td>
							<button type="button" class="btn {btn} btn-xs">{btn_name}</button>
						</td>
						<td>
							<button type="button" dataid="{sys_order_num}" class="btn btn-primary btn-xs view-detail">查看流水</button>
						</td>
					</tr>
					</script>
                  </div>
				  
				  
				  <!-- 流水详情 modal -->
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
							  <th>流水号</th>
							  <th>实际交易金额</th>
							  <th>交易时间</th>
							  <th>支付渠道</th>
							  <th>支付方式</th>
							  <th>交易状态</th>
							</tr>
						  </thead>

						  <tbody>
							<!-- ajax加载 -->
						  </tbody>
						</table>
					<!--
						<div id="testmodal2" style="padding: 5px 20px;">
						  <form id="antoform2" class="form-horizontal" role="form">
							
							<div class="form-group">
							  
							  <div class="col-sm-9">
								<label class="control-label">Title</label>
								<input type="text" class="form-control" id="title2" name="title2">
								
							  </div>
							 
							</div>
							<div class="form-group">
							  
							  <div class="col-sm-9">
								
								<input type="text" class="form-control" value="">
								
							  </div>
							 
							</div>

						  </form>
						</div>
						-->
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default antoclose2" data-dismiss="modal">关闭</button>
						<!--<button type="button" class="btn btn-primary antosubmit2">Save changes</button>-->
					  </div>
					</div>
				  </div>
				</div>
				<!-- 流水列表HTML模板 -->
				<script type="text/template" id="detail_table_template">
				<tr>
					<td>{serial_num}</td>
					<td>{charge_money}</td>
					<td>{charge_time}</td>
					<td>{payment_channel_name}</td>
					<td>{charge_type_name}</td>
					<td>
						<button type="button" class="btn {btn} btn-xs">{btn_name}</button>
					</td>
				</tr>
				</script>
                </div>
              </div>
            </div>
			
<!-- 支付渠道与方式对应关系处理 -->			
<script>
	var payment = <?php echo $charge_type; ?>;
	
	$('#form-search').find('select[name="payment_channel"]').change(function(){
		var key = $(this).val();
		
		var opt = "";
			
		if(key){
			
			for(var k in payment[key]){
				
				opt+="<option value='"+k+"'>"+payment[key][k]+"</option>"
			}
		}
		
		$('#form-search').find('select[name="charge_type"]').html(opt);
	});
	
</script>
<script src="/admin/adminJS/trade/index.js"></script>   	

       

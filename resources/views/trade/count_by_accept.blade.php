<script src="/admin/adminJS/echarts.js"></script>
		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>按受理渠道统计<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
				  
				  <!-- search start -->
					<form id="form-search" class="x_panelNew">
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
			
<script src="/admin/adminJS/trade/count_by_accept.js"></script>   	

       

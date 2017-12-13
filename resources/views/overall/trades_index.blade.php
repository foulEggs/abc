<script src="/admin/adminJS/echarts.js"></script>
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>总交易量(本月)</span>
              <div class="count" id="month_number"></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>4% </i>同比上月</span>-->
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i>总交易金额(本月)</span>
              <div class="count" id="month_money"></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i>同比上月</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>总交易量(本周)</span>
              <div class="count green" id="week_number"></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i>同比上周</span>-->
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>总交易金额(本周)</span>
              <div class="count" id="week_money"></div>
              <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i>同比上周</span>-->
            </div>
          </div>
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>总交易量(今天)</span>
              <div class="count" id="day_number"></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>4% </i>同比昨日</span>-->
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i>总交易金额(今天)</span>
              <div class="count" id="day_money"></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i>同比昨日</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>峰值并发数</span>
              <div class="count">0</div>
              <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i>同比历史最高</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>实时并发数</span>
              <div class="count">0</div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i>同比今日最高</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>交易成功率</span>
              <div class="count" id="success_rate">NaN</div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i>同比历史成功率</span>-->
            </div>
          </div>
          <!-- /top tiles -->
            <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-3">
                    <h3>受理渠道统计<small>(月度)</small></h3>
                  </div>
                  
                  <form id="accept-form-search" class="x_panelNew">
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

                </div>

                <div class="col-md-7 col-sm-7 col-xs-12">
                  <div id="accept-main" style="width:100%;height:400px;"></div>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>Top4受理渠道</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div id="topAccept-main" style="width:100%;height:310px;"></div>

                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-3">
                    <h3>支付渠道统计<small>(月度)</small></h3>
                  </div>
                  <form id="payment-form-search" class="x_panelNew">
                  <div class="btn-group focus-btn-group">
                    <label for="user_no">支付渠道
                      <select name="payment_channel" class="form-control form-controlNew" condition="=">
                      <option value=''>不限</option>
                      @foreach($payment_channel as $k=>$vo)
                      <option value='{{$vo->key}}'>{{$vo->name}}</option>
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
                </div>

                <div class="col-md-7 col-sm-7 col-xs-12">
                  <div id="payment-main" style="width:100%;height:400px;"></div>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>Top4支付渠道</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div id="topPayment-main" style="width:100%;height:310px;"></div>
                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                  <div class="col-md-3">
                  <h2>收入TOP5</h2>
                  </div>
                  <form id="district-form-search" class="x_panelNew">              
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
                    <label for="fullname">查找
                    <button type="button" class="form-control btn btn-primary btn-sm search-btn"><i class="fa fa-search"></i></button>
                    </label>
                  </div>
                  </form>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="topDistrict-main" style="width:100%;height:310px;"></div>
                </div>
              </div>
            </div>


            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>收入指标</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <ul class="quick-list">
                      <li><i class="fa fa-calendar-o"></i><a href="#">数字电视</a>
                      </li>
                      <li><i class="fa fa-bars"></i><a href="#">宽带</a>
                      </li>
                      <li><i class="fa fa-bar-chart"></i><a href="#">VOD</a> </li>
                      <li><i class="fa fa-line-chart"></i><a href="#">智慧城市</a>
                      </li>
                      <li><i class="fa fa-bar-chart"></i><a href="#">智慧教育</a> </li>
                      <li><i class="fa fa-line-chart"></i><a href="#">数据农村</a>
                      </li>
                    </ul>

                    <div class="sidebar-widget">
                        <h4>业绩完成度</h4>
                        <canvas width="150" height="80" id="chart_gauge_01" class="" style="width: 160px; height: 100px;"></canvas>
                        <div class="goal-wrapper">
                          <span id="gauge-text" class="gauge-value pull-left">0</span>
                          <span class="gauge-value pull-left">%</span>
                          <span id="goal-text" class="goal-value pull-right">100%</span>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
				
 <script src="/admin/adminJS/overall/trades_index.js"></script>           

		<div class="row">
		
		</div>
		   <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>对账报表<small>可选时间内全部对账</small></h2>
                  
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
						<input type="text" class="form-control datepicker form-controlNew" name="start-create_time" placeholder="" condition="start">
					</label>
						
					  </div>
					<div class="btn-group focus-btn-group" style="width:215px;">
					<label for="start_time">结束时间
						<input type="text" class="form-control datepicker form-controlNew" name="end-create_time" placeholder="" condition="end">
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
                          <th>对账笔数</th>
                          <th>对账金额（元）</th>
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
						<td>{correct_sum}</td>
						<td>{correct_money}</td>
						<!-- <td>
							<button type="button" dataid="{id}" class="btn btn-primary btn-xs view-detail">查看流水</button>
						</td> -->
					</tr>
					</script>
                  </div>				
                </div>
              </div>
            </div>
<script src="/admin/adminJS/statement/account_statement.js"></script>   	

       

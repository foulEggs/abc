 <div class="">

            <div class="row">
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>安全事件<small>最近更新</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- <article class="media event">
                      <a class="pull-left date">
                        <p class="month">7月</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">端口扫描</a>
                        <p>检测到有不明原因的数据库端口扫描行为</p>
                      </div>
                    </article> -->
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>操作事件<small>最近更新</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- <article class="media event">
                      <a class="pull-left date">
                        <p class="month">7月</p>
                        <p class="day">11</p>
                      </a>
                      <div class="media-body">
                         <a class="title" href="#">新建业务员</a>
                        <p>账号Admin进行了新建业务员操作，新建业务员“李维”</p>
                      </div>
                    </article> -->
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>业务事件<small>最近更新</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- <article class="media event">
                      <a class="pull-left date">
                        <p class="month">7月</p>
                        <p class="day">14</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">银行对账</a>
                        <p>本日凌晨02:23:12开始进行银行对账，03:01:12成功完成</p>
                      </div>
                    </article> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>事件统计<small>动态更新</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">安全事件</a>
                          </li>
                          <li><a href="#">操作事件</a>
                          </li>
                          <li><a href="#">业务事件</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row" style="border-bottom: 1px solid #E0E0E0; padding-bottom: 5px; margin-bottom: 5px;">
                      <div class="col-md-7" style="overflow:hidden;">
                        <span class="sparkline_one" style="height: 160px; padding: 10px 25px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                        <h4 style="margin:18px">事件数量</h4>
                      </div>

                      <div class="col-md-5">
                        <div class="row" style="text-align: center;">
                          <div class="col-md-4">
                            <canvas class="canvasDoughnut" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">影响程度</h4>
                          </div>
                          <div class="col-md-4">
                            <canvas class="canvasDoughnut" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">存续时间</h4>
                          </div>
                          <div class="col-md-4">
                            <canvas class="canvasDoughnut" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">事件子类</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>安全走势图 <small>安全走势</small></h2>
                    <div class="filter">
                      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:280px">
                        <div id="chart_plot_02" class="demo-placeholder"></div>
                      </div>
                      <div class="tiles">
                        <div class="col-md-4 tile">
                          <span>累计补丁</span>
                          <h2>22</h2>
                          <span class="sparkline11 graph" style="height: 160px;">
                               <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span>
                        </div>
                        <div class="col-md-4 tile">
                          <span>未防御风险</span>
                          <h2>22</h2>
                          <span class="sparkline22 graph" style="height: 160px;">
                                <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span>
                        </div>
                        <div class="col-md-4 tile">
                          <span>持续威胁</span>
                          <h2>21</h2>
                          <span class="sparkline11 graph" style="height: 160px;">
                                 <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span>
                        </div>
                      </div>

                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <div class="x_title">
                          <h2>TOP5安全事件</h2>
                          <div class="clearfix"></div>
                        </div>
                        <ul class="list-unstyled top_profiles scroll-view">
                          <li class="media event">
                            <a class="pull-left border-aero profile_thumb">
                              <i class="fa fa-user aero"></i>
                            </a>
                            <div class="media-body">
                              <a class="title" href="#">端口扫描</a>
                              <p><strong>0%</strong>占安全事件总数</p>
                              <p> <small>迄今0起</small>
                              </p>
                            </div>
                          </li>
                          <li class="media event">
                            <a class="pull-left border-green profile_thumb">
                              <i class="fa fa-user green"></i>
                            </a>
                            <div class="media-body">
                              <a class="title" href="#">病毒</a>
                              <p><strong>0%</strong> 占安全事件总数 </p>
                              <p> <small>迄今0起</small>
                              </p>
                            </div>
                          </li>
                          <li class="media event">
                            <a class="pull-left border-blue profile_thumb">
                              <i class="fa fa-user blue"></i>
                            </a>
                            <div class="media-body">
                              <a class="title" href="#">木马</a>
                              <p><strong>0%</strong> 占安全事件总数 </p>
                              <p> <small>迄今0起</small>
                              </p>
                            </div>
                          </li>
                          <li class="media event">
                            <a class="pull-left border-aero profile_thumb">
                              <i class="fa fa-user aero"></i>
                            </a>
                            <div class="media-body">
                              <a class="title" href="#">Ddos</a>
                              <p><strong>0%</strong> 占安全事件总数 </p>
                              <p> <small>迄今0起</small>
                              </p>
                            </div>
                          </li>
                          <li class="media event">
                            <a class="pull-left border-green profile_thumb">
                              <i class="fa fa-user green"></i>
                            </a>
                            <div class="media-body">
                              <a class="title" href="#">篡改网页</a>
                              <p><strong>0%</strong> 占安全事件总数 </p>
                              <p> <small>迄今0起</small>
                              </p>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
               <!-- table content -->
        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>历史事件<small>事件列表</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>事件ID</th>
                          <th>事件类型</th>
                          <th>事件子类</th>
                          <th>性质</th>
                          <th>开始时间</th>
                          <th>结束时间</th>
                          <th>事件描述</th>
                          <th>事件状态</th>
                        </tr>
                      </thead>


                      <tbody>
                        <!-- <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>9012920738</td>
                          <td>操作事件</td>
                          <td>登陆</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">安全</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>用户Admin使用PC登陆，登陆IP：10.102.13.123</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>
                        <tr>
                          <td>7012920738</td>
                          <td>安全事件</td>
                          <td>端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-warning btn-xs">一般</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>来自多个IP的持续端口扫描</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">防护</button>
                          </td>
                        </tr>
                        <tr>
                          <td>3012920738</td>
                          <td>业务事件</td>
                          <td>结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">计划中</button>
                          </td>
                          <td>2017-04-25 09-21-12</td>
                          <td>2017-04-25 09-21-12</td>
                          <td>进行前日业务的资金结算和内部结算</td>
                          <td>
                             <button type="button" class="btn btn-success btn-xs">完成</button>
                          </td>
                        </tr>-->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
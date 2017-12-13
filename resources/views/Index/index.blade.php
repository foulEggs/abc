<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>聚合支付平台</title>

    <!-- Bootstrap -->
    <link href="/admin/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/admin/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/admin/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="/admin/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="/admin/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<link rel="stylesheet" href="/admin/css/xenon-forms.css">

    <!-- Custom Theme Style -->
    <link href="/admin/build/css/custom.min.css" rel="stylesheet">
	<style>
		.error,.required {
			color:#ec3a49;
		}
		.x_panelNew{
			padding: 0 5px 6px;
		} 
		.form-controlNew{
			border: 1px solid #DEDEDE;
			border-radius: 4px;
		}
	</style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"><i class="fa fa-paw"></i> <span>聚合支付平台</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="/admin/images/user.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>欢迎回来</span>
                <h2>{{Auth::user()->username}}</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>主菜单</h3>
                <ul class="nav side-menu">
                  @foreach($menus as $item)
                  <li><a><i class="{{$item->icon}}"></i> {{$item->name}} @if(isset($item->_child))<span class="fa fa-chevron-down"></span>@endif</a>
                    @if(!empty($item->_child))
                    <ul class="nav child_menu">
                      @foreach($item->_child as $item1)
                        @if(!isset($item1->_child))
                        <li><a href="{{$item1->extra}}">{{$item1->name}}</a></li>
                        @else
                        <li><a>{{$item1->name}}<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            @foreach($item1->_child as $item2)
                            <li><a href="{{$item2->extra}}">{{$item2->name}}</a></li>
                            @endforeach
                          </ul>
                        </li>
                        @endif
                      @endforeach
                    </ul>
                    @endif
                  </li>
                  @endforeach
                </ul>
                <!--
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> 全局视图 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/view-overall_trades">交易汇总</a></li>
                      <li><a href="#/overall-overview_hitches">故障汇总</a></li>
                      <li><a href="#/overall-overview_events">事件汇总</a></li>
                      <li><a href="#">系统运行图</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> 交易管理 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/view-trades_detail">交易明细</a></li>
                      <li><a href="#/view-trades_operation">交易处理</a></li>
					  
                        <li><a>交易统计<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <li><a href="#/view-count_by_district">按区域统计</a>
                            </li>
                            <li><a href="#/view-count_by_accept">按受理渠道统计</a>
                            </li>
                            <li><a href="#/view-count_by_payment">按支付渠道统计</a>
                            </li>
                          </ul>
                        </li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i>对账管理<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/index-wait">账务汇总</a></li>
                      <li><a href="#/account-account_detail">对账明细</a></li>
                      <li><a href="#/account-account_operation">对账处理</a></li>
                      <li><a href="#/index-wait">对账统计</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-table"></i> 结算管理 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/view-clearing_all">结算汇总</a></li>
                      <li><a href="#/view-clearing_detail">结算明细</a></li>
                      <li><a href="#">结算处理</a></li>
                    </ul>
                  </li>
				  
                  <li><a><i class="fa fa-bar-chart-o"></i> 终端管理 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     
                      <li><a href="#/view-terminal">终端列表</a></li>
                      <li><a href="#/index-wait">终端统计</a></li>
					          </ul>
                  </li>
                
                  <li><a><i class="fa fa-clone"></i>流程引擎 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
					
                      <li><a href="#/index-wait">创建流程</a></li>
                      <li><a href="#/index-wait">流程审核</a></li>
                      <li><a href="#/procedure-procedure_search">流程查询</a></li>
                    </ul>
                  </li>
				  
                  <li><a><i class="fa fa-clone"></i>统计报表 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/view-trade_statement">交易报表</a></li>
                      <li><a href="#/view-account_statement">对账报表</a></li>
                      <li><a href="#/view-clearing_statement">结算报表</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-clone"></i>系统管理 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#/view-menus">菜单管理</a></li>
                      <li><a href="#/system-system_account">账号管理</a></li>
                      <li><a href="#/system-system_role">角色权限管理</a></li>
                      <li><a href="#/view-charge_staffs">收费员管理</a></li>
                      <li><a href="#/view-client">客户端管理</a></li>
                      <li><a href="#/view-districts">区域管理</a></li>
                      <li><a href="#/view-accept_channel">业务受理渠道管理</a></li>
                      <li><a href="#/view-payment_channel">支付渠道管理</a></li>
                      <li><a href="#/view-sys_param">参数配置管理</a></li>
                      <li><a href="#/index-wait">版本管理</a></li>
                    </ul>
                  </li>
				        </ul>-->
              </div>
			  <!--
              <div class="menu_section">
                <h3>小工具</h3>
                <ul class="nav side-menu">                  
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 日程表 <span class="label label-success pull-right">Tips</span></a></li>                  
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 消息盒子 <span class="label label-warning pull-right">Tips</span></a></li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>帮助文档</h3>
                <ul class="nav side-menu">                  
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 操作指南 <span class="label label-success pull-right">Tips</span></a></li>                  
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 业务说明 <span class="label label-warning pull-right">Tips</span></a></li>
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 业务说明 <span class="label label-warning pull-right">Tips</span></a></li>
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 业务说明 <span class="label label-warning pull-right">Tips</span></a></li>
                  <li><a href="#/index-wait"><i class="fa fa-laptop"></i> 业务说明 <span class="label label-warning pull-right">Tips</span></a></li>
                </ul>
              </div>-->
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
			<!--
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="设置">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="全屏">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="锁定">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="退出登录" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>-->
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="/admin/images/user.png" alt="">{{Auth::user()->username}}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> 个人资料 </a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>设置面板</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">帮助</a></li>
                    <li><a href="/logout"><i class="fa fa-sign-out pull-right"></i>退出登录</a></li>
                  </ul>
                </li>
                <script type="text/javascript">
                  function logout(){
                    $.ajax({
                      url: "/logout",
                      method: 'POST',
                      dataType: 'json',
                      success: function(resp)
                      {
                        window.location.href = '/';
                      }
                    });
                  }
                </script>
                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">2</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
					 
                        <span class="image"><img src="/admin/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3分钟前</span>
                        </span>
                        <span class="message">
                          有一笔新交易
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
					  
                        <span class="image"><img src="/admin/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3分钟前</span>
                        </span>
                        <span class="message">
                          有一笔新交易
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>查看所有通知</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
	<!-- jQuery -->
    <script src="/admin/vendors/jquery/dist/jquery.min.js"></script>
	
	<!-- 分页 -->
	<script src="/admin/adminJS/paging.js"></script>
	<link href="/admin/adminJS/pagnate.css" rel="stylesheet">
	<!-- 验证 -->
	<script src="/admin/vendors/jquery-validate/jquery.validate.min.js"></script>
	<!-- 提示语 -->
	<link href="/admin/vendors/toastr/toastr.min.css" rel="stylesheet">
	<script src="/admin/vendors/toastr/toastr.min.js"></script>
	
        <!-- page content -->
        <div class="right_col" role="main">
           
        
               
        </div>
               <!-- /table content -->

        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            All right reserved by Syrinix Lnc.,<a href="http://www.cssmoban.com/" target="_blank" title="模板之家"></a><a href="#" title="网页模板" target="_blank"></a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>
	
	
    
    <!-- Bootstrap -->
    <script src="/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/admin/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/admin/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="/admin/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="/admin/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/admin/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    
    <!-- Skycons -->
    <script src="/admin/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="/admin/vendors/Flot/jquery.flot.js"></script>
    <script src="/admin/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="/admin/vendors/Flot/jquery.flot.time.js"></script>
    <script src="/admin/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="/admin/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="/admin/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="/admin/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="/admin/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="/admin/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/admin/vendors/moment/min/moment.min.js"></script>
	<script src="/admin/vendors/datepicker/bootstrap-datepicker.js"></script>
    <script src="/admin/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
	

    <!-- Custom Theme Scripts -->
    <script src="/admin/build/js/custom.js"></script>
	<!-- ajax div -->
	<script>
		
		var public_vars = public_vars || {};
		
		public_vars.$hashPage = {};

    var load_opts = {
      "closeButton": true,
      "debug": false,
      "positionClass": "toast-top-right",
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
		
		if (window.onhashchange !== undefined) {
			var default_addr = 'index-welcome';
			window.onhashchange = function () {
				config_load_page(default_addr);
				load_config();
			}
			config_load_page(default_addr);
			load_config();
		}
		
		function config_load_page (default_page) 
		{
			
			if (window.location.hash != '') {
				
				$('#modal_container').empty();
				
				//var module = window.location.pathname.split('/');
				
				var contr_act = window.location.hash.split('/')[1].split('-');
				
				if (contr_act.length < 2) {
					window.location.href = '#/'+default_page;
					return;
				}
				
				var pageurl = '/'+contr_act.join('/');
				console.log(pageurl);
				if(pageurl != "/index/wait"){
					if (public_vars.$hashPage[pageurl] === undefined) {
						console.log(localStorage.getItem("interval"));
						if(localStorage.getItem("interval") != undefined){
							clearInterval(localStorage.getItem("interval"));
							localStorage.removeItem("interval");
						}
						
						jQuery.ajax({
							url:pageurl,
							//type:'json',
							async:false,
							success:function(pageview) {
								if(pageview != 400){
									//public_vars.$hashPage[pageurl] = pageview;
									jQuery('div.right_col').html(pageview);
								}
							},
							error:function () {
								toastr.error('网络状况不佳，请稍后再试', "", load_opts);
							}
						});
					} else {
						jQuery('div.right_col').empty();
						jQuery('div.right_col').html(public_vars.$hashPage[pageurl]);
					}
				}
			} else {
				window.location.href = '#/'+default_page;
			}
		}
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			complete:function(XMLHttpRequest,textStatus){
			//通过XMLHttpRequest取得响应结果
        if(XMLHttpRequest.status == 401){
				
					  //如果超时就处理 ，跳转登陆页
					  alert("登录过期，请重新登录");
					  window.location.replace("/login");
					  return false;
        }	
			}
		});
	</script>
	
	
  </body>
</html>

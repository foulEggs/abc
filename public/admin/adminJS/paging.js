/**
 * @author (王智鹏)
 * -------------------------------------------------------------------
 * @fileoverview
 * 功能名称 分页插件组
 * @createDate 2014-11
 * @updateDate 2017-1-22
 * @version 0.1.5
 */

(function (window) {
	'use strict';
	
	window.AiTPagecontrol = function (_id) {
		var _TPagecontrol = this;

		//私有属性
		var DivID, //div ID
		DivOBJ, //div 对象
		PageControlMode = 3, //记录数和分页，仅分页, 无记录数和分页
		PageLines = 10, //定义每页含多少记录数
		PageLineArr, //当前每页含多少记录数
		TotalPages, //共有页数
		TotalDataNum, //共有多少条数据
		TotalPagesLength = 5, //页数显示长度
		CurrentPage = 1, //当前页号
		PageObj, //每页数据长度控件对象
		PageLineObj, //页码控件对象
		OnPageChangeFun = function () {}, //页码控件对象
		OnPageChangeBeforeFun = function () {}, //页码控件对象
		OnPageLinesChangeFun = function () {}, //页码控件对象
		PageStyle = { //分页样式
			first : '首页', //首页显示配置
			prev : '上一页', //上一页显示配置
			next : '下一页', //下一页显示配置
			last : '尾页', //尾页显示配置
			number : '{page}', //页码显示配置
			totalpage : '共{total}页', //总页数配置
			skip : true, //跳转功能是否开启
			skipGo : '跳转',  //跳转按钮显示
			disabled : true, //超过规定页数禁用形式（false 隐藏 true 不可用）
			firstLast : true, //是否显示首页和尾页 按钮
			tagType : 'button' //标签类型
		};

		_TPagecontrol.destroyAll = function () {
			if (DivOBJ) {
				DivOBJ.children().remove();
				DivOBJ.html('');
			}
			GarbageDispose();
		}

		//垃圾回收
		var GarbageCollection = [];

		//垃圾处理
		function GarbageDispose () {
			var garbage_len = GarbageCollection.length;
			for (var i = 0; i < garbage_len; i++) {
				GarbageCollection[i] = null;
			}
			GarbageCollection = null;
			try {
				CollectGarbage();
			} catch (_err) {

			}
			GarbageCollection = [];
		}

		//初始化元素
		function initialize (_id) {
			DivID = _id;
			//DIV JQuery 对象
			DivOBJ = $('#' + DivID);
			DivOBJ.html('&nbsp;');
			PageObj = null;

			DivOBJ.after($('<div clear style="clear:both;"></div>'));
		}

		//生成更新每页数据长度控件
		function createPageLine () {
			if (!PageLineObj) {
				PageLineObj = $('<div></div>');
				PageLineObj.addClass('AI-PageLines');
				//PageLineObj.css('float', 'left');
				
				var _select = createPageLineSelect();
				if (_select) {
					PageLineObj.append(_select);
				}
				
				DivOBJ.append(PageLineObj);
				GarbageCollection[GarbageCollection.length + 1] = _select;
			}
		}

		//生成选择长度select
		function createPageLineSelect () {
			var _select = PageLineObj.children('select');
			var _flag_return = true;
			if (_select.length == 0) {
				_select = $('<select class="pagelines" ></select>');
				_select.bind('change', function () {
					_OnPageLinesChange(this.value);
				});
			} else {
				_flag_return = false;
				var option_len = _select.get(0).options.length;
				for (var i = 0; i < option_len; i++) {
					_select.get(0).options.remove(i);
				}
			}
			var _option = null;
			$.each(PageLineArr, function (_idx, _val) {
				_option = $('<option></option>');
				_option.val(_val);
				_option.html(_val);
				if (PageLines == _val) {
					_option.attr('selected', 'selected');
				}
				_select.append(_option);
				GarbageCollection[GarbageCollection.length + 1] = _option;
			});
			return _flag_return ? _select : false;
		}

		//更新禁用/启用
		function updateDisabled () {
			var _page_nums = PageObj.find('.pagenum').children();
			_page_nums.each(function () {
				if ($(this).prop('number') == _TPagecontrol.GetCurrentPage()) {
					$(this).attr('disabled', 'disabled');
				} else {
					$(this).attr('disabled', null);
				}
			});

			var _first_btns = PageObj.find('.first_btn');
			_first_btns.each(function () {
				if (_TPagecontrol.GetCurrentPage() > 1) {
					$(this).attr('disabled', null);
				} else {
					$(this).attr('disabled', 'disabled');
				}
			});

			var _last_btns = PageObj.find('.last_btn');
			_last_btns.each(function () {
				if (_TPagecontrol.GetCurrentPage() < _TPagecontrol.GetTotalPages()) {
					$(this).attr('disabled', null);
				} else {
					$(this).attr('disabled', 'disabled');
				}
			});
		}

		//生成更新页码控件
		function createPage () {
			
			var _flag_append = false;
			
			if (!PageObj) {
				PageObj = $('<div></div>');
				PageObj.addClass('AI-Paging');
				//PageObj.css('float', 'right');
				_flag_append = true;
			}

			var _first = PageObj.children('.first');
			if (_first.length == 0) {
				_first = $('<span class="first" ></span>');
				_first.bind('click', function () {
					if (CurrentPage != 1) {
						_OnPageChangeBefore();
						_TPagecontrol.SetCurrentPage(1);
						updateDisabled();
						_OnPageChange();
					}
				});
			}
			GarbageCollection[GarbageCollection.length + 1] = _first;

			var _prev = PageObj.children('.prev');
			if (_prev.length == 0) {
				_prev = $('<span class="prev" ></span>');
				_prev.bind('click', function () {
					if (CurrentPage > 1) {
						_OnPageChangeBefore();
						_TPagecontrol.SetCurrentPage(CurrentPage - 1);
						updateDisabled();
						_OnPageChange();
					}
				});
			}
			GarbageCollection[GarbageCollection.length + 1] = _prev;

			var _next = PageObj.children('.next');
			if (_next.length == 0) {
				_next = $('<span class="next" ></span>');
				_next.bind('click', function () {
					if (CurrentPage < TotalPages) {
						_OnPageChangeBefore();
						_TPagecontrol.SetCurrentPage(CurrentPage + 1);
						updateDisabled();
						_OnPageChange();
					}
				});
			}
			GarbageCollection[GarbageCollection.length + 1] = _next;

			var _span_num = PageObj.children('.pagenum');
			if (_span_num.length == 0) {
				_span_num = $('<span class="pagenum"></span>');
			}
			GarbageCollection[GarbageCollection.length + 1] = _span_num;

			var _last = PageObj.children('.last');
			if (_last.length == 0) {
				_last = $('<span class="last" ></span>');
				_last.bind('click', function () {
					if (CurrentPage != TotalPages) {
						_OnPageChangeBefore();
						_TPagecontrol.SetCurrentPage(TotalPages);
						updateDisabled();
						_OnPageChange();
					}
				});
			}
			GarbageCollection[GarbageCollection.length + 1] = _last;

			var _total = PageObj.children('.totalpage');
			if (_total.length == 0) {
				_total = $('<span class="totalpage" ></span>');
			}
			GarbageCollection[GarbageCollection.length + 1] = _total;

			var _count = 0;
			//切换页码
			var _pagestart = (Math.floor((CurrentPage - 1) / TotalPagesLength) * TotalPagesLength) + 1;
			var _ele_type = PageStyle['tagType'] == 'button' ? ' type="button"' : ''

			var _active = PageObj.children('.pagenum').children('.active');
			if (_active.length > 0) {
				_active.each(function () {
					$(this).removeClass('active');
					$(this).attr('disabled')
				});
			}

			for (var _i = _pagestart; _i <= _pagestart + TotalPagesLength; _i++) {
				//添加首页、上一页
				if (_i == _pagestart && PageObj.children('.first').children('.first_btn').length == 0) {
					var _button = $('<' + PageStyle['tagType'] + _ele_type + ' class="first_btn">' + PageStyle['first'] + '</' + PageStyle['tagType'] + '>');
					var _button2 = $('<' + PageStyle['tagType'] + _ele_type + ' class="prev_btn">' + PageStyle['prev'] + '</' + PageStyle['tagType'] + '>');
					GarbageCollection[GarbageCollection.length + 1] = _button;
					GarbageCollection[GarbageCollection.length + 1] = _button2;
					if (PageStyle['disabled']) {
						if (CurrentPage == 1) {
							_button.attr('disabled', 'disabled');
							_button2.attr('disabled', 'disabled');
						}
						if (PageStyle['firstLast']) {
							_first.append(_button);
						}
						_prev.append(_button2);
						PageObj.append(_first).append(_prev).append(_span_num);
					} else {
						if (CurrentPage != 1) {
							if (PageStyle['firstLast']) {
								_first.append(_button);
							}
							_prev.append(_button2);
							PageObj.append(_first).append(_prev).append(_span_num);
						}
					}
				}
				//添加页码
				if (_count < TotalPagesLength) {
					++_count;
					var _button_num = PageObj.children('.pagenum').children('.pnum_' + _count);
					if (_i <= TotalPages) {
						var _flag_add = false;
						if (_button_num.length == 0) {
							_flag_add = true;
							_button_num = $('<' + PageStyle['tagType'] + _ele_type + ' class="pnum_' + _count + '"></' + PageStyle['tagType'] + '>');
							_button_num.bind('click', function () {
								_OnPageChangeBefore();
								_TPagecontrol.SetCurrentPage($(this).prop('number'));
								updateDisabled();
								_OnPageChange();
							});
						}
						GarbageCollection[GarbageCollection.length + 1] = _button_num;

						GarbageCollection[GarbageCollection.length + 1] = _span_num;

						_button_num.attr('number', _i);
						_button_num.prop('number', _i);
						_button_num.html(PageStyle['number'].replace('{page}', _i));
						if (CurrentPage == _i) {
							PageObj.attr('currentpage', CurrentPage);
							_button_num.attr('disabled', 'disabled');
							_button_num.addClass('active');
						}
						if (_flag_add) {
							_span_num.append(_button_num);
						}
					} else {
						_button_num.remove();
					}
				}
				//添加尾页、下一页
				if ((_i == TotalPagesLength + _pagestart || _i == TotalPages) && PageObj.children('.last').children('.last_btn').length == 0) {
					var _button = $('<' + PageStyle['tagType'] + _ele_type + ' class="last_btn">' + PageStyle['last'] + '</' + PageStyle['tagType'] + '>');
					var _button2 = $('<' + PageStyle['tagType'] + _ele_type + ' class="next_btn">' + PageStyle['next'] + '</' + PageStyle['tagType'] + '>');
					GarbageCollection[GarbageCollection.length + 1] = _button;
					GarbageCollection[GarbageCollection.length + 1] = _button2;
					//分页样式判断
					if (PageStyle['disabled']) {
						if (CurrentPage == TotalPages) {
							_button.attr('disabled', 'disabled');
							_button2.attr('disabled', 'disabled');
						}
						if (PageStyle['firstLast']) {
							_last.append(_button);
						}
						_next.append(_button2);
						PageObj.append(_next).append(_last);
					} else {
						if (CurrentPage != TotalPages) {
							if (PageStyle['firstLast']) {
								_last.append(_button);
							}
							_next.append(_button2);
							PageObj.append(_next).append(_last);
						}
					}
					PageObj.append(_total);

					//分页跳转功能
					if (_flag_append === true && PageStyle['skip']) {
						var _skip = $('<span class="skippage" ></span>');
						var _input = $('<input type="text" />');
						GarbageCollection[GarbageCollection.length + 1] = _skip;
						GarbageCollection[GarbageCollection.length + 1] = _input;
						_input.css('width', '40px');
						
						var _go = $('<' + PageStyle['tagType'] + _ele_type + ' class="skip_btn" ></' + PageStyle['tagType'] + '>');
						GarbageCollection[GarbageCollection.length + 1] = _go;
						_go.html(PageStyle['skipGo']);
						_go.bind('click', function () {
							//获得跳转页码
							var _skipPage = $(this).parent().find('input[type="text"]').val();
							if (_skipPage > 0 && _skipPage <= TotalPages) {
								_OnPageChangeBefore();
								_TPagecontrol.SetCurrentPage(_skipPage);
								updateDisabled();
								_OnPageChange();
							}
						});

						_input.bind('keyup', function () {
							if (event.keyCode == 13) {
								_go.click();
							}
						});

						_skip.append(_input).append(_go);
						PageObj.append(_skip);
					}
					break;
				}
			}

			if (_flag_append) {
				DivOBJ.append(PageObj);
			}

			if (TotalPages <= 0) {
				PageObj.css('visibility', 'hidden');
			} else {
				PageObj.css('visibility', 'visible');
			}

			_total.html(PageStyle['totalpage'].replace('{total}', TotalPages).replace('{data}', TotalDataNum));
			updateDisabled();
		}

		//设置分页样式
		this.SetPageStyle = function (_val) {
			$.each(_val, function (_k, _v) {
				if (PageStyle[_k]) {
					PageStyle[_k] = _v;
				}
			});
		}

		//设置可选数据长度
		this.SetPageLineArr = function (_val) {
			PageLineArr = _val;
		}

		//设置分页模式
		this.SetPageControlMode = function (_val) {
			PageControlMode = parseInt(_val);
		}

		//设置当前页码
		this.SetCurrentPage = function (_val) {
			CurrentPage = _val;
		}

		//获得当前页号
		this.GetCurrentPage = function () {
			return parseInt(CurrentPage);
		}

		//获得当前页号
		this.GetTotalDataNum = function () {
			return parseInt(TotalDataNum);
		}

		//设置页数显示长度
		this.SetTotalPagesLength = function (_val) {
			TotalPagesLength = parseInt(_val);
		}

		//设置总页数 受保护的
		function _SetTotalPages (_val) {
			TotalPages = parseInt(_val);
		}

		//设置总数据数 受保护的
		function _SetTotalDataNum (_val) {
			TotalDataNum = parseInt(_val) - 1;
		}

		//获得总页数
		this.GetTotalPages = function () {
			return parseInt(TotalPages) || 0;
		}

		//设置每页含多少记录数
		this.SetPageLines = function (_val) {
			PageLines = parseInt(_val);
		}

		//获得每页含多少记录数
		this.GetPageLines = function () {
			return parseInt(PageLines);
		}

		//刷新显示
		this.Refresh = function () {
			DivOBJ.css('display', 'block');
			//执行分页模式判断
			switch (PageControlMode) {
				case 1 :
					createPageLine();
					createPage();
					break;
				case 2 :
					createPage();
					break;
				case 3 :
					DivOBJ.css('display', 'none');
					break;
			}
			GarbageDispose();
		}

		//当用户改变每页记录数时触发。私有
		function _OnPageLinesChange (_PageLines) {
			PageLines = _PageLines;
			CurrentPage = 1;
			_TPagecontrol.DataPagecontrol.Refresh();
			OnPageLinesChangeFun.call(_TPagecontrol.DataPagecontrol, PageLines, CurrentPage);
		}

		//当用户改变每页记录数时触发。
		this.OnPageLinesChange = function (_func) {
			OnPageLinesChangeFun = _func;
		}

		//当用户点选其他页号时触发。私有
		function _OnPageChangeBefore () {
			OnPageChangeBeforeFun.call(_TPagecontrol.DataPagecontrol, CurrentPage, PageLines);
		}

		//当用户点选其他页号时触发（触发前执行）。
		this.OnPageChangeBefore = function (_func) {
			OnPageChangeBeforeFun = _func;
		}

		//当用户点选其他页号时触发。私有
		function _OnPageChange () {
			_TPagecontrol.DataPagecontrol.Refresh();
			OnPageChangeFun.call(_TPagecontrol.DataPagecontrol, CurrentPage, PageLines);
		}

		//当用户点选其他页号时触发。
		this.OnPageChange = function (_func) {
			OnPageChangeFun = _func;
		}

		//本地数据分页控件组
		function TLocalDataPagecontrol () {

			//初始化
			function initialize () {
				var DataSource, //数据源。该数据源是一个JSON、XML、CSV或者JS数组
				SearchDataSource, //查询筛选数据源。
				DataOutput, //分页处理后的当前页数据。
				LastSortParams; //最后一次排序参数

				//设置数据源
				this.SetDataSource = function (_val) {
					_SetTotalDataNum(_val.length);
					DataSource = _val;
				}

				//获得数据源
				this.GetDataSource = function () {
					return DataSource;
				}

				//获得数据源
				this.GetLastSortParams = function () {
					return LastSortParams;
				}

				//查询筛选数据覆盖数据源
				this.SetSearchToSource = function (_val, _search_if, _rule) {
					_search_if = _search_if || {};

					if (!_val || _val == '') {
						SearchDataSource = undefined;
					} else {
						_val = _val.replace(/([\(\)\-\{\}\*\+\$\^\|\.\?\,\\\/\_])/ig, '\\$1');
						
						var _searchRegexp = new RegExp(_val, 'i');

						var _dataSource = DataSource;

						var _dataSortSourceLen = _dataSource.length;

						var _dataSearchSource = [_dataSource[0]];

						for (var i = 1; i < _dataSortSourceLen; i++) {
							if (!_dataSource[i].length) {
								for (var searchKey in _dataSource[i]) {
									var _flag_if = false;
									if (_rule === true && (_search_if[searchKey] && _searchRegexp.test(_dataSource[i][searchKey]))) {
										_flag_if = true;
									} else if (_rule === false && (_search_if[searchKey] === undefined && _searchRegexp.test(_dataSource[i][searchKey]))) {
										_flag_if = true;
									} else if (_rule === undefined && (_searchRegexp.test(_dataSource[i][searchKey]) && (_search_if[searchKey] === undefined || _search_if[searchKey] === true))) {
										_flag_if = true;
									}
									if (_flag_if) {
										_dataSearchSource[_dataSearchSource.length] = _dataSource[i];
										break;
									}
								}
							} else {
								var _dataSortSourceSubLen = _dataSource[i].length;
								for (var j = 0; j < _dataSortSourceSubLen; j++) {
									_dataSource[i][j] += '';
									var _flag_if = false;
									if (_rule === true && (_search_if[j + 1] && _searchRegexp.test(_dataSource[i][j]))) {
										_flag_if = true;
									} else if (_rule === false && (_search_if[j + 1] === undefined && _searchRegexp.test(_dataSource[i][j]))) {
										_flag_if = true;
									} else if (_rule === undefined && (_searchRegexp.test(_dataSource[i][j]) && (_search_if[j + 1] === undefined || _search_if[j + 1] === true))) {
										_flag_if = true;
									}
									if (_flag_if) {
										_dataSearchSource[_dataSearchSource.length] = _dataSource[i];
										break;
									}
								}
							}
						}
						SearchDataSource = _dataSearchSource;
					}
				}

				//冒泡排序
				function bubbleSort (_dataSource, _row, _sort) {
					// 正序
					if (_sort) {
						for (var i = 1; i < _dataSource.length; i++) {
							for (var j = 1; j < _dataSource.length - 1; j++) {
								var c1 = _dataSource[j][_row];
								c1 = isNaN(c1) ? c1 : parseFloat(c1);
								var c2 = _dataSource[j + 1][_row];
								c2 = isNaN(c2) ? c2 : parseFloat(c2);
								if (_compareBigSmall(c1, c2)) {
									tmp = _dataSource[j];
									_dataSource[j] = _dataSource[j + 1];
									_dataSource[j + 1] = tmp;
								}
							}
						}
					} else { // 倒序
						for (var i = _dataSource.length - 1; i > 0; i--) {
							for (var j = _dataSource.length - 1; j > 0; j--) {
								var c1 = _dataSource[j][_row];
								c1 = isNaN(c1) ? c1 : parseFloat(c1);
								var c2 = _dataSource[j - 1][_row];
								c2 = isNaN(c2) ? c2 : parseFloat(c2);
								if (_compareBigSmall(c1, c2)) {
									tmp = _dataSource[j];
									_dataSource[j] = _dataSource[j - 1];
									_dataSource[j - 1] = tmp;
								}
							}
						}
					}
					return _dataSource;
				}

				//快速排序
				function quickSort(_dataSource, _row, _sort){

					var _dataSourceLen = _dataSource.length;
					var _first_placeholder = _dataSource.splice(0, 1);

					function sort (_data, _row, _sort) {
						if (_data.length > 1) {
							var _val     = _data[0][_row];
							var _val_arr = _data[0];
							var _little  = [];
							var _large   = [];
							var _val_len = _data.length;
							var _return_data = [];

							for (var i = 1; i < _val_len; i++) {
								var c1 = _data[i][_row];
								c1 = isNaN(c1) ? c1 : parseFloat(c1);
								if (_compareBigSmall(c1, _val) === false) {
									_little[_little.length] = _data[i];
								} else {
									_large[_large.length]  = _data[i];
								}
							}

							_little = sort(_little, _row, _sort);
							_large  = sort(_large, _row, _sort);

							if (_sort) {
								for (var i = 0; i < _little.length; i++) {
									_return_data[_return_data.length] = _little[i];
								}

								_return_data[_return_data.length] = _val_arr;

								for (var i = 0; i < _large.length; i++) {
									_return_data[_return_data.length] = _large[i];
								}
							} else {
								for (var i = 0; i < _large.length; i++) {
									_return_data[_return_data.length] = _large[i];
								}

								_return_data[_return_data.length] = _val_arr;

								for (var i = 0; i < _little.length; i++) {
									_return_data[_return_data.length] = _little[i];
								}
							}
							return _return_data;
						} else {
							return _data;
						}
					}

					var _dataSource = sort(_dataSource, _row, _sort);
					_dataSource.splice(0, 0, _first_placeholder);
					return _dataSource;
				}

				//精确比较大小
				function _compareBigSmall (_compare1, _compare2) {

					if (_compare1 == undefined || _compare2 == undefined) {
						return false;
					}

					var _len1 = _compare1.length || _compare1.toString().length;
					var _len2 = _compare2.length || _compare1.toString().length;
					var _flag = false;


					if (_len1 > _len2 || _len2 > _len1) {
						var _tmp_num = 0;
						var _tmp_num2 = 0;

						for (var _i = 0; _i < _len1; _i++) {
							_tmp_num += _compare1.toString()[_i].charCodeAt();
						}
						for (var _i = 0; _i < _len2; _i++) {
							_tmp_num2 += _compare2.toString()[_i].charCodeAt();
						}
						if (_tmp_num > _tmp_num2) {
							_flag = true;
						} else {
							_flag = false;
						}
					} else if (_len1 == _len2) {
						for (var _i = 0; _i < _len1; _i++) {
							if (_compare1[_i] && isNaN(_compare1[_i])) {
								_compare1[_i] = _compare1[_i].charCodeAt();
							}
							if (_compare2[_i] && isNaN(_compare2[_i])) {
								_compare2[_i] = _compare2[_i].charCodeAt();
							}
							if (_compare1[_i] && _compare2[_i]) {
								if (_compare1[_i] > _compare2[_i]) {
									_flag = true;
									break;
								} else if (_compare1[_i] < _compare2[_i]) {
									break;
								}
							} else {
								if (_compare1 > _compare2) {
									_flag = true;
									break;
								}
							}
						}
					}
					return _flag;
				}

				//排序数据源覆盖数据源
				this.SetSortToSource = function (_row, _sort, _notsort) {
					var _dataSortSource = SearchDataSource || DataSource;
					LastSortParams = [_row, _sort];
					
					if (_notsort) {
						var _noSortData = [];
						var _length = _dataSortSource.length;
						
						for (var i = 1; i < _length; i++) {
							var _break = false;
							for (var _key in _notsort) {
								for (var _idx in _notsort[_key]) {
									if (_dataSortSource[i][_key] == _notsort[_key][_idx]) {
										_noSortData[_noSortData.length] = _dataSortSource[i];
										_dataSortSource.splice(i, 1);
										--i;
										--_length;
										_break = true;
										break;
									}
								}
								if (_break) {
									break;
								}
							}
						}
					}
					
					//_dataSortSource = bubbleSort(_dataSortSource, _row, _sort);
					_dataSortSource = quickSort(_dataSortSource, _row, _sort);
					
					if (_notsort) {
						_length = _noSortData.length;
						for (var i = 0; i < _length; i++) {
							_dataSortSource.splice(i + 1, 0, _noSortData[i]);
						}
						_noSortData = null;
					}
				
					if (SearchDataSource) {
						SearchDataSource = _dataSortSource;
					} else {
						DataSource = _dataSortSource;
					}
				}

				//处理数据源
				function DisposeDataSource () {
					var _pLine = _TPagecontrol.GetPageLines();
					var useDataSource = SearchDataSource || DataSource;
					_SetTotalDataNum(useDataSource.length);
					
					_SetTotalPages(Math.ceil((useDataSource.length - 1) / _pLine));
					if (_TPagecontrol.GetCurrentPage() > _TPagecontrol.GetTotalPages() && _TPagecontrol.GetTotalPages() != 0) {
						_TPagecontrol.SetCurrentPage(_TPagecontrol.GetTotalPages());
					}
					DataOutput = [useDataSource[0]];
					var _start = ((_TPagecontrol.GetCurrentPage() - 1) * _pLine) + 1;
					$.each(useDataSource,  function (_idx, _val) {
						if (_idx > 0 && _idx >= _start && _idx < (_start + _pLine)) {
							DataOutput[DataOutput.length] = _val;
						}
					});
				}

				//获得处理后的当前数据
				this.GetDataOutput = function () {
					return DataOutput;
				}

				//刷新显示以及数据输出
				this.Refresh = function () {
					if (PageControlMode == 3) {
						PageLines = DataSource.length;
					}
					DisposeDataSource();
					_TPagecontrol.Refresh();
				}

				//当用户改变每页记录数时触发。
				this.OnPageLinesChange = function (_func) {
					_TPagecontrol.OnPageLinesChange(_func);
				}

				//当用户点选其他页号时触发。
				this.OnPageChange = function (_func) {
					_TPagecontrol.OnPageChange(_func);
				};

				//当用户点选其他页号时触发（触发前执行）。
				this.OnPageChangeBefore = function (_func) {
					_TPagecontrol.OnPageChangeBefore(_func);
				};

				return this;
			}

			return new initialize();
		}

		//远程数据分页控件组
		function TServiceDataPagecontrol () {
			
			//初始化
			function initialize () {
				var DataTotal, //数据总数
				DataSource, //数据源。该数据源是一个JSON、XML、CSV或者JS数组
				DataOutput //分页处理后的当前页数据。

				//设置数据源
				this.SetDataSource = function (_val) {
					_SetTotalDataNum(_val.length);
					DataSource = _val;
				}

				//获得数据源
				this.GetDataSource = function () {
					return DataSource;
				}

				//设置数据总数
				this.SetDataTotal = function (_val) {
					DataTotal = parseInt(_val);
				}

				//获得数据总数
				this.GetDataTotal = function () {
					return DataTotal;
				}

				//处理数据源
				function DisposeDataSource () {
					var _pLine = _TPagecontrol.GetPageLines();
					_SetTotalPages(Math.ceil(DataTotal / _pLine));
					DataOutput = [DataSource[0]];
					$.each(DataSource,  function (_idx, _val) {
						if (_idx > 0) {
							DataOutput[DataOutput.length] = _val;
						}
					});
				}

				//刷新显示以及数据输出
				this.Refresh = function () {
					if (PageControlMode == 3) {
						PageLines = DataTotal;
					}
					DisposeDataSource();
					_TPagecontrol.Refresh();
				}

				//当用户改变每页记录数时触发。
				this.OnPageLinesChange = function (_func) {
					_TPagecontrol.OnPageLinesChange(_func);
				}

				//当用户点选其他页号时触发。
				this.OnPageChange = function (_func) {
					_TPagecontrol.OnPageChange(_func);
				};

				//当用户点选其他页号时触发（触发前执行）。
				this.OnPageChangeBefore = function (_func) {
					_TPagecontrol.OnPageChangeBefore(_func);
				};

				return this;
			}

			return new initialize();
		}

		//注册本地分页控件
		this.RegisterLocal = function () {
			_TPagecontrol.DataPagecontrol = TLocalDataPagecontrol();
			return _TPagecontrol;
		}

		//注册远程分页控件
		this.RegisterService = function () {
			_TPagecontrol.DataPagecontrol = TServiceDataPagecontrol();
			return _TPagecontrol;
		}

		initialize(_id);

		return _TPagecontrol;
	}
})(window);

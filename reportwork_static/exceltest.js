/**
 * 原型链上添加日期格式化自定义
 * 格式化日期类型 自定义函数来格式化时间
 * yyyy-MM-dd
 */
Date.prototype.format = function(format) {
	var date = {
		"M+": this.getMonth() + 1,
		"d+": this.getDate(),
		"h+": this.getHours(),
		"m+": this.getMinutes(),
		"s+": this.getSeconds(),
		"q+": Math.floor((this.getMonth() + 3) / 3),
		"S+": this.getMilliseconds()
	};
	if (/(y+)/i.test(format)) {
		format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
	}
	for (var k in date) {
		if (new RegExp("(" + k + ")").test(format)) {
			format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
		}
	}
	return format;
}

/* 2025年法定假https://www.gov.cn/zhengce/zhengceku/202411/content_6986383.htm **/
let FADING_INCLUDE = [
	'2025/1/1', //元旦
	'2025/1/28', '2025/1/29', '2025/1/30', '2025/1/31', '2025/2/1', '2025/2/2', '2025/2/3', '2025/2/4', //春节
	'2025/4/4', '2025/4/5', '2025/4/6', //清明
	'2025/5/1', '2025/5/2', '2025/5/3', '2025/5/4', '2025/5/5', //五一
	'2025/5/31', '2025/6/1', '2025/6/2', //端午
	'2025/10/1', '2025/10/2', '2025/10/3', '2025/10/4', '2025/10/5', '2025/10/6', '2025/10/7', '2025/10/8' //国庆中秋
]
/* 2025年调休日,即周末上班日期 **/
let FADING_EXCLUDE = [
	'2025/1/26', '2025/2/8', '2025/4/27', '2025/9/28', '2025/10/11'
]

FADING_INCLUDE = FADING_INCLUDE.map(item => {
	return new Date(item).getTime()
})

FADING_EXCLUDE = FADING_EXCLUDE.map(item => {
	return new Date(item).getTime()
})

/*********************************************************/
/*********************************************************/
let editor = ''
const LAYDATACACHE = 'lay-data-cache'
const TABLEFILTER = 'table-filter-test'

// const app = Vue.createApp(App);
// for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
//  console.log(key)
//   app.component(key, component)
// }
// app.use(ElementPlus);
// app.mount("#app");

let global_table = []
layui.use(['table', 'form', 'laydate', 'element', 'upload', 'transfer'], async function() {
	let upload = layui.upload,
		$ = layui.$,
		layer = layui.layer,
		table = layui.table,
		transfer = layui.transfer,
		local_storage_set = function(k, v, const_name = LAYDATACACHE) {
			layui.data(const_name, {
				key: k,
				value: v
			})
		},
		local_storage_get = function(k, const_name = LAYDATACACHE) {
			let d = layui.data(const_name)
			return d[k]
		},
		createtable = function(i) {
			let a = $('#ID-table-data').clone()
			// a.insertAfter('#ID-table-data')
			document.body.appendChild(a[0])
			a[0].id = 'table_copy' + i
			return a[0]

			// createtable = function(setObject) {
			// 	return setObject.entries().reduce(function(o, v, i, me_){
			// 		console.log(arguments)
			// 		let a = $('#ID-table-data').clone()
			// 		a.insertAfter('#ID-table-data')
			// 		a[0].id = 'table_copy'+(i+1)
			// 		o[v[0]] = a[0]
			// 		return o
			// 	},{})
		}

	function transfer_render(){
		let data = [
			{"value": "A", "title": "ID_COL"}
			, {"value": "B", "title": "BIAOJI_COL"}
			, {"value": "C", "title": "BIANHAO_COL"}
			, {"value": "D", "title": "GONGSI_COL"}
			, {"value": "E", "title": "WORKNAME_COL"}
			, {"value": "F", "title": "MINGHUO_COL"}
			, {"value": "G", "title": "JIBIE_COL"}
			, {"value": "H", "title": "CHEJIAN_COL"}
			, {"value": "J", "title": "CONTENT_COL"}
			, {"value": "K", "title": "JIEZHI_COL"}
			, {"value": "M", "title": "POS_COL"}
			, {"value": "N", "title": "START_T_COL"}
			, {"value": "O", "title": "END_T_COL"}
			, {"value": "P", "title": "FUZEREN_COL"}
			, {"value": "Q", "title": "PERSON_COL"}
		]

		let selectedTransfers = []
		transfer.render({
			elem: '#transferTest',
			title: ['待选','已选'],
			data: data,
			id: 'transfer1', //定义索引
			onchange: function(data, index){
        debugger
				if(index === 0){
					selectedTransfers.push(data)
				} else {
					selectedTransfers.splice(data)
				}
				$("#transferTest div[data-index='1'] ul li").attr("draggable", "true")
				console.log(data) //
				console.log(index) //数据穿梭方向,0左→右,1左←右
			}
		})
		$("#transferTest div[data-index='1'] ul li").attr("draggable", "true")
		loadDrag()

		function loadDrag() {
				let list = document.querySelector("#transferTest div[data-index='1'] ul")
				let currentLi
				list.addEventListener('dragstart', (e) => {
						e.dataTransfer.effectAllowed = 'move'
						currentLi = e.target
						setTimeout(() => {
								currentLi.classList.add('moving')
						})
				}, { passive: false })

				list.addEventListener('dragenter', (e) => {
						e.preventDefault()
						if (e.target === currentLi || e.target === list) {
								return
						}
						let liArray = Array.from(list.childNodes)
						let currentIndex = liArray.indexOf(currentLi)
						let targetIndex = liArray.indexOf(e.target)
						if (currentIndex < targetIndex) {
								if (e.target.nextElementSibling.nodeName === 'LI') {
										list.insertBefore(currentLi, e.target.nextElementSibling)
								}
						} else {
								if (e.target.nodeName === 'LI') {
										list.insertBefore(currentLi, e.target)
								}
						}
				}, { passive: false })
				list.addEventListener('dragover', (e) => {
						e.preventDefault()
				}, { passive: false })
				list.addEventListener('dragend', (e) => {
						currentLi.classList.remove('moving')
				}, { passive: false })
		}

	}
	// transfer_render()

	// layui 渲染模板函数
	function templet_cell(d) {
		// 特定字段名  描述  读写状态
		// LAY_CHECKED  当前行的选中状态  可读可写
		// LAY_DISABLED 当前行是否禁止选择 可读可写
		// LAY_INDEX  当前行下标。每页重新从零开始计算  只读
		// LAY_NUM  当前行序号 只读
		// LAY_COL  当前列的表头属性配置项 只读
		let tipstyle = '',
			cellstyle = '';
		// console.log({'this':this, d}); // 当前列的表头属性配置项

		let errmsg = '',
			render_text = d[this.field]

		if (d.errinfo[d.LAY_COL.field]) {
			tipstyle = " background-color: #ffc58bc7; border-radius: 1em 1em 1em 1em; text-align: center; color: red; font-weight: bold;"
			errmsg += d.errinfo[d.LAY_COL.field]
		}
		if (this.field == 'starttime') {
			render_text += '\r\n' + d['endtime']
		}
		if (this.field == 'jibie') {
			render_text = ({ '是': '录像', '否': '不录像' })[d['luxiang']] + '\r\n' + render_text
		}

		if (this.field == 'minghuo') {
			render_text = `${d['id']} ${render_text}`
		}

		if (!/非明火/.test(d.minghuo)) {
			// cellstyle += 'background-color: #5fb878; color: #fff;'
			cellstyle += 'color: green'
		}

		// 返回模板内容
		return `<div style="${cellstyle}">${render_text}<div style="${tipstyle}">${errmsg}</div></div>`
		// return '<a href="/detail/'+ d.id +'" class="layui-table-link">'+ d.title +'</a>'
	}

	/**
	 * 根据table的id属性与data数据渲染表格
	 * @param  {Array} data 表格数据
	 * @param  {String or ElementObject} id   表格元素的id属性
	 */
	function table_render_handler(data, elem) {

		// 已知数据渲染
		let inst = table.render({
			elem: `#${elem}`, // 绑定原始 table 元素，方法渲染方式必填。
			// id, // 设定实例唯一索引，以便用于其他方法对 table 实例进行相关操作。若该属性未设置，则默认从 elem 属性绑定的原始 table 元素中的 id 属性值中获取。
			// url: '/exceltest.php',
			// css: [// 对开启了编辑的单元格追加样式
			// 	'.layui-table-view td[data-edit]{color: #16B777;}'
			// ].join(''),
			cols: [function() {
				let arr = [
					{ field: 'biaoji', title: '标记', width: 90, templet: templet_cell },
					{ field: 'id', title: 'ID', width: 50, sort: true, templet: templet_cell },
					{ field: 'bianhao', title: '编号', width: 130, templet: templet_cell },
					{ field: 'gongsi', title: '公司', width: 110, templet: templet_cell },
					{ field: 'minghuo', title: '明火', width: 80, sort: true, templet: templet_cell },
					{ field: 'jibie', title: '级别', width: 90, edit: editable, templet: templet_cell },
					{ field: 'workname', title: '项目名称', width: 150, templet: templet_cell },
					{ field: 'chejian', title: '车间', width: 120, templet: templet_cell },
					{ field: 'question', title: '原因', width: 130, templet: templet_cell },
					{ field: 'content', title: '内容', width: 230, templet: templet_cell },
					{ field: 'jiezhi', title: '介质', width: 110, templet: templet_cell },
					{ field: 'dept', title: '作业单位', width: 130, templet: templet_cell },
					{ field: 'pos', title: '位置', width: 200, templet: templet_cell },
					{ field: 'starttime', title: '开始时间', width: 110, sort: true, templet: templet_cell },
					{ field: 'endtime', title: '结束时间', width: 110, templet: templet_cell },
					{ field: 'fuzeren', title: '负责人', width: 100, templet: templet_cell },
					{ field: 'person', title: '作业人', width: 130, templet: templet_cell },
					{ field: 'luxiang', title: '录像', width: 60, templet: templet_cell },
					{ field: 'report', title: 'ISREPORT', width: 60, templet: templet_cell },
					{ field: 'reportstate', title: '报备状态', width: 60, templet: templet_cell },
					{ field: 'regperson', title: '登记人', width: 100, templet: templet_cell },
					{ field: 'regtime', title: '登记时间', width: 130, templet: templet_cell },
          { field: 'gudingshexiangtou', title: '固定摄像', width: 100, templet: templet_cell },
				];

				// 初始化筛选状态
				var local = layui.data('table-filter-test'); // 获取对应的本地记录
				layui.each(arr, function(index, item) {
					if (item.field in local) {
						item.hide = local[item.field];
					}
				});
				return arr;
			}()],
			done: function() {
				// 记录筛选状态
				let that = this;
				that.elem.next().on('mousedown', 'input[lay-filter="LAY_TABLE_TOOL_COLS"]+', function() {
					let input = $(this).prev()[0];
					// 此处表名可任意定义
					// local_storage_set('table-filter-test')
					layui.data('table-filter-test', {
						key: input.name,
						value: input.checked
					})
				});
			},
			data: data,
			// skin: 'line', // 表格风格
			// even: true,
			toolbar: '#toolbar-setRowChecked',
			defaultToolbar: ['filter'],
			page: false, // 是否显示分页
			// page: {
			//  layout: ['prev', 'page', 'next', 'limit', 'count', ],
			//  groups: 1, //只显示 1 个连续页码
			//  first: false, //不显示首页
			//  last: false //不显示尾页
			// },
			limit: 5000, // 每页默认显示的数量
			height: 'full-25',
			// limits: [15,30,45],
			autoSort: true,
			initSort: {
				field: 'minghuo',
				type: 'asc'
			}
			// size: 'sm'
		});

		// 暴露inst,data,table等关键变量,方便调试
		let el = document.getElementById(elem)
		el.table_inst = inst;
		el.table_data = data;
		el.table_ = table;

		//备份table数据
		local_storage_set('data_bak', table.cache[elem])
	}

	var editable = function(d) {
		if (this.field == 'jibie') {
			return 'text'
		}
		// if(d.editable) return 'text'; // 这里假设以 editable 字段为判断依据
	};

	// 渲染table表格
	upload.render({
		elem: '#ID-upload-drag',
		accept: 'file',
		url: './index', // 上传文件接口。
    // url: './exceltest.php', // 上传文件接口。
		done: function(res) {
			layer.msg('上传成功');
			// local_storage_set('wordurl', res.url)

			let data = res.data;
			let error_num = 0
			data = data.map(item => {
				item.errinfo = { 'biaoji': '', 'id': '', 'bianhao': '', 'gongsi': '', 'minghuo': '', 'jibie': '', 'workname': '', 'chejian': '', 'question': '', 'content': '', 'jiezhi': '', 'dept': '', 'pos': '', 'starttime': '', 'endtime': '', 'fuzeren': '', 'person': '', 'luxiang': '', 'report': '', 'reportstate': '', 'regperson': '', 'regtime': '' }
				item.minghuo = item.minghuo.replace('作业', '')
				item.jibie = item.jibie.replaceAll('作业', '').replaceAll(',', '\r\n')
				item.person = item.person.replace(/^[\s,，、。.;；\/\\]+/, '')
				item.person = item.person.replace(/[\s,，、。.;；\/\\]+$/, '')
				return checkfun(item)
			})
			// debugger
			// data = data.map(item => {
			// 	item.jibie =
			// 	return
			// })

			// data = data.filter(item => (/审批(结束|中)/.test(item.biaoji)))
			table_render_handler(data, 'ID-table-data')
		},
		error: function(e) {
			console.log(e)
		}
	});

	// $('input#ID-upload-form-files').change(async function(e) {
	// let file = e.target.files
	// let formData = new FormData()
	// formData.append('file', file[0])
	// let { data } = await axios.post('/exceltest.php', formData, {
	//  headers: {'Content-Type': 'ultipart/form-data'}
	// })
	// data[1].LAY_CHECKED = true
	// })

	// 后端排序
	// table.on('sort(ID-table-data)', function(obj) {
	// })

	// 行单击事件( 双击事件为: rowDouble ), 配合工具栏的"当前行"按钮
	table.on('rowDouble(ID-table-data)', function(obj) {
		obj.del();
		// let data = obj.data; // 获取当前行数据
		// table.cache['ID-table-data'] = storage_datacache
		// table.renderData('ID-table-data')
	})

	// 行双击事件( 单击事件为: row ), 配合工具栏的"当前行"按钮
	table.on('row(ID-table-data)', function() {
		// let data = obj.data; // 获取当前行数据
		// console.log('当前表格是', obj.config.elem, '表格id是',obj.config.id)
		// layer.msg('当前行数据：<br>' + JSON.stringify(data), { // 显示 - 仅用于演示
		// 	offset: '65px'
		// });

		// obj.setRowChecked({ // 标注当前点击行的选中状态
		// 	type: 'radio' // radio 单选模式；checkbox 复选模式
		// });
	});

	// 头工具栏事件
	table.on('toolbar(ID-table-data)', function(obj) {
		// console.log(obj)
		switch (obj.event) {
			case 'getCheckData':
				let checkStatus = table.checkStatus(obj.config.id); //获取选中行状态
				let data = checkStatus.data; // 获取选中行数据
				layer.alert(JSON.stringify(data)); // 显示 - 仅用于演示
				break;
			case 'downRiskTable': //研判表
				离线风险研判(table, local_storage_get)
				// let wordurl = local_storage_get('wordurl')
				// window.open(wordurl)
				break;
			case 'fuwei':
				{
					let storage_datacache = local_storage_get('data_bak')
					table.cache['ID-table-data'] = storage_datacache
					table.renderData('ID-table-data')
					break;
				}
			case 'donghuo':
				{ //动火受限
					let datacache = table.cache['ID-table-data']
					datacache = datacache.filter(item => {
						return /[火受]/.test(item.jibie)
					})
					table.cache['ID-table-data'] = datacache
					table.renderData('ID-table-data')
					break;
				}
			case 'jielong':
				{
					let works = getEveryDateWork(table, local_storage_get)

					works = works.keys().reduce(function(o, v, i, me_) {

						let s = o.get(v).reduce((o, item, i, me_) => {

							let level = item.jibie,
								chejian = item.chejian,
								workname = item.workname

							chejian = chejian.replace('聚丙烯一', '聚丙一').replace('聚丙烯二', '聚丙二').replace('车间', '')
							let tmp = `${level.replace(/[^火受盲高吊临土断检]/g, '')}-` + chejian + workname
							tmp = tmp.replace(/[ *]/g, '')
							o += tmp + '\r\n'
							return o
						}, '')
						o.set(v, s)
						console.log(o, v, i, me_, s)
						return works;
					}, works)

					let jielong_text = works.keys().reduce(function(r, item, index) {
						let d = item,
							c = works.get(item),
							s = `${d}<div style="user-select: all;">${c}</div>` + r
						return s
					}, '').replace(/[、；;，,&]/g,'')

					swal.fire({ html: `<div style="font-size: 0.8rem;text-align: left;white-space: pre-wrap;">${jielong_text}</div>` })
					break;
				}
			case 'upLoadFile':
				$('#ID-upload-form-files').val('');
				$('#ID-upload-form-files').click()
				break;
			case 'selectFile':
				break;
				// case 'chaifen':{
				// 	let storage_datacache = local_storage_get('data_bak')

				// 	let tmp = storage_datacache.reduce(function(o, v, i){
				// 		let outname = new Date(v.starttime).format("yyyy-MM-dd")
				// 		if(!o.has(outname)){
				// 			o.set(outname,[])
				// 		}
				// 		o.get(outname).push(v)
				// 		return o
				// 	},new Map())

				// tmp = tmp.keys().reduce(function(o, v, i, me_){
				// 	let elem = createtable(i+1),
				// 		data = o.get(v)

				// 	let inst = table_render_handler(data, elem.id)
				// 	o.set(v, {elem, data, inst})
				// 	elem.table_map = o;
				// 	return o
				// }, tmp)
				// 	break;
				// }
		};
	});
});



// 根据storage备份的数据表统计所有日期,返回日期数组
function getEveryDateWork(table, local_storage_get) {
	let storage_datacache = local_storage_get('data_bak')
	storage_datacache = table.cache['ID-table-data']

	// 因为缓冲数据中有被删除的数据,被删除的表现形式为空数组[]
	storage_datacache = storage_datacache.filter(item => item.length!=0)

	let tmp = storage_datacache.reduce(function(o, v, i) {
		let outname = new Date(v.starttime).format("yyyy-MM-dd")
		if (!o.has(outname)) {
			o.set(outname, [])
		}
		o.get(outname).push(v)
		return o
	}, new Map())

	return tmp
}

// 检查每一个作业的合法性
function checkfun(item, error_num = 0) {
	let { biaoji, id, bianhao, gongsi, minghuo, jibie, workname, chejian, question, content, jiezhi, dept, pos, starttime, endtime, fuzeren, person, luxiang, report, reportstate, regperson, regtime, gudingshexiangtou, errinfo } = item

	let personNums = person.match(/\d+/),
		personTags = person.match(/[^\s,，、。.;；\/\\]+/g)
	personNums = personNums === null ? 0 : personNums[0]
	personTags = (personTags ?.length) ?? 0

	// 是否含特殊作业
	if(!/含/.test(jibie)){
		errinfo.jibie += '是否新改扩(含特殊作业)'
		error_num++
	}

	if (/[火限]/.test(jibie)) {

		// if (!minghuo.includes('非明火') && jibie.includes('一级动火') && !jibie.includes('是')) {
		// 	errinfo.minghuo += '一级明火录像'
		// 	error_num++
		// }

		let tmp_reg = new Date(regtime)
		tmp_reg.setHours(15, 30, 59)
		if (new Date(regtime).getTime() > tmp_reg.getTime()) {
			errinfo.regtime += '报备时间超时'
			errinfo.minghuo += '报备时间超时'
			error_num++
		}

		// 作业单位检查
		if (dept.includes('处')) {
			errinfo.dept += '作业单位'
			errinfo.minghuo += '作业单位'
			error_num++
		}

    // 固定摄像头
    if (gudingshexiangtou.trim().length<1) {
      errinfo.gudingshexiangtou += '固定摄像头'
      errinfo.minghuo += '固定摄像头'
      error_num++
    }

		// 作业人检查
		if (personTags != personNums) {
			errinfo.person += '人数不符'
			errinfo.minghuo += '人数不符'
			error_num++
		} else if (personTags > 6 || personNums > 6) {
			errinfo.person += '人数超限'
			errinfo.minghuo += '人数超限'
			error_num++
		}

		// 介质检查
		if (jiezhi.includes('无')) {
			errinfo.jiezhi += '介质不能无'
			errinfo.minghuo += '介质不能无'
			error_num++
		} else if (minghuo.includes('非明火') && /[焊磨切割]/.test(content)) {
			errinfo.minghuo += '非明火是否正确'
			error_num++
		} else if (jiezhi.includes('空气') && !minghuo.includes('非明火')) {
			errinfo.jiezhi += '填写原介质,注意隔断措施'
			errinfo.minghuo += '填写原介质,注意隔断措施'
			error_num++
		// } else if (jiezhi.includes('空气') && content.includes('保温') && minghuo.includes('非明火')) {
		// 	errinfo.jiezhi += '保温管道设备介质'
		// 	errinfo.minghuo += '保温管道设备介质'
		// 	error_num++
		// } else if (!jiezhi.includes('空气') && !jibie.includes('特级动火') && jibie.includes('动火')) {
		// 	errinfo.jiezhi += '未倒空应为特级'
		// 	errinfo.minghuo += '未倒空应为特级'
		// 	error_num++
		// } else if (!jiezhi.includes('空气') && !jibie.includes('特级动火') && jibie.includes('受限')) {
		// 	errinfo.jiezhi += '受限带介质'
		// 	errinfo.minghuo += '受限带介质'
		// 	error_num++
		}

		// 乙烯裂解炉必须是特级动火
		if (/^乙烯车间/.test(chejian) && /炉/.test(content) && !/特级/.test(jibie) && /火/.test(jibie)) {
			errinfo.jibie += '裂解炉特级录像'
			errinfo.minghuo += '裂解炉特级录像'
			error_num++
		}
		// 库房必须特级动火
		else if ((/库/.test(content) || /库/.test(pos)) && !/特级/.test(jibie) && /火/.test(jibie)) {
			errinfo.jibie += '库内特级且录像'
			errinfo.minghuo += '库内特级且录像'
			error_num++
		}
		// 污水系统、污油系统必须特级动火
		else if ((/[污废][水油]/.test(content) || /[污废][水油]/.test(pos)) && !/特级/.test(jibie) && /火/.test(jibie)) {
			errinfo.jibie += '污水特级录像'
			errinfo.minghuo += '污水特级录像'
			error_num++
		}
		// 机柜间，配电室，中控室，电缆室特级动火
		else if ((/(机柜|中控|配电|变电|总控|控室|制室)/.test(content) || /(机柜|中控|配电|变电|总控|控室|制室)/.test(pos)) && !/特级/.test(jibie) && /火/.test(jibie)) {
			errinfo.jibie += '机柜间中控特级录像'
			errinfo.minghuo += '机柜间中控特级录像'
			error_num++
		}

		//节假日检查
		if (!FADING_EXCLUDE.includes(new Date(starttime).setHours(0, 0, 0)) && (
				new Date(starttime).getDay() == 6 ||
				new Date(starttime).getDay() == 0 ||
				FADING_INCLUDE.includes(new Date(starttime).setHours(0, 0, 0))
			)) {
			if (/[一二]级动火/.test(jibie)) {
				errinfo.jibie += '假日升级录像'
				errinfo.minghuo += '假日升级录像'
				error_num++
			}
		}

		// 录像检查
		if (((/(特级)/.test(jibie)) || (/受限/.test(jibie))) && (!/是/.test(luxiang))) {
			errinfo.luxiang += '特级受限录像'
			errinfo.minghuo += '特级受限录像'
			error_num++
		}

		// 时间检查
		if (new Date(endtime).getHours() >= 18) {
			errinfo.endtime += '结束晚于18时'
			errinfo.starttime += '结束晚于18时'
			errinfo.minghuo += '结束晚于18时'
			error_num++
		}
		if (new Date(starttime).getHours() < 8) {
			errinfo.starttime += '开始早于8时'
			errinfo.minghuo += '开始早于8时'
			error_num++
		}
		if (new Date(endtime).getDate() != new Date(starttime).getDate()) {
			errinfo.endtime += '时间超过24小时'
			errinfo.starttime += '时间超过24小时'
			errinfo.minghuo += '时间超过24小时'
			error_num++
		}
		if (new Date(endtime).getTime() <= new Date(starttime).getTime()) {
			errinfo.starttime += '开始晚于结束'
			errinfo.minghuo += '开始晚于结束'
			error_num++
		}
	}

	// 检查建议
	if (jibie.includes('火')) {
		if (minghuo.includes('非')) {
			if (/(磨|焊|割)/.test(content)) {
				errinfo.minghuo += '应明火'
				error_num++
			}
		}
		// else{
		// 	if(!/(磨|焊|割)/.test(content)){
		// 		errinfo.minghuo += '应该是非明火'
		// 		error_num++
		// 	}
		// }
	}
	item.errinfo = errinfo

	item.jibie = /^检维修（.含特殊）$/.test(item.jibie)?item.jibie.replace(/检维修（.含特殊）/g,'检维修'):item.jibie.replace(/检维修（.含特殊）/g,'')
	return item
}



function generate() {
	const doc = new docx.Document({
		sections: [{
			properties: {},
			children: [
				new docx.Paragraph({
					children: [
						new docx.TextRun("Hello World"),
						new docx.TextRun({
							text: "Foo Bar",
							bold: true,
						}),
						new docx.TextRun({
							text: "\tGithub is the best",
							bold: true,
						}),
					],
				}),
			],
		}]
	});

	docx.Packer.toBlob(doc).then(blob => {
		console.log(blob);
		saveAs(blob, "example.docx");
		console.log("Document created successfully");
	});
}


function 离线风险研判(table, local_storage_get) {
	debugger
	let works = getEveryDateWork(table, local_storage_get)

	works = works.keys().reduce(function(o, v, i, me_) {

		let levels = o.get(v).reduce((o, item, i, me_) => {
			let level = item.jibie,
				chejian = item.chejian,
				workname = item.workname

			o.push(level.replace(/[\r\n\t,，]+/g, ','))
			return o
		}, [])

		let heji = levels.length
		let riqi = v

		levels = levels.map((item) => {
			return item.replace(/\(.*\)/g, '')
		}).join(',')

		let a = {
			'特级动火': 0,
			'一级动火': 0,
			'二级动火': 0,
			'受限': 0,
			'Ⅰ级高处': 0,
			'Ⅱ级高处': 0,
			'Ⅲ级高处': 0,
			'Ⅳ级高处': 0,
			'一级吊装': 0,
			'二级吊装': 0,
			'三级吊装': 0,
			'盲板': 0,
			'临时用电': 0,
			'动土': 0,
			'断路': 0,
			'检维修': 0,
			'动火': 0,
			'高处': 0,
			'吊装': 0
		}
		let convert = function(k) {
			let r = new RegExp(k, 'g'), result
	    if(k=='检维修'){
	      result = (levels.match(/(不|检维修作业(?![（]))/g) || []).length
	      // result += (levels.match(/检维修作业(?!（)/) || []).length
	    }else{
	      result = (levels.match(r) || []).length
	    }
			return result;
		}
		Object.keys(a).forEach((item) => {
			a[item] = convert(item)
		})

		let starttime = new Date(v)
		let word_url = `/tp6/public/index.php/word/exportWord?workdata[]=${a['特级动火']}&workdata[]=${a['一级动火']}&workdata[]=${a['二级动火']}&workdata[]=${a['受限']}&workdata[]=${a['盲板']}&workdata[]=${a['高处']}&workdata[]=${a['吊装']}&workdata[]=${a['临时用电']}&workdata[]=${a['动土']}&workdata[]=${a['断路']}&workdata[]=${a['检维修']}&cbs=是&ssc=否&ktc=否&riqi=${starttime.getTime()/1000}`
		console.log(a)

		let html_text = `
		<table style="text-align: center; width: 100%;" border="1">
		<thead>
			<tr class="header">
			<th>合计</th>
			<th>日期</th>
			<th>动火特级</th>
			<th>动火一级</th>
			<th>动火二级</th>
			<th>受限空间</th>
			<th class="tag" style="border-left: solid red 2px;">盲板</th>
			<th>高处</th>
			<th>吊装</th>
			<th>临时用电</th>
			<th class="tag" style="border-left: solid red 2px;">动土</th>
			<th>断路</th>
			<th>检维修</th>
			</tr>
		</thead>
		<tbody>
			<tr>
			<td>${heji}</td>
			<td><a target="_blank" href="${word_url}">${riqi}</a></td>
			<td>${a['特级动火']}</td>
			<td>${a['一级动火']}</td>
			<td>${a['二级动火']}</td>
			<td>${a['受限']}</td>
			<td class="tag" style="border-left: solid red 2px;">${a['盲板']}</td>
			<td>${a['高处']}</td>
			<td>${a['吊装']}</td>
			<td>${a['临时用电']}</td>
			<td class="tag" style="border-left: solid red 2px;">${a['动土']}</td>
			<td>${a['断路']}</td>
			<td>${a['检维修']}</td>
			</tr>
		</tbody>
		</table>`

		+`<table style="text-align: center; width: 100%; margin-top: 20px" border="1">
		  <thead>
		    <tr class="header">
		      <th colspan=3>${riqi}作业数量</th>
		      <th>${heji}</th>
		    </tr>

		    <tr class="header">
		      <th>作业类别</th>
		      <th>作业级别</th>
		      <th>作业数量</th>
		      <th>合计</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr>
		      <td rowspan=4>高处作业</td>
		      <td>高处一级</td>
		      <td>${a['Ⅰ级高处']}</td>
		      <td rowspan=4>${a['高处']}</td>
		    </tr>
		    <tr>
		      <td>高处二级</td>
		      <td>${a['Ⅱ级高处']}</td>
		    </tr>
		    <tr>
		      <td>高处三级</td>
		      <td>${a['Ⅲ级高处']}</td>
		    </tr>
		    <tr>
		      <td>高处四级</td>
		      <td>${a['Ⅳ级高处']}</td>
		    </tr>
		    <tr>
		      <td rowspan=3>动火作业</td>
		      <td>动火一级</td>
		      <td>${a['一级动火']}</td>
		      <td rowspan=3>${a['动火']}</td>
		    </tr>
		    <tr>
		      <td>动火二级</td>
		      <td>${a['二级动火']}</td>
		    </tr>
		    <tr>
		      <td>动火特级</td>
		      <td>${a['特级动火']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>受限</td>
		      <td colspan=2>${a['受限']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>吊装</td>
		      <td colspan=2>${a['吊装']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>临时用电</td>
		      <td colspan=2>${a['临时用电']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>断路</td>
		      <td colspan=2>${a['断路']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>动土</td>
		      <td colspan=2>${a['动土']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>盲板</td>
		      <td colspan=2>${a['盲板']}</td>
		    </tr>
		    <tr>
		      <td colspan=2>检维修作业</td>
		      <td colspan=2>${a['检维修']}</td>
		    </tr>
		  </tbody>
		</table>`

		o.set(v, html_text)
		return works;
	}, works)

	let yanpan_text = works.keys().reduce(function(r, item, index) {
		let d = item,
			c = works.get(item),
			s = `<div style="zoom: 70%; margin: 0px 0px 20px 0px; border-bottom: 1px red solid;">${c}</div>` + r
		return s
	}, '')

	swal.fire({ html: yanpan_text })
}
























// async function shuake(lastLearnTime, sign='20250806152552-402414-404641-57fe91', duration=60){
// 	let d = {
// 		"client": "pc",
// 		sign,
// 		duration,
// 		"status": "0",
// 		"lastLearnTime": lastLearnTime,
// 		"events": {
// 			"watching": {
// 				"watchTime": duration
// 			}
// 		}
// 	}
// 	d = $qs.stringify(d)
// 	let url = 'https://www.ciedu.com.cn/api/courses/98774/tasks/404504/event_v2/doing'
// 	d = await $axios.patch(url, d, {headers:{
// 		'x-csrf-token': '4CV_k15NTJSDwB9kwzuQPFMvRoOwJu102DHxhjMKcuQ',
// 		'x-requested-with': 'XMLHttpRequest',
// 		'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
// 		'accept': 'application/vnd.edusoho.v2+json'
// 	}})
// 	d = d.data
// 	if(d.learnControl.allowLearn){
// 		console.log(d)
// 	}else{
// 		console.error('刷课失败')
// 	}
// }
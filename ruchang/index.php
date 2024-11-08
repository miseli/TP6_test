<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>人员/车辆入厂申请表 - layui</title>
    <link rel="stylesheet" href="layui/css/layui.css">

	<!-- <script src="./sweetalert2.all.min.js"></script> -->
	<script src="qs.min.js"></script>
	<script>
		 // new VConsole()
	</script>
</head>

<body>
    <div class="layui-container">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">车辆入厂申请</li>
                <li>人员入厂申请</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <form class="layui-form layui-form-pane" action="" method="post" lay-filter="formtest">
<div class="layui-collapse">
  <div class="layui-colla-item">
    <h2 class="layui-colla-title" style="color:red;">车辆情况(必填)</h2>
    <div class="layui-colla-content layui-show">
                        <div class="layui-form-item">
                            <label class="layui-form-label">*司机姓名</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" required lay-verify="required" placeholder="请输入司机姓名" autocomplete="off" lay-verType="tips" lay-reqText="" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*联系电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="tel" required lay-verify="required|phone" placeholder="请输入司机联系电话" autocomplete="off" lay-verType="tips" class="layui-input">
                            </div>
                            <!-- <div class="layui-form-mid layui-word-aux">辅助文字</div> -->
                        </div>
                        <div class="layui-form-item" id="carid">
                            <label class="layui-form-label">*所属单位</label>
                            <div class="layui-input-block">
                                <input type="text" name="suoshudanwei" required lay-verify="required" placeholder="请输入所属单位" autocomplete="off" lay-verType="tips" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" id="carid">
                            <label class="layui-form-label">*车牌号码</label>
                            <div class="layui-input-block">
                                <input type="text" name="carid" required lay-verify="required" placeholder="无牌照则填原因" autocomplete="off" lay-verType="tips" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*车牌类型</label>
                            <div class="layui-input-block">
                                <select name="caridtype" required lay-verify="required">
                                    <option value=""></option>
                                    <option value="标准民用车" selected>标准民用车</option>
                                    <option value="新能源车">新能源车</option>
                                    <option value="02式民用车">02式民用车</option>
                                    <option value="警车">警车</option>
                                    <option value="民用车双行尾牌">民用车双行尾牌</option>
                                    <option value="使馆车">使馆车</option>
                                    <option value="农用车">农用车</option>
                                    <option value="摩托车">摩托车</option>
                                    <option value="其他车牌">其他车牌</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*车牌颜色</label>
                            <div class="layui-input-block">
                                <select name="cardidcolor" lay-verType="tips" required lay-verify="required">
                                    <option value=""></option>
                                    <option value="蓝色">蓝色</option>
                                    <option value="黄色" selected>黄色</option>
                                    <option value="白色">白色</option>
                                    <option value="黑色">黑色</option>
                                    <option value="绿色">绿色</option>
                                    <option value="民航黑色">民航黑色</option>
                                    <option value="其他颜色">其他颜色</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*车辆类型</label>
                            <div class="layui-input-block">
                                <select name="cartype" required lay-verify="required">
                                    <option value=""></option>
                                    <option value="其他车">其他车</option>
                                    <option value="小型车" selected>小型车</option>
                                    <option value="大型车">大型车</option>
                                    <option value="摩托车">摩托车</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*车辆颜色</label>
                            <div class="layui-input-block">
                                <select name="carcolor" lay-verType="tips" required lay-verify="required">
                                    <option value=""></option>
                                    <option value="白色" selected>白色</option>
                                    <option value="银色">银色</option>
                                    <option value="灰色">灰色</option>
                                    <option value="黑色">黑色</option>
                                    <option value="红色">红色</option>
                                    <option value="深蓝">深蓝</option>
                                    <option value="蓝色">蓝色</option>
                                    <option value="黄色">黄色</option>
                                    <option value="绿色">绿色</option>
                                    <option value="棕色">棕色</option>
                                    <option value="粉色">粉色</option>
                                    <option value="紫色">紫色</option>
                                    <option value="其他颜色">其他颜色</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*入厂时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="startTime" class="layui-input date" placeholder="请选择入厂时间" required lay-verify="required|date" readonly lay-verType="tips">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*出厂时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="endTime" class="layui-input date" placeholder="请选择出厂时间" required lay-verify="required|date" readonly lay-verType="tips">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*入厂事由</label>
                            <div class="layui-input-block">
                                <input type="text" name="inreason" required lay-verify="required" placeholder="请输入入厂事由" autocomplete="off" class="layui-input" lay-verType="tips">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">进厂目的地</label>
                            <div class="layui-input-block">
                                <input type="text" name="mudidi" lay-verify="" placeholder="精确到装置" autocomplete="off" class="layui-input">
                            </div>
                        </div>
    </div>
  </div>
</div>

<div class="layui-collapse">
  <div class="layui-colla-item">
    <h2 class="layui-colla-title">接待单位情况(可打印后手写)</h2>
    <div class="layui-colla-content">
                        <div class="layui-form-item">
                            <label class="layui-form-label">接待单位</label>
                            <div class="layui-input-block">
                                <input type="text" name="jiedaidanwei" lay-verify="" placeholder="请输入接待单位名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">接待负责人</label>
                            <div class="layui-input-block">
                                <input type="text" name="jiedairen" lay-verify="" placeholder="请输入接待负责人姓名" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">联系电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="jiedaitel" lay-verify="" placeholder="请输入接待人联系电话" autocomplete="off" class="layui-input">
                            </div>
                        </div>
    </div>
  </div>
</div>

                        <div class="layui-form-mid layui-word-aux">注:请如实填写!</div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">导出申请表</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="layui-tab-item">此功能尚未添加</div>
            </div>
        </div>
    </div>
</body>

</html>
<!-- 引入 layui.js 的 <script> 标签最好放置在 html 末尾 -->
<script src="./layui/layui.js"></script>
<script type="text/javascript">
	let s = document.createElement('script')
	s.src = './ruchang.js?' + new Date().getTime()
	document.head.appendChild(s)
</script>
<!-- <script src="./test.js"></script> -->
<!-- <div class="layui-progress" style="margin: 15px 0 30px;"> -->
<!-- <div class="layui-progress-bar" lay-percent="100%"></div> -->
<!-- </div> -->
<!-- <form class="layui-form" id="myForm" action="/tp6/public/"method="post"> -->
<!-- 默认风格： -->
<!-- <input type="checkbox" name="n[]" title="写作" checked> -->
<!-- <input type="checkbox" name="n[]" title="发呆">  -->
<!-- <input type="checkbox" name="n[]" title="禁用" disabled>  -->
<!-- <input type="submit" value="提交" /> -->
<!-- <form/> -->
<!-- <div class="layui-form-item"> -->
<!-- <label class="layui-form-label">开关</label> -->
<!-- <div class="layui-input-block"> -->
<!-- <input type="checkbox" name="switch" lay-skin="switch"> -->
<!-- </div> -->
<!-- </div> -->
<!-- <div class="layui-form-item"> -->
<!-- <label class="layui-form-label">单选框</label> -->
<!-- <div class="layui-input-block"> -->
<!-- <input type="radio" name="sex" value="男" title="男"> -->
<!-- <input type="radio" name="sex" value="女" title="女" checked> -->
<!-- </div> -->
<!-- </div> -->
<!-- <div class="layui-form-item layui-form-text"> -->
<!-- <label class="layui-form-label">文本域</label> -->
<!-- <div class="layui-input-block"> -->
<!-- <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea> -->
<!-- </div> -->
<!-- </div> -->
<!-- <div class="layui-form-item"> -->
<!-- <label class="layui-form-label">复选框</label> -->
<!-- <div class="layui-input-block"> -->
<!-- <input type="checkbox" name="like[write]" title="写作"> -->
<!-- <input type="checkbox" name="like[read]" title="阅读" checked> -->
<!-- <input type="checkbox" name="like[dai]" title="发呆"> -->
<!-- </div> -->
<!-- </div> -->
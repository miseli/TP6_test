//Demo
layui.use(['form', 'laydate', 'element', 'upload'], function() {
    // layui.data(table, settings)
    var form = layui.form;
    var laydate = layui.laydate;
    var element = layui.element;

    // form.val('formtest', {
        // "title": "123",
        // "tel": "12311231123",
        // "carid": "123",
        // "suoshudanwei": "单位",
        // "caridtype": "标准民用车",
        // "cartype": "小型车",
        // "cardidcolor": "黄色",
        // "cardcolor": "白色",
        // "startTime": "2024-05-09",
        // "endTime": "2024-05-16",
        // "inreason": "送餐",
        // "mudidi": "",
        // "jiedaidanwei": "",
        // "jiedairen": "",
        // "jiedaitel": ""
    // })

    //实例化一个上传控件
    // upload({
    //   url: '上传接口url'
    //   ,success: function(data){
    //     console.log(data);
    //   }
    // })

    //执行一个laydate实例
    laydate.render({
        elem: '.date', //指定元素
        // range: true, //开启选择范围
        done: function(value, date, endDate) {
            console.log(value); //得到日期生成的值，如：2017-08-18
            console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
            console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
        }
    });

    //一些事件触发
    element.on('tab(docDemoTabBrief)', function(data) {
        console.log(data);
    });

    //监听提交
    form.on('submit(formDemo)', function(data) {
        // data.field.today = new Date().toLocaleDateString().replaceAll('/','.')

        // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
        // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
        console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
        let start = new Date(data.field.startTime).getTime()
        let endTime = new Date(data.field.endTime).getTime()
        if (start>endTime){
          layer.alert('入厂时间 应早于 出厂时间',{icon: 7, title: '重填提示'})
          return false
        }

        // layer.msg(JSON.stringify(data.field));
        let url = '/tp6/public/ruchang/exportWord?type=0&riqi=' + parseInt(new Date().getTime()/1000) + '&' + Qs.stringify(data.field)
        // layer.msg(url)
        console.log(url)
        downloadfile(url)
        // layui.$.get(url, data.field, function(res){
        //   // 处理服务器返回的响应
        //   console.log(res);
        //   downloadfile(res, '你好.xlsx')
        // });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });

    form.on('checkbox', function(data) {
        console.log(data)
        data.value = data.value == 'on' ? 1 : 0
    })

    form.on('select', function(data) {
        console.log(data.elem); //得到select原始DOM对象
        console.log(data.value); //得到被选中的值
        console.log(data.othis); //得到美化后的DOM对象
    });

    form.on('radio(filter)', function(data) {
        console.log(data.elem); //得到radio原始DOM对象
        console.log(data.value); //被点击的radio的value值
    });

  form.on('switch', function(data){
    if(data.elem.checked){
      layui.$('#carid')
    }else{

    }
    console.log(data.elem); //得到checkbox原始DOM对象
    console.log(data.elem.checked); //开关是否开启，true或者false
    console.log(data.value); //开关value值，也可以通过data.elem.value得到
    console.log(data.othis); //得到美化后的DOM对象
  });

  layui.$('[lay-verify*="required"]').parent().prevAll().css('color','red')
});


        /**
         * 前端下载文件函数,支持file,blob,base64str
         * @param  {[type]} obj [description]
         * @return {[type]}     [description]
         */
        function downloadfile(obj, name=""){
            let dataurl = ''
            if('string'==typeof obj){
                dataurl = obj
            }else if('object'==typeof obj){
                if(obj instanceof File){
                    dataurl = URL.createObjectURL(new Blob([obj], { type: obj.type }));
                }else if(obj instanceof Blob){
                    // 为Blob创建一个URL对象
                    dataurl = URL.createObjectURL(obj);
                }
            }

            if(dataurl==''){
                alert('下载失败')
                return 1;
            }

            let a = document.createElement('a')
            a.download = name
            a.href = dataurl
            a.target = "_blank"
            a.click()
            a.remove()
            a = null
        }
var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/limitIp',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'LastLogonDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	search: false,
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { checkbox: true},
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'UserID',title: '管理',align:'center', formatter: function(value, row, index){
                return '<button type="button" class="btn btn-secondary btn-sm" onclick="upip(\''+row.AddrString+'\')"><i class="fa fa-edit"></i>&nbsp; 更新</button>&nbsp;|&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="delip(\''+row.AddrString+'\')"><i class="fa fa-trash-o"></i>&nbsp;删除</button>';
            }},
            { field: 'AddrString',title: '限制地址',align:'center'},
            { field: 'EnjoinLogon',title: '限制登录',align:'center', formatter: getNullity},
            { field: 'EnjoinRegister',title: '限制注册',align:'center', formatter: getNullity},
            { field: 'EnjoinScore',title: '限制分数',align:'center', formatter: getNullity},
            { field: 'EnjoinOverDate',title:'失效时间',align:'center', formatter: function(value, row, index){
                if(value == null)
                {
                    return '永久限制';
                }else{
                    return value;
                }
            }},
            { field: 'CollectDate',title:'录入时间',align:'center'},
            { field: 'CollectNote',title:'备注',align:'center'},
        ],
        queryParams: function (params) {
            var hhs = formmy.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
            };
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){
            // jQuery("#t1").val(data.extend.arr1.t1);
            // jQuery("#t2").val(data.extend.arr1.t2);
        },
    });


});

function getNullity(value, row, index)
{
    if(value == 0)
    {
        return '正常';
    }else{
        return '<span style="color:red;">禁止</span>';
    }
}

function upip(ip)
{
    jQuery.post('/index.php/admin/user/queryIp',{ip:ip}).done(function(data){
        jQuery("#lip").val(data.AddrString);
        jQuery("#lip2").val(data.AddrString);
        if(data.EnjoinLogon == 1){
            jQuery("#inline-radio1").prop("checked", true);
        }
        if(data.EnjoinRegister == 1){
            jQuery("#inline-radio2").prop("checked", true);
        }
        if(data.EnjoinScore == 1){
            jQuery("#inline-radio3").prop("checked", true);
        }
        if(data.EnjoinOverDate != ''){
            jQuery("#OverDate").val(data.EnjoinOverDate);
        }
    });
    jQuery('#mediumModal').modal();
}

function delip(ip)
{
    var msg = "确定删除？";
    if(confirm(msg) == true){
        jQuery.post('/index.php/admin/user/delIP',{ip:ip, type: 1}).done(function(data){
            $table.bootstrapTable('refresh',{
                    pageNumber: 1,
            });
        });
    }
}


$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#newadd").click(function(){
    jQuery('#mediumModal2').modal();
});

$("#kickPeople").click(function(){
    var msg = "确定删除？";
    if(confirm(msg) == true)
    {
        var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
        if(getSelectRows.length == 0){
            alert('请先选择要操作的对象');
            return false;
        }
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['AddrString'];
        });
        jQuery.post('/index.php/admin/user/delsIP', {arr: arr, type: 1}).done(function(data){
            alert('删除成功');
            $table.bootstrapTable('refresh',{
                     pageNumber: 1,
            });
        });
    }
});

$("#editip").click(function(){
    var forms = jQuery("#editForm");
    var fodata = forms.serializeArray();
    var obj2 = { };
    for (var item in fodata){
        obj2[fodata[item].name] = fodata[item].value;
    }
    var ckall = [];
    jQuery("input[name='limitip']").each(function(i){//把所有被选中的复选框的值存入数组
        if(jQuery(this).prop('checked')){
            ckall[i] = 1;
        }else{
            ckall[i] = 0;
        }   
    });
    obj2['limitip'] = ckall;
    console.log(obj2);
    if(obj2.remarks == '')
    {
        alert('备注不能为空');
        return false;
    }

    jQuery.post('/index.php/admin/user/editIP', {obj2: obj2, type: 1}).done(function(data){
        alert('修改成功');
        $table.bootstrapTable('refresh',{
            pageNumber: 1,
        });
    });
});

$("#addip").click(function(){
    var forms = jQuery("#addForm");
    var fodata = forms.serializeArray();
    var obj2 = { };
    for (var item in fodata){
        obj2[fodata[item].name] = fodata[item].value;
    }
    var ckall = [];
    jQuery("input[name='limitip2']").each(function(i){//把所有被选中的复选框的值存入数组
        if(jQuery(this).prop('checked')){
            ckall[i] = 1;
        }else{
            ckall[i] = 0;
        }   
    });
    obj2['limitip'] = ckall;
    console.log(obj2);
    if(obj2['ips2'].length < 1){
        alert('限制IP不能为空');
        return false;
    }

    if(jQuery("input[name='limitip2']:checked").length < 1){
        alert('限制条件不能为空');
        return false;
    }

    if(obj2['remarks2'].length < 1){
        alert('备注不能为空');
        return false;
    }
    jQuery.post('/index.php/admin/user/addIp', {obj2: obj2}).done(function(data){
        if(data.ret == 2)
        {
            alert(data.msg);
            return false;
        }
        alert('添加成功');
        $table.bootstrapTable('refresh',{
            pageNumber: 1,
        });
    });
});

var $table3 = jQuery('#table3');
$("#iptp1").click(function(){
    $table3.bootstrapTable({
        url: '/index.php/admin/user/topIp',
        locale: "zh-CN",
        pagination: false,
        sortClass: 'table-active',
        sortName: 'LastLogonDate',
        search: false,
        clickToSelect: true,
        showRefresh: true,
        showToggle:true,
        searchOnEnterKey:true,
        columns: [
            { checkbox: true},
            { field: 'RowNumber',title:'排名',align:'center'},
            { field: 'AddrString',title: 'IP',align:'center'},
            { field: 'ct',title: '注册人数',align:'center'},
            { field: 'EnjoinLogon',title: '限制登录',align:'center', formatter: getNullity},
            { field: 'EnjoinRegister',title: '限制注册',align:'center', formatter: getNullity},
            { field: 'EnjoinOverDate',title:'失效时间',align:'center', formatter: function(value, row, index){
                if(value == null)
                {
                    return '永久限制';
                }else{
                    return value;
                }
            }},
        ],
        queryParams: function (params) {
            var arr = {
                type: 1,  
            };
            return arr; 
        },
    });
    jQuery('#mediumModal33').modal();
});

$("#foreverip").click(function(){
    var msg = "确认此操作？";
    if(confirm(msg) == true)
    {
        var getSelectRows = $table3.bootstrapTable('getSelections', function (row) {});
        if(getSelectRows.length == 0){
            alert('请先选择要操作的对象');
            return false;
        }
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['AddrString'];
        });
        jQuery.post('/index.php/admin/user/foreverIP', {arr: arr, type: 1}).done(function(data){
            alert('操作成功');
            $table3.bootstrapTable('refresh',{
                     
            });
        });
    }
});
var $table = $('#tabless');
var formmy = $("#queryForm");
var type = 1;
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/registerList',
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
            { field: 'GameID',title: 'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'z_pay',title: '充值金额',align:'center'},
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_score',title:'总',align:'center',},
            { field: 'ZrVipSum',title: 'VIP转入',align:'center'},
            { field: 'ZcVipSum',title: '转出到VIP',align:'center'},
            { field: 'device',title: '登录方式',align:'center'},
            { field: 'SpreaderID',title:'渠道号',align:'center'},
            { field: 'RegisterMachine',title:'注册机器码',align:'center'},
            { field: 'RegisterIP',title:'注册IP',align:'center'},
            { field: 'registArea',title:'注册IP归属地',align:'center'},
            { field: 'RegisterDate',title:'注册时间',align:'center'},
            { field: 'Nullity',title:'状态',align:'center', formatter: function(value, row, index){
                if(value == 1){
                    return '<span style="color:red;">封号中</span>';
                }else{
                    return '正常';
                }
            }},
            { field: 'CheatLevel',title:'虫子等级',align:'center', formatter: function(value, row, index){
                if(value == 1){
                    return '一级';
                }else if(value == 2){
                    return '二级';
                }else if(value == 3){
                    return '三级';
                }
            }},
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
                type: type,
            };
            $table.extend(arr, obj);
            return arr; 
        },
    });


});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#setCheat").click(function(){
    
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    jQuery("#myModalLabel").text("设置虫子");
    jQuery('#myModal').modal();
    
});

$("#btn_submit").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    var arr = new Array();
    jQuery.each(getSelectRows, function(index, value){
        arr[index] = value['UserID'];
    });
    var grade = jQuery("#grade option:selected").val();
    jQuery.post('/index.php/admin/setmultiplex/setCheat',{grade:grade, arr:arr}).done(function(data){
        if(data > 0){
            $table.bootstrapTable('refresh');
        }
    });
});

$("#fhao").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    var msg = '确定封号？';
    if(confirm(msg) == true){
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['UserID'];
        });
        jQuery.post('/index.php/admin/setmultiplex/fhao',{arr:arr,type:1}).done(function(data){
            if(data > 0){
                $table.bootstrapTable('refresh');
            }
        });
    }
});

$("#reg1").click(function(){
    jQuery(this).removeClass('btn btn-outline-primary').addClass('btn btn-primary');
    jQuery("#reg2").removeClass('btn btn-primary').addClass('btn btn-outline-primary');
    type = 1;
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#reg2").click(function(){
    jQuery(this).removeClass('btn btn-outline-primary').addClass('btn btn-primary');
    jQuery("#reg1").removeClass('btn btn-primary').addClass('btn btn-outline-primary');
    type = 2;
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

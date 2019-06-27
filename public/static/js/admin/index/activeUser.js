var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/index/activeUser',
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
            { field: 'GameID',title: 'GameID',align:'center',searchable:true, formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_gold',title:'总',align:'center',sortable: true},
            { field: 'device',title:'登录方式',align:'center'},
            { field: 'SpreaderID',title:'渠道号',align:'center'},
            { field: 'LastLogonDate',title:'登录时间',align:'center',sortable: true},
            { field: 'VipServer',title:'运维VIP',align:'center'},
            { field: 'RegisterDate',title:'注册时间',align:'center'},
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
                order : params.order,
                search : params.search,
                sort : params.sort,
            };
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){
            jQuery("#t1").val(data.extend.arr1.t1);
            jQuery("#t2").val(data.extend.arr1.t2);
            jQuery("#z_recharge").html(data.extend.arr1.ct);
        },
    });


});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#kickPeople").click(function(){
    
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    // jQuery("#myModalLabel").text("设置虫子");
    jQuery('#myModal').modal();
    
});


$("#btn_submit").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    var arr = new Array();
    jQuery.each(getSelectRows, function(index, value){
        arr[index] = value['UserID'];
    });
    var grade = jQuery("#yunid").val();
    jQuery.post('/index.php/admin/setmultiplex/editYunVip',{grade:grade, arr:arr}).done(function(data){
        if(data == 1){
            $table.bootstrapTable('refresh');
        }else{
            alert('范围必须在0~127之间');
        }
    });
});


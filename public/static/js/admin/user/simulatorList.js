var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/simulatorList',
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
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_score',title:'总',align:'center',},
            { field: 'LastLogonDate',title:'最后登录时间',align:'center'},
            { field: 'Nullity',title:'状态',align:'center', formatter: function(value, row, index){
                if(value == 1){
                    return '<span style="color:red;">封号中</span>';
                }else{
                    return '正常';
                }
            }},
            { field: 'SpreaderID',title:'渠道号',align:'center'},
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
        // console.log(data);
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


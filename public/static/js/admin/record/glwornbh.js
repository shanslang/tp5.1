var $table = $('#tabless');
var formmy = $("#queryForm");
$(function() {
    $table.bootstrapTable({
        url: '/index.php/admin/record/glwornbh',
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
        showExport: true,
        columns: [
            { checkbox: true},
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title:'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Accounts',title: '帐号',align:'center'},
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title: '银行',align:'center'},
            { field: 'z_score',title: '总',align:'center'},
            { field: 'insertTime',title: '时间',align:'center'},
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

$("#delCheat").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    var msg = '确定删除？';
    if(confirm(msg) == true){
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['UserID'];
        });
        jQuery.post('/index.php/admin/record/delRelation',{arr:arr}).done(function(data){
            if(data.ret > 0){
                $table.bootstrapTable('refresh');
            }
        });
    }
});


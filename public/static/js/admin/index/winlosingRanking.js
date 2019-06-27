var $table = $("#tabless");
var hh = $("#queryForm");
var tis = 10;
$(function(){
    $table.bootstrapTable({
        url: '/index.php/admin/index/winlosingRanking',
        locale: "zh-CN",
        pageSize: 20,
        pageList: [20, 10],
        pagination: true,
        sortClass: 'table-active',
        sidePagination: 'server',
        clickToSelect: true,
        showRefresh: true,
        showToggle:true,
        searchOnEnterKey:true,
        columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center'},
            { field: 'GameSum',title: '输赢汇总',align:'center' },
        ],
        queryParams: function (params) {
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                tis: tis,
            };
            var hhs = hh.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){
            jQuery("input[name='startTime']").val(data.extend.arr1.t1);
            jQuery("input[name='endTime']").val(data.extend.arr1.t2);
        },
    });
});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("input[type=button]").click(function(){
    tis = jQuery(this).prop('value');
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});


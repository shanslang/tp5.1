var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {

    $table.bootstrapTable({
        url: '/index.php/admin/record/realCard',
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
            { field: 'BuildID',title:'生产批次',align:'center'},
            { field: 'BuildDate',title:'生成时间',align:'center'},
            { field: 'AdminName',title: '管理员',align:'center'},
            { field: 'SalesPerson',title: '销售商',align:'center'},
            { field: 'CardName',title: '实卡名称',align:'center'},
            { field: 'BuildCount',title: '实卡数量',align:'center'},
            { field: 'CardPrice',title: '实卡价格',align:'center' },
            { field: 'z_score',title: '总金额',align:'center' },
            { field: 'Currency',title: '赠送游戏豆',align:'center' },
            { field: 'Score',title: '赠送游戏金币',align:'center' },
            { field: 'BuildAddr',title: '地址',align:'center' },
            { field: 'DownLoadCount',title: '导出次数',align:'center'},
            { field: 'NoteInfo',title: '备注',align:'center' },
            { field: 'BuildID',title: '管理',align:'center' },
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
            jQuery("#t2").val(data.extend.t2);
            if(data.extend.status > 0){
                alert(data.extend.errmsg);
                return false;
            }
        },
    });

});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
        pageNumber: 1,
    });
});


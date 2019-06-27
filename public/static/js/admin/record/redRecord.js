var $table = $('#tabless');
var formmy = $("#queryForm");
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/record/redRecord',
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
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'OrderID',title:'订单号码',align:'center'},
            { field: 'Accounts',title: '用户标识',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',formatter: gameidReturn},
            { field: 'PayeeAccount',title: '支付宝账号',align:'center',formatter: redNickName},
            { field: 'PayeeRealName',title: '账户姓名',align:'center'},
            { field: 'Amount',title: '转账金额',align:'center'},
            { field: 'TransferDate',title: '记录时间',align:'center' },
            { field: 'TransferStatus',title: '状态',align:'center'},
            { field: 'SpreaderID',title: '渠道号',align:'center' },
            { field: 'SuccessDate',title: '发放时间',align:'center' },
            { field: 'ReasonId',title: '原因',align:'center' },
            { field: '',title: '返还',align:'center' },
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
            jQuery("span[name='znum']").text(data.extend.z_sl);
        },
    });

});


$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});



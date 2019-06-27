var $table = jQuery("#tabless");
$(function(){
	

	$table.bootstrapTable({
    	url: '/index.php/admin/index/queryOrder',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'ApplyDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',searchable:true, formatter: redirectGameID},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'OrderID', title: '订单号',align:'center'},
            { field: 'PayAmount',title: '金额',align:'center' },
            { field: 'OrderStatus',title:'状态',align:'center', formatter: getStatus},
            { field: 'ApplyDate',title:'订单时间',align:'center'},
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
        	var cardType = jQuery("#grade option:selected").val();
        	var info = jQuery("#info").val();
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
                cardType: cardType,
                info: info,
            };
            
            console.log(arr);
            return arr; 
        },
    });
})

function getParams(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function redirectGameID(value, row, index)
{
    url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function getStatus(value, row, index)
{
	var msg = '';
	switch(value)
	{
		case 0:
			msg = '<span class="badge badge-danger">未支付</span>';
			break;
		case 1:
			msg = '<span class="badge badge-warning">未到账</span>';
			break;
		case 2:
			msg = '<span class="badge badge-success">已完成</span>';
			break;
	}
	return msg;
}

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});
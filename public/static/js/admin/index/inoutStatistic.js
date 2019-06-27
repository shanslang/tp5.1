var $table = $("#tabless");

$(function(){

	$table.bootstrapTable({
    	url: '/index.php/admin/index/inoutStatistic',
    	locale: "zh-CN",
    	pagination: false,
    	sortClass: 'table-active',
    	sortName: 'CollectDate',
        sortOrder: 'desc',
    	// sidePagination: 'server',
    	// undefinedText: '-',
        // clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
    	columns: [
            { field: 'KindName',title:'游戏名',align:'center'},
            { field: 'ct',title: '次数',align:'center'},
        ],
        queryParams: function (params) {
            var uid = getParams('uid');
            var arr = {
                uid: uid, 
            };
            return arr; 
        },
        onLoadSuccess: function(data){
        	jQuery("#z_ct").html(data.extend.para);
        },
    });
});
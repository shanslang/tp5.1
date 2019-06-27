var $table = $("#tabless");

$(function(){
	$table.bootstrapTable({
    	url: '/index.php/admin/index/gameRecord',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'CollectDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	undefinedText: '-',
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'KindName',title: '游戏',align:'center', formatter: function(value, row, index){
            	return row.KindName+'-'+row.ServerName;
            }},
            // { field: 'SourceGameID',title: '桌子编号',align:'center'},
            { field: 'ChairID',title: '椅子编号',align:'center'},
            { field: 'Score', title: '输赢',align:'center'},
            { field: 'InsertTime',title: '记录时间',align:'center' },
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
        	var t1 = jQuery("input[name='startTime']").val();
        	var t2 = jQuery("input[name='endTime']").val();
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
                t1: t1,
                t2: t2,     
            };
            console.log(arr);
            return arr; 
        },
        onLoadSuccess: function(data){
        	jQuery("input[name='startTime']").val(data.extend.arr1.t1);
        	jQuery("input[name='endTime']").val(data.extend.arr1.t2);
        },
    });
});

$("#sub").click(function(){
	datetype = 0;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
var $table = $("#tabless");

$(function(){
	$table.bootstrapTable({
    	url: '/index.php/admin/index/goldRanking',
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
            { field: 'GameID',title: 'GameID',align:'center',searchable:true, formatter: gameidReturn},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Score',title: '金币',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_gold',title: '总金币',align:'center'},
            { field: 'SpreaderID',title: '渠道号',align:'center'},
        ],
        queryParams: function (params) {
        	var types = jQuery("#types option:selected").val();
        	var gid   = jQuery("#gids").val();
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                gid: gid,
                types: types,
            };
            
            return arr; 
        },
    });
});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
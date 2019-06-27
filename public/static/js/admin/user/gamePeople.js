var $table = $('#tabless');
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/gamePeople',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
        pagination: true,
        sidePagination: 'server',
        sortName: 'CollectDate',
        sortOrder: 'desc',
    	search: false,
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title: '序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center', formatter: redNickName},
            { field: 'ServerName',title: '游戏房间',align:'center'},
            { field: 'device',title: '设备',align:'center'},
            { field: 'Score',title: '游戏金币',align:'center'},
            { field: 'InsureScore',title: '银行',align:'center'},
            { field: 'z_score',title: '总金币',align:'center', sortable:true},
            { field: 'CollectDate',title: '时间',align:'center', sortable:true},
        ],
        queryParams: function (params) {
            var kid = getParams('kid');
            var type= getParams('type');
            var arr = {
                kid: kid,  //页码
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                sort: params.sort,
                type2: type,
            };
            return arr; 
        },
    });


});

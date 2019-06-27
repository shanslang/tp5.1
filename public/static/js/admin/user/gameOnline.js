var $table = $('#tabless');

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/gameOnline',
    	locale: "zh-CN",
    	pagination: false,
    	search: false,
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'ServerName',title:'房间名',align:'center', formatter: function(value, row, index){
                return '<a href="/index.php/admin/user/roomWin?serid='+row.ServerID+'" class="btn btn-link" title="游戏名" target="_blank">' + value + '</a>';
            }},
            { field: 'c',title: '人数',align:'center', formatter: function(value, row, index){
                return '<a href="/index.php/admin/user/gamePeople?kid='+row.ServerID+'&type=2" class="btn btn-link" title="人数">' + value + '</a>';
            }},
            { field: 'c_charge',title: '付费',align:'center'},
        ],
        queryParams: function (params) {
            var kid = getParams('kid');
            var arr = {
                kid: kid,  //页码
            };
            return arr; 
        },
    });


});
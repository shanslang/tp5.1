var $table = $('#tabless');

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/onlineGame',
    	locale: "zh-CN",
    	pagination: false,
    	search: false,
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'KindName',title:'游戏名',align:'center', formatter: function(value, row, index){
                return '<a href="/index.php/admin/user/gameOnline?kid='+row.KindID+'" class="btn btn-link" title="游戏名" target="_blank">' + value + '</a>';
            }},
            { field: 'c',title: '人数',align:'center', formatter: function(value, row, index){
                return '<a href="/index.php/admin/user/gamePeople?kid='+row.KindID+'&type=1" class="btn btn-link" title="人数">' + value + '</a>';
            }},
            { field: 'c_charge',title: '付费',align:'center'},
        ],
    });


});
var $table = $('#tabless');
var iswin = 0;
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/roomWin',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
        pagination: true,
        sidePagination: 'server',
    	search: false,
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title: '排名',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center', formatter: redNickName},
            { field: 'zong',title: '输赢',align:'center'},
        ],
        queryParams: function (params) {
            var serid = getParams('serid');
            var arr = {
                serid: serid,  //页码
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                iswin: iswin,
            };
            return arr; 
        },
        onLoadSuccess: function(data){
            document.getElementById("sername").innerHTML=data.extend.ServerName;
            // jQuery("#t2").val(data.extend.arr1.t2);
        },
    });


});

$("#setCheat").click(function(){
    iswin = 2;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#kickPeople").click(function(){
    iswin = 1;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
var $table = $('#tabless');


$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/index/otherGold',
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
            { field: 'RecordNote',title: '来源',align:'center'},
            { field: 'GoodsCount',title: '数量',align:'center'},
            { field: 'RecodrdDate', title: '时间',align:'center'},
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
            };
            
            return arr; 
        },
        onLoadSuccess: function(data){
        	jQuery("#returns").prop("href", "/index.php/admin/index/userInfoUid?uid="+data.extend.uid);
        },
    });


});

var $table2 = $("#table2");
$("#custom-nav-profile-tab").click(function(){
	$table2.bootstrapTable({
		url: '/index.php/admin/index/otherGold2',
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
            { field: 'Reason',title: '来源',align:'center'},
            { field: 'AddScore',title: '数量',align:'center'},
            { field: 'CollectDate', title: '时间',align:'center'},
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
            };
            
            return arr; 
        },
	});
});

var $table3 = $("#table3");
$("#custom-nav-email-tab").click(function(){
    $table3.bootstrapTable({
        url: '/index.php/admin/index/otherGold3',
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
            { field: 'Title',title: '来源',align:'center'},
            { field: 'gold',title: '数量',align:'center'},
            { field: 'receiveTime', title: '时间',align:'center'},
        ],
        queryParams: function (params) {
            var uid = getParams('uid');
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
            };
            
            return arr; 
        },
    });
});

function getParams (name, url) {
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


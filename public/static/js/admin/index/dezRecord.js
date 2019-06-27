var $table = $("#tabless");

$(function(){

	$table.bootstrapTable({
    	url: '/index.php/admin/index/dezRecord',
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
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'KindName',title: '游戏名',align:'center'},
            { field: 'InsertTime',title: '记录时间',align:'center'},
            { field: 'GameID1',title: '本家GameID',align:'center', formatter: gameidReturn1},
            { field: 'NickName1', title: '本家昵称',align:'center', formatter: redNickName1},
            { field: 'GameID2',title: '对家GameID',align:'center', formatter: gameidReturn2},
            { field: 'NickName2',title:'对家昵称',align:'center', formatter: redNickName2},
            { field: 'Score1',title: '输赢',align:'center'},
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
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

function gameidReturn1(value, row, index)
{
    var url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID1;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function gameidReturn2(value, row, index)
{
    var url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID2;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function redNickName1(value, row, index)
{
    if(row.Member1 > 0){
        return '<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return value;
    }
}

function redNickName2(value, row, index)
{
    if(row.Member2 > 0){
        return '<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return value;
    }
}

var $table = $("#tabless");

$(function(){
	jQuery.post("/index.php/admin/Setmultiplex/gameName",{}).done(function(data){
		// console.log(data);
		jQuery.each(data, function(index, item){
			jQuery("<option value='"+item.KindID+"'>"+item.KindName+"</option>").appendTo("#KindID");    
		});
	});

	$table.bootstrapTable({
    	url: '/index.php/admin/index/inoutRecord',
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
            { field: 'KindName',title: '游戏名',align:'center'},
            { field: 'ServerName',title: '房间名',align:'center'},
            { field: 'enti',title: '进入时间',align:'center'},
            { field: 'EnterScore', title: '进入金币',align:'center'},
            { field: 'EnterInsure',title: '进入保险箱',align:'center' },
            { field: 'EnterClientIP',title:'进入IP',align:'center'},
            { field: 'leti',title: '离开时间',align:'center'},
            { field: 'LeaveClientIP',title: '离开IP',align:'center'},
            { field: 'LeaveReason',title: '离开原因',align:'center', formatter: leaveReason},
            { field: 'Score', title: '分数变更',align:'center'},
            { field: 'Insure',title: '保险箱变更',align:'center' },
            { field: 'leav_gold',title: '离开金币',align:'center'},
            { field: 'leav_Insure',title: '离开银行',align:'center'},
            { field: 'leav_z', title: '离开总',align:'center'},
            { field: 'PlayTimeCount',title: '游戏时间',align:'center' },
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
        	var t1 = jQuery("input[name='startTime']").val();
        	var t2 = jQuery("input[name='endTime']").val();
        	var KindID = jQuery("#KindID option:selected").val();
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
                t1: t1,
                t2: t2, 
                KindID: KindID,    
            };
            console.log(arr);
            return arr; 
        },
        onLoadSuccess: function(data){
        	jQuery("input[name='startTime']").val(data.extend.arr1.t1);
        	jQuery("input[name='endTime']").val(data.extend.arr1.t2);
        	jQuery("#z_score").html(data.extend.arr1.z_score);
        },
    });
});

function leaveReason(value, row, index)
{
	switch(value)
	{
		case 0: return '常规离开';break;
		case 1: return '系统原因';break;
		case 2: return '网络原因';break;
		case 3: return '用户冲突';break;
		case 4: return '人满为患';break;
		case 5: return '条件限制';break;
		default: return '未知';break;
	}
}

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
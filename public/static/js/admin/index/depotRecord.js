var $table = $("#tabless");
var datetype = 0;
$(function(){
	$table.bootstrapTable({
    	url: '/index.php/admin/index/depotRecord',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'CollectDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	// undefinedText: '-',
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'CollectDate',title: '交易时间',align:'center'},
            { field: 'SourceGameID',title: '发起人GameID',align:'center', formatter: redirectGameID},
            { field: 'SourceNickname',title: '发起人',align:'center',formatter: redNickName},
            { field: 'TargetGameID', title: '接收人GameID',align:'center', formatter: redirectGameID2},
            { field: 'TargetNickName',title: '接收人',align:'center',formatter: redNickName2 },
            { field: 'TradeType',title:'操作类型',align:'center', formatter: getTradeType},
            { field: 'SwapScore',title:'交易金额',align:'center'},
            { field: 'Revenue',title:'服务费',align:'center'},
            { field: 'Gold',title:'交易后游戏币',align:'center'},
            { field: 'Bank',title:'交易后银行',align:'center'},
            { field: 'z_jb',title:'交易后总额',align:'center'},
        ],
        queryParams: function (params) {
        	var uid = getParams('uid');
        	var cardType = jQuery("#grade option:selected").val();
        	var t1 = jQuery("input[name='startTime']").val();
        	var t2 = jQuery("input[name='endTime']").val();
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                uid: uid,
                cardType: cardType,
                t1: t1,
                t2: t2,
          		datetype: datetype,      
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

function redirectGameID(value, row, index)
{
	url = "/index.php/admin/index/userInfoUid?uid=" + row.SourceUserID;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function redirectGameID2(value, row, index)
{
	url = "/index.php/admin/index/userInfoUid?uid=" + row.TargetUserID;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function redNickName(value, row, index)
{
    if(row.SourceMemberOrder > 0){
        return '<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return value;
    }
}

function redNickName2(value, row, index)
{
    if(row.TargetMemberOrder > 0){
        return '<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return value;
    }
}

function getTradeType(value, row, index)
{
	if(value == 1)
	{
		return '存款';
	}else if(value == 2)
	{
		return '取款';
	}else if(value == 3){
		var uid = getParams('uid');
		if(row.SourceUserID == uid){
			return '转出';
		}else{
			return '转入';
		}
	}else if(value == 4){
		return '<span class="badge badge-danger">异常</span>';
	}else if(value == 5){
        return '领取税收';
    }
}

$("#sub").click(function(){
	datetype = 0;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#sub1").click(function(){
	datetype = 1;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#sub2").click(function(){
	datetype = 2;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#sub3").click(function(){
	datetype = 3;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#sub4").click(function(){
	datetype = 4;
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
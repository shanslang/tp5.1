var $table = $('#tabless');
var hh = $("#queryForm");
var ids = 6;

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/vip/transList',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'ApplyDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	search: false,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'CollectDate',title: '转账时间',align:'center'},
            { field: 'SourceGameID',title: '支付人GameID	',align:'center'},
            { field: 'Sourcename',title: '支付人',align:'center',formatter: redNickName1},
            { field: 'SourceMember', title: '是否vip',align:'center', formatter: function(value, row, index){
                if (value > 0) {
                    return '<span class="badge badge-danger">VIP</span>';
                }else{
                    return '普通';
                }
            }},
            { field: 'sourceSpreaderID',title: '渠道号',align:'center' },
            { field: 'SourceIP',title: 'IP',align:'center'},
            { field: 'TargetGameID',title:'接收人GameID',align:'center'},
            { field: 'Targetname',title:'接收人',align:'center', formatter: redNickName2},
            { field: 'TargetMember',title:'是否vip',align:'center', formatter: function(value, row, index){
                if (value > 0) {
                    return '<span class="badge badge-danger">VIP</span>';
                }else{
                    return '普通';
                }
            }},
            { field: 'targetSpreaderID',title:'渠道号',align:'center'},
            { field: 'TargetIP',title:'IP',align:'center'},
            { field: 'SwapScore',title:'支付金额',align:'center'},
            { field: 'Revenue',title:'税收金额',align:'center'},
        ],
        queryParams: function (params) {
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                ids: ids,
            };
            var hhs = hh.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }

            $table.extend(arr, obj);

            return arr; 
        },
        onLoadSuccess: function(data){
            // console.log(data);
            document.getElementById("z_ss").innerHTML= data.extend.arr1.z_ss;
            document.getElementById("zr_sum").innerHTML= data.extend.arr1.zr_sum;
            document.getElementById("zc_sum").innerHTML= data.extend.arr1.zc_sum;
            jQuery("#t1").val(data.extend.arr1.t1);
        },
    });


});

function redNickName1(value, row, index)
{
	var url = "/index.php/admin/index/userInfoUid?uid=" + row.SourceUserID;
	if(row.Sourcemem > 0){
		return '<a href="' + url + '" class="btn btn-link" title="用户信息"><span style="color:#f00;">' + value + '</span></a>';
	}else{
		return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
	}
}

function redNickName2(value, row, index)
{
	var url = "/index.php/admin/index/userInfoUid?uid=" + row.TargetUserID;
	if(row.Targetmem > 0){
		return '<a href="' + url + '" class="btn btn-link" title="用户信息"><span style="color:#f00;">' + value + '</span></a>';
	}else{
		return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
	}
}

$("#sub").click(function(){
	$table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#nav-tab a").click(function(){
	ids = jQuery(this).index()+1;
	$table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
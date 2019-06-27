var $table = $('#tabless');
var formmy = $("#queryForm");
var success = 2;
$(function() {
    $table.bootstrapTable({
        url: '/index.php/admin/record/fareRecord',
        locale: "zh-CN",
        pageSize: 20,
        pageList: [20, 10],
        pagination: true,
        sortClass: 'table-active',
        sortName: 'LastLogonDate',
        sortOrder: 'desc',
        sidePagination: 'server',
        search: false,
        clickToSelect: true,
        showRefresh: true,
        showToggle:true,
        searchOnEnterKey:true,
        showExport: true,
        columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'Accounts',title:'用户账号',align:'center',formatter: gameidReturn},
            { field: 'MobilePhone',title: '手机号码',align:'center'},
            { field: 'GoodsName',title: '物品名称',align:'center'},
            { field: 'GoodsCount',title: '物品数量',align:'center'},
            { field: 'SpreaderID',title: '渠道号',align:'center' },
            { field: 'ExchangeDate',title: '兑换日期',align:'center'},
            { field: 'Success',title: '发放标识',align:'center', formatter: identifi},
            { field: 'SuccessDate',title: '记录时间',align:'center'},
            { field: 'SuccessNote',title: '发放备注',align:'center'}, 
        ],
        queryParams: function (params) {
            var hhs = formmy.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                success: success,
            };
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){
            jQuery("span[name='znum']").text(data.extend.z_sl);
        },
    });

});

function identifi(value, row, index)
{
    if(value == 1)
    {
        return '<span class="badge badge-pill badge-success">已发放</span>';
    }else{
        return '<div class="input-group"><input type="text" name="input2-group2" placeholder="发放备注" class="form-control"><div class="input-group-btn"><button class="btn btn-primary" onclick="grants('+row.RecordID+', this)">发放</button></div></div>';
    }
}

function grants(reid, thiss)
{
    var val = thiss.parentNode.parentNode;
  	var val3 = val.firstChild.value;
  	if(val3.length == 0){
    	alert('发放备注不能为空');
      	return false;
    }
    jQuery.post('/index.php/admin/record/editFare',{reid:reid, remark:val3}).done(function(data){
        if(data > 0){
          	$table.bootstrapTable('refresh',{
                    // pageNumber: 1,
            });
			return;
       }
    });
}

$("#sub").click(function(){
     success = 2;
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#btn_isf").click(function(){
    success = 1;
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#btn_isf2").click(function(){
    success = 0;
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});



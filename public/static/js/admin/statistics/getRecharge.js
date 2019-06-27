var $table = $('#tabless');
var hh = $("#queryForm");

$(function() {
    

    // $('#tabless').bootstrapTable('destroy');
    $table.bootstrapTable({
    	url: '/index.php/admin/statistics/getRecharge',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sidePagination: 'server',
    	search: false,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
        // undefinedText: 0,
    	columns: [
            { field: 'dates',title:'日期',align:'center'},
            { field: 'sums',title: '充值总',align:'center'},
            { field: '1',title: '实卡',align:'center'},
            { field: '43',title:'春启向阳支付宝',align:'center'},
            { field: '9',title:'春启向阳快捷',align:'center'},
            { field: '42',title:'七分支付宝',align:'center'},
            { field: '3',title:'七分微信',align:'center'},
            { field: '35',title: 'ZCY支付宝',align:'center'},
            { field: '34',title: 'ZCY快捷',align:'center'},
            { field: 'golds',title:'增加金币',align:'center'},
        ],
        queryParams: function (params) {
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
            };
            var hhs = hh.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            // console.log(obj);

            $table.extend(arr, obj);
            // console.log(hhs);
            // console.log(obj);
            console.log(arr);
            return arr; 
        },
        onLoadSuccess: function(data){
            console.log(data);
            document.getElementById("z_recharge").innerHTML= data.extend.z_sums;
        },
    });


});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});


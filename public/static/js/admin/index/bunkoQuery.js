var $table = $('#tabless');
var hh = $("#queryForm");

$(function() {
    jQuery.post("/index.php/admin/Setmultiplex/gameName",{}).done(function(data){
        // console.log(data);
        jQuery.each(data, function(index, item){
            jQuery("<option value='"+item.KindID+"'>"+item.KindName+"</option>").appendTo("#KindID");    
        });
    });
    
    $table.bootstrapTable({
    	url: '/index.php/admin/index/bunkoQuery',
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
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'Accounts',title: '帐号	',align:'center'},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'KindName', title: '游戏',align:'center', formatter: function(value, row, index){
                return value+'-'+row.ServerName;
            }},
            { field: 'Score',title: '输赢情况',align:'center' },
            { field: 'InsertTime',title: '时间',align:'center'},
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

            $table.extend(arr, obj);

            return arr; 
        },
        onLoadSuccess: function(data){
            // console.log(data);
            document.getElementById("z_score").innerHTML= data.extend.arr1.z_score;
            // jQuery("#t1").val(data.extend.arr1.t1);
        },
    });


});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});
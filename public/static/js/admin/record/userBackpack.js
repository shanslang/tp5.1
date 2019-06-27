var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/record/userBackpack',
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
    	columns: [
            { checkbox: true},
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'RowNumber',title:'管理',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'GoodsName',title: '物品名称',align:'center'},
            { field: 'GoodsCount',title: '物品数量',align:'center' },
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
            };
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){

        },
    });
    jQuery.post("/index.php/admin/Setmultiplex/backpackType",{}).done(function(data){
        //console.log(data);
        jQuery.each(data, function(index, item){
            jQuery("<option value='"+item.GoodsID+"'>"+item.GoodsName+"</option>").appendTo("#type1");    
        });
    });

});



$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

$("#setCheat").click(function(){
    
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    jQuery('#myModal').modal();
    jQuery.post("/index.php/admin/Setmultiplex/backpackType",{}).done(function(data){
        //console.log(data);
        jQuery.each(data, function(index, item){
            jQuery("<option value='"+item.GoodsID+"'>"+item.GoodsName+"</option>").appendTo("#type2");    
        });
    });
    
});

$("#btn_submit").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    var arr = new Array();
    jQuery.each(getSelectRows, function(index, value){
        arr[index] = value['UserID'];
    });
    var grade = jQuery("#type2 option:selected").val();
    var wpsl = jQuery("input[name='wpsl']").val();
    jQuery.post('/index.php/admin/record/sendBack',{grade:grade, wpsl:wpsl, arr:arr}).done(function(data){
      //  console.log(data);
        if(data > 0){
            $table.bootstrapTable('refresh');
        }
    });
});

$("#fhao").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    var msg = '确定删除？';
    if(confirm(msg) == true){
        var arr = new Array();
        var gid = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['UserID'];
            gid[index] = value['GoodsID'];
        });
        jQuery.post('/index.php/admin/record/backDel',{arr:arr, gid:gid}).done(function(data){
           // console.log(data);
            if(data == 0){
                $table.bootstrapTable('refresh');
            }
        });
    }
});

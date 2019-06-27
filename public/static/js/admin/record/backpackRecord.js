var $table = $('#tabless');
var formmy = $("#queryForm");
var isImport = 1;
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/record/backpackRecord',
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
            { checkbox: true},
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'Accounts',title:'账号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'RecordType',title: '记录类型',align:'center',formatter:function(value, row, index){
                if(value == 2){
                    return '获得';
                }else{
                    return '消耗';
                }
            }},
            { field: 'GoodsName',title: '物品名称',align:'center'},
            { field: 'GoodsCount',title: '物品数量',align:'center' },
            { field: 'RecordNote',title: '记录备注',align:'center'},
            { field: 'RecodrdDate',title: '记录日期',align:'center' },
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
                isImport: isImport, 
            };
            $table.extend(arr, obj);
            return arr; 
        },
        onLoadSuccess: function(data){
            jQuery("span[name='znum']").text(data.extend.z_sl);
        },
    });
    jQuery.post("/index.php/admin/Setmultiplex/backpackType",{}).done(function(data){
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
    
});

$("#btn_submit").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    var arr = new Array();
    jQuery.each(getSelectRows, function(index, value){
        arr[index] = value['UserID'];
    });
    var grade = jQuery("#type2 option:selected").val();
    jQuery.post('/index.php/admin/Setmultiplex/setCheat',{grade:grade, arr:arr}).done(function(data){
        if(data > 0){
            $table.bootstrapTable('refresh');
        }
    });
});

$("#bt_export").click(function(){
    //isImport = 0;
  	var hhs = formmy.serializeArray();
    var obj = { };
    for (var item in hhs){
      obj[hhs[item].name] = hhs[item].value;
   }
    jQuery.post('/index.php/admin/record/backpackRecordExport',{obj:obj}).done(function(data){
       // console.log(data);
        //console.log(data.url);
      download_export(data.url, data.method, data.filedir, data.filename);
    });
});
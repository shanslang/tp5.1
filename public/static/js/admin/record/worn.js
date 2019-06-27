var $table = $('#tabless');
var formmy = $("#queryForm");
$(function() {
    $table.bootstrapTable({
        url: '/index.php/admin/record/worn',
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
            { field: 'GameID',title:'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'CheatLevel',title: '虫子等级',align:'center',formatter: cheatOrder},
            { field: 'nControlPer',title: '胜率',align:'center'},
            { field: 'Username',title: '操作人',align:'center' },
            { field: 'wormTime',title: '时间',align:'center'},
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
    });

});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});

function cheatOrder(value, row, index)
{
    switch(value)
    {
        case 1: return '一级'; break;
        case 2: return '二级'; break;
        case 3: return '三级'; break;
        default: return '';break;
    }
}

$("#setCheat").click(function(){
    
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    jQuery("#myModalLabel").text("设置虫子");
    jQuery('#myModal').modal();
    
});

$("#btn_submit").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    var arr = new Array();
    jQuery.each(getSelectRows, function(index, value){
        arr[index] = value['UserID'];
    });
    var grade = jQuery("#grade option:selected").val();
    jQuery.post('/index.php/admin/setmultiplex/setCheat',{grade:grade, arr:arr}).done(function(data){
        if(data > 0){
            $table.bootstrapTable('refresh');
        }
    });
});

$("#sub_setW").click(function(){
    var set_form = jQuery("#f_setWorn");
    var hhs = set_form.serializeArray();
    var arr = { };
    for (var item in hhs){
        arr[hhs[item].name] = hhs[item].value;
    }
    var chs = {};
  	jQuery("input[name = xz]:checked").each(function(index){ 
        chs[index] = this.value;
      	//alert(this.value);
    });
    arr['xz'] = chs;
    console.log(arr);
   // console.log(chs);
    jQuery.post('/index.php/admin/record/setCheat',{arr:arr}).done(function(data){
        console.log(data);
        alert(data.msg);
      	$table.bootstrapTable('refresh');
    });
});
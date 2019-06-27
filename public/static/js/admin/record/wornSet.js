var $table = $('#tabless');
var wornsl = new Array();
$(function() {
    $table.bootstrapTable({
        url: '/index.php/admin/record/wornSet',
        locale: "zh-CN",
        pagination: false,
        search: false,
        showRefresh: true,
        showToggle:true,
        searchOnEnterKey:true,
        showExport: true,
        columns: [
            { field: 'CheatLevel',title: '虫子等级',align:'center',formatter: cheatOrder},
            { field: 'nControlPer',title: '胜率',align:'center'},
            { field: 'LastModify',title: '时间',align:'center'},
        ],
        onLoadSuccess: function(data){
            wornsl = data['rows'];
            jQuery("#worn_sl").val(data['rows'][0]['nControlPer']);
        },
    });

});

function cheatOrder(value, row, index)
{
    switch(value)
    {
        case '1': return '一级'; break;
        case '2': return '二级'; break;
        case '3': return '三级'; break;
        default: return value;break;
    }
}

$("#selectLg").change(function(){
    var index = jQuery(this).val() - 1;
    jQuery("#worn_sl").val(wornsl[index]['nControlPer']);
});

$("#sub_setW").click(function(){
    var wormG = jQuery("#selectLg").find('option:selected').prop('value');
    var sv    = jQuery("#worn_sl").val();
    if(isNaN(sv))
    {
        alert('必须是数字');
        return false;
    }
    jQuery.post('/index.php/admin/record/wornGradeSet',{wormG:wormG, sl:sv}).done(function(data){
        // console.log(data);
        alert(data.msg);
        if(data.ret == 0){
            $table.bootstrapTable('refresh');
        }
    });
});
var $table = $('#tabless');
$(function() {
    $table.bootstrapTable({
        url: '/index.php/admin/record/wornSetrecord',
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
            { field: 'Username',title: '操作人',align:'center'},
            { field: 'registerIP',title: '同IP',align:'center' },
            { field: 'RegisterMachine',title: '同机器码',align:'center'},
            { field: 'infoMobile',title: '同手机号',align:'center'},
            // { field: 'wormTime',title: '同设备名称',align:'center'},
            { field: 'insertTime',title: '时间',align:'center'},
        ],
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

$("#delCheat").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    var msg = '确定删除？';
    if(confirm(msg) == true){
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['ID'];
        });
        jQuery.post('/index.php/admin/record/delWormset',{arr:arr}).done(function(data){
            if(data.ret > 0){
                $table.bootstrapTable('refresh');
            }
        });
    }
});

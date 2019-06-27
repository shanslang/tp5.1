var $table = $('#tabless');
var formmy = $("#queryForm");
$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/record/recUngame',
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
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',formatter: gameidReturn},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'RechargeSum',title: '充值金额',align:'center'},
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_score',title:'总',align:'center',},
            { field: 'Zr',title: '转入',align:'center'},
            { field: 'Zc',title: '转出',align:'center'},
            { field: 'LastLogonMachine',title:'最后登录机器码',align:'center'},
            { field: 'RegisterIP',title:'注册IP',align:'center'},
            { field: 'RegisterDate',title:'注册时间',align:'center'},
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


var $table = $('#tabless');
var formmy = $("#queryForm");

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/fhaoRecord',
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
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'Score',title: '金币',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'gold_sum',title:'总金币',align:'center'},
            { field: 'type',title:'状态',align:'center'},
            { field: 'LastLogonDate',title:'最后登录时间',align:'center'},
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
            // jQuery("#t1").val(data.extend.arr1.t1);
            // jQuery("#t2").val(data.extend.arr1.t2);
        },
    });


});

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
    var val = jQuery("select[name = 'isvip']").find('option:selected').prop('value');
    if(val == 2)
    {
        jQuery("#kickPeople").prop('disabled', false);
    }else{
        jQuery("#kickPeople").prop('disabled', true);
    }
});

$("#kickPeople").click(function(){
    var msg = "此操作会清除金币，请确定";
    if(confirm(msg) == true)
    {
        var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
        if(getSelectRows.length == 0){
            alert('请先选择要操作的对象');
            return false;
        }
        var arr = new Array();
        jQuery.each(getSelectRows, function(index, value){
            arr[index] = value['UserID'];
        });
        jQuery.post('/index.php/admin/user/editNickname', {arr: arr}).done(function(data){
            alert(data);
            $table.bootstrapTable('refresh',{
                     pageNumber: 1,
            });
        });
    }
});


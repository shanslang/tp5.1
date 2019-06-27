var $table = $('#tabless');

// $table.on('load-success.bs.table', function(e, data) {
//     console.log(data.extend.z_recharge);
//     document.getElementById("z_recharge").innerHTML=data.extend.z_recharge;
// });


$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/index/getOnlineUser',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'CollectDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	search: true,
        formatSearch: function () {
            return "请输入GameID";
        },
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { checkbox: true},
            { field: 'UserID',visible: false},
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',searchable:true, formatter: redirectGameID},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'ServerName', title: '游戏房间',align:'center'},
            { field: 'Score',title: '游戏',align:'center' },
            { field: 'InsureScore',title:'银行',align:'center'},
            { field: 'z_gold',title:'总',align:'center',sortable: true},
            { field: 'device',title:'登录方式',align:'center'},
            { field: 'SpreaderID',title:'渠道号',align:'center'},
            { field: 'CollectDate',title:'进入时间',align:'center',sortable: true},
            { field: 'CheatLevel',title:'虫子等级',align:'center',formatter: cheatOrder},
            { field: 'RegisterDate',title:'注册时间',align:'center'},
        ],
        queryParams: function (params) {
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                order : params.order,
                search : params.search,
                sort : params.sort,
            };
            
            // console.log(arr);
            return arr; 
        },
        onLoadSuccess: function(data){
            // console.log(data);
            document.getElementById("z_score").innerHTML= data.extend.zong.z_score;
            document.getElementById("gameGold").innerHTML= data.extend.zong.gameGold;
            document.getElementById("ffCt").innerHTML= data.extend.zong.ffCt;
            document.getElementById("mfCt").innerHTML= data.extend.zong.mfCt;
            document.getElementById("mobileCt").innerHTML= data.extend.zong.mobileCt;
            document.getElementById("ct").innerHTML= data.extend.zong.ct;
            document.getElementById("ffIphone").innerHTML= data.extend.zong.ffIphone;
            document.getElementById("ffAnd").innerHTML= data.extend.zong.ffAnd;
            document.getElementById("ffPc").innerHTML= data.extend.zong.ffPc;
            document.getElementById("mfIphone").innerHTML= data.extend.zong.mfIphone;
            document.getElementById("mfAnd").innerHTML= data.extend.zong.mfAnd;
            document.getElementById("mfPc").innerHTML= data.extend.zong.mfPc;
        },
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

function redirectGameID(value, row, index)
{
    url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息">' + value + '</a>';
}

function redNickName(value, row, index)
{
    var Cheat = '';
    switch(row.CheatLevel)
    {
        case 1: Cheat = '<i class="fa fa-bug" style="color:#000;"></i>'; break;
        case 2: Cheat = '<i class="fa fa-bug" style="color:#00CD66;"></i>'; break;
        case 3: Cheat = ' <i class="fa fa-bug" style="color:#F00;"></i>'; break;
    }
    var isMobile = '';
    if(row.exchangeMobile > 0){
        isMobile = '<i class="fa fa-mobile" style="color:#00CD66;"></i>';
    }
    if(row.MemberOrder > 0){
        return Cheat+'<label for="NickName" style="color:red;">'+value+'</label>'+isMobile;
    }else{
        return Cheat+value+isMobile;
    }
}

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});



$("#kickPeople").click(function(){
    var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
    var msg = "您确定踢人吗？";
    var arr = new Array();
    if (confirm(msg)==true){
        jQuery.each(getSelectRows,function(index,value){
            // alert(value['UserID']);
            arr[index] = value['UserID'];
        });
        // console.log(arr);
        jQuery.get('/index.php/admin/index/kickPeople',{arr:arr}).done(function (data){
            // console.log(data);
            // alert(data);
            $table.bootstrapTable('refresh');
        });
    }
});

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
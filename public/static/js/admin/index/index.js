var $table = jQuery('#tabless');
$(function() {

	var sel = getParams('sel');
	var info = getParams('info');
	if(sel != null)
	{
		jQuery("#hiddenid").show();
		
		$table.bootstrapTable({
	    	url: '/index.php/admin/index/index',
	    	locale: "zh-CN",
	    	pageSize: 20,
	    	pageList: [20, 10],
	    	pagination: true,
	    	sortClass: 'table-active',
	    	sortName: 'name',
	    	sidePagination: 'server',
	    	search: false,
	    	showRefresh: true,
	    	showToggle:true,
	    	clickToSelect: true,
	    	columns: [
	            { checkbox: true,align:'center' },
	            { field: 'RowNumber',title: '序号',align:'center' },
	            { field: 'GameID',title:'游戏ID',align:'center', formatter: redirectGameID },
	            { field: 'Accounts',title:'账号',align:'center' },
	            { field: 'NickName',title: '昵称',align:'center', formatter: redNickName },
	            { field: 'Score',title:'金币',align:'center' },
	            { field: 'InsureScore',title:'银行',align:'center' },
	            { field: 'gold_sum',title:'总额',align:'center' },
	            { field: 'CustomFaceVer',title: '屏蔽状态',align:'center', formatter:function(value, row, index){
	            	if(value > 0){
	            		return "<span style='color:red;'>屏蔽中</span>";
	            	}else{
	            		return '正常';
	            	}
	            }},
	            { field: 'Nullity',title:'封号状态',align:'center', formatter:function(value, row, index){
	            	if(value > 0){
	            		return "<span style='color:red;'>封号中</span>";
	            	}else{
	            		return '正常';
	            	}
	            }},
	            { field: 'RegisterDate',title:'注册时间',align:'center' },
	            { field: 'LastLogonDate',title: '最后登陆时间',align:'center' },
	            { field: 'GameSum',title:'游戏所得',align:'center', formatter: function(value, row, index){
	            	// return '<button type="button" class="btn btn-success mb-1" data-toggle="modal" id="getgame" data-target="#largeModal">游戏所得</button>';
	            	return '<button type="button" class="btn btn-success mb-1" onclick="gamescore('+row.UserID+')" data-target="#largeModal">游戏所得</button>';
	            }},
	            { field: 'CheatLevel',title:'虫子等级',align:'center' ,formatter: cheatOrder},
	        ],
	        queryParams: function (params) {
	        	var arr = {
	                offset: params.offset,  //页码
	                limit: params.limit,   //页面大小
	                sel: sel,
	                info:info,
	            };
	            return arr;
	        },
	    });
	}
    
})

function gamescore(uid)
{
	jQuery.post('/index.php/admin/index/getGameSum', {uid:uid}).done(function(data){
		jQuery("#gamescore").text(data);
	});
	jQuery('#largeModal').modal();
}

function getParams(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
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

    if(row.MemberOrder > 0){
        return Cheat+'<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return Cheat+value;
    }
}

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

$("#fhao").click(function(){
	var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
	var msg = '确定封号？';
	if(confirm(msg) == true){
		var arr = new Array();
	    jQuery.each(getSelectRows, function(index, value){
	        arr[index] = value['UserID'];
	    });
	    jQuery.post('/index.php/admin/setmultiplex/fhao',{arr:arr,type:1}).done(function(data){
	    	if(data > 0){
	    		$table.bootstrapTable('refresh');
	    	}
	    });
	}
});

$("#jiefen").click(function(){
	var getSelectRows = $table.bootstrapTable('getSelections', function (row) {});
    if(getSelectRows.length == 0){
        alert('请先选择要操作的对象');
        return false;
    }
	var msg = '确定解封？';
	if(confirm(msg) == true){
		var arr = new Array();
	    jQuery.each(getSelectRows, function(index, value){
	        arr[index] = value['UserID'];
	    });
	    jQuery.post('/index.php/admin/setmultiplex/fhao',{arr:arr,type:0}).done(function(data){
	    	if(data > 0){
	    		$table.bootstrapTable('refresh');
	    	}
	    });
	}
});
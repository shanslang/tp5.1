var $table = $('#tabless');
var hh = $("#queryForm");
var ids = 1;

$(function() {
    
    $table.bootstrapTable({
    	url: '/index.php/admin/user/queryRelevance',
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
            { checkbox: true,align:'center' },
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gameidReturn},
            { field: 'Accounts',title:'账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center', formatter: redNickName},
            { field: 'Score',title: '金币',align:'center'},
            { field: 'InsureScore', title: '银行',align:'center'},
            { field: 'z_score',title: '总额',align:'center' },
            { field: 'CustomFaceVer',title: '屏蔽状态',align:'center', formatter: function(value, row, index){
                if(value>0){
                    return '<span style="color:red;">屏蔽中</span>';
                }else{
                    return '正常';
                }
            }},
            { field: 'Nullity',title:'封号状态',align:'center', formatter: function(value, row, index){
                if(value>0){
                    return '<span style="color:red;">封号中</span>';
                }else{
                    return '正常';
                }
            }},
            { field: 'RegisterDate',title:'注册时间',align:'center'},
            { field: 'LastLogonDate',title:'最后登录时间',align:'center'},
            { field: 'operation',title:'游戏所得',align:'center', formatter: function(value, row, index){
                return '<button type="button" class="btn btn-success mb-1" onclick="gamescore('+row.UserID+')" data-target="#largeModal">游戏所得</button>';
            }},
            { field: 'CheatLevel',title:'虫子等级',align:'center', formatter: function(value, row, index){
                if(value == 0){
                    return '';
                }else if(value == 1){
                    return '一级';
                }else if(value == 2){
                    return '二级';
                }else{
                    return '三级';
                }
            }},
        ],
        queryParams: function (params) {
            var uid = getParams('uids');
            var con = jQuery("input[name=con]").prop("value");
            if(uid != null && con.length == 0){
                jQuery("select[name=type]").find("option[value='4']").prop("selected",true);
                jQuery("input[name=con]").prop("value",uid);
            }
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                ids: ids,
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
            document.getElementById("z_ss").innerHTML= data.extend.games;
        },
    });


});

function gamescore(uid)
{
    jQuery.post('/index.php/admin/index/getGameSum', {uid:uid}).done(function(data){
        jQuery("#gamescore").text(data);
    });
    jQuery('#largeModal').modal();
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
        // console.log(data);
        if(data > 0){
            $table.bootstrapTable('refresh');
        }
    });
});

$("#sub").click(function(){
	$table.bootstrapTable('refresh',{
            pageNumber: 1,
    });
});

$("#nav-tab a").click(function(){
	ids = jQuery(this).index()+1;
	$table.bootstrapTable('refresh',{
            pageNumber: 1,
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
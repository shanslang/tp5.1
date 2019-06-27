var $table = $('#tabless');
var hh = $("#queryForm");

// $table.on('load-success.bs.table', function(e, data) {
//     console.log(data.extend.z_recharge);
//     document.getElementById("z_recharge").innerHTML=data.extend.z_recharge;
// });


$(function() {
    

    // $('#tabless').bootstrapTable('destroy');
    $table.bootstrapTable({
    	url: '/index.php/admin/index/rechargeRecord',
    	locale: "zh-CN",
    	pageSize: 20,
        pageList: [20, 10],
    	pagination: true,
    	sortClass: 'table-active',
    	sortName: 'ApplyDate',
        sortOrder: 'desc',
    	sidePagination: 'server',
    	search: true,
        formatSearch: function () {
            return "请输入GameID";
        },
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'RowNumber',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center',searchable:true, formatter: redirectGameID},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName',title: '昵称',align:'center',formatter: redNickName},
            { field: 'OrderID', title: '订单号',align:'center', formatter: function(value, row, index){
                if (row.CurrencyType>0) {
                    var url = "/index.php/admin/index/queryOrder?uid=" + row.GameID;
                    return '<a href="' + url + '" class="btn btn-success" title="OrderID" target="_blank">' + value + '</a>';
                }else{
                    var url = "/index.php/admin/index/LivcardManage?SerialID=" + row.SerialID;
                    return '<a href="' + url + '" class="btn btn-success" title="OrderID">' + row.SerialID + '</a>';
                }
            }},
            { field: 'PayAmount',title: '金额',align:'center' },
            // { field: 'GameID',title: 'GameID',sortable:true ,},
            { field: 'CurrencyType',title: '充值类型',align:'center',formatter: function(value, row, index){
                switch (value) 
                { 
                    case 1: return '钻石'; break; 
                    case 2: return '金币'; break; 
                    case 3: return '普通礼包'; break; 
                    case 4: return '首冲礼包'; break; 
                    case 5: return '周卡'; break; 
                    case 6: return '月卡'; break; 
                    default: return '实卡';
                }
            }},
            { field: 'Currency',title:'充值数',align:'center'},
            { field: 'BeforeCurrency',title:'充值前',align:'center'},
            { field: 'ApplyDate',title:'充值时间',align:'center'},
            { field: 'SpreaderID',title:'渠道号',align:'center'},
            { field: 'ShareNote',title:'冲值方',align:'center',class:'like'},
        ],
        queryParams: function (params) {
            var arr = {
                offset: params.offset,  //页码
                limit: params.limit,   //页面大小
                order : params.order,
                search : params.search,
                sort : params.sort,
            };
            var hhs = hh.serializeArray();
            var obj = { };
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            // console.log(obj);

            $table.extend(arr, obj);
            // console.log(hhs);
            // console.log(obj);
            console.log(arr);
            return arr; 
        },
        onLoadSuccess: function(data){
            // console.log(data);
            document.getElementById("z_recharge").innerHTML= data.extend.z_recharge;
        },
        onClickCell: function(field, value, row, $element){
            // alert(value);
        },
    });


});



function redirectGameID(value, row, index)
{
    url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID;
    return '<a href="' + url + '" class="btn btn-link" title="ss" target="_blank">' + value + '</a>';
}

function redNickName(value, row, index)
{
    var Cheat = '';
    switch(row.CheatLevel)
    {
        case 1: Cheat = '<i class="fa fa-bug" style="color:#000;"></i>'; break;
        case 2: Cheat = '<i class="fa fa-bug" style="color:#00CD66;"></i>'; break;
        case 3: Cheat = '<i class="fa fa-bug" style="color:#F00;"></i>'; break;
    }
    if(row.MemberOrder > 0){
        return Cheat+'<label for="NickName" style="color:red;">'+value+'</label>';
    }else{
        return Cheat+value;
    }
}

$("#sub").click(function(){
    $table.bootstrapTable('refresh',{
             pageNumber: 1,
    });
});


$("#editPsw").click(function(){
    jQuery("#myModalLabel").text("修改密码");
    jQuery('#myModal').modal();
});

var pswform = $("#editPswForm");
$("#btn_submit").click(function(){
	var datas = pswform.serializeArray();
	var obj = {};
	for(var item in datas)
	{
		obj[datas[item].name] = datas[item].value;
	}

	if(obj.types == 1 && (obj.logpsw).length==0)
	{
		alert('请输入登录密码');
		return false;
	}else if(obj.types == 1 && (obj.logpsw).length>32){
		alert('密码长度不能超过32位');
		return false;
	}else if(obj.types == 1 && (obj.logpsw != obj.conlog))
	{
		alert('登录密码两次不一致');
		return false;
	}

	if(obj.types == 2 && (obj.bankpsw).length==0)
	{
		alert('请输入银行密码');
		return false;
	}else if(obj.types == 2 && (obj.bankpsw != obj.conbank))
	{
		alert('银行密码两次不一致');
		return false;
	}

	if(obj.types == 3 && ((obj.logpsw).length==0 || (obj.bankpsw).length==0))
	{
		alert('密码不能为空');
		return false;
	}else if(obj.types == 3 && (obj.logpsw != obj.conlog))
	{
		alert('登录密码两次不一致');
		return false;
	}else if(obj.types == 3 && (obj.bankpsw != obj.conbank))
	{
		alert('银行密码两次不一致');
		return false;
	}
	
    jQuery.post('/index.php/admin/index/editPsw',{obj:obj}).done(function(data){
        console.log(data);
        if(data == 0){
            alert('修改成功');
        }else{
        	alert('修改失败');
        }
    });
});

$("#editUinfo").click(function(){
    jQuery('#userModal').modal();
});

$("#btn_submit2").click(function(){
	var Uid = jQuery("#UserID2").val();
	var gid = jQuery("#gid").val();
	var account = jQuery('#account').val();
	var nickname = jQuery('#nickname').val();
	var type = 1;

	if(gid.length > 0 && gid.length < 5)
	{
		alert('GameID至少是5位数');
		return false;
	}
	if(gid.length > 0)
	{
		jQuery.post('/index.php/admin/index/EditInfo',{type:type, info:gid, Uid:Uid}).done(function(data){
	        console.log(data);
	        if(data.ret == 0){
	            alert('修改成功');
	            window.location.reload();
	        }else{
	        	alert(data.msg);
	        }
	    });
	}else if(account.length > 0)
	{
		type = 2;
		jQuery.post('/index.php/admin/index/EditInfo',{type:type, info:account, Uid:Uid}).done(function(data){
	        console.log(data);
	        if(data.ret == 0){
	            alert('修改成功');
	            window.location.reload();
	        }else{
	        	alert(data.msg);
	        }
	    });
	}else if(nickname.length > 0){
		type = 3;
		jQuery.post('/index.php/admin/index/EditInfo',{type:type, info:nickname, Uid:Uid}).done(function(data){
	        console.log(data);
	        if(data.ret == 0){
	            alert('修改成功');
	            window.location.reload();
	        }else{
	        	alert(data.msg);
	        }
	    });
	}

});

$("#editVip").click(function(){
	var uid = jQuery("#getuid").val();
	var vipGrade = jQuery("#vipGrade").val();
	if(vipGrade < 0 || vipGrade > 127)
	{
		alert('不能超出范围');
		return false;
	}

	jQuery.post('/index.php/admin/index/yunVip',{uid:uid, vipGrade:vipGrade}).done(function(data){
        if(data == 1){
            alert('修改成功');
        }
    });
});

$("#inoutBt").click(function(){
	var uid = jQuery("#getuid").val();
	jQuery.post('/index.php/admin/index/GetInout',{uid:uid}).done(function(data){
		jQuery('#inoutp').html(data);
        jQuery('#smallmodal').modal();
    });
});

$("#locking").click(function(){
	var msg = "您确定锁定？";
    if (confirm(msg)==true){
    	var uid = jQuery("#getuid").val();
    	jQuery.post('/index.php/admin/index/setLock',{uid:uid, type:1}).done(function(data){
			alert('锁定成功');
			window.location.reload();
	    });
    }
});

$("#unlock").click(function(){
	var msg = "您确定解锁？";
    if (confirm(msg)==true){
    	var uid = jQuery("#getuid").val();
    	jQuery.post('/index.php/admin/index/setLock',{uid:uid, type:2}).done(function(data){
			alert('解锁成功');
			window.location.reload();
	    });
    }
});

$("#delvip").click(function(){
	var msg = '确定删除会员？';
	if (confirm(msg)==true){
    	var uid = jQuery("#getuid").val();
    	jQuery.post('/index.php/admin/index/delVip',{uid:uid}).done(function(data){
			alert('删除成功');
			window.location.reload();
	    });
    }
});

$("#fhao").click(function(){
	var msg = '确认封号？';
	if (confirm(msg) == true){
		var reason = jQuery("#fhreason").val();
		if(reason.length < 1){
			alert('请填写封号原因');
			return false;
		}
		var uid = jQuery("#getuid").val();
		jQuery.post('/index.php/admin/index/fhao', {uid:uid, reason:reason, type: 1}).done(function(data){
			alert('封号成功');
			window.location.reload();
		});
	}
	
});

$("#jief").click(function(){
	var msg = '确认解封？';
	if (confirm(msg) == true){
		var reason = jQuery("#fhreason").val();
		if(reason.length < 1){
			alert('请填写解封原因');
			return false;
		}
		var uid = jQuery("#getuid").val();
		jQuery.post('/index.php/admin/index/fhao', {uid:uid, reason:reason, type: 2}).done(function(data){
			alert('解封成功');
			window.location.reload();
		});
	}
	
});

$("#shield").click(function(){
	var msg = '确定屏蔽？';
	if(confirm(msg) == true){
		var reason = jQuery("#shieldReason").val();
		if(reason.length < 1){
			alert('请填写屏蔽原因');
			return false;
		}
		var uid = jQuery("#getuid").val();
		jQuery.post('/index.php/admin/index/fhao', {uid:uid, reason:reason, type: 3}).done(function(data){
			alert('屏蔽成功');
			window.location.reload();
		});
	}
});

$("#jieShield").click(function(){
	var msg = '确定解除屏蔽？';
	if(confirm(msg) == true){
		var reason = jQuery("#shieldReason").val();
		if(reason.length < 1){
			alert('请填写解除原因');
			return false;
		}
		var uid = jQuery("#getuid").val();
		jQuery.post('/index.php/admin/index/fhao', {uid:uid, reason:reason, type: 4}).done(function(data){
			alert('解除成功');
			window.location.reload();
		});
	}
});

var $tableCard = $("#tableCard");
$("#cards").click(function(){
	$tableCard.bootstrapTable({
		url: '/index.php/admin/index/identityCard',
    	locale: "zh-CN",
    	pagination: false,
    	sortClass: 'table-active',
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'rowid',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gidLink},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName', title: '昵称',align:'center',formatter: redNickName},
            { field: 'Score',title:'身上金币',align:'center'},
            { field: 'InsureScore',title: '银行',align:'center'},
            { field: 'Nullity',title: '封号状况',align:'center', formatter: function(value, row, index){
            	if(value > 0){
            		return '<label for="Nullity" style="color:red;">封号中</label>';
            	}else{
            		return '<label for="Nullity">正常</label>';
            	}
            }},
            { field: 'CustomFaceVer', title: '屏蔽状况',align:'center', formatter: function(value, row, index){
            	if(value > 0){
            		return '<label for="Nullity" style="color:red;">屏蔽中</label>';
            	}else{
            		return '<label for="Nullity">正常</label>';
            	}
            }},
        ],
        queryParams: function (params) {
        	var PassPortID = jQuery("#cards").text();
            var arr = {
                PassPortID: PassPortID,
            };
            
            return arr; 
        },
	});
	jQuery('#mediumModal').modal();
});

var $showMobile = $("#tableMobile");
$("#mobilep").click(function(){
	$showMobile.bootstrapTable({
		url: '/index.php/admin/index/PhoneList',
    	locale: "zh-CN",
    	pagination: false,
    	sortClass: 'table-active',
        clickToSelect: true,
    	showRefresh: true,
    	showToggle:true,
        searchOnEnterKey:true,
    	columns: [
            { field: 'rowid',title:'序号',align:'center'},
            { field: 'GameID',title: 'GameID',align:'center', formatter: gidLink},
            { field: 'Accounts',title: '账号',align:'center'},
            { field: 'NickName', title: '昵称',align:'center',formatter: redNickName},
            { field: 'Score',title:'身上金币',align:'center'},
            { field: 'InsureScore',title: '银行',align:'center'},
            { field: 'Nullity',title: '封号状况',align:'center', formatter: function(value, row, index){
            	if(value > 0){
            		return '<label for="Nullity" style="color:red;">封号中</label>';
            	}else{
            		return '<label for="Nullity">正常</label>';
            	}
            }},
            { field: 'CustomFaceVer', title: '屏蔽状况',align:'center', formatter: function(value, row, index){
            	if(value > 0){
            		return '<label for="CustomFaceVer" style="color:red;">屏蔽中</label>';
            	}else{
            		return '<label for="CustomFaceVer">正常</label>';
            	}
            }},
        ],
        queryParams: function (params) {
        	var mobile = jQuery("#mobilep").text();
            var arr = {
                mobile: mobile,
            };
            
            return arr; 
        },
	});
	jQuery('#mediumModal2').modal();
});

$("#Untying").click(function(){
	var msg = '确定解绑？';
	if(confirm(msg) == true){
		var uid = jQuery("#getuid").val();
		jQuery.post('/index.php/admin/index/jiebang', {uid:uid}).done(function(data){
			alert('解除成功');
			window.location.reload();
		});
	}
});

$("#binding").click(function(){
	jQuery('#perfectModal').modal();
});

var perfectForm = $("#perfectForm");
$("#btn_perfect").click(function(){
	var arr = perfectForm.serializeArray();
	var obj = {};
	for(var item in arr)
	{
		obj[arr[item].name] = arr[item].value;
	}
	// console.log(obj);
	if((obj.names).length < 1)
	{
		alert('请填写真实姓名！');
		return false;
	}else if((obj.idCard).length != 15 && (obj.idCard).length != 18)
	{
		alert('请填写正确的身份证号！');
		return false;
	}else if((obj.mobiles).length != 11)
	{
		alert('请填写 11 位手机号！');
		return false;
	}
	jQuery.post('/index.php/admin/index/binding', {obj:obj}).done(function(data){
		if(data>0){
			alert('已超过手机号绑定次数');
			return false;
		}else
		{
			alert('完善成功！');
			window.location.reload();
		}
	});
});


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

function gidLink(value, row, index)
{
	return '<a href="/index.php/admin/index/userInfoUid?uid='+row['UserID']+'">'+row['GameID']+'</a>';
}
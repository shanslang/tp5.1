var sendform = $("#sendV");
$("#payment-button").click(function(event){
	event.preventDefault();
	var hhs = sendform.serializeArray();
	var obj = { };
    for (var item in hhs){
        obj[hhs[item].name] = hhs[item].value;
    }

	if(obj.gid == ''){
		alert('请输入游戏ID');
		return false;
	}
			
	if(obj.days == "" && obj.score == "" && obj.sendNum == ""){
		alert("请输入赠送");
		return false;
	}
	if(obj.days != "" && isNaN(obj.days)){
		alert("请输入数字");
		return false;
	}else if(obj.days != "" && obj.reason == ""){
		alert("请填写会员赠送原因");
		return false;
	}
	if(obj.score != "" && isNaN(obj.score)){
		alert("请输入数字");
		return false;
	}
	else if(obj.score != "" && obj.score>10000000000){
		alert('超过最大额度');
		return false;
	}
	else if(obj.score != "" && obj.reason2 == ""){
		alert("请填写金币赠送原因");
		return false;
	}
	if(obj.sendNum != "" && isNaN(obj.sendNum)){
		alert("请输入数字");
		return false;
	}else if(obj.sendNum != "" && (obj.sendNum).length <= 3){
		alert("靓号长度必须大于3");
		return false;
	}

	jQuery.post('/index.php/admin/user/sendUser',{obj:obj}).done(function(data){
		alert(data);
	});
		
});

$("#recover").click(function(){
	event.preventDefault();
	
	var formre = jQuery("#reform");
	var hhs = formre.serializeArray();
	var obj = { };
    for (var item in hhs){
        obj[hhs[item].name] = hhs[item].value;
    }
    if(obj.sourGameID == ''){
		alert('请输入赠送人GameID');
		return false;
	}else if(isNaN(obj.sourGameID)){
		alert('请输入正确的GameID');
		return false;
	}

	if(obj.targetGameID == ''){
		alert('请输入目标GameID');
		return false;
	}else if(isNaN(obj.targetGameID)){
		alert('请输入正确的目标GameID');
		return false;
	}

	if(obj.sources == ''){
		alert('请输入赠送金币');
		return false;
	}else if(isNaN(obj.sources)){
		alert('金币必须是数字');
		return false;
	}else if(obj.sources < 1){
		alert('金币数要 > 0');
		return false;
	}

	jQuery.post('/index.php/admin/user/recoverGold', {obj: obj}).done(function(data){
		alert(data);
	});

});
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

function gameidReturn(value, row, index)
{
	var url = "/index.php/admin/index/userInfoUid?uid=" + row.UserID;
    return '<a href="' + url + '" class="btn btn-link" title="用户信息" target="_blank">' + value + '</a>';
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

//  导出文件下载
function download_export(url, method, filedir, filename) {
  jQuery('<form action="/index.php/admin/Operation/download" method="post">' +  // action请求路径及推送方法
         '<input type="text" name="filedir" value="' + filedir + '"/>' + // 文件路径
         '<input type="text" name="filename" value="' + url + '"/>' + // 文件名称
         '</form>')
    .appendTo('body').submit().remove();
};

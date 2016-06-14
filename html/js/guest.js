var fileupload = {
  sendfiles:function(data, obj){
	var self=this;
  html.pagehold();
	var formData = new FormData();
	var xhr = new XMLHttpRequest();
	formData.append("uploadfile",data);
	xhr.open ('POST',"/adminapi/uploadimage/");
	xhr.onload = function(event) {
    html.closepop2();
    if (xhr.status === 200) {
      var aa = JSON.parse(xhr.responseText);
      if(aa.code == '10'){
        // fileupload.replaceinput(aa.path,obj);//////////////change function
        html.tips("upload success");
        return true;
      }
      html.tips(aa.msg);
    } else {
      html.tips("upload error");
    }
  };
    xhr.upload.onprogress = self.updateProgress;
    xhr.send (formData);
  },
  updateProgress:function(event){
    if (event.lengthComputable){
        var percentComplete = event.loaded;
        var percentCompletea = event.total;
        var press = (percentComplete*100/percentCompletea).toFixed(2);//onprogress show
    }
  },
  replaceinput:function(url ,obj){
    var a= '<i class="fa fa-times"></i><img src="'+url+'" style="width:200px;display:block;" class="newspic">';
    obj.after(a);
    obj.remove();
  },
}

var popbox={
  logsubmit:function(){
    html.pagehold();
    $.ajax({
      url:"/site/api/action/alaialogin/xsscode/"+pagecode.xsscode,
      dataType:"json",
      type:"POST",
      data:{
        user: $("#logname").val(),
        password: $("#logpassword").val()
      },
      error: function(jqXHR, textStatus, errorMsg){
        html.closepop2();
        html.tips("Request Error");
      },
      success:function(data){
        if(data == '14'){
            window.location.href='/site/list/';
        }
        if(data == '15'|| data == '11'){
          html.closepop2();
          html.tips("password or username Error");
        }
        if(data == '52' ||data =='53'){
          window.location.reload();
        }
      }
    });
  },
  onload: function(){
    var self = this;
    $("#logbt").click(function(){
      popbox.logsubmit();
    });
  }
}


var adminlist = {
  orderlist:[],
  page:1,
  count:0,
  buildfirstname:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="firstname"></i>Family Name：</dt>';
    a += '<dd><input type="text" id="orderfirstname"></input></dd>';
    a += '</dl>';
    return a;
  },
  buildsecondname:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="secondname"></i>Family Name：</dt>';
    a += '<dd><input type="text" id="ordersecondname"></input></dd>';
    a += '</dl>';
    return a;
  },
  buildcardno:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="cardno"></i>Card No：</dt>';
    a += '<dd><input type="text" id="ordercardno"></input></dd>';
    a += '</dl>';
    return a;
  },
  buildbak:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="bak"></i>Remark：</dt>';
    a += '<dd><input type="text" id="orderbak"></input></dd>';
    a += '</dl>';
    return a;
  },
  buildopenidd:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="openidd"></i>Login：</dt>';
    a += '<dd>';
    a += '<select id="orderopenidd">';
    a += '<option value="1">Yes</option>';
    a += '<option value="0">No</option>';
    a += '</select>';
    a += '</dd>';
    a += '</dl>';
    return a;
  },
  buildopenid:function(){
    var a='<dl>';
    a += '<dt><i class="fa fa-minus-square faleft" opt="openid"></i>Openid：</dt>';
    a += '<dd><input type="text" id="orderopenid"></input></dd>';
    a += '</dl>';
    return a;
  },
  showlsit: function(data){
    var al = data.length;
    var a = '';
    for(var i=0 ;i<al ;i++){
        a += '<tr sid="'+data[i]["id"]+'">';
        a += '<th>'+i+'</th>';
        a += '<th>'+data[i]["firstname"]+'</th>';
        // a += '<th>'+data[i]["secondname"]+'</th>';
        a += '<th>'+data[i]["cardno"]+'</th>';
        // a += '<th>'+data[i]["bak"]+'</th>';
        if(data[i]["openid"]){
            a += '<th>Yes</th>';
          }else{
            a += '<th>No</th>';
          }
        a += '<th>'+data[i]["createtime"]+'</th>';
        a += '</tr>';
    }
    return a;
  },
  shownav: function(pm, pc){/*a is pagenumber b count of pag*/
    var a = '';
    var b = '';
    if(pc <= 15 ){
      for(var i=0 ;i<pc ;i++){
        b = i+1;
        if( pm == b){
          a += '<li pagid = "'+b+'" class="chooseli">'+b+'</li>';
        }else{
          a += '<li pagid = "'+b+'" class="pagenum">'+b+'</li>';
        }
      }
      return a;
    }
    if( pm <= 8 ){
      for(var i=0 ;i<13 ;i++){
        b = i+1;
        if( pm == b){
          a += '<li pagid = "'+b+'" class="chooseli">'+b+'</li>';
        }else{
          a += '<li pagid = "'+b+'" class="pagenum">'+b+'</li>';
        }
      }
      a += '<li class="notfeedback">…</li>';
      a += '<li  pagid = "'+pc+'" class="pagenum">'+pc+'</li>';
      return a;
    }
    if( pm > 8 && pm <= pc-8 ){
      a += '<li pagid = "1" class="pagenum">1</li>';
      a += '<li class="notfeedback">…</li>';
      var c = pm-5;
      for(var i=0 ;i<11 ;i++){
        b = i+c;
        if( pm == b){
          a += '<li pagid = "'+b+'" class="chooseli">'+b+'</li>';
        }else{
          a += '<li pagid = "'+b+'" class="pagenum">'+b+'</li>';
        }
      }
      a += '<li class="notfeedback">…</li>';
      a += '<li  pagid = "'+pc+'" class="pagenum">'+pc+'</li>';
      return a;
    }
    if( pm > pc-8 ){
      a += '<li pagid = "1" class="pagenum">1</li>';
      a += '<li class="notfeedback">…</li>';
      for(var i=0 ;i<13 ;i++){
        b = i+pc-12;
        if( pm == b){
          a += '<li pagid = "'+b+'" class="chooseli">'+b+'</li>';
        }else{
          a += '<li pagid = "'+b+'" class="pagenum">'+b+'</li>';
        }
      }
      return a;
    }
  },
  addoption: function(){
    var self = this;
    var val = $("#allorders").val();
    var opt ='';
    if(self.orderlist.indexOf(val) == "-1"){
      switch (val){
        case 'bak':
          opt = self.buildbak();
          break;
        case 'firstname':
          opt = self.buildfirstname();
          break;
        case 'secondname':
          opt = self.buildsecondname();
          break;
        case 'cardno':
          opt = self.buildcardno();
          break;
        case 'openid':
            opt = self.buildopenid();
            break;
        case 'openidd':
            opt = self.buildopenidd();
            break;
      }
      self.orderlist.push(val);
      $(".dataoption").append(opt);
      return true;
    }
    html.tips("This option already added!!!");
  },
  deloption:function(obj){
    var self = this;
    self.orderlist = self.delarraykey(self.orderlist ,obj.attr("opt"));
    obj.parent().parent().remove();
  },
  delarraykey: function(ar ,key){
    var a = [];
    var b = ar.length;
    for(var i=0 ;i<b; i++){
      if(ar[i] != key)
        a.push(ar[i]);
    }
    return a;
  },
  submitsearch:function(){
    var self = this;
    var subdata = {};
    var a = self.orderlist.length;
    for(var i=0 ;i<a ;i++){
      var b = self.trim($("#order"+self.orderlist[i]).val());
      if(b.length == 0){
        html.tips("Please check your input!!!");
        return true;
      }
      subdata[self.orderlist[i]] = b;
    }
    return subdata;
  },
  opsearch:function(){
    var self = this;
    adminlist.page = 1;
    self.getpagecount(self.submitsearch());
  },
  changepage:function(){
    var self = this;
    self.ajaxsend(self.submitsearch() ,adminlist.page ,$("#everypage").val());
  },
  ajaxsend:function(subdata ,a ,b){/*a is pagnumber ,b is suminonepage*/
    html.pagehold();
    subdata['numb'] = a;
    subdata['one'] = b;
    $.ajax({
      url:"/site/adminapi/action/getpage",
      dataType:"json",
      type:"POST",
      data:subdata,
      error: function(jqXHR, textStatus, errorMsg){
        html.closepop2();
        html.tips("Request Error");
      },
      success:function(data){
        if(data == '4'){
          window.location.reload();
          return true;
        }
        if(data != '11'){
          $(".bespeaklist tbody").html(adminlist.showlsit(data));
          $(".bespeaklistfoot ul").html(adminlist.shownav(adminlist.page ,adminlist.count));
          html.closepop2();
          return true;
        }
        html.closepop2();
        html.tips("Please check your input!!!");
      }
    });
  },
  trim:function(str){
　　    return str.replace(/(^\s*)|(\s*$)/g, "");
　},
  comfircome:function(sid){
    html.pagehold();
    $.ajax({
      url:"/site/adminapi/action/comfirmbespk",
      dataType:"json",
      type:"POST",
      data:{id: sid},
      error: function(jqXHR, textStatus, errorMsg){
        html.closepop2();
        html.tips("Request Error");
      },
      success:function(data){
        if(data == '4'){
          window.location.reload();
          return true;
        }
        if(data == '12'){
          adminlist.changepage();
          html.closepop2();
          html.tips("Update Success");
          return true;
        }
        adminlist.changepage();
        html.closepop2();
        html.tips("Update error");
      }
    });
  },
  getpagecount:function(subdata){
    var self = this;
    html.pagehold();
    $.ajax({
      url:"/site/adminapi/action/getcount",
      dataType:"json",
      type:"POST",
      data:subdata,
      error: function(jqXHR, textStatus, errorMsg){
        html.closepop2();
        html.tips("Request Error");
      },
      success:function(data){
        if(data == '4'){
          window.location.reload();
          return true;
        }
        if(data != '11'){
          adminlist.count = Math.ceil(parseInt(data['count'])/parseInt($("#everypage").val()));
          $("#sumtotal").text("TOTAL:"+parseInt(data['count']));
          html.closepop2();
          self.ajaxsend(self.submitsearch() ,1 ,$("#everypage").val());
          return true;
        }
        html.closepop2();
        html.tips("Please check your input!!!");
      }
    });
  },
  logout:function(){
    html.pagehold();
    $.ajax({
      url:"/site/logout",
      dataType:"json",
      type:"POST",
      error: function(jqXHR, textStatus, errorMsg){
        html.closepop2();
        html.tips("Request Error");
      },
      success:function(data){
          window.location.reload();
          return true;
      }
    });
  },
  onload:function(){
    var self = this;
    $(".checkoption .fa-plus-square").click(function(){
      self.addoption();
    });
    $(".dataoption").on("click" ,".fa-minus-square" ,function(){
      self.deloption($(this));
    });
    $("#searchbt").click(function(){
      self.opsearch();
    });
    $("#logout").click(function(){
      self.logout();
    });
    $(".bespeaklist").on("click" ,"tbody .logbt" ,function(){
      var id = $(this).parent().parent().attr("sid");
      self.comfircome(id);
    });
    $(".bespeaklistfoot").on("click" ,".pagenum" ,function(){
      var pagid = $(this).attr("pagid");
      adminlist.page = pagid;
      adminlist.changepage();
    });
    //upload userlist
    $("#myfiles").change(function(){
      alert($("#myfiles").val());
    });
  }

}

var html={
  asshowa:function(data){//隐藏->显示
  var self = this;
  if(data.rgba > 1){
    clearTimeout(tb);
    return true;
  }
  data.rgba +=0.02;
  data.object.css("opacity",data.rgba);
  tb = setTimeout(function(){self.asshowa(data)} ,data.gap);
},
  asshowb:function(data){//显示->隐藏
  var self = this;
  if(data.rgba <0){
    clearTimeout(tb);
    clearTimeout(tc);
    $(".tembox").empty();
    return true;
  }
  data.rgba -=0.02
  data.object.css("opacity",data.rgba);
  tb = setTimeout(function(){self.asshowb(data)} ,data.gap);
  },
  tips:function(content){
  var self = this;
    $(".tembox").html('<div class="tips">'+content+'</div>');
    var obj= $(".tembox").children();
    var data = {
        object:obj,
        gap:15,
        total:50,
        rgba:0,
      }
    self.asshowa(data);
    tc = setTimeout(function(){self.asshowb(data)} ,1000);
  },
  pagehold:function(){
    $(".popupbox2").html('<div class="faload"><i class="fa fa-spinner fa-pulse"></i></div>');
    $(".popupbox2").css({"display":"block" ,"background-color":"rgba(0, 0, 0, 0.4)"});
  },
  closepop2:function(){
    $(".popupbox2").empty();
    $(".popupbox2").css("display","none");
  },
}

$(function(){
  popbox.onload();
  adminlist.onload();
});

var n,tab,searchStart,searchEnd,searchReturn=0,searchReturn1=0,searchPsnt=1,searchOver=0,trayState=0,str=0,keyCode,ac=0;
$(document).ready(function() {
	$(document).keydown(function(e){
		if (!e) var e = window.event;
		var keyCode = e.keyCode || e.which;
		if (keyCode == 13) { 
		e.preventDefault();
	  } 
	});
	$("#qty"+n).keydown(function(e) { 
	  var keyCode = e.keyCode || e.which; 
	  if (keyCode == 9) { 
		e.preventDefault();
		addItem();
		str=1;
	  } 
	});
	$(".searchBox").bind("focus",function(e){
		$(this).css("visibility","visible");
	}); 
              
});
function addItem()
{
	if(str==0)
	{
	$("#qty"+n).off("keydown");
	n++;
	row = '<tr id="itemRow'+n+'">          <td><input name="sno" type="text" class="billText tCenter" readonly value="'+(n+1)+'"></td>          <td><input id="item'+n+'" name="item'+n+'" onKeyUp="searchItem(this.id,this.value,\'\',event)" onClick="searchItem(this.id,this.value,\'\',event)"  autocomplete="off"  type="text" autofocus class="billText" tabindex="'+(tab++)+'"></td>          <td><input id="qty'+n+'" name="qty'+n+'" type="text" onKeyUp="amntCalc('+n+')" class="billText tCenter" tabindex="'+(tab++)+'"></td>          <td><input id="netAmnt'+n+'" name="netAmnt'+n+'" type="hidden">          <input id="amnt'+n+'" name="amount'+n+'" type="text" class="billTextRight tRight  amnt" readonly>          </td>          <td><i class=icon-trash style="cursor:pointer" onClick="removeItem(\'itemRow'+n+'\')"></i></td>        </tr>';
	$("#items").append(row);
	i=1;
	$("input[name=sno]").each(function(index, element) {
        $(this).val(i++);
    });
	$("#qty"+n).keydown(function(e) { 
		  var keyCode = e.keyCode || e.which; 
		  if (keyCode == 9) { 
			e.preventDefault(); 
			addItem();
			str=0;
		  } 
	}); 
	$("#item"+n).focus();
	$("#totalItems").val(n+1);
	}
	else
		str=0;
}
function removeItem(id)
{
	$("#"+id).remove();
	i=1;
	$("input[name=sno]").each(function(index, element) {
        $(this).val(i++);
    });
	$("input[title=qty]").each(function(index, element) {
		qtyId = $(this).attr("id");
	});
	$("#"+qtyId).keydown(function(e) { 
		  var keyCode = e.keyCode || e.which; 
		  if (keyCode == 9) { 
			e.preventDefault(); 
			addItem();
		  } 
	});
	mkTotal();
}

function searchItem(id,val,type,e)
{
	$("#"+id).blur(function(){
		closeSearchBox();
	});
	$(".searchBox").bind("mouseover",function(e){
		searchOver = 1;
	});
	$(".searchBox").bind("mouseout",function(e){
		searchOver = 0;
	});
	srcId = id.split("item");
	srcId = srcId[1];
	val = val.toLowerCase();
	pos = $("#"+id).offset();
	$(".searchBox").css("left",pos.left-5);
	$(".searchBox").css("top",(pos.top+$("#"+id).height()+6));
	$(".searchBox").css("width",$("#"+id).width()-1);
	$(".searchBox").css("visibility","visible");
	if (!e) var e = window.event;
	keyCode = e.keyCode || e.which;
	  if (keyCode == 40) { 
		e.preventDefault();
		$(".searchResult").each(function(index, element) {
            $(this).css("background-color","");
		 	 $(this).css("color","#444444");
        });
		if(searchReturn == 1)
		{
			searchStart++;
			searchReturn = 0;
		}
		searchReturn1 =0;
		searchPsnt = searchStart;
		if(searchStart!=searchEnd)
		{
			$("#sr"+searchStart).css("background-color","#316cca");
			$("#sr"+searchStart).css("color","#FFF");
			searchStart++;		
		}
		else
		{
			searchReturn1=1;
			$("#sr"+searchEnd).css("background-color","#316cca");
			$("#sr"+searchEnd).css("color","#FFF");
		}				
	  }
	  else if(keyCode == 38){
		  e.preventDefault();
		  $(".searchResult").each(function(index, element) {
            $(this).css("background-color","");
		 	 $(this).css("color","#444444");
          });
		  if(searchReturn1 == 0)
		  {
		  	searchReturn1 = 1;
			searchStart--;
		  }
			searchPsnt = searchStart;
			if(searchStart!=1)
			{
				searchStart--;
				$("#sr"+searchStart).css("background-color","#316cca");
				$("#sr"+searchStart).css("color","#FFF");		
			}
			else
			{
				$("#sr"+searchStart).css("background-color","#316cca");
				$("#sr"+searchStart).css("color","#FFF");
			}
			if(searchStart==1)
				searchReturn = 1;
			$("#"+id).val($("#"+id).val())
	  }
	  else if(keyCode == 13){
		  $("#sr"+searchPsnt).click();
	  }
	  else{
		  searchStart=1;
		  searchEnd=1;
		  count = 0;
		  rst ='<table border="5" cellspacing="5" cellpadding="3" style="border-top-left-radius:5px;margin-top:-2px; cursor:pointer; width:100%; height:auto;width:'+($("#"+id).width()-1)+'>';
		  for(i=0;i<products.length;i++)
		  {
			if(i==0)
			  	rst ="";
			prst = products[i].name.indexOf(val);
			prst1 = products[i].id.indexOf(val);
			if(prst != -1 || prst1 != -1)
			{
				id = searchEnd++;
				count++;
				rst += '<tr id="sr'+(id)+'" class="searchResult" onclick="selectItem(\''+(i)+'\',\''+srcId+'\',\''+type+'\')" style="border-bottom:#CCC 1px dashed; height:25px;"> <td width="20%">'+products[i].id+'</td><td width="80%">'+products[i].name+'</td>        </tr>';
				if(count > 10)
				{
					$(".searchBox").css("height","240");
					$(".searchBox").css("overflow","auto");
				}
				else
				{
					$(".searchBox").css("height","auto");
				}
			}
			else
			{
				$(".searchBox").html('<div style="text-align:center;">No Item Found</div>');
			}
		  }
		  rst+='</table>';
		  searchEnd--;
		  $(".searchBox").html(rst);
	  }
}
function closeSearchBox()
{
	if(searchOver == 0)
	{
		$(".searchBox").css("visibility","hidden");
	}
}
function selectItem(itemId,srcId,type)
{
	$("#item"+srcId).val(products[itemId].name);
	if(type!='a')
	{
		$("#iId"+srcId).val(products[itemId].id);
		$("#netAmnt"+srcId).val(products[itemId].amnt);
		$("#amnt"+srcId).val(products[itemId].amnt);
		$("#qty"+srcId).focus();
		$("#qty"+srcId).val('1');
		$("#qty"+srcId).select();
		mkTotal();
	}
	$(".searchBox").css("visibility","hidden");
	$(".searchBox").css("top","-1000");
	$(".searchBox").css("left","-1000");
}
function amntCalc(srcId)
{ 
	net = $("#netAmnt"+srcId).val();
	qty = $("#qty"+srcId).val();
	amnt = net * qty;
	$("#amnt"+srcId).val(amnt);
	mkTotal();
}
function mkTotal()
{
	ac_rate = 0;
	if(ac)
	{
		ac_rate = $("#ac_rate").val()
	}
	tot = parseInt(ac_rate);
	$(".amnt").each(function(index, element) {
		if($(this).val()!="")
       	tot += parseInt($(this).val()); 
    }); 
	$("#total").val(tot);
	$("#recieved").val(tot);
	$("#returned").val('0');
	$("#net_total").val(tot);
}
function ckRecieved()
{
	tot = $("#total").val();
	rvd = $("#recieved").val();
	ret = $("#returned").val();
	tmp = rvd-tot;
	if(tmp>0)
		$("#returned").val(tmp);
	else
		$("#returned").val('0');
}
function printBill()
{
	$("#printMode").click()
}
function saveBill()
{
	$("#saveMode").click();
}
function cancelBill()
{
	$("#cancelMode").click();
}
function addItemFromTray(id)
{
	nt = $("#totalItems").val();
	ok=-1;
	for(i=0;i<nt;i++)
	{
		if(typeof($("#item"+i).val())!='undefined')
		{
		if($("#item"+i).val()=="")
		{
			id--;
			$("#item"+i).val(products[id].name);
			$("#netAmnt"+i).val(products[id].amnt);
			$("#amnt"+i).val(products[id].amnt);
			$("#qty"+i).focus();
			$("#qty"+i).val('1');
			$("#qty"+i).select();
			mkTotal();
			ok++;
			break;
		}
		}
	}
	if(ok==-1)
	{
		id--;
		addItem();
		//nt = $("#totalItems").val();
		$("#item"+n).val(products[id].name);
		$("#netAmnt"+n).val(products[id].amnt);
		$("#amnt"+n).val(products[id].amnt);
		$("#qty"+nt).focus();
		$("#qty"+n).val('1');
		$("#qty"+n).select();
		mkTotal();
	}
}
function minimizeItem()
{
	if(trayState == 0)
	{
		$(".itemsList").css("overflow","hidden");
		$(".itemsList").animate({height:'3.5%'});
		trayState = 1;
	}
	else
	{
		$(".itemsList").animate({height:'85%'});
		trayState = 0;
	}
}
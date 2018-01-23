var n,tab,searchStart,searchEnd,searchReturn=0,searchReturn1=0,searchPsnt=1,searchOver=0,trayState=0,str=0;
$(document).ready(function(e) {
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
	row = '<tr id="itemRow'+n+'">          <td><input name="sno" type="text" class="billText tCenter" readonly value="'+(n+1)+'"></td>          <td><input id="item'+n+'" name="item'+n+'" onKeyUp="searchItem(this.id,this.value)" onClick="searchItem(this.id,this.value)"  autocomplete="off"  type="text" autofocus class="billText" tabindex="'+(tab++)+'"></td>          <td><input id="qty'+n+'" name="qty'+n+'" type="text" class="billText tCenter" tabindex="'+(tab++)+'"></td>          <td><input id="netAmnt'+n+'" name="netAmnt'+n+'" type="hidden">          <input id="amnt'+n+'" name="amount'+n+'" type="text" class="billTextRight tRight" readonly>          </td>          <td><i class=icon-trash style="cursor:pointer" onClick="removeItem(\'itemRow'+n+'\')"></i></td>        </tr>';
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
	$("#totalItems").val(n);
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
	var keyCode = e.keyCode || e.which;
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
			prst2 = products[i].amnt.indexOf(val);
			if(prst != -1 || prst1 != -1 || prst2 !=-1)
			{
				id = searchEnd++;
				count++;
				rst += '<tr id="sr'+(id)+'" class="searchResult" onclick="selectItem(\''+(i)+'\',\''+srcId+'\',\''+type+'\')" style="border-bottom:#CCC 1px dashed; height:25px;"> <td>'+products[i].name+'</td>       </tr>';
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
	$("#pId"+srcId).val(products[itemId].id);
	$("#qty"+srcId).focus();
	$(".searchBox").css("visibility","hidden");
	$(".searchBox").css("top","-1000px");
	$(".searchBox").css("left","-1000px");
	if(type == 2)
	{
		$("#qtype"+srcId).val(products[itemId].type);
	}
}

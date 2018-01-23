var searchOver=0,searchLen,searchPntr=1,customerWin=payment=window,round_off=0,tax_mode=0,tax_rate = 0;
$(document).ready(function(e) {
	removeItem();
	billingSettings(1);	
	$(".searchBox").bind("mouseover",function(e){
		searchOver = 1;
	});
	$(".searchBox").bind("mouseout",function(e){
		searchOver = 0;
	});
	$(".charges").change(function(e) {
        calcItemNet();
    });
	$(".charges").keyup(function(e) {
        calcItemNet();
    });
});
function billingSettings(noCalc)
{
	calcItemNet(noCalc);
	$("#MitemId").val('');
	$("#MitemName").val('');
	$("#MitemType").html('');
	$("#MitemQty").val('');
	$("#MnetAmnt").val('');
	$("#MitemAmnt").val('');
	$("#MitemGst").val('');
	$("#MitemVat").val('');
	$("#MitemDis").val('');
	$("#MitemName").focus();
	$("#MitemName").bind("keyup",function(e) {
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
		if (keyCode == 40) { 
		e.preventDefault();
		searchPntr++;
		$(".searchResult").each(function(index, element) {
			$(this).css("background-color","");
			 $(this).css("color","#444444");
		});
		if(searchPntr<=searchLen)
		{
			$("#sr"+searchPntr).css("background-color","#316cca");
			$("#sr"+searchPntr).css("color","#FFF");
					
		}
		else
		{
			searchPntr=1;
			$("#sr"+searchPntr).css("background-color","#316cca");
			$("#sr"+searchPntr).css("color","#FFF");
		}				
	  }
	  else if(keyCode == 38){
			e.preventDefault();
			searchPntr--;
			$(".searchResult").each(function(index, element) {
				$(this).css("background-color","");
				$(this).css("color","#444444");
			});
			if(searchPntr>=1)
			{
				$("#sr"+searchPntr).css("background-color","#316cca");
				$("#sr"+searchPntr).css("color","#FFF");		
			}
			else
			{
				searchPntr=searchLen;
				$("#sr"+searchPntr).css("background-color","#316cca");
				$("#sr"+searchPntr).css("color","#FFF");
			}
			
		}
		else if(keyCode == 13){
		  if(searchPntr == 0)
		  	searchPntr = 1;
		  $("#sr"+searchPntr).click();
		}
		else
		{
			pos = $("#MitemName").offset();
		  	searchLen=1;
			searchPntr = 0;
		  	val = $(this).val().toLowerCase();		
		  	i=0;
			rst = '';
		  	sel = Array();
			$.ajax({
			  type: 'POST',
			  url: 'ajax_item_search.php',
			  data: {'value':val,'src':'B'},
			  success: function(ajax_result){
				$(".searchBox").html(ajax_result);
				$(".searchResult").each(function(index, element) {
                    searchLen++;
                });
				searchLen--;
				$(".searchBox").css("left",pos.left-5);
				$(".searchBox").css("top",(pos.top+$("#MitemName").height()+6));
				$(".searchBox").css("width",$("#MitemName").width()-1);
				$(".searchBox").css("visibility","visible");
				$(".searchResult").each(function(index, element) {
				var pos = index;
				$(this).click(function(e) {
					id = $(".searchResultId:eq("+pos+")").val();
					for(i=0;i<products.length;i++)
					{
						if(products[i].id==id)
						{	
							available = parseInt(products[i].available);
							
							if(available<=0){
								alert('Stock is Not Enough');
							}else{
								name = products[i].name;
								type = products[i].type;
								amnt = products[i].amnt;
								vat = products[i].vat;
								gst = products[i].gst;
								code = products[i].pid;
								
								$("#MitemId").val(products[i].id);
								$("#MitemCode").val(code);
								$("#MitemName").val(name);
								units= '';
								i = 0;
								for(;i<productType.length;i++)
								{
									prst1 = productType[i].from.indexOf(type);
									if(prst1 != -1)
										break;
								}
								baseType = productType[i].to;
								i = 0;
								for(;i<productType.length;i++)
								{
									if(productType[i].to == baseType)
									{
										units +='<option>'+productType[i].from+'</option>';
									}
								}			
								$("#MitemType").append(units);
								$("#MitemType").val(type);
								$("#MitemQty").val(1);
								$("#MnetAmnt").val(amnt);
								$("#MitemAmnt").val(amnt);
								$("#MitemVat").val(vat);
								var totalGst = 0;
								$.each(gst,function(index,num){
										totalGst+=parseFloat(num) || 0;
								});
								$("#MitemGst").val(totalGst);
								$(".searchBox").css("visibility","hidden");
								$("#MitemQty").focus();
								$("#MitemQty").select();
							}
							break;
						}
					}
					calcMItemNet();
				});
			});
			  },
			  beforeSend: function(){
					$(".searchBox").html('Loading Data Please Wait....');
			  },
			  error: function( xhr, tStatus, err ) {
					
			  }
			});
		}
    });
	$("#MitemName").bind("blur",function(e){
		closeSearchBox();
	});
	$("#MitemQty").bind("keyup",function(e) {
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
		if($(this).val()!=''){
			if(keyCode == 13){
			 $("#MitemType").focus();
			}
			else
			{
				id = $("#MitemId").val();
				for(i=0;i<products.length;i++)
				{
					if(products[i].id==id){
						available = products[i].available;
						inp = parseInt($(this).val())
						if(parseInt(available) < inp){
							alert('Stock is Not Enough');
							$(this).val(0);
							$(this).select();
						}else{
							calcMItemNet();
						}
						break;
					}
				}
			}
		}
    });
	$("#MitemQty").click(function(e) {
		$(this).select();
    });
	$("#MitemName").click(function(e) {
		$(this).select();
    });
	$("#MitemType").bind("change keyup",function(e) {
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
        if(keyCode == 13){
		 $("#MitemDis").focus();
		 $("#MitemDis").select();
		}
		else
		{
			calcMItemNet();
		}
    });
	$("#MitemAmnt").bind("keyup",function(e) {
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
        if(keyCode == 13){
		 $("#MitemDis").focus();
		}
    });
	$("#MitemDis").bind("keyup",function(e) {
		$('#MitemQty').focus();
		if(isNaN($(this).val()))
		{
			$(this).val(0);
		}
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
        if(keyCode == 13){
			 itemId = $("#MitemId").val();
			 itemName = $("#MitemCode").val()+" - "+$("#MitemName").val();
			 itemQty = parseFloat($("#MitemQty").val());
			 itemNet = parseFloat($("#MnetAmnt").val());
			 itemType = $("#MitemType").val();
			 itemAmnt = parseFloat($("#MitemAmnt").val());
			 itemVat =  parseFloat($("#MitemVat").val());
			 itemGst = (itemAmnt/100)*parseFloat($("#MitemGst").val());
			 cid = $("#customerId").val();
			 $.ajax({
			  type: 'POST',
			  url: 'ajax_customer_discount.php',
			  data: {'cid':cid,'pid':itemId},
			  success: function(discount){
				 if(discount > 0)
   				    itemDis=discount;
				 else
					itemDis =  parseFloat($("#MitemDis").val());
				 if(isNaN(itemDis))
				 	itemDis = 0;
				 selected =0;
				 if(itemId!='')
				 {
				 $(".itemId").each(function(index, element) {
					 if(!selected)
					 {
						if($(this).val()=='')
						{
							$(this).val(itemId);
							$(".itemName:eq("+index+")").val(itemName);
							$(".itemQty:eq("+index+")").val(itemQty);
							$(".netAmnt:eq("+index+")").val(itemNet);
							$(".amnt:eq("+index+")").val(itemAmnt.toFixed(2));
							$(".itemType:eq("+index+")").html(itemType);
							$(".itemTypeT:eq("+index+")").val(itemType);
							$(".itemVat:eq("+index+")").val(itemVat);
							$(".itemGst:eq("+index+")").val(itemGst);
							$(".itemDis:eq("+index+")").val(itemDis);
							selected =1;
							$(".searchBox").html('');
							unbindBillSettings()
							billingSettings();
						}
						else if($(this).val() == itemId)
						{
							i=0;
							for(;i<products.length;i++)
							{
								prst1 = products[i].id.indexOf(itemId);
								avail = parseFloat(products[i].available);
								type = products[i].type;
								if(prst1!=-1)
								{
									break;
								}
							}
							qty = convert($("#MitemType").val(),$("#MitemId").val(),parseFloat(parseFloat($(".itemQty:eq("+index+")").val())+itemQty));	
							if(avail<qty)
							{
								//alert('Available Stock is: '+avail+' '+type);
								//$("#MitemQty").val(1);
								//$("#MitemQty").select();
								//calcMItemNet();
								//selected =1;
								$(".itemQty:eq("+index+")").val(parseFloat($(".itemQty:eq("+index+")").val())+itemQty);
								$(".amnt:eq("+index+")").val((parseFloat($(".amnt:eq("+index+")").val())+itemAmnt).toFixed(2));
								$(".itemType:eq("+index+")").html(itemType);
								$(".itemVat:eq("+index+")").val(itemVat);
								$(".itemGst:eq("+index+")").val(itemGst);
								$(".itemDis:eq("+index+")").val(itemDis);
								selected =1;
								$(".searchBox").html('');
								unbindBillSettings()
								billingSettings();
							}
							else
							{
								$(".itemQty:eq("+index+")").val(parseFloat($(".itemQty:eq("+index+")").val())+itemQty);
								$(".amnt:eq("+index+")").val((parseFloat($(".amnt:eq("+index+")").val())+itemAmnt).toFixed(2));
								$(".itemType:eq("+index+")").html(itemType);
								$(".itemVat:eq("+index+")").val(itemVat);
								$(".itemGst:eq("+index+")").val(itemGst);
								$(".itemDis:eq("+index+")").val(itemDis);
								selected =1;
								$(".searchBox").html('');
								unbindBillSettings()
								billingSettings();
							}
						}
					 }
				});
				if(!selected)
				{
					addItem();
					$(".itemId").each(function(index, element) {
						if($(this).val()=='' && selected!=1 )
						{
							$(this).val(itemId);
							$(".itemName:eq("+index+")").val(itemName);
							$(".itemQty:eq("+index+")").val(itemQty);
							$(".netAmnt:eq("+index+")").val(itemNet);
							$(".amnt:eq("+index+")").val(itemAmnt.toFixed(2));
							$(".itemType:eq("+index+")").html(itemType);
							$(".itemTypeT:eq("+index+")").val(itemType);
							$(".itemVat:eq("+index+")").val(itemVat);
							$(".itemGst:eq("+index+")").val(itemGst);
							$(".itemDis:eq("+index+")").val(itemDis);
							selected =1;
							$(".searchBox").html('');
							unbindBillSettings()
							billingSettings();
						}
					});
				}
				}
			  },
			  error: function( xhr, tStatus, err ) {
					
			  }
			});
		}
    });
	$(".itemQty").bind("keyup",function(e){
		
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
        if(keyCode == 13){
		 $("#MitemName").focus();
		}
		else
		{
			calcItemNet();
		}
	});
	$(".itemQty").bind("click",function(e){
		$(this).select();
	});
	$("#discountAmnt").keyup(function(e) {
        calcItemNet();
    });
	$(".itemDis").keyup(function(e) {
		if (!e) var e = window.event;
		keyCode = e.keyCode || e.which;
        if(keyCode == 13){
		 $("#MitemName").focus();
		}
		else
		{
			if($(this).val()=='' || isNaN($(this).val()))
				$(this).val(0)
			calcItemNet();
		}
    });
	$(".itemDis").click(function(e) {
       $(this).select();
    });
	$("#recieved").keyup(function(e) {
        calcPayment();
    });
	$("#recieved").click(function(e) {
       $(this).select();
	   
    });
	$("#returned").click(function(e) {
       $(this).select();
    });
}
function unbindBillSettings()
{
	$("#MitemName").unbind("keyup");
	$("#MitemQty").unbind("keyup");
	$("#MitemType").unbind("keyup");
	$("#MitemAmnt").unbind("keyup");
	$("#MitemDis").unbind("keyup");
}
function addItem()
{
	var newItem = '<tr class="itemSet">                  <td align="center" style="font-weight:bold;" class="Isno"></td>                  <td><input id="itemName" name="item[]"  tabindex="-100" type="text" class="billText itemName"  autocomplete="off" readonly></td>                  <td><input id="itemQty" name="qty[]" type="text" title="qty" class="billText tCenter itemQty" autocomplete="off"></td>                  <td class="itemType" align="right"></td>                  <td><input id="itemId" name="itemId[]" type="hidden" class="itemId">                    <input id="itemVat" name="itemVat[]" type="hidden" class="itemVat">                    <input id="itemTypeT" name="itemType[]" type="hidden" class="itemTypeT">                    <input id="netAmnt" name="netAmnt[]" type="hidden" class="netAmnt">                    <input id="amnt" name="amount[]" type="text" class="billTextRight tRight amnt" autocomplete="off" tabindex="-100" readonly ></td> <td><input id="itemGst" name="itemGst[]" type="text" class="billTextRight tRight itemGst" autocomplete="off"  readonly></td>                 <td>                  <input id="itemDis" name="itemDis[]" type="text" class="billTextRight tRight itemDis" autocomplete="off" >                  </td>                  <td><i class="icon-trash removeItem" style="cursor:pointer"></i></td>                </tr>';
	$("#itemSelected").append(newItem);
	removeItem();
	orderItems();
}
function orderItems()
{
	var count =1;
	$(".Isno").each(function(index, element) {
        $(this).html(count++);
    });
	calcItemNet();
}
function removeItem()
{
	$(".removeItem").click(function(e) {
        $(this).closest("tr").remove();
		orderItems();
    });
}
function closeSearchBox()
{
	if(searchOver == 0)
	{
		$(".searchBox").css("visibility","hidden");
	}
}
function addFromTray(id)
{
	unbindBillSettings();
	billingSettings();
	for(i=0;i<products.length;i++)
	{
		if(products[i].id == id)
		{
			id = products[i].id;
			name = products[i].name;
			type = products[i].type;
			amnt = products[i].amnt;
			vat = products[i].vat;
		 }
	}
	units= '';
	i = 0;
	for(;i<productType.length;i++)
	{
		prst1 = productType[i].from.indexOf(type);
		if(prst1 != -1)
			break;
	}
	baseType = productType[i].to;
	i = 0;
	for(;i<productType.length;i++)
	{
		if(productType[i].to == baseType)
		{
			units +='<option>'+productType[i].from+'</option>';
		}
	}			
	$("#MitemType").append(units);		
	$("#MitemId").val(id);
	$("#MitemName").val(name);
	$("#MitemType").val(type);
	$("#MitemQty").val(1);
	$("#MnetAmnt").val(amnt);
	$("#MitemAmnt").val(amnt);
	$("#MitemVat").val(vat)
	$(".searchBox").css("visibility","hidden");
	$("#MitemQty").focus();
	$("#MitemQty").select();
	calcMItemNet();
}
function calcMItemNet()
{	
	$("#MitemType").val();
	if(isNaN($("#MitemQty").val()))
	{
		$("#MitemQty").val(1);
	}
	tot = parseFloat($("#MitemQty").val())*parseFloat($("#MnetAmnt").val())+(parseFloat($("#MitemQty").val())*(parseFloat($("#MnetAmnt").val())/100)*parseFloat($("#MitemVat").val()));
	i=0;
	for(;i<products.length;i++)
	{
		prst1 = products[i].id.indexOf($("#MitemId").val());
		avail = parseFloat(products[i].available);
		type = products[i].type;
		if(prst1!=-1)
		{
			break;
		}
	}
	pos = i;
	qty = convert($("#MitemType").val(),$("#MitemId").val(),$("#MitemQty").val());
	if(avail<parseFloat(qty))
	{
		//alert('Available Stock is: '+avail+' '+type);
		//$("#MitemQty").val(1);
		//calcMItemNet();
	}
	tot = getType($("#MitemType").val(),pos,$("#MitemQty").val(),tot);
	if(isNaN(tot))
		tot = 0;
	$("#MitemAmnt").val(tot.toFixed(2));
}
function getType(type,pId,val,amnt)
{
	if(type!=products[pId].type)
	{		
		for(i=0;i<productType.length;i++)
		{
			if(productType[i].from == type){
				amnt =  (amnt)/productType[i].val;		
			}
		}
	}
	return parseFloat(amnt);
}
function convert(type,pId,qty)
{
	for(i=0;i<productType.length;i++)
	{
		if(productType[i].from == type){
			qty =  (qty)/productType[i].val;		
		}
	}
	return parseFloat(qty);
}
function calcPayment()
{
	net = $("#net").val();
	rvd = $("#recieved").val();
	tot = parseFloat(rvd-net);
	if(tot<0)
		tot=0
	tot = parseFloat(tot);
	$("#returned").val(tot.toFixed(2));
}
function calcItemNet(noCalc)
{
	totAmnt = 0.0;
	totNTax = 0.0;
	totGst = 0.0;
	totDis = 0.0;
	$(".itemId").each(function(index, element) {
		if($(this).val()!='')
		{
			tot=0;
			if(isNaN($(".itemQty:eq("+index+")").val()))
			{
				$(".itemQty:eq("+index+")").val(1);
			}
			
			tot =  parseFloat($(".itemQty:eq("+index+")").val()*$(".netAmnt:eq("+index+")").val()+($(".itemQty:eq("+index+")").val()*($(".netAmnt:eq("+index+")").val()/100)*$(".itemVat:eq("+index+")").val()));
			avail = 0;
			type = 0;
			i=0;
			for(;i<products.length;i++)
			{
				prst1 = products[i].id.indexOf($(".itemId:eq("+index+")").val());
				avail = parseFloat(products[i].available);
				type = products[i].type;
				if(prst1!=-1)
				{
					break;
				}
			}
			pos = i;
			qty = convert($(".itemType:eq("+index+")").html(),$(".itemId:eq("+index+")").val(),$(".itemQty:eq("+index+")").val());
			if(avail<parseFloat(qty))
			{
				//alert('Available Stock is: '+avail+' '+type);
				//$(".itemQty:eq("+index+")").val(1);
				//$(".itemQty:eq("+index+")").select();
				//calcItemNet();
			}
			tot = getType($(".itemType:eq("+index+")").html(),pos,$(".itemQty:eq("+index+")").val(),tot);
			totalProdNet = parseFloat($(".itemQty:eq("+index+")").val()*$(".netAmnt:eq("+index+")").val());
			totAmnt +=  totalProdNet;			
			totNTax += tot;
			totGst += parseFloat($(".itemGst:eq("+index+")").val());
			totDis += (totalProdNet/100)*parseFloat($(".itemDis:eq("+index+")").val());
			$(".amnt:eq("+index+")").val(parseFloat(tot).toFixed(2));
		}
    });
	if(isNaN($("#discountPerc").val()))
		$("#discountPerc").val(0);
	dis_val = (totAmnt/100)*$("#discountPerc").val();
	$("#discountAmnt").val(dis_val);
	if($("#discountAmnt").val() == '')
		netDis = 0;
	else
		netDis = parseFloat($("#discountAmnt").val());
	netTax = parseFloat(((totAmnt-totDis-netDis)/100)*tax);
	net =  parseFloat(((totAmnt/100)*tax)+totAmnt);
	finalNet = parseFloat(netTax+totGst+totNTax-totDis-netDis);
	$("#total").val(totNTax.toFixed(2));
	$("#other_discount").val(netDis.toFixed(2));
	$("#gst_total").val(totGst)
	$("#tax").val(netTax.toFixed(2));
	$("#netdiscount").val(totDis.toFixed(2));
	packing = 1;
	$(".charges").each(function(index, element) {
		tot = 0;
		val = 0;
		if(!isNaN($(this).val()))
			packing = parseFloat($(this).val());
    });
	if(isNaN(packing))
		packing = 0;
	finalNet += packing;
	if(round_off ==0)
		finalNet = Math.floor(finalNet);
	else if(round_off ==1)
		finalNet = Math.ceil(finalNet);
	else if(round_off ==2)
		finalNet = Math.round(finalNet);
	$("#net").val(finalNet.toFixed(2));
}
function clearDiscount()
{
	$("#discountAmnt").val('');
	$("#discountReason").val('');
}
function setCustomer(id,name,phone,discount)
{
	$("#customerId").val(id);
	$("#customerName").html(name);
	$("#customerPhone").html(phone);
	//$("#discountPerc").val(discount);
	//calc_discount_amnt($("#discountPerc").val());
	customerWin.close();
}
function addCustomer()
{
	hei = ($(document).height())-50;
	wid = ($(document).width()/2)-398.4;
	customerWin = window.open("customerEntry.php?doy=1","New Customer");
}
function loadCustomer()
{
	hei = ($(document).height())-50;
	wid = ($(document).width()/2)-398.4;
	customerWin = window.open("customerLoad.php?doy=1","New Customer");
}
function openPayment()
{
	hei = ($(document).height())-50;
	wid = ($(document).width()/2)-398.4;
	payment = window.open("bill_payment.php?doy=1","New Customer");
}
function completePayment()
{
	payment.close();
}
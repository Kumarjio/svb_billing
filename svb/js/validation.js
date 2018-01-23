var noSubmit = 0,noSubReg = new Array(3),valId=0,valIdSrc = new Array(),valIdSrcId=0;
var varPrams = (function(){
	var result = {};
	var params = window.location.search.slice(1).split("&");
	}());
$(document).ready(function(e) {
	for(i=0;i<noSubReg.length;i++)
		noSubReg[i] = 0;
});
function createValiation(valId)
{
	Valhtm = '<div class="validation" id="validation'+valId+'" style="visibility:hidden; top:0px;	left:0px;	position:absolute;	z-index:1005;	color:#FFF;	font-weight:bold;	padding-right:5px;	border:#000 1px solid;	border-bottom-right-radius:1500px;	border-top-right-radius:1500px;	height:auto;	background-image:url(img/wrng.png);	background-repeat:no-repeat;	background-position:left;	background-size:10px 10px;	background-color:#000;	padding-left:15px;"></div>';
	$("html").append(Valhtm);
	valId++;
}
function openValidation(src,src1)
{ 
	if( typeof( window.innerWidth ) == 'number' ) { 
	myWidth = window.innerWidth;
	myHeight = window.innerHeight; 
	}else if( document.documentElement &&( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) { 
	myWidth = document.documentElement.clientWidth; 
	myHeight = document.documentElement.clientHeight; 
	}else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) { 
	myWidth = document.body.clientWidth; 
	myHeight = document.body.clientHeight;
	}
	src1 = "#"+src1
	pos = $(src1).offset();
	lft = pos.left + ($(src1).width());
	if(lft+ $(src1).width() > myWidth)
	{
		lft = (pos.left - $(src).width() - 12);
		$(src).css("border-bottom-right-radius",0);
		$(src).css("border-top-right-radius",0);
		$(src).css("border-bottom-left-radius",1500);
		$(src).css("border-top-left-radius",1500);
		$(src).css("background-position","right");
		$(src).css("padding-left","5");
		$(src).css("padding-right","15px");
	}
	else
	{
		$(src).css("border-bottom-right-radius",1500);
		$(src).css("border-top-right-radius",1500);
		$(src).css("border-bottom-left-radius",0);
		$(src).css("border-top-left-radius",0);
		$(src).css("background-position","left");
		$(src).css("padding-left","15");
		$(src).css("padding-right","5px");
	}
	$(src).animate({left:lft+5},5,function(e){
		$(src).animate({top:($(src1).offset().top)},5,function(e){
			$(src).fadeIn("10",function(e){
				$(src).css("visibility","visible");
			});
			setTimeout("closeValidation()",1000);
		});
	});
}
function closeValidation()
{
	$("div[class='validation']").each(function(index, element) {
		$(this).fadeOut("1500",function(e){		
			$(this).css("visibility","hidden"); 
		});
	});
}
function Validate()
{
	var argv =  Validate.arguments;
	var argc = argv.length;
	for(i=0,j=1;j<argc;i=j+1,j=i+1) 
	{
		lng='no';
		rChar = '|no|';
		if(argv[j].indexOf('(')!= -1 && argv[j].indexOf(')')!= -1)
		{
			typ = argv[j].split('(');
			type = typ[0];
			if(typ[1].length>1)
			{
				lng = typ[1].split(')');
				lng = lng[0];
			}
		}
		else
		{
			type = argv[j];
		}
		if(type.indexOf('{')!= -1 && type.indexOf('}')!= -1)
		{
			typ = type.split('{');
			type = typ[0];
			if(typ[1].length>1)
			{
				rChar = typ[1].split('}');
				rChar = rChar[0];
				rChar = rChar.split(",");
				rChar.join('|-|');
			}
		}
		switch(type)
		{
			case 'char':
				ValChar(argv[i],lng,rChar);
				break;
			case 'phone':
				ValPhone(argv[i])
				break;
			case 'mail':
				ValMail(argv[i])
				break;
			case 'number':
				ValNumber(argv[i]);
				break;
		}
	}
}
function ValChar(src,lng,rChar)
{
	src1 = src;
	src = "#"+src;
	if(valIdSrc.indexOf(src1) == -1)
	{
		valIdSrc[valIdSrcId] = src1;
		createValiation(valIdSrcId)
		valIdSrcId++;
	}
	noSubReg[valIdSrc.indexOf(src1)] = 0;
	noSubTmp = noSubReg.indexOf(1);
	val = $(src).val();
	if(val.length == 0 && lng == 'no' && rChar == '|no|')
	{
		src ="#validation"+ valIdSrc.indexOf(src1);
		$(src).html("Enter Value");
		noSubmit = 1;
		openValidation(src,src1)
		noSubReg[valIdSrc.indexOf(src1)] = 1;
	}
	else if(val.length<lng && rChar == '|no|')
	{
		src ="#validation"+ valIdSrc.indexOf(src1);
		$(src).html(lng+" Characters Must");
		noSubmit = 1;
		openValidation(src,src1)
		noSubReg[valIdSrc.indexOf(src1)] = 1;
	}
	else if(rChar != '|no|')
	{
		fReg ='/^['+rChar+']+$/';
		var reg = fReg;
		if(!val.match(reg))
		{
			
			src ="#validation"+ valIdSrc.indexOf(src1);
			$(src).html("char support ["+rChar+"]");
			noSubmit = 1;
			openValidation(src,src1)
			noSubReg[valIdSrc.indexOf(src1)] = 1;
		}
	}
	else
	{
		if( noSubTmp < 0)
			noSubmit = 0;
		else
			noSubmit = 1;
	}
}
function ValPhone(src)
{
	src1 = src;
	src = "#"+src;
	if(valIdSrc.indexOf(src1) == -1)
	{
		valIdSrc[valIdSrcId] = src1;
		createValiation(valIdSrcId)
		valIdSrcId++;
	}
	noSubReg[valIdSrc.indexOf(src1)] = 0;
	noSubTmp = noSubReg.indexOf(1);
	ValidChars ="0123456789";
	val = $(src).val();
	if(val.length>10 || val.length<10)
	{
		src ="#validation"+ valIdSrc.indexOf(src1);
		$(src).html("Phone No must be in 10 characters");
		noSubmit = 1;
		openValidation(src,src1)
		noSubReg[valIdSrc.indexOf(src1)] = 1;
	}
	else
	{
		if( noSubTmp < 0)
			noSubmit = 0;
		else
			noSubmit = 1;
	}
	for (i = 0; i < val.length; i++) 
	{
		Char = val.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) 
		{
			tmp = val.split(val.charAt(i));
			val = tmp[0]+tmp[1];
			if(valIdSrc.indexOf(src1) == -1)
			{
				valIdSrc[valIdSrcId] = src1;
				createValiation(valIdSrcId)
				valIdSrcId++;
			}
			src ="#validation"+ valIdSrc.indexOf(src1);
			$(src).html("Number Only Allowed");
			openValidation(src,src1)
		}
	}
	src = "#"+src1
	$(src).val(val);
}
function ValMail(src)
{
	src1 = src;
	src = "#"+src;
	if(valIdSrc.indexOf(src1) == -1)
	{
		valIdSrc[valIdSrcId] = src1;
		createValiation(valIdSrcId)
		valIdSrcId++;
	}
	noSubReg[valIdSrc.indexOf(src1)] = 0;
	noSubTmp = noSubReg.indexOf(1);
	var reg =/^[A-Za-z0-9._%+-]+(@[A-Za-z0-9]{2,15})+\.[A-Za-z]{2,12}$/;
	if(!$(src).val().match(reg))
	{
		src ="#validation"+ valIdSrc.indexOf(src1);
		$(src1).html("Incorrect Email Format");
		openValidation(src,src1)
	}
}
function ValNumber(src)
{
	src1 = src;
	src = "#"+src;
	if(valIdSrc.indexOf(src1) == -1)
	{
		valIdSrc[valIdSrcId] = src1;
		createValiation(valIdSrcId)
		valIdSrcId++;
	}
	noSubReg[valIdSrc.indexOf(src1)] = 0;
	noSubTmp = noSubReg.indexOf(1);
	ValidChars ="0123456789";
	val = $(src).val();
	for (i = 0; i < val.length; i++) 
	{
		Char = val.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) 
		{
			tmp = val.split(val.charAt(i));
			val = tmp[0]+tmp[1];
			if(valIdSrc.indexOf(src1) == -1)
			{
				valIdSrc[valIdSrcId] = src1;
				createValiation(valIdSrcId)
				valIdSrcId++;
			}
			src ="#validation"+ valIdSrc.indexOf(src1);
			$(src).html("Number Only Allowed");
			openValidation(src,src1)
		}
	}
	src = "#"+src1
	$(src).val(val);
}
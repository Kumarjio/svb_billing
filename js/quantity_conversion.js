var productType = [];
productType['Gram']['Kg']=1000
productType['Tone']['Kg']=0.001;
productType['Ml']['Ltr']=1000;
productType['Cm']['Mtr']=1000;
productType['Inch']['Mtr']=0.001;
function getType(type,val,amnt)
{
	alert(productType[type]['Kg']);
}
<script src="js/jquery.js"></script>
<script src="js/menu_sticky.js"></script>
<script src="js/searchBox.js"></script>
<script src="js/less.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.uniform.min.js"></script>
<script src="js/bootstrap.timepicker.js"></script>
<script src="js/bootstrap.datepicker.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/jquery.inputmask.min.js"></script>
<script src="js/jquery.jgrowl_minimized.js"></script>
<script src="js/jquery.form.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/bbq.js"></script>
<script src="js/jquery-ui-1.8.22.custom.min.js"></script>
<script src="js/jquery.form.wizard-min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/custom.js"></script><script src="js/demo.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/jquery.dataTables.bootstrap.js"></script>
<script src="js/tableTools/js/TableTools.min.js"></script>
<script type="text/javascript">
function confirmDel(e)
{
	if (!e) var e = window.event;
		var keyCode = e.keyCode || e.which;
	cnf = confirm('Confirm')
	if(!cnf)
	{
		e.preventDefault();
	}
}
<?php 
if($curFile!= 'billing_wb.php' ){ ?>
$(document).ready(function(e) {
    $('form input[type=submit]').bind("click",function(e){
		confirmDel(event);
	});
	$('form button[type=submit]').bind("click",function(e){
		confirmDel(event);
	});
});
<?php 
} ?>
</script>
<script type="text/javascript" src="js/jquery.poshytip.js"></script>
<script type="text/javascript">
		$(function(){
			$('.tooltip').parent().poshytip({
				className: 'tip-skyblue',
				bgImageFrameSize: 9,
				offsetX: 0,
				offsetY: 10
			});
		});
</script>

<!-- side switch plugin -->
<script src="js/switcher.js"></script>
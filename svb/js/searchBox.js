// JavaScript Document
var searchLock = 0;
$(document).ready(function(e) {
	 $(".searchBox").focusin(function(e) {
        if($(this).val()=='Search Here...')
			$(this).val('');
    });
    $(".searchBox").keyup(function(e) {
		val = $(this).val();
		$.ajax({
			  type: 'POST',
			  url: 'search.php',
			  data: {'val':val       
			  },
			  success: function(data){
				   $(".searchBoxResult").html(data);
					$(".searchBoxResult").css("display","block");
				  },
          error: function( xhr, tStatus, err ) {
			alert(err);
            }
		});
       
    });
	$(".searchBox").blur(function(e) {
		if(!searchLock)
			$(".searchBoxResult").css("display","none");
		if($(this).val()=='')
			$(this).val('Search Here...');
    });
	$(".searchBoxResult").mouseenter(function(e) {
        searchLock = 1;
    });
	$(".searchBoxResult").mouseleave(function(e) {
        searchLock = 0;
    });
});
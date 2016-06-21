$(function () {

        var rating = 1.6;

       $("#comments-div").hide();

        

         $(".rateyo").rateYo({
    		normalFill: "#EEEEEE",
		ratedFill: "#2EC1D3"	
 	 });
	
	  $(".rateyo").rateYo()
              .on("rateyo.set", function (e, data) {
 		var rating = data.rating;
		$("#experience").hide();
		$("#counter").val(20*rating);
		$("#comments-div").slideDown();
		
	 });
	
	   $(".rateyo").rateYo({
 
    		onChange: function (rating, rateYoInstance) {
 		$(this).next().text(rating);
    		}
 	   });

	$("#savecomment").click(function(){
		
		$("#page-result").html("<div class='loading-center'><img src='images/loading.gif'></div>");
		var comments = $("#comments").val();
		var counter  = $("#counter").val();
	
		if(counter!=0)
		{
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addComments',comments: comments,counter:counter},
				success: function(text){
				 	if(typeof $(text).find(".warning").html()!=="undefined")
					{
						$("#page-result").html(text);
					}
					else
					{
						window.location.href='thankyou.php';
					}
				},
			
			});			
		} 	
		
	
	});
});
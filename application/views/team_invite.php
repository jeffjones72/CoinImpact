<?php 
	/** Ticket #58 
	Team invite page
	**/
?>
<script type="text/javascript">

$(document).ready(function(){


	
    $("#request_friend").click(function(e){
	   var id=$("#request_values").val();
		
		 $.ajax({
		    	type: "POST",
		    	data:{share_id:id},
		  	    url:"<?php echo base_url()."team/ajax_team_request/"?>",
		 	    //data: {user_id: id, user_name: user },
		 	    success:function(result){
			 	     $('#ajax_result').html(result);
		 	    	 //location.reload();
			 	     //$(".message").html("User added");
			    	//console.log(result);	    
			 	 	   
		 	  	 }
			  	 
		 	    
		       });
		//  e.preventDefault();
    });
	
})
</script>
		
		

		<div class="bossActiveSlot">
		Share my details: <br />
		<div class = "share_details" id="link_val">
    		<h2>Link :</h2>
    		
    		<?php $share_link=base_url()."team_request?share_id=".$player_code;?>
    		<input value="<?php echo $share_link;?>" size="59">
			<div class="paste">
    			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
						width="110" height="14" id="clippy">
					<param name="movie" value="/_flash/clippy.swf" />
					<param name="allowScriptAccess" value="always" />
					<param name="quality" value="high" />
					<param name="scale" value="noscale" />
					<param NAME="FlashVars"
							value="text=<?php echo $share_link?>">
					<embed src="/_flash/clippy.swf" width="110" height="14"
							name="clippy" quality="high" allowScriptAccess="always"
							type="application/x-shockwave-flash"
							pluginspage="http://www.macromedia.com/go/getflashplayer"
							FlashVars="text=<?php echo urlencode($share_link)?>" />
				</object>
			</div>
    	</div>
    		
    		
    		<br />
    		<div class = "share_details" id="code_val" >
				<h2>Code :</h2>
				<input value="<?php echo $player_code;?>" size="59">    	
				<div class="paste">
    		        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
						width="110" height="14" id="clippy">
						<param name="movie" value="/_flash/clippy.swf" />
						<param name="allowScriptAccess" value="always" />
						<param name="quality" value="high" />
						<param name="scale" value="noscale" />
						<param NAME="FlashVars"
							value="text=<?php echo $player_code?>">
						<embed src="/_flash/clippy.swf" width="110" height="14"
							name="clippy" quality="high" allowScriptAccess="always"
							type="application/x-shockwave-flash"
							pluginspage="http://www.macromedia.com/go/getflashplayer"
							FlashVars="text=<?php echo urlencode($player_code)?>" />
					</object>
				</div>
    		</div>
        </div>
        
    <hr />    
        
        <div class="bossActiveSlot">
			Friend Details<br>
			<div class="share_details">
				<h2>CODE:</h2>
			
				<input name="request_friend"  id="request_values" size="59">
				<input type="button" id="request_friend" class="blue blueBtnS" value="Send Request">
					  <div id = "ajax_result"></div>
			</div>
        </div>
        
        
        
	
	
	
	
	
	
	

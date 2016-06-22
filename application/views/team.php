
<script>
function loadPageTeam()
{
    var sort = $('#team_sort').val();
    var page = $('#team_page').val();
    $('#teamPageContainer').empty();
    $('#teamPageContainer').load('/team/page_team/' + sort +'/' + page);
    $('#teamPaginationContainer').empty();
    $('#teamPaginationContainer').load('/team/pagination_team/' + sort +'/' + page);
}
$(document).ready(function(){
   // loadPageTeam();
 
	$(".inventoryStats").hide();

	$(".acceptBtn").click(function(){
      id=$(this).attr('id');
      $(this).show();
      $.ajax({
        type: "POST", 
        data: {id: id},
  	    url:"<?php echo base_url()."team/add_in_team/"?>",
  	    success:function(result){
  	  	    
  	  	 //   alert(result);
  	    	$("#add_squad_m").html("You added them");
    	      window.location.reload(true);
    	      
  	    	
  	  	    }
        });
      });


	/** Ticket #58 
	set/unset  favorite team player without a page refresh
	**/
    $(".set-fav").click(function(e){
    id=$(this).attr('id');
    var obj = $(this);
  	var is_fav;
  	//alert(obj.attr("class"));
   	if(obj.hasClass("star-favorited set-fav") ){
   	   	 is_fav=0;
   	}else if(obj.hasClass("star set-fav") ) {
      	 is_fav=1;
   	}
   	 //alert(is_fav);
     $.ajax({
    	type: "POST",
  	    url:"<?php echo base_url()."team/team_favorite"?>",
 	    data: {user_id: id, fav : is_fav },
 	    success:function(result){
	    	 
	    	 if(obj.hasClass("star set-fav")){
	    		  obj.removeClass("star").addClass("star-favorited");	   

		    } else  if(obj.hasClass("star-favorited set-fav")){
	    		  obj.removeClass("star-favorited").addClass("star");	   

		    }
		    
	 	 	   
 	  	 }
	  	 
 	    
       });
   //  return false;
     e.preventDefault();
       });

    $(".ajaxSquad").click(function(e){
        whole_id=$(this).attr("id");
        var id=whole_id.split("_");
        val_is=$(this).val();
	   $.ajax({
		   type: "POST",
		   data: {id: id[1]},
		   url: "<?php echo base_url()."team/add_to_squad"?>",
		   success:function(result){
			   $("#team_message > .inside_message").html(result);
			  $("#team_message ").show();
			   }
		  }); 
	});
	$("#team_message").click(function(e){
		 window.location.reload(true);
		});


    
	$("#select_requests").change(function(){
	      var selected=$(this).val();
//	      $("#filterRequests").submit();
    	  window.location.href="<?php echo base_url().'team/order_requests/';?>"+selected+"#section3";
  	     $(this).val(selected);
 	        });

	$("#team_sort").change(function(){
	      var selected=$(this).val();
//	      $("#filterRequests").submit();
	      window.location.href="<?php echo base_url().'team/order_team/';?>"+selected+"#section2";
	      $(this).val(selected);
	 });
	

})

</script>


<div class="box team" style="height: 510px;">


 
    <div class="yellowSectionBtn"  style="margin-right:10px;" onclick="javascript:showonlyone('section2')">
        TEAM
    </div>
       <div  class="teamInviteBtn"   onclick="javascript:showonlyone('section4');" >
       Team Invite
    </div>
    <div class="greenSectionBtn"  onclick="javascript:showonlyone('section1');">
        SQUAD
    </div>

    
        <?php   if(count($team_request)>0){ ?>
    <div  class="teamRequestBtn" onclick="javascript:showonlyone('section3');" >
       Team requests
    </div>
    
       <?php }?>
       

    <div class="teamSquadSection section" name="section" id="section1" style="display:none">
    
        <?php  $this->view('team_squad');?>
        
    </div>

    <div class="teamSection section" name="section" id="section2">
    


        
                <?php $this->view('team_page_team');?>
            <!-- SEE team_page_team -->
        
    </div>


    <div class="teamRequestSection section" name="section" id="section3" style="display:none;clear:both">

                <?php $this->view('team_requests'); ?>
    </div>
    
    <div class="teamInviteSection section" name="section" id="section4" style="display:none;clear:both">

                <?php $this->view('team_invite'); ?>
    </div>

</div>


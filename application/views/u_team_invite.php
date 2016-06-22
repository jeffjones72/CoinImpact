<?php 
	/** Ticket #58 
	Team invite page
	**/
?>
<script type="text/javascript">

$(document).ready(function(){


	
    $(".invitePlayer").click(function(e){
       var id=$(this).attr('id');
	   var obj = $(this);
	   var user = $("#user_"+id).text();
		
		 $.ajax({
		    	type: "POST",
		  	    url:"<?php echo base_url()."team/ajax_team_invite"?>",
		 	    data: {user_id: id, user_name: user },
		 	    success:function(result){
			 	      $('#allPlayers').html(result);
		 	    	 //location.reload();
			 	     //$(".message").html("User added");
			    	//console.log(result);	    
			 	 	   
		 	  	 }
			  	 
		 	    
		       });
		//  e.preventDefault();
    });
	
})
</script>


  
    
		
		
		
		<div id="allPlayers">
		<?php foreach ($free_players as $player){  ?>
		<div class="bossActiveSlot">
    
    <?php
    if ($player->profile_image != "") {
        
        $item_image_path=$_SERVER['DOCUMENT_ROOT'].'_images/data/accounts/'.$player->acc_id.'/'.$player->profile_image;
    }
    
        if(isset($item_image_path) && file_exists($item_image_path)){
            $player_image = base_url().'_images/data/accounts/'.$player->acc_id.'/'.$player->profile_image;
        }else{
            $player_image = base_url()."_images/male.jpg";
        }
        
        
        ?>
        
        <div class="bossActiveImageSlot" style="width:90px"> 
            <img  src="<?php echo $player_image; ?>" width="80px" height="80px" />
       </div>               
    
    
    
    
    
    
    <div class="bossInfoBox" style="vertical-align: bottom; margin-top: 20px"  id="<?php echo "user_".$player->p_id?>">
    
    
        <?php
            $username=($player->acc_username) == "" ? "{$player->acc_first_name} {$player->acc_last_name}" : $player->acc_username ;
        ?>    
        <h2 id="<?php echo "user_".$player->p_id?>"><?php echo $username ; ?></h2>   
     
    
    </div>
        
          
            
            <div class="bossActiveSlotBar" style="display: block" >
            <div style="margin-top:5px;float:left;font-weight:bold; display:block">  
            
                Lvl : <?php echo $player->level_id?> &nbsp;|&nbsp;
                Rank : <?php echo $player->label?> &nbsp;|&nbsp;
                Attack: <?php echo $player->p_attack?> &nbsp;|&nbsp;
                Defense: <?php echo $player->p_defense?>&nbsp;|&nbsp;
                Power:<?php echo $player->power?>
                 </div>
                <div style="float: right;">
                      <input type="submit" value="Invite" class="bossButton invitePlayer" id="<?php echo  $player->p_id; ?>">
                </div>
            </div>
    
        </div>
 <?php }?>
	</div>
	
	
	
	
	
	
	

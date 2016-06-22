 
     
        <h2>Your Squad</h2>
        <div class="teamNav">
            <form class="filterMenu">
                <div class="filterCenter">
                    Sort by:
                    <select>
                        <option value="level">Level</option>
                        <option value="attack">Attack</option>
                    </select>
                </div>
            </form>
        </div>
        
        
        
        

        <?php 
        
        if($squad_members!=0){

        foreach ($squad_members as $i=>$player){?>
       <div class="squadMembersBox" >
              <div class="squadMemberName">
                    <?php 
                    if(isset($player->acc_username) && $player->acc_username == ""){
                        $username="{$player->acc_first_name} {$player->acc_last_name}";
                    }else{
                        $username = $player->acc_username;
                    }
                    if($player->is_NPC == 1){
                        $npc=" NPC <br />";
                    }else {
                        $npc="<br />";
                    }
                    
                    echo $npc.$username;?>
                </div>
                <div class="squadMemberName">
                    Lvl: <?php echo $player->level_id?>
                </div>
                <div class="squadAtk">
                    <b>Attack:</b>
                    <div class="squadStatNumbers">
                        <?php echo $player->p_attack?>
                    </div>
                </div>
                <div class="squadDef">
                    <b>Defense:</b>
                    <div class="squadStatNumbers">
                        <?php echo $player->p_defense?>
                    </div>
                </div>
            
            
            
            
            	<div class="squadMemberImage">
			<img class="squadMemberLevelImage"
				src="<?php echo base_url(); ?>_images/data/ranks/<?php echo $player->rank;?>t.png"
				width="25px" height="40px" />

                        <?php
    if (isset($player->profile_image) && $player->profile_image  != "") {
        ?>
                        <img class="squadMemberProfileImage"
				src="<?php echo base_url().'_images/data/accounts/'.$player->acc_id.'/'.$player->profile_image; ?>"
				width="60px" height="60px" />
                        <?php
    } else if(isset($player->picture) &&  $player->picture != ""){?>
    
      <img class="squadMemberProfileImage" src="<?php echo base_url()."_images/squad/".$player->picture; ?>" height="60px" width='60px' alt="<?php echo $player->acc_username?>">
    
    <?php 
        
    }
    else

     {
        ?>
                       <img class="squadMemberProfileImage"
				src="<?php echo base_url(); ?>_images/male.jpg" width="60px"
				height="60px" />
                        <?php }?>
                    </div>
                    <br />
                    <div style="clear:both; padding: 5px; color:#932B2A" >
                    <?php if (isset($player->left_time)){
                        echo "Left ".$player->left_time;
                    }
                    ?>
                    </div>
                    
                   </div> 
                    
            
            
            <?php
        }
        }else{
           ?>
<h2>No squad members</h2>
           <?php }?>


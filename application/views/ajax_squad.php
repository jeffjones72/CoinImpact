 
     
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
        <div>


        <?php 
     
        if($squad_members!=0){

        foreach ($squad_members as $i=>$player){?>
<!-- <pre> -->
        <?php //var_dump($player);?>
<!--         </pre> -->
            <div class="squadMembersBox" style="float: left">
                <div class="squadMemberName">
                    <?php echo $player->acc_username;?>
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
				width="37px" height="50px" />

                        <?php
    if ($player->profile_image != "") {
        ?>
                        <img class="squadMemberProfileImage"
				src="<?php echo base_url().'_images/data/accounts/'.$player->acc_id.'/'.$player->profile_image; ?>"
				width="80px" height="80px" />
                        <?php
    } else {
        ?>
                       <img class="squadMemberProfileImage"
				src="<?php echo base_url(); ?>_images/male.jpg" width="80px"
				height="80px" />
                        <?php }?>
                    </div>
                    
                   </div> 
                    
            
            
            <?php
        }
        }else{
           ?>
<h2>No squad members</h2>
           <?php }?>

        </div>
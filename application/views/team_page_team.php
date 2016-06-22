    <h2>Your Team</h2>
        <div class="teamNav">
            <form class="filterMenu">
                <div class="filterSort">
                    Sort by:
                    <select id="team_sort">
                        <option value="">--Sort--</option>
                        <option value="favorite">Favorite</option>
                        <option value="power">Power</option>
                        <option value="newest">Newest</option>
                        <option value="level">Level</option>
                    </select>
                </div>
                <input type="hidden" id="team_page" value="1" />
            </form>

             <form id="teamPaginationContainer" class="filterFormPagination">
              <span class="page-number">
                    <b> <?php echo $start_item;?>-<?php echo $last_item;?></b> of <b> <?php echo  $count_players;?></b>
                </span>

             <?php echo $pagination;?>

                <!-- SEE team_pagination_team -->
            </form>
       </div>
       
       
       

<div id="teamPageContainer" style="clear:both">

<div id="team_message" class="teamModal" style="display: none;">
    <div class="inside_message"></div>
        <input type="button"  value = "OK">
</div>

<?php 
if( count($players) == 0){?>
<h2>You don't have any team players yet</h2>

<?php
}
else{
foreach ($players as $i => $member) { 

    //var_dump($member);
    ?>

<!-- Modal for profile -->

<div class="inventoryStats rarity1" style="width: 300px; display: block;" id="<?php  echo $i?>">

	<h1>
					<?php if(isset($member->username)) {
					    echo $member->username;
					} else{
					    echo $member->acc_username;
					}
					    
					    ?></h1>

	<table class="info">
		<tr>
			<th><h2>Lvl</h2></th>
			<th><img alt="attack icon"
				src="<?php echo base_url()?>/_images/icons/attack_small.png"></th>
			<th><img alt="defense icon"
				src="<?php echo base_url()?>/_images/icons/defense_small.png"></th>
			<th><img alt="defense icon"
				src="<?php echo base_url()?>/_images/icons/dodge_small.png"></th>
			<th><img alt="defense icon"
				src="<?php echo base_url()?>/_images/icons/luck_small.png"></th>


		<tr>
			<td><?php echo $member->level_id?></td>
			<td><?php echo $member->p_attack?></td>
			<td><?php echo $member->p_defense?></td>
			<td><?php echo isset($member->p_dodge) ? $member->p_dodge : "" ;?></td>
			<td><?php echo isset($member->p_luck) ? $member->p_luck : "" ;?></td>
		</tr>
	</table>
	<div class="clear"></div>

<?php
 $m_items = $member->itemsI;
?>
<div>
<div class="inventoryOptions" style="clear: both;width:300px">

<div id="add_squad_m"></div>
<?php

 foreach ($m_items as $item_m){
//      print "<pre>";
//      print_r($item_m);
//      print "</pre>";

        $item_image='';
        if(isset($item_m["id_item"])){

            //echo $item_m['quality'];
            if( $item_m['has_quality']== '1' &&  isset($item_m['quality']) && $item_m['quality']!=0){

                //echo "{$item_m['id']}-{$item_m['quality']}";
                $item_image_path=$_SERVER['DOCUMENT_ROOT']."/_images/data/items/{$item_m['id_item']}-{$item_m['quality']}t.png";
                if(file_exists($item_image_path)){
                    $item_image=base_url()."_images/data/items/{$item_m['id_item']}-{$item_m['quality']}t.png";
                }
            }else{
                $item_image_path=$_SERVER['DOCUMENT_ROOT']."/_images/data/items/{$item_m['id_item']}t.png";
                if(file_exists($item_image_path)){
                    $item_image=base_url()."_images/data/items/{$item_m['id_item']}t.png";
                }
            }



        ?>
       

      <?php if($item_image!==''){

          ?>
     <div style='float:left;margin-right:10px;margin-bottom:10px'>
     <?php // echo $item_m["id_item"];?> 
         <img  class="itemImageModal"  id="<?php echo $item_m['id_item']."-".$member->p_id;?>" src="<?php echo $item_image;?>" width="30px" height="30px">
         <!-- itemDetailsModal -->
         <div class="itemDetailsModal" id="<?php echo $item_m['id_item']."-".$member->p_id.'content';?>" >
             <?php /**style="background-image: url('<?php echo $item_image.'s.png';?>')"  **/?>
            <div>
            <span><?php echo substr( $item_m["item_name"], 0, 30);?> </span> <br />
            Pieces: <?php echo $item_m["pieces"];?>  <br />
            Attack: <?php echo $item_m["attack"]?><br>
            Defense: <?php echo $item_m["defense"]?></div>
        </div>
        <!-- / itemDetailsModal --> 
     </div>
     <?php } else{
         ?>
       <div style='float:left;margin-right:10px;margin-bottom:10px;'>
         <img alt="<?php echo $item_m['id_item'];?>"   class="itemImageModal"  id="<?php echo $item_m['id_item']."-".$member->p_id?>" src="<?php echo base_url().'_images/default.jpg'?>" width="30px" height="30px">
          <!-- itemDetailsModal -->
         <div class="itemDetailsModal" id="<?php echo $item_m['id_item']."-".$member->p_id.'content';?>" >
             <?php /**style="background-image: url('<?php echo $item_image.'s.png';?>')"  **/?>
            <div>
            <span><?php echo $item_m["item_name"];?> </span> <br />
            Pieces: <?php echo $item_m["pieces"];?>  <br />
            Attack: <?php echo $item_m["attack"]?><br>
            Defense: <?php echo $item_m["defense"]?></div>
        </div>
        <!-- / itemDetailsModal -->
     </div>

         <?php
     } ?>

            <?php
    }
 }
    ?>
    </div>

	 <br />	

<div style="clear:both;"></div>
		<form method="post">
			<div>
				<input name="closeModal" value="Ok" class="cancel blue modBtn " style="text-align: center; margin-left: 35%">
			</div>
		</form>
	</div>
</div>

<!-- End Modal for profile -->

<div class="teamMembersBox">




	<div>
		<!--                     <div id=""> -->
		<p style="margin-top: 0px; margin-bottom: 0px;">

		
		<?php 
	//echo "<pre>";
	//print_r($member);
		if($member->favorite == "0"){?>
			<a href="" id="<?php echo $member->p_id?>" class="star set-fav"></a>
			<?php } else if($member->favorite == "1"){?>
			<a href="" id="<?php echo $member->p_id?>" class="star-favorited set-fav"></a>
			<?php }?>
		</p>
		<!--                     </div> -->
		<form>
  <?php
    // $is_member=$player->is_team_player($member->id);
//     var_dump($member->p_id);
//     var_dump($member->squad_id);
    if ($member->activation_key != "") {
        ?>
      <input class="teamBtnGray" value="Add to Squad"></input>
    <?php
    }

    else
        if ($member->squad_id == 0) {

            ?>
       <input class="teamBtnGreen ajaxSquad" id="ajax_<?php echo $member->p_id; ?>" value="Add to Squad"></input>
<?php
// }
                  // else if($member->squad_id!=0){
            ?>

<!--         <input class="teamBtnGray" value="Add to Squad"></input>  -->

    <?php
        } else
            if ($member->squad_id != 0) {
                ?>
                
                       <a
				href="<?php echo base_url()."team/remove_from_squad/".$member->p_id?>">
				<input class="teamBtnRed" value="Remove from Squad"></input>
			</a>
  <?php  }      ?>

                    </form>

	</div>
	<?php //var_dump($member)?>
	<div style="padding-top: 26px;">
		<div class="squadMemberName" style="clear: both;">
                        <?php
    $username = isset($member->username) ? $member->username : $member->acc_username;
    if ( $username=="") {
        $username = $member->first_name . ' ' . $member->last_name;
    }
    echo $username;
    ?>

                    </div>

		<div class="squadMemberName">
                        Lvl: <?=$member->level_id?> 
                    </div>


		<div class="squadMemberImage">
			<img class="squadMemberLevelImage"
				src="<?php echo base_url(); ?>_images/data/ranks/<?=$member->rank;?>t.png"
				width="37px" height="50px" />

                        <?php
    if (isset($member->profile_image) && $member->profile_image != "") {
        ?>
                        <img class="squadMemberProfileImage"
				src="<?php echo base_url().'_images/data/accounts/'.$member->acc_id.'/'.$member->profile_image; ?>"
				width="80px" height="80px" />
                        <?php
    } else if(isset($member->picture) && $member->picture != ""){ ?>
    
          <img class="squadMemberProfileImage" src="<?php echo base_url()."_images/squad/{$member->picture}"; ?>" width="80px" height="80px" />
        <?php 
        }else {
        ?>
                       <img class="squadMemberProfileImage"
				src="<?php echo base_url(); ?>_images/<?php echo strtolower($member->gender); ?>.jpg" width="80px"
				height="80px" />
                        <?php }?>
                    </div>

		<form>
		<?php if($member->is_NPC == 0){?>
			<input class="teamBtnBlue blue sendGift" value="Send Gift" />
		<?php } else{?>
		      <input class="teamBtnBlue blue" style="background-color: gray" value="NPC" />
		<?php }?>	
			 <input class="teamBtnBlue blue profileBtn " id="<?php echo $i;?>"  value="Profile" />
        
		</form>
	</div>
</div>




<?php
    unset($username);
    unset($member);
}
//else if no team members
}
   

?>
</div>

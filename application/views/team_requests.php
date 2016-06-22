<?php 
	/** Ticket #58 
	Team requests page
	**/
?>



<h2>Your Team Requests <?php echo "(".count($team_request).")";?></h2>
<div class="teamNav">
	<form class="filterMenu" id="filterRequests">
		<div class="filterCenter">
			Sort by: <select name="select_requests" id="select_requests">
				<option value="date_asc">Date ASC</option>
				<option value="date_desc">Date DESC</option>
				<option value="attack_asc">Attack ASC</option>
				<option value="attack_desc">Attack DESC</option>
				<option value="defense_asc">Defense ASC</option>
				<option value="defense_desc">Defense DESC</option>
			</select>
		</div>
	</form>
</div>

<div id="team_ajax">
<?php
foreach ($team_request as $i => $request) {
    $req_player = new Player($request->player_id);
    if ($i % 2 == 0) {
        $style = "float:left;";
        $br = "";
    } else {
        $align = "right";
        $style = "float:right;margin-left:50px;";
        $br = "<br>";
    }
    
    ?>



<div class="teamMembersBox" style="width: 300px; height: 150px">



	<div class="bossInfoBox" style="float: right; width: 150px">
		<p class="bossTitle" style="margin: 0px;">
		<?php
    // echo $req_player->account->username =="" ? ;
    if ($req_player->account->username == "") {
        $username = "{$req_player->account->first_name} {$req_player->account->last_name}";
    } else {
        $username = $req_player->account->username;
    }
    echo $username;
    ?>

		</p>
		<p class="bossInfo">Level: <?php echo $req_player->level_id;?></p>
		<p class="bossInfo">Rank:  <?php echo $req_player->rank->name;?> </p>
		<p class="bossInfo">Attack: <?php echo $req_player->attack;?></p>
		<p class="bossInfo">Defense: <?php echo $req_player->defense;?></p>
		<p>
			<input class="bossButton engageActiveBoss acceptBtn" type="submit"
				id="<?php echo $req_player->id?>" value="Accept">
		</p>
	</div>




	<div class="bossActiveImageSlot" style="width: 130px; height: 140px"float:left">
<?php if($req_player->account->profile_image==""){?>
<img style="float: left; width: 100%; height: 100%"
			src="<?php echo base_url(); ?>_images/male.jpg" />
<?php }else{?>
<img
			src="<?php echo base_url().'_images/data/accounts/'.$req_player->account->id.'/'.$req_player->account->profile_image; ?>"
			style="float: left; width: 100%; height: 100%" />
<?php }?>


</div>



<?php
    /*
     * ?>
     * <h2><?php echo $request->player_id?></h2>
     * <h2><?php echo $request->squad_id?></h2>
     * <h2><?php echo $request->activation_key?></h2>
     * <?php
     */
    ?>
</div>
<div id="my_res"></div>
<?php
    echo $br;
}

?>
</div>

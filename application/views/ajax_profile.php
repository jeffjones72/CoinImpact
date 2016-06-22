<?php /**Ticket #58 **/ ?>

<script type="text/javascript">
$(document).ready(function() {


    $('#cancel_drop').click(function(e) {
        $('#drop_modal').hide();
        e.stopPropagation();
    });
    $('[data-drop-id]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#drop_id').val($(this).attr('data-drop-id'));
        $('#drop_modal').show();
    });

});
</script>
<div class="ajaxContent">

	<img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('playerStats');" width="18" height="18"
		alt="Add" />

<?php
/*
 * CI:B0217 squad members
 */
?>
	<div>

		<input type="hidden" name="player_id"
			value="<?php echo $player->id;?>" id="input_id"> <select
			name="squad_members" class="selectorSquad">
			<option value="<?php echo "member_".$player->id?>"><?php
if ($player->account->username == "") {
    $p_username = "{$player->first_name} {$player->last_name}";
} else {
    $p_username = $player->account->username;
}

echo $p_username;
?></option>
	     <?php foreach ($squad_members as $member){?>
	     <?php

        if ($member->account_username == "") {
            $username = "{$member->first_name} {$member->last_name}";
        } else {
            $username = $member->account_username;
        }
        ?>
	     <option value="<?php echo "member_".$member->p_id?>"><?php echo substr($username, 0, 20);?></option>
	     <?php

}
    foreach ($squad_npc as $npc) {
        ?>
	      <option value="<?php echo "npc_".$npc->id?>"><?php echo substr($npc->account_username, 0, 20);?></option>

	   <?php  } ?>

	   </select>
	</div>
	<h1 class="addButtonBarLight">
		<strong>PLAYER:</strong><?php echo $player->account->first_name. ' ' . $player->account->last_name; ?>
	</h1>




	<div id="playerStats">
		<div class="bulletBg">
			<div class="squadPaper">
				<h2 class="page">Squad Activity</h2>
				<span style="margin-left: 20%">Coming soon</span>
				<!--
                <div>
                        Blain reached the rank of Colonel. 15 mins ago.
                        <input class="blueBtnS" type="submit" name="submit" value="Send gift">
                </div>
                -->
			</div>

			<div class="inventoryBox">

				<img class="profileBorder"
					src="<?php echo base_url(); ?>_images/side_profile_left.jpg" alt="">

				<div class="profileFrame">
					<img class="profileRank<?=$player->gender?>"
						src="<?php echo base_url(); ?>_images/rank.png" height="20"
						width="20" alt="rank"> <img
						src="<?php echo base_url(); ?>_images/<?=strtolower($player->gender)?>.jpg" height="150"
						width="130" alt="profile image">

				</div>
				<img class="profileBorder"
					src="<?php echo base_url(); ?>_images/side_profile_right.jpg"
					alt="">


				<div class="equippedItems">
<?php
foreach (Equipment::getSlotTypes() as $eq_slot) {
    $slot_id = $eq_slot->getId();
    ?>
<?php
    // is companion or vehicle
    if ($eq_slot->isCompanion() || $eq_slot->isVehicle()) {
        continue;
    }
    ?>
                    <div class="small-pad">
						<span class="smallTitle"><?php echo $eq_slot->getName(); ?></span>


                        <?php

    /*
     * $p_item = null;
     * if ($eq_slot->getId() == Item::RIGHT_HAND_SLOT_ID) {
     * $p_item = $player->getEquipment()->getItemEquippedAt('weapon2');
     * } else {
     * $p_item = $player->getEquipment()->getItemEquippedAt($eq_slot->getType());
     * }
     * if ($p_item) {
     */

    $p_item = null;
    if (isset($squad_items) && count($squad_items)) {
        foreach ($squad_items as $item) {
            if ($item->slot_id == $slot_id) {
                $p_item = $item;
                break;
            }
        }
    }
    if ($p_item) {
        if ($p_item->item->weight == 2) {
            ?> 
                        <img class="opacityRightHand"  
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?php if ($p_item->item->has_quality) {echo '-' . $p_item->quality;}?>.png"
							alt="<?=htmlspecialchars($p_item->item->name)?>"
							title="<?=$p_item->item->name. ' ' . $p_item->durability . '%'; ?>"
							width="85" /> 
                        <?php }?>
                        <img
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?=$p_item->item->has_quality ? '-'.$p_item->quality : ''?>.png" 
							alt="<?=htmlspecialchars($p_item->item->name)?>"
							title="<?=htmlspecialchars($p_item->item->name) . ' ' . $p_item->durability . '%'?>"
							width="85">
						<div
							class="equipStats equip<?=ucfirst($eq_slot->getType())?> rarity<?=$p_item->item->rarity_id?>">
							<h1>
								<span><?=$eq_slot->getName()?></span><?=$p_item->item->name?><?php if ($p_item->item->has_quality) {echo '- Q' . $p_item->quality;}?></h1>
							<p><?=$p_item->item->description?></p>
							<img
								src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?php if ($p_item->item->has_quality) {echo '-' . $p_item->quality . 's';}?>.png"
								alt="<?=$p_item->item->name?>"
								title="<?=$p_item->item->name . ' ' . $p_item->durability . '%'?>">
							<div class="statBox">
								<table>
                                    <?php foreach(Item::$stat_fields as $i => $field) { ?>
                                    <?php if($p_item->item->{$field}) {?>
                                    <tr>
										<th><?=Item::$stat_initials[$i]?></th>
										<td><?=$p_item->item->{$field}?></td>
									</tr>
                                    <?php } ?>
                                    <?php } //end foreach ?>
                                </table>
							</div>
							<form action="" method="post"> <?php //echo base_url().'action/unequip'; ?>
								<div>
									<input type="hidden" name="player_item_id"
										value="<?=$p_item->id?>" /> <input class="unEquip red"
										name="remove_item" type="submit" value="Remove">
								</div>
							</form>
						</div>
                        <?php } else { ?>
							<img class="opacity" style="margin:0" src="<?php echo base_url(); ?>_images/inventoryBg/<?php echo $eq_slot->getName(); ?><?php if ($eq_slot->getId() == Item::RIGHT_HAND_SLOT_ID) {echo '2'; } ?>.jpg" alt="head" height="49" width="85">
						<?php } ?>
                        
                        
                        
                    </div>
                    <?php }?>
                </div>
			</div>
			<!-- TODO: Finish section. -->
			<div class="squadInfo">
				<h2 class="page">Squad</h2>
				<h1>Squad Name</h1>
				<div class="squadAtk">
					<b>Squad Attack:</b>
					<div class="squadStatNumbers squadAttackNumber"><?php echo $squad_attack;?></div>
				</div>
				<div class="squadDef">
					<b>Squad Defense:</b>
					<div class="squadStatNumbers squadDefenseNumber"><?php echo $squad_defense;?></div>
				</div>
                <?php if ($friends) { ?>
                    <ul
					style="list-style-type: none; margin: 0; padding: 0;">
					<li style="margin: 0;"><a href="<?php echo base_url(); ?>profile">{{request.player}}</a></li>
					{% for friend in friends %}
					<li><a href="<?php echo base_url(); ?>profile/{{friend.friend.id}}">{{friend.friend}}</a></li>
					{% endfor %}
				</ul>
                <?php } ?>
                <?php if ($player) { ?>
            <!-- <form action="<?php echo base_url(); ?>remove-friend" method="post">

                    <div>
                            <input type="hidden" name="id" value="{{player.id}}" />
                            <input class="button" type="submit" value="Remove from team" />
                    </div>
            </form> -->
<?php } ?>
                <div style="clear: both"></div>
                <div class="squadBoost">
					<b>Squad bonuses:</b>
					<ul>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
				<input class="redBtnL squadButton red"
					onclick="window.location = '<?php echo base_url(); ?>team#section1'"
					type="submit" name="submit" value="EDIT SQUAD"> <input
					class="blueBtnL squadButton blue"
					onclick="window.location = '<?php echo base_url(); ?>team#section4'"
					type="submit" name="submit" value="RECRUIT FRIENDS">
			</div>
		 
			<!-- Vehicle -->
			<?php //$vehicle = $player->getVehicle(); 
          
            ?>
            <div class="vehicle">
				<div class="large-pad">
					<span class="largeTitle">Vehicle</span>

					<?php if($vehicle){?>
						<img
							src="<?php echo base_url(); ?>_images/data/items/<?php echo $vehicle->item->id;?>

							<?php if ($vehicle->item->has_quality) {
								echo '-' . $vehicle->quality . 's';
							} ?>.png"

							alt="<?php echo $vehicle->item->name; ?>"
							title="<?php echo $vehicle->item->name . ' ' . $vehicle->durability . '%'; ?>"
							height="88" width="155" />

					<h3><?php echo $vehicle->item->name; ?></h3>
					<p><?php echo $vehicle->item->description; ?></p>

					<div class="equipStats equipVehicle rarity<?=$vehicle->item->rarity_id?>">
						<h1><span>Vehicle</span><?=$vehicle->item->name?>
							<?php if ($vehicle->item->has_quality) {echo '- Q' . $vehicle->quality;}?></h1>
						<p><?=$vehicle->item->description?></p>
						<img src="<?php echo base_url(); ?>_images/data/items/<?=$vehicle->item->id?>
							<?php if ($vehicle->item->has_quality) {echo '-' . $vehicle->quality . 's';}?>.png"
							alt="<?=$vehicle->item->name?>"
							title="<?=$vehicle->item->name ?>">
						<div class="statBox">
							<table>
                            <?php foreach(Item::$stat_fields as $i => $field) { ?>
                                <?php if($vehicle->item->{$field}) {?>
                                    <tr>
										<th><?=Item::$stat_initials[$i]?></th>
										<td><?=$vehicle->item->{$field}?></td>
									</tr>
                                <?php } ?>
                            <?php } ?>
                            </table>
						</div>
						<form action="<?php echo base_url(); ?>action/unequip" method="post">
							<div>
								<input type="hidden" name="player_item_id" value="<?=$vehicle->id?>" />
								<input class="unEquip red" type="submit" value="Remove">
							</div>
						</form>
					</div>
			<?php } else { ?>
				<img class="emptyLargeSlot" src="/_images/inventoryBg/Vehicle.jpg" >
				<h3>Empty Slot</h3>
				<p>No Vehicle Equipped</p>
			<?php } ?>
                    <div class="itemStats">
						<img
							src="<?php echo base_url(); ?>_images//icons/attack_small.png"
							alt="attack icon">
						<p><?php
							if ($vehicle) {
								echo $vehicle->item->attack;
							} else {
								echo '0';
							}
							?></p>
					</div>
					<div class="itemStats">
						<img
							src="<?php echo base_url(); ?>_images/icons/defense_small.png"
							alt="defense icon">
						<p><?php
							if ($vehicle) {
								echo $vehicle->item->defense;
							} else {
								echo '0';
							}
							?></p>
					</div>
					<div class="itemStats">
						<img src="<?php echo base_url(); ?>_images/icons/energy_small.png"
							alt="energy icon">
						<p><?php
							if ($vehicle) {
								echo $vehicle->item->energy;
							} else {
								echo '0';
							}
							?></p>
					</div>
				</div>

				<!-- omitted till implemented
                <div style="float:left">
                        <div class="barContainerItem">
                                <span class="statTextItem">/0</span>
                                <span class="statTextItemCurrent">0</span>
                                <div id="vehicleIntegrity" class="HealthBar"></div>
                        </div>
                        <div class="statContainerItem">
                                <div class="statFormatItem">
                                        <div class="statBarTitle">INTEGRITY</div>
                                        <div class="clear"></div>
                                </div>
                        </div>
                </div>
                -->
			</div>

			<!-- Companion -->
			<?php //$companion = $player->getCompanion(); ?>
            <div class="companion">
				<div class="large-pad">
					<span class="largeTitle">Companion</span>
					<?php if($companion){ ?>
                        <img src="<?php echo base_url(); ?>_images/data/items/<?=$companion->item->id; ?>s.png"
							alt="<?=$companion->item->name; ?>"
							title="<?=$companion->item->name; ?>"
							height="88" width="155" />

						<h3><?php echo $companion->item->name; ?></h3>
						<p><?php echo $companion->item->description; ?></p>

						<div class="equipStats equipCompanion rarity<?=$companion->item->rarity_id?>">
							<h1><span>Companion</span><?=$companion->item->name?></h1>
							<p><?php echo $companion->item->description; ?></p>
                        	<img src="<?php echo base_url(); ?>_images/data/items/<?=$companion->item->id; ?>s.png"
								alt="<?=$companion->item->name; ?>"
								title="<?=$companion->item->name; ?>" />
							<div class="statBox">
								<table>
                        	 	   <?php foreach(Item::$stat_fields as $i => $field) { ?>
                           	 			<?php if($companion->item->{$field}) {?>
                                    		<tr>
												<th><?=Item::$stat_initials[$i]?></th>
												<td><?=$companion->item->{$field}?></td>
											</tr>
                                		<?php } ?>
                            		<?php } ?>
                            	</table>
							</div>
							<form action="<?php echo base_url(); ?>action/unequip" method="post">
								<div>
									<input type="hidden" name="player_item_id" value="<?=$companion->id?>" />
									<input class="unEquip red" type="submit" value="Remove">
								</div>
							</form>
						</div>
					<?php } else { ?>
						<div class="emptyBlock">
							<img class="emptyLargeSlot" src="/_images/inventoryBg/Companion.jpg" >
						</div>
						<h3>Empty Slot</h3>
						<p>No Companion Equipped</p>
					<?php } ?>

 					<div class="itemStats">
						<img
							src="<?php echo base_url(); ?>_images//icons/attack_small.png"
							alt="attack icon">
						<p><?php
    if ($companion) {
        echo $companion->item->attack;
    } else {
        echo '0';
    }
    ?></p>
					</div>
					<div class="itemStats">
						<img
							src="<?php echo base_url(); ?>_images//icons/defense_small.png"
							alt="defense icon">
						<p><?php
    if ($companion) {
        echo $companion->item->defense;
    } else {
        echo '0';
    }
    ?></p>
					</div>
					<div class="itemStats">
						<img src="<?php echo base_url(); ?>_images/icons/critical_small.png"
							alt="strike icon">
						<p><?php
    if ($companion) {
        echo $companion->item->strike;
    } else {
        echo '0';
    }
    ?></p>
					</div>
				</div>

				<!-- omitted till implemented
                <div style="float:left">
                        <div class="barContainerItem">
                                <span class="statTextItem">/0</span>
                                <span class="statTextItemCurrent">0</span>
                                <div id="petHealth" class="HealthBar"></div>
                        </div>
                        <div class="statContainerItem">
                                <div class="statFormatItem">
                                        <div class="statBarTitle">Health</div>
                                        <div class="clear"></div>
                                </div>
                        </div>
                </div>

                <div style="float:left">
                        <div class="barContainerItem">
                                <span class="statTextItem">/0</span>
                                <span class="statTextItemCurrent">0</span>
                                <div id="petExperience" class="LevelBar"></div>
                        </div>
                        <div class="statContainerItem">
                                <div class="statFormatItem">
                                        <div class="statBarTitle">Level 0</div>
                                        <div class="clear"></div>
                                </div>
                        </div>
                </div>
                <input class="treat blue" type="submit" name="submit" value="Give a treat">
                -->
			</div>
			<div class="purchaseMenu">
				<div class="buyBox"
					style="width: 180px; margin: 14px 10px 0px 10px;">
					<div
						style="width: 90px; float: left; margin: 3px 0px; color: #333;">
						Featured Weapon: Bayonet, Quality 5</div>
					<img
						src="<?php echo base_url(); ?>_images/chesty_pullers_ka_bar.jpg"
						style="float: right" height="45" width="80" alt="item">
					<div style="width: 22px; float: left; border-right: 1px solid #333">
						Buy</div>
					<div style="width: 60px; float: left; padding-left: 5px;">Learn
						more</div>
				</div>
				<input style="float: right; margin-top: 5px;" class="buyItemBtn"
					type="submit" name="submit" value="Buy More"> <input
					style="float: right; margin-top: 5px;" class="buyCoinBtn"
					type="submit" name="submit" value="Buy More">
				<div style="width: 60px; float: right; padding-top: 13px;">
					<h2 class="brownFont">store:</h2>
					<img style="margin: 0px auto;"
						src="<?php echo base_url(); ?>_images/icoAdd.png" width="18"
						height="18" alt="Add">
				</div>
			</div>
		</div>
	</div>
	<img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('statusTotals');" width="18" height="18"
		alt="Add" />
	<h2 class="addButtonBar">Stats</h2>
	<div class="statDogTagBg" id="statusTotals">
		<div>
			<table class="list statDogTagFormat">
				<tr class="statTitle">
					<th></th>
					<td><img src="/_images/icons/attack_small.png" alt="attack icon"></td>
					<td><img src="/_images/icons/defense_small.png" alt="defense icon"></td>
					<td><img src="/_images/icons/stamina_small.png" alt="stamina icon"></td>
					<td><img src="/_images/icons/energy_small.png" alt="energy icon"></td>
					<td><img src="/_images/icons/health_small.png" alt="health icon"></td>
					<td><img src="/_images/icons/critical_small.png"
						alt="critical strike icon"></td>
					<td><img src="/_images/icons/dodge_small.png" alt="dodge icon"></td>
					<td><img src="/_images/icons/luck_small.png" alt="luck icon"></td>
				</tr>

				<tr class="statData">
					<th>Base:</th>
					<td class="statData"><?php echo $stats->base_attack; ?></td>
					<td class="statData"><?php echo $stats->base_defense; ?></td>
					<td class="statData"><?php echo $stats->stamina_limit; ?></td>
					<td class="statData"><?php echo $stats->energy_limit; ?></td>
					<td class="statData"><?php echo $stats->health_limit; ?></td>
					<td class="statData"><?php echo $stats->base_strike; ?></td>
					<td class="statData"><?php echo $stats->dodge; ?></td>
					<td class="statData"><?php echo $stats->luck; ?></td>
				</tr>
<?php ?>
                <tr class="statData">
					<th>Items:</th>
					<td class="statData"><?php echo $stats->delta_attack; ?></td>
					<td class="statData"><?php echo $stats->delta_defense; ?></td>
					<td class="statData"><?php echo $stats->delta_stamina_limit; ?></td>
					<td class="statData"><?php echo $stats->delta_energy_limit; ?></td>
					<td class="statData"><?php echo $stats->delta_health_limit; ?></td>
					<td class="statData"><?php echo $stats->delta_strike; ?></td>
					<td class="statData"><?php echo $stats->delta_dodge; ?></td>
					<td class="statData"><?php echo $stats->delta_luck; ?></td>
				</tr>
				<tr>
					<th>Total:</th>
					<td class="statData"><?php echo $stats->base_attack + $stats->delta_attack; ?></td>
					<td class="statData"><?php echo $stats->base_defense + $stats->delta_defense ; ?></td>
					<td class="statData"><?php echo $stats->stamina_limit + $stats->delta_stamina_limit ; ?></td>
					<td class="statData"><?php echo $stats->energy_limit + $stats->delta_energy_limit ; ?></td>
					<td class="statData"><?php echo $stats->health_limit + $stats->delta_health_limit ; ?></td>
					<td class="statData"><?php echo $stats->base_strike + $stats->delta_strike ; ?></td>
					<td class="statData"><?php echo $stats->dodge + $stats->delta_dodge ; ?></td>
					<td class="statData"><?php echo $stats->luck + $stats->delta_luck ; ?></td>
				</tr>
			</table>
		</div>
	</div>
                <?php if ($player->skill) { ?>
        <p>You have <?php echo $player->skill; ?> unused skill points. <a
			href="<?php echo base_url(); ?>skill">Use them</a>
	</p>
                <?php } ?>
    <div class="clear"></div>


                <?php if (isset($items['unequipped'])) { ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('itemSection');" width="18" height="18"
		alt="Add" />
	<div class="inventoryCount">Inventory: <?php echo $player->getInventoryItemsCount() . '/' . $player->getInventoryCapacity(); ?></div>
	<h2 class="addButtonBar">Items:</h2>
	<div class="clear"></div>

	<div id="itemSection">


		<!--         <pre> -->
        <?php
                    // var_dump($items['unequipped']);
                    ?>
                    <?php foreach ($items['unequipped'] as $item) { ?>
                <div class="inventory-slot">
			<span class="largeTitle"><?php
	/*
	 * CODE  CI:B0201
	 * changed the appearence for  number of items
	 * $pieces is the number of items the player has
	 * the same for things with $thing_pieces
	 */

			if($item['pieces']>1){
			    $pieces=" ({$item['pieces']})";
			}else{
			    $pieces="";
			}
			echo "{$item['name']}$pieces"; ?></span>
			<img
				src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['id']; ?>
				<?php
				            if ($item['has_quality']) {
                            echo '-' . $item['quality'];
                        }
                        ?>.png"
				alt="" />
			<div class="inventoryStats rarity<?php echo $item['rarity_id']; ?>">
                        <?php
                        if ($item['section_id'] == 13 || $item['section_id'] == 15 || $item['section_id'] == 19)
                            $itemType = 'Head';

                        if ($item['section_id'] == 8 || $item['section_id'] == 10)
                            $itemType = "Eyes";

                        if ($item['section_id'] == 16 || $item['section_id'] == 20 || $item['section_id'] == 21)
                            $itemType = "Neck";

                        if ($item['section_id'] == 29 || $item['section_id'] == 31)
                            $itemType = "Shoulders";

                        if ($item['section_id'] == 4 || $item['section_id'] == 34)
                            $itemType = "Chest";

                        if ($item['section_id'] == 26 || $item['section_id'] == 27)
                            $itemType = "Shirt";

                        if ($item['section_id'] == 2 || $item['section_id'] == 6 || $item['section_id'] == 7)
                            $itemType = "Back";

                        if ($item['section_id'] == 9 || $item['section_id'] == 14 || $item['section_id'] == 25)
                            $itemType = "Hands";

                        if ($item['section_id'] == 12 || $item['section_id'] == 17 || $item['section_id'] == 24 || $item['section_id'] == 30 || $item['section_id'] == 32)
                            $itemType = "Weapon";

                        if ($item['section_id'] == 3 || $item['section_id'] == 36)
                            $itemType = "Belt";

                        if ($item['section_id'] == 22)
                            $itemType = "Legs";

                        if ($item['section_id'] == 5 || $item['section_id'] == 28)
                            $itemType = "Feet";

                        if ($item['section_id'] == 1 || $item['section_id'] == 11 || $item['section_id'] == 35)
                            $itemType = "Vehicle";

                        if ($item['section_id'] == 23 || $item['section_id'] == 33)
                            $itemType = "Companion";
                        if ($item['section_id'] == 18)
                            $itemType = "Masks";

                        ?>

                        <h1>
					<span><?php echo $itemType ?></span><?php echo $item['name']; ?><?php

                        if ($item['has_quality'] > 0) {
                            echo '- Q' . $item['quality'];
                        }
                        ?></h1>
				<p><?php echo $item['description']; ?></p>

				<table class="info">
					<tr>
                                <?php if ($item['attack']) { ?><th><img
							src="/_images/icons/attack_large.png" alt="attack icon"></th><?php } ?>
                                <?php if ($item['defense']) { ?><th><img
							src="/_images/icons/defense_large.png" alt="defense icon"></th><?php } ?>
                                <?php if ($item['energy']) { ?><th><img
							src="/_images/icons/energy_large.png" alt="energy icon"></th><?php } ?>
                                <?php if ($item['stamina']) { ?><th><img
							src="/_images/icons/stamina_large.png" alt="stamina icon"></th><?php } ?>
                                <?php if ($item['health']) { ?><th><img
							src="/_images/icons/health_large.png" alt="health icon"></th><?php } ?>
                                <?php if ($item['strike']) { ?><th><img
							src="/_images/icons/critical_large.png"
							alt="critical strike icon"></th><?php } ?>
        <?php if ($item['dodge']) { ?><th><img
							src="/_images/icons/dodge_large.png" alt="dodge icon"></th><?php } ?>
        <?php if ($item['luck']) { ?><th><img
							src="/_images/icons/luck_large.png" alt="luck icon"></th><?php } ?>
                            </tr>
					<tr>
        <?php
                        if ($item['attack']) {
                            echo '<td>' . $item['attack'] . '</td>';
                        }
                        ?>
        <?php
                        if ($item['defense']) {
                            echo '<td>' . $item['defense'] . '</td>';
                        }
                        ?>
        <?php
                        if ($item['energy']) {
                            echo '<td>' . $item['energy'] . '</td>';
                        }
                        ?>
        <?php
                        if ($item['stamina']) {
                            echo '<td>' . $item['stamina'] . '</td>';
                        }
                        ?>
            <?php
                        if ($item['health']) {
                            echo '<td>' . $item['health'] . '</td>';
                        }
                        ?>
        <?php
                        if ($item['strike']) {
                            echo '<td>' . $item['strike'] . '</td>';
                        }
                        ?>
        <?php
                        if ($item['dodge']) {
                            echo '<td>' . $item['dodge'] . '</td>';
                        }
                        ?>
                <?php
                        if ($item['luck']) {
                            echo '<td>' . $item['luck'] . '</td>';
                        }
                        ?>
                            </tr>
				</table>
				<div class="clear"></div>

				<div class="inventoryOptions">
					<form action="<?php echo base_url(); ?>action/equip" method="post">
						<div>
							<input type="hidden" name="player_item_id"
								value="<?php echo $item['player_item_id']; ?>" /> <input
								type="submit" class="blue enableItem" value="Enable" />
						</div>
					</form>

					<form action="<?php echo base_url(); ?>action/drop" method="post">
						<div>
							<input type="hidden" name="player_item_id"
								value="<?php echo $item['player_item_id']; ?>" /> <input
								type="submit" class="cancel red" value="Drop"
								data-drop-id='<?=$item['player_item_id']?>' />
						</div>
					</form>
				</div>
			</div>
		</div>
                            <?php } ?>
        </div>
	<div class="clear"></div>
                        <?php } ?>
<?php if($inactive_modifiers){ ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('modifiersSection');" width="18"
		height="18" alt="Add" />
	<h2 class="addButtonBar">Modifiers</h2>
	<div id="modifiersSection">
		<div id="popModifier" class="popUp">
			<div class="closeBtn closeModPos"
				onclick="javascript:toggle('popModifier')"></div>
			<p>Are you sure you want to use the mod on this item, changes will be
				permanent?</p>
			<input type="button" id="modify" class="modBtn blue" value="Enable">
		</div>
    <?php

foreach ($inactive_modifiers as $p_modifier) {
                        $stat_types = array(
                            'attack',
                            'defense',
                            'energy',
                            'stamina',
                            'health',
                            'strike',
                            'dodge',
                            'luck'
                        );
                        ?>
        <div class="mod-slot">
			<span class="largeTitle"><?=$p_modifier->modifier->name?></span> <img
				src="<?php echo base_url(); ?>_images/data/modifiers/<?=$p_modifier->modifier->id?>.png"
				height="92" width="165" alt="" />
			<div class="modSlotInfo">
				<table class="info">
					<tr>
                    <?php foreach($stat_types as $stat_type) {?>
                        <?php if ($p_modifier->modifier->$stat_type && $stat_type != 'strike') { ?><th><img
							src="/_images/icons/<?=$stat_type?>_small.png"
							alt="<?=$stat_type?> icon"></th><?php } ?>
                        <?php if ($p_modifier->modifier->$stat_type && $stat_type == 'strike') { ?><th><img
							src="/_images/icons/critical_small.png"
							alt="critical strike icon"></th><?php } ?>
                    <?php }?>
                    </tr>
					<tr>
                    <?php foreach($stat_types as $stat_type) {?>
                        <?php if ($p_modifier->modifier->$stat_type) { ?><th><?=$p_modifier->modifier->$stat_type?></th><?php } ?>
                    <?php }?>
                    </tr>
				</table>
				<form class="modEnable"
					action="<?php echo base_url(); ?>action/enable_modifier"
					method="post">
					<input type="hidden" name="modifier_id"
						value="<?=$p_modifier->id?>"> <input type="button"
						class="modBtn blue" value="Enable">
					<!--<label for="item">Item</label>-->
					<select class="modifier_item" name="player_item_id">
						<option value="default">--Select an Item--</option>
<?php
                        foreach ($p_modifier->getItems() as $p_item) {
                            echo '<option value="' . $p_item->id . '">' . $p_item->item->name . '</option>';
                        }
                        ?>
                    </select>
				</form>
				<form class="modCancel"
					action="<?php echo base_url(); ?>action/drop_modifier"
					method="post" style="float: left;">
					<div>
						<input type="hidden" name="modifier_id"
							value="<?=$p_modifier->id?>" /> <input type="submit"
							class="modBtn red" value="Drop"
							data-drop-id="<?=$p_modifier->id?>" />
					</div>
				</form>
			</div>
		</div>
    <?php }?>
        <div class="clear"></div>
	</div>
<?php } ?>
<?php if (isset($things)) { ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('thingsSection')" width="18" height="18"
		alt="Add">
	<h2 class="addButtonBar">Things</h2>
	<div id="thingsSection">
    <?php foreach ($things as $thing) { ?>
                <div class="inventory-slot">
			<span class="largeTitle">

			<?php

			/*
			 * CODE:  CI:B0201
			 * changed the appearence for number of things
			 */
			if($thing['pieces']>1){
			    $thing_pieces=" ({$thing['pieces']})";
			}else{
			    $thing_pieces="";
			}

			echo "{$thing['name']}{$thing_pieces}"; ?></span>
			<img
				src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['id']; ?>.png"
				alt="">
			<div class="inventoryStats rarity<?php echo $thing['rarity_id']; ?>">
				<h1>
					<span>Things</span><?php echo $thing['name']; ?></h1>
				<p><?php echo $thing['description']; ?></p>
			</div>
		</div>
            <?php } ?>
        </div>
	<div class="clear"></div>

<?php } ?>

<?php if (isset($boosts)) { ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('boostsSection')" width="18" height="18"
		alt="Add">
	<h2 class="addButtonBar">Boosts</h2>
	<div id="boostsSection">
    <?php foreach ($boosts as $boost) { ?>
                <div class="inventory-slot">
			<span class="largeTitle"><?php echo $boost['boost_name']; ?></span> <img
				src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['boost_id']; ?>.png"
				alt="<?php echo $boost['boost_name']; ?>">

			<div class="inventoryStats">
				<h1>
					<span>Boosts</span><?php echo $boost['boost_name']; ?></h1>
				<p><?php echo $boost['boost_description']; ?></p>
				<div class="inventoryOptions">
					<form action="<?php echo base_url(); ?>apply-boost" method="post">
						<div>
							<input type="hidden" name="id" value="{{boost.id}}" /> <input
								type="submit" class="blue" value="Apply" />
						</div>
					</form>
				</div>
			</div>
		</div>
    <?php } ?>
        </div>
	<div class="clear"></div>
<?php } ?>

<?php if (isset($boosts['active'])) { ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('activeBoostSection')" width="18"
		height="18" alt="Add">
	<h2 class="addButtonBar">Active Boosts</h2>
	<div id="activeBoost">
    <?php foreach ($boosts['active'] as $boost) { ?>
                <div class="inventory-item">
			<div style="height: 5em;">
				<h3><? echo $boost['boost_name']; ?></h3>
				<p><? echo $boost['boost_description']; ?></p>
			</div>
			<img
				src="<?php echo base_url(); ?>_images/data/boosts/<? echo $boost['boost_id']; ?>.png"
				alt="" />
                    Expires <? echo $boost['boost_expires']; ?>
                </div>
    <?php } ?>
        </div>
	<div class="clear"></div>
<?php } ?>

</div>

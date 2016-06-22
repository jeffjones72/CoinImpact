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
<div id="drop_modal" class="modal">
	Are you sure you want to DROP this item?<br>
	<form method="post" style="width: 50px; float: left">
		<input type="hidden" name="drop_id" id="drop_id">
		<button class="cancel red">Yes</button>
	</form>
	<button class="blue cancelDiscard" id="cancel_drop">No</button>
</div>
<div class="box2">
	<img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('playerStats');" width="18" height="18"
		alt="Add" />
	<h1 class="addButtonBarLight">
		<strong>PLAYER:</strong><?php echo $player->account->first_name. ' ' . $player->account->last_name; ?></h1>

	<div id="playerStats">
		<div class="bulletBg">
			<div class="squadPaper">
				<h2 class="page">Squad Activity</h2>
                <span style="margin-left:20%">Coming soon</span>
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
					<img class="profileRank"
						src="<?php echo base_url(); ?>_images/rank.png" height="20"
						width="20" alt="rank"> <img
						src="<?php echo base_url(); ?>_images/male.jpg" height="150"
						width="130" alt="profile image">

				</div>
				<img class="profileBorder"
					src="<?php echo base_url(); ?>_images/side_profile_right.jpg"
					alt="">


				<div class="equippedItems">
                    <?php foreach (Equipment::getSlotTypes() as $eq_slot) { ?>
                    <?php
                        
if ($eq_slot->isCompanion() || $eq_slot->isVehicle()) {
                            continue;
                        }
                        ?>
                    <div class="small-pad">
						<span class="smallTitle"><?php echo $eq_slot->getName(); ?></span>
                        <?php
                        $p_item = null;
                        if ($eq_slot->getId() == Item::RIGHT_HAND_SLOT_ID) {
                            $p_item = $player->getEquipment()->getItemEquippedAt('weapon2');
                        } else {
                            $p_item = $player->getEquipment()->getItemEquippedAt($eq_slot->getType());
                        }
                        if ($p_item) {
                            ?>
                        <?php if($p_item->item->weight == 2) {?>
                        <img class="opacity"
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?php if ($p_item->item->has_quality) {echo '-' . $p_item->quality;}?>.png"
							alt="<?=$p_item->item->name?>"
							title="<?=$p_item->item->name. ' ' . $p_item->durability . '%'; ?>"
							width="85" />
                        <?php }?>
                        <img
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?=$p_item->item->has_quality ? '-'.$p_item->quality : ''?>.png"
							alt="<?=$p_item->item->name?>"
							title="<?=$p_item->item->name . ' ' . $p_item->durability . '%'?>"
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
                                    <?php } ?>
                                </table>
							</div>
							<form action="<?php echo base_url(); ?>action/unequip"
								method="post">
								<div>
									<input type="hidden" name="player_item_id"
										value="<?=$p_item->id?>" /> <input class="unEquip red"
										type="submit" value="Remove">
								</div>
							</form>
						</div>
                        <?php }?>
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
					<div class="squadStatNumbers">0</div>
				</div>
				<div class="squadDef">
					<b>Squad Defense:</b>
					<div class="squadStatNumbers">0</div>
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
                <div class="squadBoost">
					<b>squad bonuses:</b>
					<ul>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
				<input class="redBtnL squadButton red"
					onclick="window.location = '<?php echo base_url(); ?>players'"
					type="submit" name="submit" value="EDIT SQUAD"> <input
					class="blueBtnL squadButton blue"
					onclick="window.location = '<?php echo base_url(); ?>players'"
					type="submit" name="submit" value="RECRUIT FRIENDS">
			</div>

			<!-- Vehicle -->
<?php $vehicle = $player->getVehicle(); ?>
            <div class="vehicle">
				<div class="large-pad">
					<span class="largeTitle">Vehicle</span>
 <?php if($vehicle){?>
                    <div class="emptyBlock">
					<img
							src="<?php echo base_url(); ?>_images/data/items/<?php
    
echo $vehicle->item->id;
    ?><?php

    if ($vehicle->item->has_quality) {
        echo '-' . $vehicle->quality . 's';
    }
    ?>.png"
							alt="<?php echo $vehicle->item->name; ?>"
							title="<?php echo $vehicle->item->name . ' ' . $vehicle->durability . '%'; ?>"
							height="88" width="155" />
					</div>
					<h3><?php echo $vehicle->item->name; ?></h3>
					<p><?php echo $vehicle->item->description; ?></p>
 <?php } else {?>
            <div class="emptyBlock">
                <img class="emptyVehicle" src="/_images/blankSlot_big.jpg" >
            </div>
            <h3></h3>
            <p></p>
                    
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
                            <?php $companion = $items['item_slots'][14]; ?>
            <div class="companion">
				<div class="large-pad">
					<span class="largeTitle">Companion</span>
					<div class="emptyBlock">
<?php { ?>
                            <img
							src="<?php echo base_url(); ?>_images/data/items/<?php
    
echo $companion['id'];
    ?><?php

    if ($companion['has_quality']) {
        echo '-' . $companion['quality'] . 's';
    }
    ?>.png"
							alt="<?php echo $companion['name']; ?>"
							title="<?php echo $companion['name'] . ' ' . $companion['durability'] . '%'; ?>"
							height="88" width="155" />
                            <?php } ?>
                    </div>
					<h3><?php echo $companion['name']; ?></h3>
					<p><?php echo $companion['description']; ?></p>

					<div class="itemStats">
						<img
							src="<?php echo base_url(); ?>_images//icons/attack_small.png"
							alt="attack icon">
						<p><?php
    if ($companion['attack'] > 0) {
        echo $companion['attack'];
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
    if ($companion['defense'] > 0) {
        echo $companion['defense'];
    } else {
        echo '0';
    }
    ?></p>
					</div>
					<div class="itemStats">
						<img src="<?php echo base_url(); ?>_images/icons/energy_small.png"
							alt="energy icon">
						<p><?php
    if ($companion['energy'] > 0) {
        echo $companion['energy'];
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
					<td class="statData"><?php echo $player->getBaseAttack(); ?></td>
					<td class="statData"><?php echo $player->getBaseDefense(); ?></td>
					<td class="statData"><?php echo $player->getBaseStaminaLimit(); ?></td>
					<td class="statData"><?php echo $player->getBaseEnergyLimit(); ?></td>
					<td class="statData"><?php echo $player->getBaseHealthLimit(); ?></td>
					<td class="statData"><?php echo $player->getBaseStrike(); ?></td>
					<td class="statData"><?php echo $player->getBaseDodge(); ?></td>
					<td class="statData"><?php echo $player->getBaseLuck(); ?></td>
				</tr>
<?php ?>
                <tr class="statData">
					<th>Items:</th>
					<td class="statData"><?php echo $player->getAttackFromItems(); ?></td>
					<td class="statData"><?php echo $player->getDefenseFromItems(); ?></td>
					<td class="statData"><?php echo $player->getStaminaFromItems(); ?></td>
					<td class="statData"><?php echo $player->getEnergyFromItems(); ?></td>
					<td class="statData"><?php echo $player->getHealthFromItems(); ?></td>
					<td class="statData"><?php echo $player->getStrikeFromItems(); ?></td>
					<td class="statData"><?php echo $player->getDodgeFromItems(); ?></td>
					<td class="statData"><?php echo $player->getLuckFromItems(); ?></td>
				</tr>
				<tr>
					<th>Total:</th>
					
					<td class="statData"><?php echo $player->getAttack(); ?></td>
					<td class="statData"><?php echo $player->getDefense(); ?></td>
					<td class="statData"><?php echo $player->getStamina(); ?></td>
					<td class="statData"><?php echo $player->getEnergy(); ?></td>
					<td class="statData"><?php echo $player->getHealth(); ?></td>
					<td class="statData"><?php echo $player->getStrike(); ?></td>
					<td class="statData"><?php echo $player->getDodge(); ?></td>
					<td class="statData"><?php echo $player->getLuck(); ?></td>
					
					
					
					<?php 
					/*
					?>
					<td class="statData"><?php echo $player->getAttack(); ?></td>
					<td class="statData"><?php echo $player->getDefense(); ?></td>
					<td class="statData"><?php echo $player->getStaminaLimit(); ?></td>
					<td class="statData"><?php echo $player->getEnergyLimit(); ?></td>
					<td class="statData"><?php echo $player->getHealthLimit(); ?></td>
					<td class="statData"><?php echo $player->getStrike(); ?></td>
					<td class="statData"><?php echo $player->getDodge(); ?></td>
					<td class="statData"><?php echo $player->getLuck(); ?></td>
					<?php */?>
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
								type="submit" class="blue" value="Enable" />
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

    <?php if (isset($collections)) { ?>
        <img class="titleAddBtn"
		src="<?php echo base_url(); ?>_images/icoAdd.png"
		onclick="javascript:toggle('collectionSection')" width="18"
		height="18" alt="Add">
	<h2 class="addButtonBar">Collections</h2>
	<div id="collectionSection">
            <?php foreach ($collections as $collection) { ?>
                <div class="combineSection">
			<h2>assembled: <?php echo $collection['name']; ?></h2>
        <?php foreach ($collection['thing'] as $thing) { ?>
                        <div class="combinePiece"
				onmouseover="javascript:show('<?php echo $thing['thing_id']; ?>t')"
				onmouseout="javascript:hide('<?php echo $thing['thing_id']; ?>t')">
				<p>+</p>
				<img <?php if(!$thing['thing_id']) {?> class="faded" <?php }?>
					src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['id']; ?>t.png"
					alt="" />
				<div id="{{t.id}}t" class="upArrowTooltip assemblePos">
					<p><?php echo $thing['name']; ?></p>
				</div>
			</div>
        <?php } ?>
                    <div class="combineFinal">
				<p class="equal">=</p>
				<img
					src="<?php echo base_url(); ?>_images/data/items/<?php echo $collection['item_id']; ?>s.png"
					alt="" />
			</div>
			<form action="<?php echo base_url(); ?>action/assemble_collection"
				method="post">
				<div>
					<input type="hidden" name="item_id"
						value="<?php echo $collection['item_id']; ?>" />
        <?php
            if ($collection['complete'] == 'yes' && ($player['inventory_count'] < $player['inventory_capacity'])) {
                echo '<input type="submit" class="button blue" value="Assemble" />';
            }
            ?>
                        </div>
			</form>
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
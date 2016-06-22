<script type="text/javascript">
$(document).ready(function(){
    $('[data-disabled-reason="inventory_full"]').click(function(e){
        $('#inventoryFullModal').show();
        e.preventDefault();
        e.stopPropagation();
    })
    $('[data-item-id]').mouseenter(function(){
        if($(this).attr('data-quality')) {
            show($(this).attr('data-item-id')+'-'+$(this).attr('data-quality'));
        } else {
            show($(this).attr('data-item-id'));
        }
    });
    $('[data-item-id]').mouseout(function(){
        if($(this).attr('data-quality')) {
            hide($(this).attr('data-item-id')+'-'+$(this).attr('data-quality'));
        } else {
            hide($(this).attr('data-item-id'));
        }
    });
    if(stat_info['health'] < 10) {
        $('.fightBox').hide();
    }
});
</script>
<div id="inventoryFullModal" class="modal">
	<p style="color: red"><?=$lang['collect_item_error_full_inventory']?></p>
</div>
<div class="box" style="height: 305px;">
	<!-- TODO: Need to have a read for levelup.
            {% if request.session.level_up %}
                    <div id="popLevel" class="popUp">
                            <div class="closeBtn closeLevelPos" onclick="javascript:toggle('popLevel')">
                            </div>
                            <img src='<?php echo base_url(); ?>_images/levelUp.jpg' alt='level up image'>
                            <h2>Level {{request.player.level.id}}</h2>
                            <p>Congratulations, you have reached level {{request.player.level.id}}.</p>
                            <form id="levelUp" action="<?php echo base_url(); ?>comfirm_levelup" method="post">
                                    <div>
                                            <input id="congratBtn" class="blue center" type="submit" value="OK" />
                                    </div>
                            </form>
                    </div>
            {% endif %}
    -->

	<img class="hideIcon" src="<?php echo base_url(); ?>_images/camera.png"
		alt="" height="25" width="25" />
	<div class="locationTitleTab"><?=$player->p_place->place->name?></div>
	<img style="position: absolute; margin-top: 25px;"
		src="<?php echo base_url(); ?>_images/data/places/<?=$player->p_place->place->id?>.png"
		alt="" width="700" height="276" />
	<div id="location" class="exploreLocation">
		<p class="bold"><?=$player->p_place->place->description?></p>
		<!--
        <div>
                <p>Combatants</p>
                {% for c in request.player.location.list_combatants %}
                <img title="{{c.combatant.name}}" src="<?php echo base_url(); ?>_images/data/combatants/{{c.combatant.id}}t.png" alt="" style="float:right;margin: 0 0 10px 10px;" />
                {% endfor %}
                <div class="clear"></div>
        </div>

        <div>
                <p>Traders</p>
                {% for c in request.player.location.list_traders %}
                <img title="{{c.trader.name}}" src="<?php echo base_url(); ?>_images/data/traders/{{c.trader.id}}t.png" alt="" style="float:right;margin: 0 0 10px 10px;" />
                {% endfor %}
                <div class="clear"></div>
        </div>
        -->
	</div>
	<div class="exploreStatBar">
		<div class="exploreProgressBg">
			<div class="exploreProgress" style="width:<?=$player->p_place->progress?>%;"></div>
			<div class="exploreProgressText">
				<strong><?=$player->p_place->place->name?></strong> - <?=$player->p_place->progress?>% explored</div>
		</div>

		<div id="exploreBar">

<?php 
/*
 * CI:B0211
 * Trust bar with coins left; max amount is $350
 * it is calculated in controller action, function  accept_trader
 */
$this->load->view("trust_bar");

?>


             <?php if ($player->energy >= $player->energy && !isset($p_combatant) && !isset($p_event) && !$hasTrader) {  //and not combatant && not boss && not event && not trader %} ?>
         <form id="explore" method="post" autocomplete="off">
				<input type="hidden" name="action" value="explore" /> <input
					type="submit" id="exploreBtn" class="exploreButton blue"
					value="Explore" />
			</form>
    <?php } ?>
         <div id="cityLevelBox">
				<img class="titleAddBtn"
					src="<?php echo base_url(); ?>_images/icoInfo.png" width="18"
					height="18" alt="Add" style="margin-top: 0px; margin-left: 10px;">
			</div>

		</div>

	</div>



</div>

<div style="height: 310px;">

	<div class="greenSectionBtn endTab"
		onclick="javascript:showonlyone('section3')">+SQUAD</div>
	<div class="blueSectionBtn"
		onclick="javascript:showonlyone('section2')">EXPLORE</div>
	<div class="tanSectionBtn"
		onclick="javascript:showonlyone('section1');">MISSIONS</div>

    <?php if (!isset($p_event) && !isset($p_combatant) && $hasTrader == 0) { ?>
        <div class="missionSection section" id="section1">
		<!-- start of missions -->

            <?php foreach ($missions as $mission) { ?>
                <?php if ($mission['completed_pct'] == 100 && !$mission['completed']) { ?>
                    <h2>Mission Completed</h2>
		<p>Congratulations, you completed <?php echo $mission['name']; ?>.</p>
		<form
			action="<?php echo base_url(); ?>action/confirm_mision_completion"
			method="post">
			<div>
				<input type="hidden" name="player_mission_id"
					value="<?php echo $mission['player_mission_id']; ?>" /> <input
					class="button blue" type="submit" value="OK" />
			</div>
		</form>
                <?php } ?>
            <?php } ?>

            <?php foreach ($missions as $mission) { ?>
                <?php if (!$mission['completed']) { ?>
                    <div
			style="background-color: white; padding: 10px; margin-bottom: 10px;">
                    <?php } else { ?>
                        <div
				style="background-color: #ccc; padding: 10px; margin-bottom: 10px;">
                        <?php } ?>
                        <img
					src="<?php echo base_url(); ?>_images/data/missions/<?php echo $mission['mission_id']; ?>s.png"
					alt="" style="float: right; margin: 0 0 10px 10px;" />
				<h1><?php echo $mission['name']; ?></h1>
				<p style="height: 4em;"><?php echo $mission['description']; ?></p>
				<!--			<?php if (!$mission['started']) { ?>
                                                                        <form action="<?php echo base_url(); ?>action/accept_mission" method="post">
                                                                                <div>
                                                                                        <input type="hidden" name="player_mission_id" value="<?php echo $mission['player_mission_id']; ?>" />
                                                                                        <input type="submit" class="button blue" value="Accept" />
                                                                                </div>
                                                                        </form>
                        <?php } else { ?>
                                                                        <form action="<?php echo base_url(); ?>action/quit_mission" method="post">
                                                                                <div>
                                                                                        <input type="hidden" name="player_mission_id" value="<?php echo $mission['player_mission_id']; ?>" />
                                                                                        <input type="submit" class="button red" value="Quit" />
                                                                                </div>
                                                                        </form>
                        <?php } ?>
                        -->
				<div class="progress-box"><?php echo $mission['completed_pct']; ?>%</div>
				<div>
                            <?php
            if ($mission['required_event_count'] > 0)
                echo 'Events: ' . $mission['required_events_completed'] . '/' . $mission['required_event_count'] . ' ';
            if ($mission['required_combatant_count'] > 0)
                echo 'Combatants: ' . $mission['required_combatants_completed'] . '/' . $mission['required_combatant_count'] . ' ';
            if ($mission['required_item_count'] > 0)
                echo 'Items: ' . $mission['required_items_completed'] . '/' . $mission['required_item_count'] . ' ';
            if ($mission['required_thing_count'] > 0)
                echo 'Things: ' . $mission['required_things_completed'] . '/' . $mission['required_thing_count'] . ' ';
            if ($mission['credit_reward'] || $mission['experience_reward'])

            ?>
                        </div>
				<div class="clear"></div>
			</div>
                <?php } ?>

                <!-- end of missions -->
		</div>
        <?php } ?>

        <?php if (!isset($p_event) && !isset($p_combatant) && $hasTrader == 0) { ?>
            <div class="exploreSectionEmpty section" id="section2"></div>
        <?php } ?>

        <?php if (isset($p_event)) { ?>
            <div class="box4">
			<h2 class="exploreTitle"><?=$p_event->event->name?></h2>
			<img class="event"
				src="<?=base_url()?>_images/data/events/<?=$p_event->event->id?>.png"
				alt="" width="240" height="180" />
			<div class="eventInfo">
				<p class="exploreSubTitle"><?=$p_event->event->name?></p>
				<p><?=$p_event->event->description?></p>
                    <?php if ($p_event->event->damage) { ?>
                        <p>
					<strong>Damage: <span class="redText">-<?=$p_event->event->damage?></span></strong>
				</p>
                    <?php } ?>
                    <form id="explore"
					action="<?=base_url()?>action/confirm_event" method="post"
					autocomplete="off">

					<input type="hidden" name="player_event_id"
						value="<?=$p_event->player_place->player_id; ?>" /> <input
						type="submit" id="exploreBtn" class="eventExplore blue"
						value="Ok, keep exploring" />
				</form>
			</div>
			<div class="clear"></div>
		</div>
        <?php } ?>


        <?php if (isset($p_combatant)) { ?>
            <div class="box4">
			<h2 class="exploreTitle"><?php echo $p_combatant->combatant->name; ?></h2>
                <?php if ($p_combatant->health > 0 && ($player->stamina == 0 or $player->health == 0)) { ?>
                    <div class="defeated">
				<h1 class="loseText">YOU LOSE!</h1>
			</div>
                            <?php } ?>

                            <?php if ($p_combatant->health == 0) { ?>
                                <div class="defeated">
				<h1 class="defeatedText">DEFEATED!</h1>
			</div>
                                        <?php } ?>

                                        <img
				src="<?php echo base_url(); ?>_images/data/combatants/<?php echo $p_combatant->combatant->id; ?>.png"
				alt="" class="combatant" width="240" height="180" />
			<div class="fightSectionStart">
                                            <?php if (!$player->isFighting($p_combatant)) { ?>
                                                <p><?=$p_combatant->combatant->description?></p>
				<div>
                                                    <?php if ($p_combatant->health && $player->stamina > 0 && $player->health > 0) { ?>
                                                    <div
						class="fightBox">
						<form id="fight" action="<?php echo base_url(); ?>action/fight"
							method="post" autocomplete="off">
							<input type="hidden" name="player_combatant_id"
								value="<?=$p_combatant->id?>" /> <input id="fightBtn"
								type="submit" class="fightExplore blue" value="Fight" />
						</form>
					</div>
                                                    <?php } ?>
                                                    <?php if ($p_combatant->health > 0) { ?>
                                                        <form id="flee"
						method="post" autocomplete="off">
						<input type="hidden" name="flee_combatant_id"
							value="<?=$p_combatant->id?>" /> <input id="fleeBtn"
							type="submit" class="fleeExplore red" value="Flee!" />
					</form>
                                                    <?php } ?>
                                                    <?php if ($p_combatant->health == 0 && !sizeof($p_combatant->getDrop())) { ?>
                                                        <form
						action="<?php echo base_url(); ?>action/confirm_combatant"
						method="post" autocomplete="off">

						<input type="hidden" name="player_combatant_id"
							value="<?=$p_combatant->id?>" /> <input type="submit"
							class="button blue" value="OK" />
					</form>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <div
					class="fightSection">
					<div id="fightStatTitle">Fight Stats:</div>

					<table class="fightStatsTitles">
						<tr>
							<td>NPC damage</td>
						</tr>
						<tr>
							<td>Health Lost</td>
						</tr>
						<tr>
							<td>Stamina used</td>
						</tr>
						<tr>
							<td>XP gained</td>
						</tr>
						<tr>
							<td>Coin gained</td>
						</tr>
					</table>

					<div id="fightStatsBorder">
						<table>
							<tr>
								<td>
                                                                    <?php
                if ($p_combatant->health == $p_combatant->combatant->health) {
                    echo '---';
                } else {
                    echo '<span id="npcDamage" class="redText">' . ($p_combatant->combatant->health - $p_combatant->health) . '</span>';
                }
                ?>
                                                                </td>
							</tr>
							<tr>
								<td>---</td>
							</tr>
							<tr>
								<td>---</td>
							</tr>
							<tr>
								<td>
                                                                    <?php
                if ($p_combatant->health == 0 && $p_combatant->combatant->experience_reward > 0) {
                    echo '<span class="greenText">' . $p_combatant->combatant->experience_reward . '</span>';
                } else {
                    echo '---';
                }
                ?>
                                                                </td>
							</tr>
							<tr>
								<td>
                                                                    <?php
                if ($p_combatant->health == 0 && $p_combatant->combatant->credit_reward > 0) {
                    echo '<span class="greenText">' . $p_combatant->combatant->credit_reward . '</span>';
                } else {
                    echo '---';
                }
                ?>
                                                                </td>
							</tr>
						</table>
					</div>
				</div>
                                                <?php if ($p_combatant->health == 0) { ?>
                                                    <p
					class="endBattleText">You defeated the <?=$p_combatant->combatant->name?>!</p>
                                                    <?php if (!sizeof($p_combatant->getDrop())) { ?>
                                                        <p
					class="endBattleText">There are no items to collect. It is time to
					move on.</p>
                                                    <?php } else { ?>
                                                        <p
					class="endBattleText">Select items you want to pick up.</p>
                                                    <?php } ?>

                                                    <div id="itemSelect"
					<?php if (!sizeof($p_combatant->getDrop())) { ?>
					style="height: 87px;" <?php } ?>>
                                                        <?php $inventory_full = $player->isInventoryFull()?>
                                                        <?php foreach($p_combatant->getDrop() as $p_item) {?>
                                                        <div
						class="reward">
						<form class="collectReward" method="post">
							<input type="hidden"
								name="player_combatant_<?=$p_item->getObj()->getClassification()?>_id"
								value="<?php echo $p_item->id; ?>" /> <input <?php if($inventory_full && $p_item instanceof PlayerCombatantItem){?>data-disabled-reason="inventory_full" <?php }?>class="reward<?php if($p_item instanceof PlayerCombatantItem && $inventory_full){?> opacity<?php }?>" type="submit" value="Keep" style="background-image:url('<?php echo base_url(); ?>_images/data/<?=$p_item->getObj()->getClassification()?>s/<?php

echo $p_item->getObj()->id;
                        ?><?php


if ($p_item instanceof PlayerCombatantItem && $p_item->item->has_quality > 0) {
                            echo '-' . $p_item->quality;
                        }
                        ?>s.png')" data-<?=$p_item->getObj()->getClassification()?>-id="<?=$p_item->getObj()->id?>" data-quality="<?php if ($p_item instanceof PlayerCombatantItem && $p_item->item->has_quality > 0) { echo $p_item->quality;}?>">
						</form>
                                                            <?php if($p_item instanceof PlayerCombatantThing || !$inventory_full) {?>
                                                            <div
							class="rewardInfoPopup"
							id="<?php

echo $p_item->getObj()->id;
                            ?><?php

                            if ($p_item instanceof PlayerCombatantItem && $p_item->item->has_quality > 0) {
                                echo '-' . $p_item->quality;
                            }
                            ?>s">
							<h3><?php echo $p_item->getObj()->name; ?></h3>
							<p><?php echo $p_item->getObj()->description; ?></p>
            <?php
                            if ($p_item instanceof PlayerCombatantItem && $p_item->item->has_quality) {
                                echo 'Quality: ' . $p_item->quality;
                            }
                            ?>
                                                            </div>
                                                            <?php }?>
                                                        </div>
                                                        <?php }?>


                                                        <div
						class="clear"></div>
				</div>
                                                                <?php if ($p_combatant->health == 0) { ?>
                                                        <form
					action="<?php echo base_url(); ?>action/confirm_combatant"
					method="post">

					<input type="hidden" name="player_combatant_id"
						value="<?=$p_combatant->id?>" /> <input type="submit"
						class="button blue" value="Continue to Explore" />
				</form>
                                                                <?php } ?>

                                                            <?php } else { ?>

                                                    <div
					class="fightSection">
					<div id="timelineTitle">Timeline:</div>
					<div id="timeline">
						<table>
            <?php foreach ($actions as $action) { ?>
                <?php if ($action['health'] > 0) { ?>
                                                                        <tr>
								<td><?=$p_combatant->combatant->name?> hit you for <span
									class="redText"><?php echo $action['health']; ?> damage</span></td>
							</tr>
                <?php } ?>

                                                                <?php if ($action['damage'] > 0) { ?>
                                                                        <tr>
								<td>You hit <?=$p_combatant->combatant->name?> for <?php echo $action['damage']; ?> damage</td>
							</tr>
                                                                <?php } ?>
            <?php } ?>
                                                            </table>
					</div>

					<div class="fightOptions">
                                                            <?php if ($p_combatant->health > 0 && $player->stamina > 0 && $player->health > 0) { ?>
                                                            <div
							class="fightBox">
							<form id="fight" action="<?php echo base_url(); ?>action/fight"
								method="post" autocomplete="off">

								<input type="hidden" name="player_combatant_id"
									value="<?=$p_combatant->id?>" /> <input id="fightBtn"
									type="submit" class="fight blue" value="Fight" />
							</form>
						</div>
                                                            <?php } ?>

            <?php if ($p_combatant->health > 0) { ?>
                                                                <form
							id="flee" method="post">
							<input type="hidden" name="flee_combatant_id"
								value="<?=$p_combatant->id?>" /> <input id="fleeBtn"
								type="submit" class="flee red" value="Flee!" />
						</form>
            <?php } ?>

            <?php if ($p_combatant->health == 0 && !sizeof($p_combatant->getDrop())) { ?>
                                                                <form
							action="<?php echo base_url(); ?>action/confirm_combatant"
							method="post">

							<input type="hidden" name="player_combatant_id"
								value="<?=$p_combatant->id?>" /> <input type="submit"
								class="button blue" value="OK" />
						</form>
                                                            <?php } ?>
                                                        </div>
				</div>
                                                        <?php } ?>
                                                    <?php } ?>
                                        </div>

			<div class="clear"></div>
			<div>
				<div class="combatantHealthBar">
					<div class="combatantHealth<?php
            if ($p_combatant->isFullHealth()) {
                echo 'Gray';
            }
            ?>" style="width:<?=$p_combatant->getHealthPercent()?>%;"></div>
					<div class="combatantHealthText">
						<strong><?=$p_combatant->combatant->name?> Health</strong> -
    <?php
            if ($player->isFighting($p_combatant)) {
                echo $p_combatant->health . '/' . $p_combatant->getFullHealth();
            } else {
                echo '?/?';
            }
            ?>
                                                </div>
				</div>
				<div class="bossLevelBar">
					<p class="bossLevelBarText">NPC level:</p>
					<div class="bossLevel">
    <?php for ($i = 0; $i < $p_combatant->combatant->getSkulls(); $i++) { ?>
                                                        <img
							class="skull"
							src="<?php echo base_url(); ?>_images/icons/goldSkull.png"
							height="28" width="28" alt="">
    <?php } ?>
                                                </div>
					<img src="<?php echo base_url(); ?>_images/icoInfo.png" width="18"
						style="float: right; padding: 6px 5px 0px 0px;" alt="">
				</div>
			</div>
			<div class="clear"></div>


		</div>
<?php } ?>

                                                <?php if ($hasTrader == 1) { ?>
                                        <div class="box4">
			<h2 class="exploreTitle"><?php echo $trader['name']; ?></h2>
			<img class="trader"
				src="<?php echo base_url(); ?>_images/data/traders/<?php echo $trader['trader_id']; ?>.png"
				alt="" width="240" height="180" />
			<div class="beggarInfo">
				<span class="beggarTitle">A beggar approaches you</span>
				<p class="beggarSubText"><?php echo $trader['description']; ?></p>
				<p class="cost">
					Cost: <span class="redText">-<?php echo $trader['cost']; ?> coins</span>
				</p>

				<div>
					<p class="bold">Give him <?php echo $trader['cost']; ?> coins?</p>
                                        <?php if ($player->balance >= $trader['cost']) { ?>
                                                        <form id="fight"
						action="<?php echo base_url(); ?>action/accept_trader"
						method="post">

						<input type="hidden" name="player_trader_id"
							value="<?php echo $trader['player_trader_id']; ?>" /> <input
							id="fightBtn" type="submit" class="fightExplore blue" value="Yes" />
					</form>
    <?php } ?>
                                                    <form id="flee"
						action="<?php echo base_url(); ?>action/ignore_trader"
						method="post">
						<input type="hidden" name="player_trader_id"
							value="<?php echo $trader['player_trader_id']; ?>" /> <input
							id="fleeBtn" type="submit" class="fleeExplore red" value="No" />
					</form>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?php } ?>

<?php if (!isset($p_event) && !isset($p_combatant) && $hasTrader == 0) { ?>
                                        <div
			class="exploreSquadSection section" id="section3">
			<div class="squadSectionTitle">Add Squad Abilities</div>
			<div class="squadSectionInfo">
				<div class="squadSectionText">Enlist the help of your squad by
					adding their attributes.</div>
				<div class="squadSectionText">Hover to see their stats.</div>
				<div class="squadSectionText">Only 1 member can be added to the
					fight. After XX mins their attributes will be removed.</div>
			</div>
			<div class="squadMemberBox">
				<div class="squadMemberName">PlayerName</div>
				<div class="squadMemberLevel">Level: 1</div>

				<img src="<?php echo base_url(); ?>_images/playerImage.jpg"
					width="80" alt="">

				<div class="squadMemberInfo">
					<img src="<?php echo base_url(); ?>_images/combat_medic.png"
						width="80" alt="" class="squadMemberIcon">
					<div class="addSquadMember">
						<a href="">add to fight</a>
					</div>
				</div>
			</div>
			<div class="squadMemberBox">
				<div class="squadMemberName">PlayerName</div>
				<div class="squadMemberLevel">Level: 1</div>

				<img src="<?php echo base_url(); ?>_images/playerImage.jpg"
					width="80" alt="">

				<div class="squadMemberInfo">
					<img src="<?php echo base_url(); ?>_images/combat_medic.png"
						width="80" alt="" class="squadMemberIcon">
					<div class="squadMemberBonus">
						+4% Luck<br> 4x Healing
					</div>
					<h2 class="redTimer">00:00:00</h2>
					<a href="" style="color: #952828;">cancel</a>

				</div>
			</div>
			<div class="squadMemberBlank">
				<div class="squadBlankText">
					<h2 class="brownFont">Add A Friend</h2>
					<img src="<?php echo base_url(); ?>_images/addBtnLight.png"
						style="margin-left: 26px;" alt="">
				</div>
			</div>
		</div>
                                    <?php } ?>
                                    <div class="clear"></div>
	</div>
<script>
    $(document).ready(function(){
        $(document).on('click', '#rankDisplay',function() {
            alert('Go to profile to change rank.');
        });
    });
</script>
<body onload="init()">
    <div id="container">
        <?php if ($page_title == 'Intro') { ?>
            <div id="content" style="padding-left:0px">
            <?php } else { ?>
                <div id="content">
                <?php } ?>
                    <?php if($player->getGameInfo(false)) { ?>
						<div id="popLevel" class="popUp">
							<div class="closeBtn closeLevelPos" onclick="javascript:toggle('popLevel')">
							</div>
							<img src='<?php echo base_url(); ?>_images/levelUp.jpg' alt='level up image'>
							<h2>Level <?=$player->level_id?></h2>
							<p><?=$player->getGameInfo()?></p>
							<form id="levelUp" action="javascript:toggle('popLevel')" method="post">
								<div>
									<input id="congratBtn" class="blue" type="submit" value="OK" />
								</div>
							</form>
						</div>
                    <?php } ?>
                    <?php if(isset($game_info)) { ?>
						<div id="game_info"><?=$game_info?></div>
                    <?php } ?>
                    <?php if($player->getGameError(false)) { ?>
						<div id="game_error"><?=$player->getGameError()?></div>
                    <?php } ?>
                    <?php if(isset($game_error)) {?>
						<div id="game_error"><?=$game_error?></div>
                    <?php }?>
                <?php echo $content; ?>
                <div class="clear"></div>
            </div>

            <div id="account-menu">
                <div style="float:left;vertical-align:middle;">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>_images/link-home.png" alt="Home" title="Main page" style="float:left;" /></a>
                    <?php if (isset($player)) { ?>
                        <a href="<?php echo base_url(); ?>account"><img src="<?php echo base_url(); ?>_images/link-settings.png" alt="Settings" title="Account settings" style="float:left;margin-left:5px;" /></a>
                    <?php } ?>
                </div>
                <div style="float:right;">
                    <?php if (!isset($player)) { ?>
                        <a style="color:orange;" href="<?php echo base_url(); ?>login">Log In</a>
                    <?php } else { ?>
                        <strong><?=$player->rank->label?></strong>
                        <strong><a href="<?php echo base_url(); ?>account"><?php echo $player->account->first_name . '  ' . $player->account->last_name; ?> (<?=$player->account->username?>)</a></strong> |
                        <a style="color:orange;" href="<?php echo base_url(); ?>login/logout" id="logout">Logout</a>
                    <?php } ?>
                </div>
                <?php if (isset($player)) { ?>
					<div id="rankDisplay" class="statsRank">
						<img src="<?php echo base_url(); ?>_images/data/ranks/<?=$player->rank->id?>t.png" height="20" width="20" alt="rank">
						<div class="stat_msg rank_msg">
							<p><span>Private:</span> +<?=$player->rank->attack?> Attack</p>
						</div>
					</div>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div id="stats">
                <?php if (isset($player)) { ?>
                    <!--
                        sq10 | CI:B0106 | 1/2

                        Rework the stats header to use the new layout.
                    -->
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <div class='td-container'>
                                        <div class="stat-icon-float si-coins">
											<div class="stat_msg comCur_msg">
												<p>Common currency used for basic purchases</p>
											</div>
										</div>
                                        <div class="stat-container sc-coins with-border">
                                            <div class="stat-format">
                                                <div class="stat-count">
                                                    $<span class='amt' id="statBalance"><?=$player->balance?></span>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class="add-button">
                                            <img src="<?php echo base_url();?>_images/icoAdd.png" alt="Add" onclick="window.location = '<?php echo base_url(); ?>store'">
                                        </div>
                                        <div class="stat-icon-float si-challenge-coins">
											<div class='stat_msg prem_msg'>
												<p>Premium currency</p>
											</div>
										</div>
                                        <div class="stat-container sc-challenge-coins with-border">
                                            <div class="stat-format">
                                                <div class="stat-count">
                                                    <span class='amt' id='statPremiumBalance'><?=$player->premium_balance?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class='stat-info-container with-border'>
                                            <div class="stat-bar-container" id="statEnergyBarContainer" title="energy">
                                                <div class="stat-bar-meter sbm-energy" id="statEnergyBar" style="width: <?=round(100*$player->energy/$player->energy_limit)?>%"></div>
                                                <div class="stat-bar-end sbe-energy" id="statEnergyBarEnd">
                                                    <img src="/_images/bars/yellow_end.png" alt>
                                                </div>
                                            </div>
                                            <div class="stat-box">
                                                <div class="stat-timer-container" id="statEnergyTimer">
                                                    <span class="timer-minutes"></span>:<span class="timer-seconds"></span>
                                                </div>
                                                <div class="stat-number-container">
                                                    <span class="sb-current" id="statEnergy"><?php echo $player->energy;?></span><!--
                                                 --><span class="sb-div">/</span><!--
                                                 --><span class="sb-limit" id="statEnergyLimit"><?=$player->energy_limit;?></span>
                                                </div>
                                            </div>
                                            <div class="stat-container">
                                                <div class="stat-format">
                                                    <div class="stat-format-title">
                                                        ENERGY
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-icon-absolute si-energy">
											<div class="stat_msg energy_msg">
												<p>Energy is used for exploration</p>
											</div>
										</div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class='stat-info-container with-border'>
                                            <div class="stat-bar-container" id="statStaminaBarContainer" title="stamina">
                                                <div class="stat-bar-meter sbm-stamina" id="statStaminaBar" style="width: <?=round(100*$player->stamina/$player->stamina_limit)?>%"></div>
                                                <div class="stat-bar-end sbe-stamina" id="statStaminaBarEnd">
                                                    <img src="/_images/bars/green_end.png" alt>
                                                </div>
                                            </div>
                                            <div class="stat-box">
                                                <div class="stat-timer-container" id="statStaminaTimer">
                                                    <span class="timer-minutes"></span>:<span class="timer-seconds"></span>
                                                </div>
                                                <div class="stat-number-container">
                                                    <span class="sb-current" id="statStamina"><?=$player->stamina?></span><!--
                                                 --><span class="sb-div">/</span><!--
                                                 --><span class="sb-limit" id="statStaminaLimit"><?=$player->stamina_limit?></span>
                                                </div>
                                            </div>
                                            <div class="stat-container">
                                                <div class="stat-format">
                                                    <div class="stat-format-title">
                                                        STAMINA
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-icon-absolute si-stamina">
											<div class="stat_msg stam_msg">
												<p>Stamina is used to fight enemies</p>
											</div>
										</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class='td-container'>
                                        <div class="stat-boosts">
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <img class="add-button-boosts" src="<?php echo base_url();?>_images/icoInfo.png" alt="Add">
											<div class="boost_msg">
												<p><span>Coming soon:</span> Boosts allowing you to deal more damage to your enemies and regenerate faster</p>
											</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class="add-button">
                                            <img src="<?php echo base_url();?>_images/icoAdd.png" onclick="window.location = '<?php echo base_url(); ?>team'" alt="Add">
                                        </div>
                                        <div class="stat-icon-float si-rank">
											<div class='stat_msg team_msg'>
												<p>Team count</p>
											</div>
										</div>
                                        <div class="sc-rank stat-container with-border">
                                            <div class="stat-format">
                                                <div class="stat-count"><?php echo count($player->get_team_players());?></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class='stat-info-container with-border'>
                                            <div class="stat-bar-container" id="statHealthBarContainer" title="health">
                                                <div class="stat-bar-meter sbm-health" id="statHealthBar" style="width: <?=round(100*$player->health/$player->health_limit)?>%"></div>
                                                <div class="stat-bar-end" id="statHealthBarEnd">
                                                    <img src="/_images/bars/red_end.png" alt>
                                                </div>
                                            </div>
                                            <div class="stat-box">
                                                <div class="stat-timer-container" id="statHealthTimer">
                                                    <span class="timer-minutes"></span>:<span class="timer-seconds"></span>
                                                </div>
                                                <div class="stat-number-container">
                                                    <span class="sb-current" id="statHealth"><?=$player->health?></span><!--
                                                 --><span class="sb-div">/</span><!--
                                                 --><span class="sb-limit" id="statHealthLimit"><?=$player->health_limit?></span>
                                                </div>
                                            </div>
                                            <div class="stat-container">
                                                <div class="stat-format">
                                                    <div class="stat-format-title">HEALTH</div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-icon-absolute si-health"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class='td-container'>
                                        <div class='stat-info-container with-border'>
                                            <div class="stat-bar-container" id="statLevelBarContainer" title="experience">
                                                <div class="stat-bar-meter sbm-level" id="statLevelBar" style="width: <?=round(100*($player->experience - $player->getCurrentLevelXP())/($player->getNextLevelXP() - $player->getCurrentLevelXP()))?>%"></div>
                                                <div class="stat-bar-end" id="statLevelBarEnd">
                                                    <img src="/_images/bars/blue_end.png" alt>
                                                </div>
                                            </div>
                                            <div class="stat-box">
												<div class="stat-timer-container" id="statLevelPoints">
													<span class="sb-current" id="statLevelCurrent"><?=($player->experience - $player->getCurrentLevelXP());?></span><!--
													--><span class="sb-div">/</span><!--
													--><span class="sb-limit" id="statLevelLimit"><?=($player->getNextLevelXP() - $player->getCurrentLevelXP()); ?></span>
                                                </div>
                                                <div class="stat-number-container">
                                                    <span class="sb-current" id="statLevel">
                                                        <?=round(100*($player->experience - $player->getCurrentLevelXP())/($player->getNextLevelXP() - $player->getCurrentLevelXP()))?>%
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="stat-container">
                                                <div class="stat-format">
                                                    <div class="stat-format-title">
                                                        LEVEL
                                                        <span class='stat-format-level' id="statExperienceLevel">
                                                            <?=$player->level_id?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-icon-absolute si-level">
											<div class="stat_msg exp_msg">
												<p><span>Experience: </span><?=($player->experience - $player->getCurrentLevelXP());?> / <?=($player->getNextLevelXP() - $player->getCurrentLevelXP()); ?></p>
											</div>
										</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- taken out for use later on
                    <div class="friendsBarSection">
                            <div class="friendsBarTitle">FRIENDS:</div>
                            <div class="friendsBarContainer"></div>
                    </div>
                    -->
                <?php } ?>
            </div>
            <div id="main-menu">

                <div id="logo" style="z-index:201">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>_images/coinimpactLogo.png" alt="logo" width="220" /></a>
                </div>

                <div style="margin-right:10px" id="navigation">
                    <ul>
                        <?php if (isset($player)) { ?>
                            <?php if ($player->p_place->place->id == Place::$ids['base']) { ?>
                                <li><a href="<?php echo base_url(); ?>base">BASE</a></li>
                            <?php } else { ?>
                                <li><a href="javascript:void(0)" onclick="javascript:popupNotAtBase()" id="base-link">BASE</a></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>map">MAP</a></li>
                            <li><a href="<?php echo base_url(); ?>explore"<?php if($player->isAtBase()) {?> style="color:#9E968C"<?php }?> id="explore_link">EXPLORE</a></li>
                            <li><a href="<?php echo base_url(); ?>bosses">BOSSES</a></li>
                            <li><a href="<?php echo base_url(); ?>profile">PROFILE</a></li>
                            <li><a href="<?php echo base_url(); ?>team">TEAM</a></li>
                            <!--
                            <li><a href="<?php echo base_url(); ?>gift">GIFT</a></li>
                            -->
                            <li><a href="<?php echo base_url(); ?>missions">MISSIONS</a></li>
                            <li><a href="<?php echo base_url(); ?>store">STORE</a></li>
                        <?php } ?>
                    </ul>
                    <div id="popBase_haveEnergy" class="popUp">
                        <div class="closeBtn closeBasePos" onclick="javascript:toggle('popBase_haveEnergy')">
                        </div>
                        <p>You are not at Base, would you like to travel there for <?=$base_place->energy?> energy.</p>
                        <form action="/action/travel" method="post">
                            <input type="hidden" name="place_id" value="<?=$base_place->id?>">
                            <input type="submit" class="left blue" value="Yes">
                            <input type="button" class="right red" value="No" onclick="javascript:toggle('popBase_haveEnergy')">
                        </form>
                    </div>
                    <div id="popBase_noEnergy" class="popUp">
                        <div class="closeBtn closeBasePos" onclick="javascript:toggle('popBase_noEnergy')">
                        </div>
                        <p>You don't have enough energy to travel to the Base</p>
                    </div>
                </div>

                <div class="clear"></div>
            </div>

            <div id="submenu">
                <ul>
                    <?php if (isset($player)) { ?>
                        <li><a href="<?php echo base_url(); ?>players">players</a></li>
                        <li><a href="<?php echo base_url(); ?>items">items</a></li>
                    <?php } ?>
                    <?php if ($player->account->is_staff) { ?>
                        <li><a href="<?php echo base_url(); ?>import">import</a></li>
                    <?php } ?>
                </ul>
            </div>
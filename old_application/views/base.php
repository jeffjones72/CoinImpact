<body onload="init()">
    <div id="container">
        <?php if ($page_title == 'Intro') { ?>
            <div id="content" style="padding-left:0px">
            <?php } else { ?>
                <div id="content">
                <?php } ?>
                    <?php if($player->getGameInfo(false)) { ?>
                    <div id="game_info"><?=$player->getGameInfo()?></div>
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
                    <img style="float:right;padding-right:5px" src="<?php echo base_url(); ?>_images/rank.png" height="20" width="20" alt="rank">
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div id="stats">
                <?php if (isset($player)) { ?>
                    <table class="statsTable">
                        <tr>
                            <td>
                                <div class="statIconCoins"></div>
                                <div class="coinsContainer statContainer">
                                    <div class="statFormat">
                                        <div class="statCount">$<?=$player->balance?></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="addBtn"><a href=""><img src="<?php echo base_url(); ?>_images/icoAdd.png" width="18" height="18" alt="Add"></a></div>
                                <div class="statIconChallengeCoins"></div>
                                <div class="challengeCoinsContainer statContainer">
                                    <div class="statFormat">
                                        <div class="statCount"><?=$player->premium_balance?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="barContainer" id="playerEnergyContainer" title="energy">
                                    <div class="playerStatBox">
                                        <span class="statText">/<?=$player->energy_limit; ?></span>
                                        <span id="player-energy" class="statTextCurrent"><?php echo $player->energy; ?></span>
                                    </div>
                                    <div id="playerEnergy" class="EnergyBar statMeter" style="width:<?=round(100*$player->energy/$player->energy_limit)?>%"></div>
                                    <div class="boxEnd" id="playerEnergyEnd" style="background-image:url(/_images/bars/yellow_end.png);width:8px;left:<?=round(100*$player->energy/$player->energy_limit)?>%"></div>
                                </div>
                                <div class="statIconEnergy"></div>
                                <div class="statContainer">
                                    <div class="statFormat">
                                        <div class="statBarTitle">
                                            ENERGY
                                        </div>
                                        <div style="position: absolute;margin-left: 110px;">

                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="barContainer" id="playerStaminaContainer" title="stamina">
                                    <div class="playerStatBox">
                                        <span class="statText">/<?=$player->stamina_limit?></span>
                                        <span id="player-stamina" class="statTextCurrent"><?=$player->stamina?></span>
                                    </div>
                                    <span id="stamina-timer" class="statTimer"></span>
                                    <div id="playerStamina" class="StaminaBar statMeter" style="width:<?=round(100*$player->stamina/$player->stamina_limit)?>%"></div>
                                    <div class="boxEnd" id="playerStaminaEnd" style="background-image:url(/_images/bars/green_end.png);width:10px;left:<?=round(100*$player->stamina/$player->stamina_limit)?>%"></div>
                                </div>
                                <div class="statIconStamina"></div>
                                <div class="statContainer">
                                    <div class="statFormat">
                                        <div class="statBarTitle">
                                            STAMINA
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="statBoosts">
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <a href=""><img class="addBtnBoosts" src="<?php echo base_url(); ?>_images/icoAdd.png" width="18" height="18" alt="Add"></a>
                                </div>
                            </td>
                            <td>
                                <div class="addBtn"><a href=""><img src="<?php echo base_url(); ?>_images/icoAdd.png" width="18" height="18" alt="Add"></a></div>
                                <div class="statIconRank"></div>
                                <div class="rankContainer statContainer">
                                    <div class="statFormat">
                                        <div class="statCount">0</div>
                                    </div>
                                </div>
                            </td>
                            <td >
                                <div class="barContainer" id="playerHealthContainer" title="health">
                                    <div class="playerStatBox">
                                        <span class="statText">/<?=$player->health_limit?></span>
                                        <span id="player-health" class="statTextCurrent"><?=$player->health?></span>
                                    </div>
                                    <span id="health-timer" class="statTimer"></span>
                                    <div id="playerHealth" class="HealthBar statMeter" style="width:<?=round(100*$player->health/$player->health_limit)?>%"></div>
                                    <div class="boxEnd" id="playerHealthEnd" style="height:24px;background-image:url(/_images/bars/red_end.png);width:7px;left:<?=round(100*$player->health/$player->health_limit)?>%"></div>
                                </div>
                                <div class="statIconHealth"></div>
                                <div class="statContainer">
                                    <div class="statFormat">
                                        <div class="statBarTitle">HEALTH</div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="barContainer">
                                    <span id="exp" class="statText"><?=round(100*($player->experience - $player->getCurrentLevelXP())/($player->getNextLevelXP() - $player->getCurrentLevelXP()))?>%</span>
                                    <!--
                                    <span class="statText">/{{request.player.next_level_xp}}</span>
                                    <span id="player-level" class="statTextCurrent">{{request.player.experience}}</span>
                                    -->
                                    <div id="playerLevel" class="LevelBar statMeter" style="width:<?=round(100*($player->experience - $player->getCurrentLevelXP())/($player->getNextLevelXP() - $player->getCurrentLevelXP()))?>%"></div>
                                    <div class="boxEnd" style="background-image:url(/_images/bars/blue_end.png);width:8px;left:<?=round(100*($player->experience - $player->getCurrentLevelXP())/($player->getNextLevelXP() - $player->getCurrentLevelXP()))?>%"></div>
                                </div>
                                <div class="statIconLevel"></div>
                                <div class="statContainer">
                                    <div class="statFormat">
                                        <div class="statBarTitle">LEVEL <?=$player->level_id?></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
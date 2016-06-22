<script type="text/javascript">
    var search_bosses_end_time = [<?php for ($i = 0; $i < sizeof($search_bosses) - 1;  ++$i) { ?><?= $search_bosses[$i]->getEndTime() ?>,<?php } ?><?php if (sizeof($search_bosses)) { ?><?= $search_bosses[sizeof($search_bosses) - 1]->getEndTime() ?><?php } ?>];
    var active_bosses_end_time = [<?php for ($i = 0; $i < sizeof($active_bosses) - 1;  ++$i) { ?><?= $active_bosses[$i]->getEndTime() ?>,<?php } ?><?php if (sizeof($active_bosses)) { ?><?= $active_bosses[sizeof($active_bosses) - 1]->getEndTime() ?><?php } ?>];
    var locate_bosses_resp_time = [<?php for ($i = 0; $i < sizeof($respawn_times) - 1;  ++$i) { ?><?= $respawn_times[$i] ?>,<?php } ?><?php if (sizeof($respawn_times)) { ?><?= $respawn_times[sizeof($respawn_times) - 1] ?><?php } ?>];
    <?php if ($current_target) { ?>
            var current_boss_end_time = <?= $current_target->getEndTime() ?>;
    <?php } ?>
        setInterval(function() {
            var types = ['search_bosses_end_time', 'active_bosses_end_time', 'locate_bosses_resp_time'];
            for (i in types) {
                for (j = 0; j < window[types[i]].length; j++) {
                    if ((window[types[i]][j] - (new Date().getTime()) / 1000) > 0) {
                        $("#" + types[i] + j).html(format((window[types[i]][j] - (new Date().getTime()) / 1000)));
                    } else if ($("#" + types[i] + j).length) {
                        $("#" + types[i] + j).html('');
                    }
                }
            }
    <?php if ($current_target) { ?>
                if (current_boss_end_time - (new Date().getTime()) / 1000 > 0) {
                    $("#current_boss_timer").html(format(current_boss_end_time - (new Date().getTime()) / 1000));
                } else {
                    $("#current_boss_timer").html('');
                }
    <?php } ?>
        }, 1000);
        /*$(document).ready(function() {
         $('.locateBossBtn').click(function(e){
         if(!confirm('Are you sure you want to challenge this boss?')) {
         e.preventDefault();
         }
         });
         $('.engageActiveBoss').click(function(e){
         if(!confirm('This will cause this boss to become your current target. Are you sure?')) {
         e.preventDefault();
         }
         });
         });*/
$(document).ready(function() {
    $('[data-collect-id]').click(function(e){
        e.stopPropagation();
        $('[data-modal-id='+$(this).attr('data-collect-id')+']').show();
    });
    $('[data-drop-id]').click(function(e){
        e.stopPropagation();
        $('[data-item-modal]').hide();
        $('[data-item-modal="'+$(this).attr('data-drop-id')+','+$(this).attr('data-type')+'"]').show();
    });
    $('[data-score-id]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('[data-score-modal-id="'+$(this).attr('data-score-id')+'"]').show();
    });
    $('.cancelDiscard').click(function(e){
        e.stopPropagation();
        $('[data-discard-modal="'+$(this).attr('data-cancel-discard-id')+
                ','+$(this).attr('data-cancel-discard-type')+'"]').hide();
    });
    $('.discardBtn').click(function(e){
        e.stopPropagation();
        $('[data-discard-modal="'+$(this).attr('data-discard-id')+
                ','+$(this).attr('data-discard-type')+'"]').show();
    });
    $('[data-stamina-btn]').click(function(e){
        if($(this).hasClass('disabledBossAttackBtn')) {
            e.preventDefault();
            e.stopPropagation();
            if(Stats.getHealth() < 10) {
                $('#not_enough_health').css('display', 'block');
            } else {
                $('#not_enough_stamina').css('display', 'block');
            }
        }
    });
});
setInterval(function(){
    if(Stats.getHealth() >= 10) {
        $('[data-stamina-btn]').each(function(){
            if(Stats.getStamina() >= $(this).attr('data-stamina-btn')) {
                $(this).removeClass('disabledBossAttackBtn');
                $(this).addClass('attackBoxGreen');
                $(this).addClass('attackBtn');
            }
        });
    }
}, 1000);
</script>
<div class="modal" style="display: none" id="not_enough_health">You do
    not have enough health to fight. Do you want to purchase health for
    coins?</div>
<div class="modal" style="display: none" id="not_enough_stamina">You do
    not have enough stamina to perform this action. Do you want to fully
    refill your stamina for 5 premium coins?</div>
<?php
foreach ($collect as $p_b_combatant) {
    $awards = $p_b_combatant->getAwards();
   
    ?>

<div data-modal-id="<?=$p_b_combatant->player_boss->id?>" class="modal"
    style="display: none"> 
        <?php if(sizeof($awards)) { ?>
        Boss dropped:<br>
        <?php } else { ?>
        No items.
        <?php } ?>
    <?php
    
foreach ($awards as $award) {
        $item = null;
        if ($award->getClassification() == 'item') {
            $item = $award->item;
        } else {
            $item = $award->thing;
        }
        ?>
        <div class="reward">
        <div class="collectBossAward" style="background-image:url('/_images/data/<?=$award->getClassification()?>s/<?=$item->id?>.png')" data-drop-id="<?=$award->id?>" data-type="<?=$award->getClassification()?>"></div>
        <div class="inventoryStats rarity<?=$item->rarity_id?>"
            style="margin: -35px 0 0 100px"
            data-item-modal="<?=$award->id?>,<?=$award->getClassification()?>"
            style="display: none;">

            <h1>
                <span><?=ucfirst($award->getClassification())?></span><?=$item->name?></h1>
            <p><?=$item->description?></p>

            <table class="info">
                <tbody>
                    <tr>
                                <?php
        
if ($award->getClassification() == 'item') {
            foreach (Item::$stat_fields as $field) {
                if ($field == 'capacity') {
                    continue;
                }
                $val = call_user_func(array(
                    $award,
                    'get' . ucfirst($field)
                ));
                $icon = $field;
                if ($icon == 'strike') {
                    $icon = 'critical';
                }
                if ($val) {
                    ?>
                                <th><img alt="<?=$field?> icon"
                            src="/_images/icons/<?=$icon?>_large.png"></th>
                                        <?php
                
}
            }
        }
        ?>
                            </tr>
                    <tr>    
                                <?php
        
if ($award->getClassification() == 'item') {
            foreach (Item::$stat_fields as $field) {
                if ($field == 'capacity') {
                    continue;
                }
                $val = call_user_func(array(
                    $award,
                    'get' . ucfirst($field)
                ));
                if ($val) {
                    ?>
                                <th><?=$val?></th>
                                        <?php
                
}
            }
        }
        ?>
                            </tr>
                </tbody>
            </table>
            <div class="clear"></div>

            <div class="inventoryOptions">
                <form method="post">
                    <div>
                        <input type="hidden" value="<?=$award->id?>"
                            name="collect_item_id"> <input type="submit" value="Collect"
                            class="blue">
                    </div>
                </form>

                <div class="discardWrapper">
                    <button class="cancel red discardBtn"
                        data-discard-id="<?=$award->id?>"
                        data-discard-type="<?=$award->getClassification()?>">Discard</button>
                    <div
                        data-discard-modal="<?=$award->id?>,<?=$award->getClassification()?>"
                        class="modal">
                        Are you sure you want to DISCARD this item?<br>
                        <form method="post" style="width: 50px">
                            <input type="hidden" name="discard_id" value="<?=$award->id?>"> <input
                                type="hidden" name="discard_type"
                                value="<?=$award->getClassification()?>">
                            <button class="cancel red">Yes</button>
                        </form>
                        <button class="blue cancelDiscard"
                            data-cancel-discard-id="<?=$award->id?>"
                            data-cancel-discard-type="<?=$award->getClassification()?>">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    </div>
<?php } ?>
<div class="box" style="height: 850px;">

    <div class="tanSectionBtn" style="margin-right: 12px;"
        onclick="javascript:showonlyone('collect')">COLLECT</div>
    <div class="yellowSectionBtn"
        onclick="javascript:showonlyone('current-target')">CURRENT TARGET</div>
    <div class="blueSectionBtn" onclick="javascript:showonlyone('active')">
        ACTIVE</div>
    <div class="brownSectionBtn"
        onclick="javascript:showonlyone('search');">SEARCH</div>
    <div class="orangeSectionBtn"
        onclick="javascript:showonlyone('locate');">LOCATE</div>

    <div class="bossLocateBox section" id="locate">
        <div class="bossTopBar">
            <h2 class="bossTitle">Locate Bosses</h2>
        </div>
        <form class="bossFilterMenu">
            <div class="bossLocateFilter">
                Boss type: <select>
                    <option value="">Human</option>
                    <option value="">Creature</option>
                    <option value="">Vehicle</option>
                </select>
            </div>
            <div class="bossLocateFilter">
                Locating cost: <select>
                    <option value="">Low cost only</option>
                    <option value="">High cost only</option>
                </select>
            </div>
            <div class="bossLocateFilter">
                Filter by difficulty: <select>
                    <option value="">Show only level 1</option>
                    <option value="">Only levels 1 & 2</option>
                    <option value="">Show only level 2</option>
                    <option value="">Only levels 2 & 3</option>
                    <option value="">Show only level 3</option>
                    <option value="">Only levels 3 & 4</option>
                    <option value="">Show only level 4</option>
                    <option value="">Only levels 4 & 5</option>
                    <option value="">Show only level 5</option>
                </select>
            </div>
            <div class="bossLocateFilter">
                Sort By: <select>
                    <option value="">Cost - ascending</option>
                    <option value="">Cost - descending</option>
                </select>
            </div>
            <div>
                <input type="submit" class="searchButton" value="Search" />
            </div>
        </form>
            <?php if (sizeof($locate_bosses)) { ?>
                <div style="margin: 100px 0px 0px 10px;">
            <h2 class="bossTitle"><?= sizeof($locate_bosses) ?> Boss<?php if (sizeof($locate_bosses) != 1) { ?>es<?php } ?> Have Been Located:</h2>
        </div>
        <div>
                    <?php
                
for ($i = 0; $i < sizeof($locate_bosses); ++ $i) {
                    $boss = $locate_bosses[$i];
                    ?>
                        <div class="bossActiveSlot">
                <div class="bossActiveImageSlot">
                    <img src="/_images/data/bosses/<?php echo $boss->id; ?>.png"
                        width="400" alt="" />
                </div>
                <div class="bossInfoBox">
                    <h2 class="bossTitle"><?php echo $boss->name; ?></h2>
                                <?php if($boss->timeout) {?>
                                <p class="bossInfo">
                        Time: <span class="redText"><?= format($boss->timeout) ?></span>
                    </p>
                                <?php }else if($boss->timeout === NULL){?>
                                <p class="bossInfo">
                        Time: <span class="redText">Unlimited</span>
                    </p>
                                <?php }?>
                                <?php if($locate_bosses[$i]->id == Boss::$ids['outlaw_guards']) {?>
                                    <?php if($player->hasBossActive($locate_bosses[$i])) {?>
                                <p class="bossInfo">
                        You can't summon/locate this boss because <span class="redText">you
                            already have him summoned</span>.
                    </p>
                                    <?php }?>
                                <?php } else {?>
                                    <?php if ($respawn_times[$i] > time()) { ?>
                                <p class="bossInfo">
                        You can summon this boss in: <span class="redText"
                            id="locate_bosses_resp_time<?= $i ?>"><?= format($respawn_times[$i]) ?></span>
                    </p>
                                    <?php } ?>
                                <?php } ?>
                                <p class="bossInfo">0/<?= $boss->max_players ?><span
                            style="float: right;"><a href="">more info</a></span>
                    </p>
                    <p class="bossInfo">Cost to locate: <?= $boss->cost ?></p>
                </div>
                <div class="bossActiveSlotBar">
                    <div class="bossActiveHealthBar">
                        <div class="combatantHealth" style="width: 100%"></div>
                                    <?php if($boss->health) {?>
                                    <div class="combatantHealthText">
                            <strong>Health</strong> - <?=$boss->health?></div>
                                    <?php } else if($boss->health === NULL) {?>
                                    <div class="combatantHealthText">
                            <strong>Health</strong> - Unlimited
                        </div>
                                    <?php } ?>
                                </div>
                    <div>
                        <div class="bossLevel">
                                        <?php for ($j = 0; $j < $boss->getSkulls(); ++$j) { ?>
                                            <img class="skull"
                                src="/_images/icons/goldSkull.png" height="28" width="28" alt="">
                                        <?php } ?>
                                        <?php for($j = $boss->getSkulls(); $j < Boss::$max_skulls; ++$j) { ?>
                                            <img class="skull"
                                src="/_images/icons/graySkull.png" height="28" width="28" alt="">
                                        <?php } ?>
                                    </div>
                        <img class="bossInfoBtn" src="/_images/icoInfo.png" width="18"
                            alt="">
                    </div>
            <?php if ($player->canSummon($boss)) { ?>
                                    <form action="" method="post">
                        <div>
                            <input type="hidden" name="locate_id"
                                value="<?php echo $boss->id; ?>"> <input type="submit"
                                class="bossButton locateBossBtn" value="Locate">
                        </div>
                    </form> 
            <?php } ?>
                            </div>
                <div class="clear"></div>
            </div>
                <?php } ?>
                </div>
    <?php } else { ?>
                <div style="margin: 100px 0px 0px 10px;">
            <h2 class="bossTitle">No bosses have been found</h2>
        </div>
    <?php } ?>
        </div>

    <div class="bossSearchBox section" id="search">
        <div class="bossTopBar">
            <h2 class="bossTitle">Search Bosses</h2>
        </div>
        <form class="bossFilterMenu">
            <div class="bossLocateFilter">
                Search by Boss name: <input type="text" name="BossName">
            </div>
            <div class="bossLocateFilter">
                Boss difficulty level: <select>
                    <option value="">Show only level 1</option>
                    <option value="">Only levels 1 & 2</option>
                    <option value="">Show only level 2</option>
                    <option value="">Only levels 2 & 3</option>
                    <option value="">Show only level 3</option>
                    <option value="">Only levels 3 & 4</option>
                    <option value="">Show only level 4</option>
                    <option value="">Only levels 4 & 5</option>
                    <option value="">Show only level 5</option>
                </select>
            </div>
            <div class="bossLocateFilter">
                Boss Size: <select>
                    <option value="">Less than 500 players</option>
                </select>
            </div>
            <div class="bossLocateFilter">
                Sort By: <select>
                    <option value="">Ending soonest</option>
                </select>
            </div>
            <div>
                <input type="submit" class="searchButton" value="Search" />
            </div>
        </form>
        <div style="margin: 100px 0px 0px 10px;">
            <h2 class="bossTitle">Your Search Yielded <?= sizeof($search_bosses) ?> Boss<?php if (sizeof($search_bosses) != 1) { ?>es<?php } ?>:</h2>
        </div>
            <?php
            for ($i = 0; $i < sizeof($search_bosses); ++ $i) {
                $player_boss = $search_bosses[$i];
                ?>
                <div class="bossActiveSlot">
            <div class="bossActiveImageSlot">
                <img
                    src="/_images/data/bosses/<?php echo $player_boss->boss->id; ?>.png"
                    width="400" alt="" />
            </div>
            <div class="bossInfoBox">
                <p class="bossInfo"><?= $player_boss->player->account->username ?>'s</p>
                <h2 class="bossTitle"><?php echo $player_boss->boss->name; ?></h2>
                <p class="bossInfo">
                    Time: <span class="redText" id="search_bosses_end_time<?= $i ?>"></span>
                </p>
                <p class="bossInfo">Players: <?= $player_boss->getCombatantsCount() ?>/<?= $player_boss->boss->max_players ?><span
                        style="float: right;"><a href="">more info</a></span>
                </p>
            </div>
            <div class="bossActiveSlotBar">
                <div class="bossActiveHealthBar">
                    <div class="combatantHealth" style="width:<?= $player_boss->getHealthPercent() ?>%"></div>
                    <div class="combatantHealthText">
                        <strong>Health</strong> - 
                                <?php if($player_boss->boss->health === null) { ?>
                                Unlimited
                                <?php }else{?>
                                <?php echo $player_boss->boss->health . '/' . $player_boss->boss->health; ?>
                                <?php } ?>
                            </div>
                </div>
                <div>
                    <div class="bossLevel">
                                <?php for ($j = 0; $j < $player_boss->boss->getSkulls();  ++$j) { ?>
                                    <img class="skull"
                            src="/_images/icons/goldSkull.png" height="28" width="28" alt="">
                                <?php } ?>
                                <?php for($j = $player_boss->boss->getSkulls(); $j < Boss::$max_skulls; ++$j) { ?>
                                    <img class="skull"
                            src="/_images/icons/graySkull.png" height="28" width="28" alt="">
                                <?php } ?>
                            </div>
                    <img class="bossInfoBtn" src="/_images/icoInfo.png" width="18"
                        alt="">
                </div>

        <?php if ($player->canEngage($player_boss)) { ?>
                            <form action="/engage-boss" method="post">
                    <div>
                        <input type="hidden" name="id"
                            value="<?php echo $player_boss->id; ?>" /> <input type="submit"
                            class="bossButton" value="Engage" />
                    </div>
                </form>
        <?php } ?>  
                    </div>
            <div class="clear"></div>
        </div>
    <?php } ?>
        </div>

    <div class="bossActiveBox section" id="active">
        <form class="filterMenu">
            <div class="filterCenter">
                Sort By: <select>
                    <option value="">Damage (ascending)</option>
                    <option value="">Damage (descending)</option>
                </select>
            </div>
        </form>
            <?php
            for ($i = 0; $i < sizeof($active_bosses); ++ $i) {
                $player_boss = $active_bosses[$i];
                $boss = $player_boss->boss;
                ?>
                <div class="bossActiveSlot">
            <div class="bossActiveImageSlot">
                <img src="/_images/data/bosses/<?php echo $boss->id; ?>.png"
                    width="400" alt="" />
            </div>
            <div class="bossInfoBox">
                <p class="bossInfo"><?= $player_boss->player->account->username ?>'s</p>
                <h2 class="bossTitle"><?php echo $boss->name; ?></h2>
                <p class="bossInfo">
                    Time: <span class="redText" id="active_bosses_end_time<?= $i ?>"></span>
                </p>
                <p class="bossInfo">Players: <?= $player_boss->getCombatantsCount() ?>/<?= $boss->max_players ?><span
                        style="float: right;"><a href="">more info</a></span>
                </p>
                <!--
                        <p>{{boss.description}}</p>
                        Health: {{boss.health}}

                        {% if not boss.engageable %}
                                {% if boss.required_places.all %}
                                        <p>Required places:
                                        {% for place in boss.required_places.all %}
                                                {{place.name}}
                                        {% endfor %}
                                        </p>
                                {% endif %}
                                {% if boss.required_items.all %}
                                        <p>Required items:
                                        {% for item in boss.required_items.all %}
                                                {{item.name}}
                                        {% endfor %}
                                        </p>
                                {% endif %}
                                {% if boss.required_missions.all %}
                                        <p>Required missions:
                                        {% for mission in boss.required_missions.all %}
                                                {{mission.name}}
                                        {% endfor %}
                                        </p>
                                {% endif %}
                        {% endif %}
                        -->
            </div>
            <div class="bossActiveSlotBar">
                <div class="bossActiveHealthBar">
                    <div class="combatantHealth" style="width:<?= $player_boss->getHealthPercent() ?>%"></div>
                    <div class="combatantHealthText">
                        <strong>Health</strong> - 
                                <?php if($player_boss->boss->health === null) { ?>
                                Unlimited
                                <?php }else{?>
                                <?php echo $player_boss->health . '/' . $boss->health; ?>
                                <?php } ?>
                            </div>
                </div>
                <div>
                    <div class="bossLevel">
        <?php for ($j = 0; $j < $boss->getSkulls();  ++$j) { ?>
                                    <img class="skull"
                            src="/_images/icons/goldSkull.png" height="28" width="28" alt="">
        <?php } ?>
                            <?php for($j = $boss->getSkulls(); $j < Boss::$max_skulls; ++$j) { ?>
                                <img class="skull"
                            src="/_images/icons/graySkull.png" height="28" width="28" alt="">
                            <?php } ?>
                            </div>
                    <img class="bossInfoBtn" src="/_images/icoInfo.png" width="18"
                        alt="">
                </div>
                <form method="post">
                    <div>
                        <input type="hidden" name="active_id"
                            value="<?php echo $player_boss->id; ?>" /> <input type="submit"
                            class="bossButton engageActiveBoss" value="Engage" />
                    </div>
                </form>
                <div class="share_box">
                    Share link: <input value='<?=$player_boss->getShareLink()?>'>
                    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
                        width="110" height="14" id="clippy">
                        <param name="movie" value="/_flash/clippy.swf" />
                        <param name="allowScriptAccess" value="always" />
                        <param name="quality" value="high" />
                        <param name="scale" value="noscale" />
                        <param NAME="FlashVars"
                            value="text=<?=$player_boss->getShareLink()?>">
                        <embed src="/_flash/clippy.swf" width="110" height="14"
                            name="clippy" quality="high" allowScriptAccess="always"
                            type="application/x-shockwave-flash"
                            pluginspage="http://www.macromedia.com/go/getflashplayer"
                            FlashVars="text=<?=urlencode($player_boss->getShareLink())?>" />
                    </object>
                </div>
            </div>
            <div class="clear"></div>
        </div>
            <?php } ?>
        </div>

    <div class="bossCurrentBox section" id="current-target">
    <?php if ($current_target) { ?>
                <div class="bossTopBarTarget">
            <a href="" style="float: left; margin-right: 7px;"><?= $current_target->player->account->username ?>'s</a>
            <h2 class="bossTitle"><?= $current_target->boss->name ?></h2>
            <div class="bossTopLevelBar">
                <div class="bossLevel">
        <?php for ($i = 0; $i < $current_target->boss->getSkulls();  ++$i) { ?>
                                <img class="skull"
                        src="/_images/icons/goldSkull.png" height="28" width="28" alt="">
        <?php } ?>
                        <?php for($j = $current_target->boss->getSkulls(); $j < Boss::$max_skulls; ++$j) { ?>
                            <img class="skull"
                        src="/_images/icons/graySkull.png" height="28" width="28" alt="">
                        <?php } ?>
                        </div>
                <img class="bossInfoBtn" src="/_images/icoInfo.png" width="18"
                    alt="">
            </div>
        </div>
        <div>
            <div class="currentBoosts">
                <div class="currentBoostsTitles">Boosts:</div>
                <div class="statBoosts" style="float: right;">
                    <ul>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>
            <div class="bossCurrentInfo">
                <div class="bossCurrentPlayers">
                    <div class="bossPlayerCount">
        Players: <?= $current_target->getCombatantsCount() ?>/<?= $current_target->boss->max_players?>
                            </div>
                    <div>
                        <a href="">Invite players</a>
                    </div>
                </div>
                <?php if($player->canAttack() && !$current_target->isDead()) {?>
                        <div class="bossCurrentAttack">
                    <div class="currentBoostsTitles">Attack:</div>
                    <form method="post">
                        <input type="hidden" name="attack" value="1">
                                <?php if($player->stamina >= 1) {?>
                                <button class="attackBoxGreen attackBtn"
                            data-stamina-btn="1">1</button>
                                <?php } else { ?>
                                <button class="disabledBossAttackBtn"
                            data-stamina-btn="1">1</button>
                                <?php } ?>
                            </form>
                    <form method="post">
                        <input type="hidden" name="attack" value="5">
                                <?php if($player->stamina >= 5) {?>
                                <button class="attackBoxGreen attackBtn"
                            data-stamina-btn="5">5</button>
                                <?php } else { ?>
                                <button class="disabledBossAttackBtn"
                            data-stamina-btn="5">5</button>
                                <?php } ?>
                            </form>
                    <form method="post">
                        <input type="hidden" name="attack" value="20">
                                <?php if($player->stamina >= 20) {?>
                                <button class="attackBoxGreen attackBtn"
                            data-stamina-btn="20">20</button>
                                <?php } else { ?>
                                <button class="disabledBossAttackBtn"
                            data-stamina-btn="20">20</button>
                                <?php } ?>
                            </form>
                </div>
                <?php } else if(!$player->canAttack()){?>
                        <div class="bossCurrentAttack">
                    <div class="currentBoostsTitles">Attack:</div>
                    <form method="post">
                        <input type="hidden" name="attack" value="1">
                        <button class="disabledBossAttackBtn" data-stamina-btn="1">1</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="attack" value="5">
                        <button class="disabledBossAttackBtn" data-stamina-btn="5">5</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="attack" value="20">
                        <button class="disabledBossAttackBtn" data-stamina-btn="20">20</button>
                    </form>
                </div>
                <?php } ?>
                        <div class="bossCurrentTime">
                    <div class="currentBoostsTitles">
                        Time: <span class="redText" id="current_boss_timer"></span>
                    </div>
                </div>
                <div class="bossCurrentItems">
                    <a href="">Possible Items</a>
                </div>
            </div>
            <img src="/_images/data/bosses/<?=$current_target->boss->id?>l.jpg"
                width="675" alt="" />
        </div>
        <div class="bossCurrentHealthBar">
            <div class="combatantHealth" style="width:<?= $current_target->getHealthPercent() ?>%"></div>
            <div class="combatantHealthText">
                <strong>Health</strong> - 
                        <?php if($player_boss->boss->health === null) { ?>
                        Unlimited
                        <?php }else{?>
                        <?= $current_target->health ?>/<?= $current_target->boss->health?>
                        <?php } ?>
                   </div>
        </div>
        <div class="bossCombatSection">
            <h2 class="page">Combat:</h2>
            <div class="bossCombatInfo">
                <!--
                    sq10 | CI:B0104 | 2/5
                    Modify the combat log to use the new format:

                    $player dealt $damage_to_boss damage and took $damage_to_player damage, gaining $xp_awarded XP.
                -->
                <?php foreach($combat_log as $info) {?>
                    <div class='logItem'>
                        <span class='playerName'><?=$info['player_name']?></span>
                        dealt
                        <span class='damageDealt'><span class='num'><?=$info['damage_to_boss']?></span> damage</span>
                        and took
                        <span class='damageTaken'><span class='num'><?=$info['damage_to_player']?></span> damage</span>,
                        gaining
                        <span class='xpAwarded'><span class='num'><?=$info['xp_awarded']?></span>XP</span>.
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="bossSquadBoostSection">
            <h2 class="page">Squad Boosts:</h2>
            <div class="bossSquadBoostInfo">
                <div class="squadMemberBox">
                    <div class="squadMemberName">PlayerName</div>
                    <div class="squadMemberLevel">Level: 1</div>

                    <img src="/_images/playerImage.jpg" width="80" alt="">

                    <div class="squadMemberInfo">
                        <img src="/_images/combat_medic.png" width="80" alt=""
                            class="squadMemberIcon">
                        <div class="addSquadMember">
                            <a href="">add to fight</a>
                        </div>
                    </div>
                </div>
                <div class="squadMemberBox">
                    <div class="squadMemberName">PlayerName</div>
                    <div class="squadMemberLevel">Level: 1</div>

                    <img src="/_images/playerImage.jpg" width="80" alt="">

                    <div class="squadMemberInfo">
                        <img src="/_images/combat_medic.png" width="80" alt=""
                            class="squadMemberIcon">
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
                        <img src="/_images/addBtnLight.png" style="margin-left: 26px;"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
                <p>You are not in combat with any boss right now</p>
    <?php } ?>
        </div>

    <div class="bossCollectBox section" id="collect">
        <div style="margin: 13px 0px 0px 10px;">
            <h2 class="bossTitle">Collect From Bosses</h2>
        </div>
    <?php foreach ($collect as $p_b_combatant) { ?>
                <div class="bossActiveSlot">
            <div class="bossActiveImageSlot" style="background: url(/_images/data/bosses/<?=$p_b_combatant->player_boss->boss->id?>.png)">
                <div class="red_overlay defeated_text">Defeated!</div>
            </div>
            <div class="bossInfoBox">
                <p class="bossInfo"><?=$p_b_combatant->player_boss->player->account->username?>'s</p>
                <h2 class="bossTitle"><?=$p_b_combatant->player_boss->boss->name?></h2>
                <p class="bossInfo">You have killed this boss <?=$player->getKillCountFor($p_b_combatant->player_boss->boss)?> time(s).
                    <span style="float: right">
                        Close this boss
                    </span>
                </p>
                <p class="bossInfo">Players: <?=$p_b_combatant->player_boss->getCombatantsCount()?>/<?=$p_b_combatant->player_boss->boss->max_players?>
                <span   style="float: right;">
                        
                        <a href="">more info</a></span>
                </p>
                <p class="bossInfo">
                    <a href="#" data-score-id="<?=$p_b_combatant->player_boss->id?>">View
                        Score</a>
                </p>
                <div class="modal"
                    data-score-modal-id="<?=$p_b_combatant->player_boss->id?>">
                        <?php foreach($p_b_combatant->player_boss->getTopPlayers() as $key => $data) {?>
                        <?=$key+1?>. <?php if($data['player']->id == $player->id){?><span
                        style="color: red"><?=$data['player']->account->username?></span><?php }else{?><?=$data['player']->account->username?><?php }?> - <?=$data['damage']?><br>
                        <?php } ?>
                        <?php
        
$player_place = $p_b_combatant->player_boss->getPlaceFor($player);
        if ($player_place > PlayerBoss::SCORE_LIST_NUM) {
            ?>
                        -----------------------------<br>
                        <?=$player_place?>. You.
                        <?=$player_place?>
                        <?php }?>
                        </div>
            </div>
            <div class="bossActiveSlotBar">
                <div class="bossStats">
                    <div style="width: 70px; float: left;">
                        <h2 class="bossTitle">Your Stats:</h2>
                    </div>
                    <div class="statSpeed">
                        Speed: <span class="redText"><?=$p_b_combatant->player_boss->getSpeed()?></span>
                    </div>
                    <div class="statDamage">
                        Damage: <span class="redText"><?=$p_b_combatant->damage?></span> /
                        <span class="redText"><?=$p_b_combatant->getPercentDMGDone()?>%</span>
                    </div>
                </div>
                <div>
                    <div class="bossLevel">
                            <?php for ($i = 0; $i < $p_b_combatant->player_boss->boss->getSkulls();  ++$i) { ?>
                                <img class="skull"
                            src="/_images/icons/goldSkull.png" height="28" width="28" alt="">
                            <?php } ?>
                            <?php for($j = $p_b_combatant->player_boss->boss->getSkulls(); $j < Boss::$max_skulls; ++$j) { ?>
                                <img class="skull"
                            src="/_images/icons/graySkull.png" height="28" width="28" alt="">
                            <?php } ?>
                            </div>
                    <img class="bossInfoBtn" src="/_images/icoInfo.png" width="18"
                        alt="">
                </div>
                <button class="collectButton"
                    data-collect-id="<?=$p_b_combatant->player_boss->id?>">Collect</button>
            </div>
            <div class="clear"></div>
        </div>
    <?php } ?>
    </div>
</div>
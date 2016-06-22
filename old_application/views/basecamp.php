<script type="text/javascript">
$(document).ready(function() {
    $('#cancel_drop').click(function() {
        $('#drop_modal').hide();
    });
    $('[data-drop-id]').click(function(e) {
        e.preventDefault();
        e.stopPropagation()
        $('#drop_id').val($(this).attr('data-drop-id'));
        $('#drop_modal').show();
    });
});
</script>
<div id="drop_modal" class="modal">
    Are you sure you want to DROP this item?<br>
    <form method="post" style="width:50px;float:left">
        <input type="hidden" name="drop_id" id="drop_id">
        <button class="cancel red">
            Yes
        </button>
    </form>
    <button class="blue cancelDiscard" id="cancel_drop">No</button>
</div>
<div class="box" style="height: 300px;">
    <img class="hideIcon" onclick="javascript:hide('location');" src="{{SITE_URL}}/@@/camera.png" alt="" height="25" width="25"/>
    <div class="locationTitleTab" onclick="javascript:show('location');">Base Camp Info</div>
    <!--
    <img style="position: absolute;margin-top: 25px;" src="{{MEDIA_URL}}/places/base_camp_01.jpg" alt="" width="700" height="276" />
    -->
    <img style="position: absolute;margin-top: 25px;" src="<?php echo base_url(); ?>_images/base_camp_01.jpg" alt="" width="700" height="276" />
    <div id="location" class="baseLocation">
        <p class="bold">basecamp info</p>
    </div>
</div>
    <div class="brownSectionBtn" onclick="javascript:showonlyone('storage')">
        STORAGE
    </div>
    <div class="yellowSectionBtn" onclick="javascript:showonlyone('nomad-shop')">
        NOMAD SHOP
    </div>
    <div class="blueSectionBtn" onclick="javascript:showonlyone('inventory')">
        INVENTORY
    </div>
    <div class="tanSectionBtn" onclick="javascript:showonlyone('missions');">
        MISSIONS
    </div>
    <div class="greenSectionBtn" onclick="javascript:showonlyone('training');">
        TRAINING
    </div>
<div style="display:block">
    <div class="baseMission section" id="missions">
        <h2 class="baseTitles">There are 4 missions available today</h2>

        {% for mission in missions %}
        {% if mission.progress = 100 and not mission.completed %}
        <div class="">
            <h2>Mission Completed</h2>
            <p>Congratulations, you completed {{mission.mission.name}}.</p>
            <form action="<?php echo base_url();?>comfirm-mission-completion" method="post">
                <div>
                    <input type="hidden" name="id" value="{{mission.id}}" />
                    <input class="button" type="submit" value="OK" />
                </div>
            </form>
        </div>
        {% endif %}
        {% endfor %}

        <div class="">
        <?php /*echo "<pre>"; var_dump($this->missions);*/?>
            {% for mission in missions %}
            {% if not mission.completed %}
            <div style="background-color:white;padding:10px;margin-bottom:10px;">
                {% else %}
                <div style="background-color:#ccc;padding:10px;margin-bottom:10px;">
                    {% endif %}
                    <img src="{{MEDIA_URL}}/missions/{{mission.mission.id}}s.png" alt="" style="float:right;margin: 0 0 10px 10px;"/>
                    <h1>{{mission.mission.name}}</h1>
                    <p style="height:4em;">{{mission.mission.description}}</p>
                    <!--{% if not mission.started %}
                            <form action="{{SITE_URL}}/accept-mission" method="post">
                                    {% csrf_token %}
                                    <div>
                                            <input type="hidden" name="id" value="{{mission.id}}" />
                                            <input type="submit" class="button" value="Accept" />
                                    </div>
                            </form>
                    {% else %}
                            <form action="{{SITE_URL}}/quit-mission" method="post">
                                    {% csrf_token %}
                                    <div>
                                            <input type="hidden" name="id" value="{{mission.id}}" />
                                            <input type="submit" class="button" value="Quit" />
                                    </div>
                            </form>
                    {% endif %}-->
                    <div class="progress-box">{{mission.progress}}%</div>
                    <div>
                        {% if mission.required_items %}
                        Items: {{mission.found_items}}/{{mission.required_items}}
                        {% endif %}
                        {% if mission.required_things %}
                        Things: {{mission.found_things}}/{{mission.required_things}}
                        {% endif %}
                        {% if mission.required_combatants %}
                        Combatants: {{mission.found_combatants}}/{{mission.required_combatants}}
                        {% endif %}
                        {% if mission.required_events %}
                        Events: {{mission.found_events}}/{{mission.required_events}}
                        {% endif %}
                        {% if mission.mission.credit_reward or mission.mission.experience_reward %}
                        Rewards:
                        {% if mission.mission.credit_reward %}
                        {{mission.mission.credit_reward}} coins
                        {% endif %}
                        {% if mission.mission.experience_reward %}
                        {{mission.mission.experience_reward}} experience
                        {% endif %}
                        {% endif %}
                    </div>
                    <div class="clear"></div>
                </div>
                {% endfor %}
            </div>
        </div>          
    </div>
    <div class="baseNomad section" id="nomad-shop">
        <h2 class="baseTitles">Nomad shop has 9 items today</h2>
    </div>	

    <div class="baseStorage section" id="storage">
        <h2 class="baseTitles">Storage: <?=$player->getStoredItemsCount()?>/<?=$player->getStorageCap()?></h2>
    </div>			

    <div class="baseInventory section" id="inventory">			
        <h2 class="baseTitles">Inventory: <?=$player->getInventoryItemsCount()?>/<?=$player->getInventoryCapacity()?></h2>
        <?php foreach($inventory_items as $p_item) {?>
            <div class="large-slot">
                <span class="largeTitle"><?=$p_item->item->name?></span>
                <img src="/_images/data/items/<?=$p_item->item->id?><?php if($p_item->item->has_quality){?>-<?=$p_item->quality?><?php }?>.png" height="92" width="165" alt="" />
                <table class="info">
                    <tr>
                        <?php $item = $p_item->item;?>
                        <?php if($item->attack){?><th>ATT</th><?php } ?>
                        <?php if($item->defense){?><th>DEF</th><?php }?>
                        <?php if($item->energy){?><th>EN</th><?php }?>
                        <?php if($item->stamina){?><th>STAM</th><?php }?>
                        <?php if($item->health){?><th>H</th><?php }?>
                        <?php if($item->strike){?><th>ST</th><?php }?>
                        <?php if($item->dodge){?><th>D</th><?php }?>
                        <?php if($item->luck){?><th>luck</th><?php }?>
                        <?php if($item->has_quality){?><th>quality</th><?php }?>	
                    </tr>
                    <tr>
                        <?php if($item->attack){?><th><?=$item->attack?></th><?php } ?>
                        <?php if($item->defense){?><th><?=$item->defense?></th><?php }?>
                        <?php if($item->energy){?><th><?=$item->energy?></th><?php }?>
                        <?php if($item->stamina){?><th><?=$item->stamina?></th><?php }?>
                        <?php if($item->health){?><th><?=$item->health?></th><?php }?>
                        <?php if($item->strike){?><th><?=$item->strike?></th><?php }?>
                        <?php if($item->dodge){?><th><?=$item->dodge?></th><?php }?>
                        <?php if($item->luck){?><th><?=$item->luck?></th><?php }?>
                        <?php if($item->has_quality){?><th><?=$p_item->quality?></th><?php }?>
                    </tr>
                </table>
                <form method="post" style="float:left;">
                    <div>
                        <input type="hidden" name="enable_id" value="<?=$p_item->id?>" />
                        <input type="submit" class="button blue" value="Enable" />
                    </div>
                </form>
                <form method="post" style="float:left;">
                    <div>
                        <input data-drop-id="<?=$p_item->id?>" type="submit" class="cancel-button red" value="Drop" style="margin-left:10px;" />	
                    </div>
                </form>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>

    <!--
        Training dialog.
    -->
    <div class="baseTraining section" id="training">
        <script src='<?php echo base_url(); ?>_scripts/Training.js'></script>
        <h2 class="baseTitles">Training</h2>
        <table>
            <tbody>
                <tr>
                    <td class='tableCell'>
                        <div class="trainingBackground">
                            <img class="bg" src="<?php echo base_url(); ?>_images/base_camp_training.jpg" alt>
                            <div class='attack'>
                                <div class='txt'>
                                    Attack: 
                                </div>
                                <div class='attackBtn greenBtn'>
                                    20
                                </div>
                                <div class='attackBtn greenBtn'>
                                    5
                                </div>
                                <div class='attackBtn greenBtn'>
                                    1
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class='tableCell trainLogCell'>
                        <div class='trainHead'>
                            Attack log
                        </div>
                        <div class='trainLog'>
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>        
    </div>
</div>
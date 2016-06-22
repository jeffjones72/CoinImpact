<script type="text/javascript">
    $(document).ready(function() {
        // Inventory tab functionality
        $('#cancel_drop').click(function() {
            $('#drop_modal').hide();
        });
        $('[data-drop-id]').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#drop_id').val($(this).attr('data-drop-id'));
            $('#drop_modal').show();
        });
        
        //Sell item
        $('#cancel_sell').click(function() {
            $('#sell_modal').hide();
        });
        $(document).on('click', '.sell_item',function(e) {
            items = parseInt($("#inventory-item-count").html()) - 1;
            setItems();
            id = $(this).attr('id');
            amt = parseInt($(this).attr('val'));
            sell_item(player_id,id);
            
            // Remove the item from the dom in inventory and storage tab.
            $("#item-" + id).remove();
            $("#item-storage-" + id).remove();
            
            Stats.addBalance(amt);
            //$('#sell_modal').show();
            
            setButtons();
        });
        $(document).on('click', '.store_item',function(e){
            items = parseInt($("#inventory-item-count").html()) - 1;
            stored = parseInt($("#storage-item-count").html()) + 1;
            setItems();
            $("#storage-item-count").html(stored);
            id = parseInt($(this).attr('storage-id'));
            $(this).removeClass();
            $(this).addClass('retrieve_item');
            $(this).html('Retrieve');
            
            store_item(player_id,id);
            $("#item-" + id).hide();
            $("#item-storage-" + id).appendTo('#storedItems');
            
            setButtons();
            
        });
        $(document).on('click', '.retrieve_item',function(e){
            items = parseInt($("#inventory-item-count").html()) + 1;
            stored = parseInt($("#storage-item-count").html()) - 1;
            setItems();
            $("#storage-item-count").html(stored);
            id = $(this).attr('storage-id');
            $(this).removeClass();
            $(this).addClass('store_item');
            $(this).html('Store');
            
            retrieve_item(player_id,id);
            $("#item-" + id).show();
            $("#item-storage-" + id).appendTo('#unstoredItems');
            
            setButtons();
        });
/*        
        function fnOpenSellItem(){
            $("#sell_item_modal").html("Confirm Dialog Box");
        
            // Define the Dialog and its properties.
            $("#dialog-confirm").dialog({
                resizable: false,
                modal: true,
                title: "Modal",
                height: 250,
                width: 400,
                buttons: {
                    "Yes": function () {
                        $(this).dialog('close');
                        callback(true);
                    },
                        "No": function () {
                        $(this).dialog('close');
                        callback(false);
                    }
                }
            });
        }

$('#btnOpenDialog').click(fnOpenNormalDialog);
function callback(value) {
    if (value) {
        alert("Confirmed");
    } else {
        alert("Rejected");
    }
}
*/
        
        //Sell thing
        $('#cancel_sell_thing').click(function() {
            $('#sell_modal').hide();
        });
        $('.sell_thing').click(function(e) {
            things = parseInt($("#inventory-thing-count").html()) - 1;
            $("#inventory-thing-count").html(things);
            id = $(this).attr('id');
            amt = parseInt($(this).attr('val'));
            sell_thing(player_id,id);
            $("#thing-" + id).hide();
            Stats.addBalance(amt);0
        });
        
        setItems();
        setButtons();

    });
    
    // Show intro on first visit to the base
    seen_intro = <?=$player->base_intro?>;
    player_id = <?=$player->id?>;
    
    // Counter variable
    item_cap = <?=$player->getInventoryCapacity()?>;
    items = <?=$player->getInventoryItemsCount()?>;
    storage_cap = <?=$player->getStorageCap()?>;
    stored = <?=$player->getStoredItemsCount()?>;
    
    function setItems(){
        $('#inventory-item-count').html(items);
    }
    
    function setButtons(){
        if (items == item_cap){
            $('.retrieve_item').hide();
        } else {
            $('.retrieve_item').show();
        }
        if (stored == storage_cap){
            $('.store_item').hide();
        } else {
            $('.store_item').show();
        }
    }
    
    if(seen_intro == 0){
        showintro('base-intro');
        seen_base_intro(player_id);
    }
    
</script>

<!-- Modal boxes for inventory tab. -->
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
<div id="sell_modal" class="modal">
    Are you sure you want to SELL this item?<br>
    <form method="post" style="width:50px;float:left">
        <input type="hidden" name="sell_id" id="sell_id">
        <button class="cancel red">
            Yes
        </button>
    </form>
    <button class="blue cancelDiscard" id="cancel_sell">No</button>
</div>

<div class="box" style="height: 300px;">
    <img class="hideIcon" onclick="javascript:hide('location');" src="<?php echo base_url(); ?>_images/camera.png" alt="" height="25" width="25"/>
    <div class="locationTitleTab" onclick="javascript:show('location');">Base Camp Info (Level: <?php echo $player->base->level; ?> Boost: <?php echo $player->base->boost * 100; ?>%)</div>
    <!--
    <img style="position: absolute;margin-top: 25px;" src="{{MEDIA_URL}}/places/base_camp_01.jpg" alt="" width="700" height="276" />
    -->
    <img style="position: absolute;margin-top: 25px;" src="<?php echo base_url(); ?>_images/base_camp_01.jpg" alt="" width="700" height="276" />
    <div id="location" class="baseLocation">
        <p class="bold">basecamp info</p>
    </div>
</div>

    <!-- Navigation tabs, display in revers order on scren -->
    <div class="greenSectionBtn" onclick="javascript:showonlyone('training');">
        TRAINING
    </div>
    <div class="greenSectionBtn" onclick="javascript:showonlyone('level');">
        BASE LEVEL
    </div>
    <div class="tanSectionBtn" onclick="javascript:showonlyone('workshop');">
        WORKSHOP
    </div>
    <div class="brownSectionBtn" onclick="javascript:showonlyone('storage')">
        STORAGE
    </div>
    <div class="blueSectionBtn" onclick="javascript:showonlyone('inventory')">
        INVENTORY
    </div>
	<div class="orangeSectionBtn" onclick="javascript:showonlyone('base-intro');">
        TIPS
    </div>
    <!--
    <div class="tanSectionBtn" onclick="javascript:showonlyone('missions');">
        MISSIONS
    </div>
    -->
    <!--
    <div class="yellowSectionBtn" onclick="javascript:showonlyone('nomad-shop')">
        NOMAD SHOP
    </div>
    -->
    
    
<div style="display:block">	

    <div class="baseInventory section" id="inventory">			
        <h2 class="baseTitles">Inventory: 
        Items: <span id="inventory-item-count"><?=$player->getInventoryItemsCount()?></span>/<?=$player->getInventoryCapacity()?> / 
        Things: <span id="inventory-thing-count"><?php echo sizeof($inventory_things); $item_num = -1; ?></span></h2>
        <?php foreach($inventory_items as $p_item) { $item_num++; ?>
            <div class="large-slot" id="item-<?=$p_item->id?>"<?php if($item_num % 4 == 0){echo ' style="clear:right;"';} ?>>
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
                        <?php if($item->luck){?><th>LUCK</th><?php }?>
                        <?php if($item->has_quality){?><th>QUAL</th><?php }?>	
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
                    <div id="<?=$p_item->id?>" val="<?=$p_item->value?>" class="sell_item">
                        Sell for: <?=$p_item->value?> coins	
                    </div>
            </div>
        <?php } ?>
        <div style="clear: both;"></div>
        <?php foreach($inventory_things as $p_thing) { ?>
            <div class="large-slot" id="thing-<?=$p_thing->player_thing_id?>">
                <span class="largeTitle"><div <?php if($p_thing->partial_item_id > 0) { echo 'class="collectionThing" title="Collection Item"';} ?>><?=$p_thing->name?></div></span>
                <img src="/_images/data/things/<?=$p_thing->id?>.png" height="92" width="165" alt="" />
                    <div id="<?=$p_thing->player_thing_id?>" val="<?=$p_thing->value?>" class="sell_thing">
                        Sell for: <?=$p_thing->value?> coins	
                    </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>

    <div class="baseStorage section" id="storage">
        <h2 class="baseTitles">Storage: <span id="storage-item-count"><?=$player->getStoredItemsCount()?></span>/<?=$player->getStorageCap()?></h2>
        <div id="storedItems">
        <?php foreach($stored_items as $p_item) { ?>
            <div class="large-slot" id="item-storage-<?=$p_item->id?>">
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
                        <?php if($item->luck){?><th>LUCK</th><?php }?>
                        <?php if($item->has_quality){?><th>QUAL</th><?php }?>	
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
                    <div storage-id="<?=$p_item->id?>" class="retrieve_item">
                        Retrieve
                    </div>
            </div>
        <?php } ?>
        </div>        
        <div class="clear"></div>
        <div id="unstoredItems">
        <?php foreach($inventory_items as $p_item) { ?>
            <div class="large-slot" id="item-storage-<?=$p_item->id?>">
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
                        <?php if($item->luck){?><th>LUCK</th><?php }?>
                        <?php if($item->has_quality){?><th>QUAL</th><?php }?>	
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
                    <div storage-id="<?=$p_item->id?>" class="store_item">
                        Store
                    </div>
            </div>
        <?php } ?>
        </div>
        <div class="clear"></div>
    </div>

<!-- Workshop section to deal with collections. -->
    <div class="baseWorkshop section" id="workshop">
        <h2 class="baseTitles">Combine collected items to create other items and things.</h2>
        <?php if (isset($collections)) { ?>
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
                if ($collection['complete'] == 'yes' && ($player->getInventoryItemsCount() < $player->getInventoryCapacity())) {
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
        <div class="clear"></div>
    </div>	

    <!--
        Training dialog.
    -->
    <div class="baseTraining section" id="training">
        <script src='<?php echo base_url(); ?>_scripts/Training.js'></script>
        <h2 class="baseTitles">Training</h2>
		<p class="baseSubtext">Attack the training target to earn experience and prepare for the challenges ahead...</p>
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
    
    
   	<div class="baseIntro section" id="base-intro">
        <h2 class="baseTitles">Introduction:</h2>
		<div class="baseIntroItem">
			<input type="checkbox">
			<p>Head over to the <a href="<?php echo base_url();?>store">store</a>, and buy a satchel with the coins we gave you! This will increase the amount of items you can carry, without using energy to return to base.</p>
			
			<div class="storeSlot storeItem">
                <span class="storeSlotTitle">Satchel</span>
                <img src="<?php echo base_url();?>_images/data/items/5.png" height="85" alt="">
			</div>
		</div>
		<div class="baseIntroItem">
			<input type="checkbox">
			<p>Consider heading over to the <a href="<?php echo base_url();?>store">premium</a> store and buying a item to make your journey much easier. Premium items are purchased using special coins, and we have started you off with 10 of them.</p>
			<img class="baseIntroImage" src="<?php echo base_url();?>_images/data/things/1003.png" onclick="window.location = '<?php echo base_url();?>store'" height="100" alt="">
		</div>
		<div class="baseIntroItem">
			<input type="checkbox">
			<p>The <a href="<?php echo base_url();?>profile">profile</a> page is where you use these items, and future items you find. Make sure you click on the items you find while exploring to collect them!</p>
			<img class="baseIntroImage" src="<?php echo base_url();?>_images/male.jpg" onclick="window.location = '<?php echo base_url();?>profile'" height="150" alt="">
		</div>
		<div class="baseIntroItem">
			<input type="checkbox">
			<p>Travel to the first zone by clicking on <a href="<?php echo base_url();?>map">map</a> and then finding the Outlaw Camp. Or, just click the map below!</p>
			<img class="baseIntroImage" src="<?php echo base_url();?>_images/map_color.jpg" onclick="window.location = '<?php echo base_url();?>map'" height="150" alt="">
		</div>
		<div class="baseIntroItem">
			<input type="checkbox">
			<p>You can attack this <a href="<?php echo base_url();?>base#training" onclick="javascript:showonlyone('training');">Training Dummy</a> to become stronger or use your extra resources. It may be easier to level up, but you will not gain items or coins.</p>
			<img class="baseIntroImage" src="<?php echo base_url();?>_images/base_camp_training.jpg" onclick="javascript:showonlyone('training');" height="200" alt="">
		</div>
		<div class="clear"></div>
    </div>	
    
    
    
    <!--
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
                    {% if not mission.started %}
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
                    {% endif %}
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
    -->
    
    <div class="baseLevel section" id="level">
        <h2 class="baseTitles">Next Level: <?php echo $player->base->level + 1; ?></h2>
        <pre>
        Current Base: <?php 
            print_r($player->base);
            print_r($player->nextBase);
        ?>
        </pre>
    </div>  
    
    
</div>
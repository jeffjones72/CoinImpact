<script>
$(document).ready(function(){
    $('#ww2-buy-item').click(function(){
        toggle('ww_buy_item_modal');
    });
    $('#won_item').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#newItemStats').show();
    })
});
</script>
<?php if(isset($new_item)) {?>
<div class="equipStats rarity<?=$new_item->rarity_id?>" id="newItemStats">
    <h1><span><?=$new_item->getSlotName() ?></span><?php echo $new_item->name; ?></h1>
    <p><?=$new_item->description?></p>
    <img src="<?php echo base_url(); ?>_images/data/items/<?=$new_item->id?>.png" alt="<?=$new_item->name?>" title="<?=$new_item->name?>">
    <div class="statBox">
        <table>
            <?php foreach(Item::$stat_fields as $field_name) {
                if($new_item->{$field_name}){?>
            <tr>
                <th><?=ucfirst($field_name)?></th>
                <td><?=$new_item->{$field_name}?></td>
            </tr>
            <?php 
                }
            }?>
        </table>
    </div>
</div>
<?php } ?>
<div style="height:750px;">
    <div id="ww_buy_item_modal" class="popUp">
        <div class="closeBtn closeStorePos" onclick="javascript:toggle('ww_buy_item_modal')"></div>
        <?php if($player->premium_balance) {?>
        Do you want to spend 1 premium coin in order to get a random item, from the list?
        <form method="post">
            <input type="hidden" name="buy" value="ww2-item">
            <input class="left blue" type="submit" value="Yes">
            <input class="right red" type="button" onclick="javascript:toggle('ww_buy_item_modal')" value="No">
        </form>
        <?php } else { ?>
        You don't have premium coins.
        <?php } ?>
        <div id="purchaseItem"></div>
    </div>
    <div id="popStore" class="popUp" style="height:auto">
        <div class="closeBtn closeStorePos" onclick="javascript:toggle('popStore')"></div>
        <div id="öbjInfo"></div>
    </div>

    <div class="tanSectionBtn storeBtn" onclick="javascript:showonlyone('section3');" style="margin-right:15px;">
        ITEMS
    </div>
    <div class="yellowSectionBtn storeBtn" onclick="javascript:showonlyone('section2');">
        PREMIUM ITEMS
    </div>
    <div class="blueSectionBtn storeBtn" onclick="javascript:showonlyone('section1');">
        WEAPONS CACHE
    </div>

    <div class="storeWeaponCashebox section" id="section1">
        <div class="dealsBox">
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">1 crate for 99 Challenge Coins</h4>
            </div>
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">3 crate for 290 Challenge Coins</h4>
            </div>
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">5 crate for 390 Challenge Coins</h4>
                <p class="infoSubText">Save 22% - *sale ends 1300 Sat.</p>
            </div>
        </div>
        <input class="buyCoinBtn" style="margin-top: 68px;" type="submit" name="submit" value="Buy More">
        <div class="cacheColumn">
            <div class="cacheBox">
                <img id="cacheSelect1" src="<?php echo base_url(); ?>_images/cache_WW2.jpg" width="220" height="124" style="position:absolute;" alt="" />
                <input id="ww2-buy-item" class="storeBuyCache" type="submit" name="submit" value="BUY">
            </div>
            <div class="cacheBox">	
                <img id="cacheSelect2" src="<?php echo base_url(); ?>_images/cache_WW2.jpg" width="220" height="124" style="position:absolute;" alt="" />
                <input class="storeBuyCache" type="submit" name="submit" value="BUY">
                <div class="unselectedCache"></div>
            </div>
            <div class="cacheBox">
                <img id="cacheSelect3" src="<?php echo base_url(); ?>_images/cache_WW2.jpg" width="220" height="124" style="position:absolute;" alt="" />
                <input class="storeBuyCache" type="submit" name="submit" value="BUY">
                <div class="unselectedCache"></div>
            </div>
            <div class="selectedCache">
                <img src="<?php echo base_url(); ?>_images/selectedCache.png" alt="" >
            </div>
        </div>

        <?php foreach ($caches as $cache) { ?>
            <div id="cache<?php echo $cache['id']; ?>" class="cacheWeaponSection">
                <?php if (isset($cache['items'])) { ?>
                    <?php foreach ($cache['items'] as $item) { ?>
                        <img class="cacheItem" src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['id']; ?><?php if ($item['has_quality']) echo '-4'; ?>.png" height="85" alt="" />
                    <?php } ?>
                <?php } ?>

                <?php if (isset($cache['things'])) { ?>
                    <?php foreach ($cache['things'] as $thing) { ?>
                        <img class="cacheItem" src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['id']; ?>.png" height="85" alt="" />
                    <?php } ?>
                <?php } ?>

                <?php if (isset($cache['boosts'])) { ?>
                    <?php foreach ($cache['boosts'] as $boost) { ?>
                        <img class="cacheItem" src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['id']; ?>.png" height="85" alt="" />
                    <?php } ?>
                <?php } ?>

                <?php if (isset($cache['modifiers'])) { ?>
                    <?php foreach ($cache['modifiers'] as $modifier) { ?>
                        <img class="cacheItem" src="<?php echo base_url(); ?>_images/data/modifiers/<?php echo $modifier['id']; ?>.png" height="85" alt="" />
                    <?php } ?>
                <?php } ?>
                <div class="clear"></div>
            </div>

        <?php } ?>
    </div>

    <div class="storePremiumBox section" id="section2">
        <img src="<?php echo base_url(); ?>_images/premiumItemsBg.png" alt="">
        <div class="premiumSalesSection">
            <div class="premiumPortraitSection">
                <h2 class="storeTitle">Premium Items For Sale</h2>
                <img style="padding-top: 14px;" src="<?php echo base_url(); ?>_images/premiumPortrait.png" alt="">
            </div>
            <div class="premiumInfoSection">
                <h2 class="storeTitle">You Have <span class="redText"><?php echo $player->premium_balance; ?></span> Premium Coins</h2>
                <div class="premiumTextArea">
                    <p class="premiumInfoText">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vestibulum feugiat ullamcorper.</p>
                </div>

                <div class="premiumSaleBox">
                    <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                    <h4 class="infoText">+20 Stamina</h4>
                </div>

                <div class="premiumSaleBox">
                    <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                    <h4 class="infoText">Full refill of your Energy</h4>
                </div>

                <div class="premiumSaleBox">
                    <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                    <h4 class="infoText">Full refill of your Health</h4>
                </div>
                <div class="premiumSaleBox">
                    <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                    <h4 class="infoText">Get a 20% bonus for all Premium Coin purchases.</h4>
                    <p class="infoSubText">*sale ends 1300 Sat.</p>
                </div>
                <div class="clear"></div>


                <div class="premiumItemBox">
                    <?php if ($store_items) { ?>
                        <h2 class="storeTitle">Premium Items</h2>
                        <?php foreach ($store_items as $item) { ?>
                            <?php if ($item['premium_price']) { ?>
                                <div class="storeSlot storeItem">
                                    <span class="storeSlotTitle"><?php echo $item['name']; ?></span>
                                    <div class="storePrice">
                                        <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'>
                                        <span>
                                            <?php if ($item['price']) echo $item['price']; ?>
                                            <?php if ($item['premium_price']) echo $item['premium_price']; ?>
                                        </span>
                                    </div>
                                    <div class="storePurchase" onclick="javascript:loadItem('<?php echo $item['id']; ?>')">
                                        <input class="items blue" value="BUY">
                                    </div>
                                    <img src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['id']; ?><?php if ($item['has_quality']) echo '-1'; ?>.png" height="85" alt="">
                                    <!--<?php /*
                                  <table class="info">
                                  <tr>
                                  {% if item.attack %}<th>ATK</th><?php } ?>
                                  {% if item.defense %}<th>DEF</th><?php } ?>
                                  {% if item.energy %}<th>EN</th><?php } ?>
                                  {% if item.stamina %}<th>STA</th><?php } ?>
                                  {% if item.health %}<th>HP</th><?php } ?>
                                  {% if item.strike %}<th>CS</th><?php } ?>
                                  {% if item.dodge %}<th>dodge</th><?php } ?>
                                  {% if item.luck %}<th>luck</th><?php } ?>
                                  {% if item.capacity %}<th>Cap</th><?php } ?>
                                  </tr>
                                  <tr>
                                  {% if item.attack %}<td>{{item.attack}}</td><?php } ?>
                                  {% if item.defense %}<td>{{item.defense}}</td><?php } ?>
                                  {% if item.energy %}<td>{{item.energy}}</td><?php } ?>
                                  {% if item.stamina %}<td>{{item.stamina}}</td><?php } ?>
                                  {% if item.health %}<td>{{item.health}}</td><?php } ?>
                                  {% if item.strike %}<td>{{item.strike}}</td><?php } ?>
                                  {% if item.dodge %}<td>{{item.dodge}}</td><?php } ?>
                                  {% if item.luck %}<td>{{item.luck}}</td><?php } ?>
                                  {% if item.capacity %}<td>{{item.capacity}}</td><?php } ?>
                                  </tr>
                                  </table>
                                 */ ?>-->
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="clear"></div>
                    <?php } ?>


                    <?php if (isset($store_modifiers)) { ?>
                        <?php foreach ($store_modifiers as $modifier) { ?>
                            <?php if ($modifier['premium_price']) { ?>
                                <div class="storeSlot storeModifier">
                                    <span class="storeSlotTitle"><?php echo $modifier['name']; ?></span>
                                    <div class="storePrice">
                                        <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'>
                                        <span>
                                            <?php if ($modifier['price']) echo $modifier['price']; ?>
                                            <?php if ($modifier['premium_price']) echo $modifier['premium_price']; ?>
                                        </span>
                                    </div>
                                    <div class="storePurchase">
                                        <input class="modifiers blue" value="BUY" onclick="javascript:loadModifier('<?php echo $modifier['id']; ?>')">
                                    </div>
                                    <img src="<?php echo base_url(); ?>_images/data/modifiers/<?php echo $modifier['id']; ?><?php if ($item['has_quality']) echo '-1'; ?>.png" height="85" alt="">
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="clear"></div>
                    <?php } ?>


                    <?php if ($store_things) { ?>
                        <?php foreach ($store_things as $thing) { ?>
                            <?php if ($thing['premium_price']) { ?>
                                <div class="storeSlot storeThings">
                                    <span class="storeSlotTitle"><?php echo $thing['name']; ?></span>
                                    <div class="storePrice">
                                        <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'>
                                        <span>
                                            <?php if ($thing['price']) echo $thing['price']; ?>
                                            <?php if ($thing['premium_price']) echo $thing['premium_price']; ?>
                                        </span>
                                    </div>
                                    <div class="storePurchase">
                                        <input class="modifiers blue" value="BUY" onclick="javascript:loadThing('<?php echo $thing['id']; ?>')">
                                    </div>
                                    <img src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['id']; ?>.png" height="85" alt="">
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="clear"></div>
                    <?php } ?>

                    <?php if ($store_boosts) { ?>
                        <?php foreach ($store_boosts as $boost) { ?>
                            <?php if ($boost['premium_price']) { ?>
                                <div class="storeSlot storeThings">
                                    <span class="storeSlotTitle"><?php echo $boost['name']; ?></span>
                                    <div class="storePrice">
                                        <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'>
                                        <span>
                                            <?php if ($boost['price']) echo $boost['price']; ?>
                                            <?php if ($boost['premium_price']) echo $boost['premium_price']; ?>
                                        </span>
                                    </div>
                                    <div class="storePurchase">
                                        <input class="modifiers blue" value="BUY" onclick="javascript:loadBoosts('<?php echo $boost['id']; ?>')">
                                    </div>
                                    <img src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['id']; ?>.png" height="85" alt="">
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="clear"></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="storeItemBox section" id="section3">
        <img src="<?php echo base_url(); ?>_images/premiumItemsBg.png" alt="">
        <div class="premiumSalesSection">
            <div class="premiumPortraitSection">
                <h2 class="storeTitle">Common Items For Sale</h2>
            </div>
            <div>
                <h2 class="storeTitle">You Have <span class="redText"><?php echo $player->balance; ?></span> Coins</h2>
            </div>
            <div class="commonItemBox">
                <?php if ($store_items) { ?>
                    <h2 class="storeTitle">Items</h2>
                    <?php foreach ($store_items as $item) { ?>
                        <?php if ($item['price']) { ?>
                            <div class="storeSlot storeItem">
                                <span class="storeSlotTitle"><?php echo $item['name']; ?></span>
                                <div class="storePrice">
                                    <img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'>
                                    <span>
                                        <?php if ($item['price']) echo $item['price']; ?>
                                        <?php if ($item['premium_price']) echo $item['premium_price']; ?>
                                    </span>
                                </div>
                                <div class="storePurchase" onclick="javascript:loadItem('<?php echo $item['id']; ?>')">
                                    <input class="items blue" value="BUY" >
                                </div>
                                <img src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['id']; ?><?php if ($item['has_quality']) echo '-1'; ?>.png" height="85" alt="">
                                <!--<?php /*
                              <table class="info">
                              <tr>
                              {% if item.attack %}<th>ATK</th><?php } ?>
                              {% if item.defense %}<th>DEF</th><?php } ?>
                              {% if item.energy %}<th>EN</th><?php } ?>
                              {% if item.stamina %}<th>STA</th><?php } ?>
                              {% if item.health %}<th>HP</th><?php } ?>
                              {% if item.strike %}<th>CS</th><?php } ?>
                              {% if item.dodge %}<th>dodge</th><?php } ?>
                              {% if item.luck %}<th>luck</th><?php } ?>
                              {% if item.capacity %}<th>Cap</th><?php } ?>
                              </tr>
                              <tr>
                              {% if item.attack %}<td>{{item.attack}}</td><?php } ?>
                              {% if item.defense %}<td>{{item.defense}}</td><?php } ?>
                              {% if item.energy %}<td>{{item.energy}}</td><?php } ?>
                              {% if item.stamina %}<td>{{item.stamina}}</td><?php } ?>
                              {% if item.health %}<td>{{item.health}}</td><?php } ?>
                              {% if item.strike %}<td>{{item.strike}}</td><?php } ?>
                              {% if item.dodge %}<td>{{item.dodge}}</td><?php } ?>
                              {% if item.luck %}<td>{{item.luck}}</td><?php } ?>
                              {% if item.capacity %}<td>{{item.capacity}}</td><?php } ?>
                              </tr>
                              </table>
                             */ ?>-->
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="clear"></div>
                <?php } ?>


                <?php if ($store_modifiers) { ?>
                    <h2 class="storeTitle">Modifiers</h2>
                    <?php foreach ($store_modifiers as $modifier) { ?>
                        <?php if ($modifier['price']) { ?>
                            <div class="storeSlot storeModifier">
                                <span class="storeSlotTitle"><?php echo $modifier['name']; ?></span>
                                <div class="storePrice">
                                    <img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'>
                                    <span>
                                        <?php if ($modifier['price']) echo $modifier['price']; ?>
                                        <?php if ($modifier['premium_price']) echo $modifier['premium_price']; ?>
                                    </span>
                                </div>
                                <div class="storePurchase">
                                    <input class="modifiers blue" value="BUY" onclick="javascript:loadModifier('<?php echo $modifier['id']; ?>')">
                                </div>
                                <img src="<?php echo base_url(); ?>_images/data/modifiers/<?php echo $modifier['id']; ?><?php if ($modifier['has_quality']) echo '-1'; ?>.png" height="85" alt="" />
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="clear"></div>
                <?php } ?>


                <?php if ($store_things) { ?>
                    <h2 class="storeTitle">Things</h2>
                    <?php foreach ($store_things as $thing) { ?>
                        <?php if ($thing['price']) { ?>
                            <div class="storeSlot storeModifier">
                                <span class="storeSlotTitle"><?php echo $thing['name']; ?></span>
                                <div class="storePrice">
                                    <img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'>
                                    <span>
                                        <?php if ($thing['price']) echo $thing['price']; ?>
                                        <?php if ($thing['premium_price']) echo $thing['premium_price']; ?>
                                    </span>
                                </div>
                                <div class="storePurchase">
                                    <input class="modifiers blue" value="BUY" onclick="javascript:loadThing('<?php echo $thing['id']; ?>')">
                                </div>
                                <img src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['id']; ?><?php if ($thing['has_quality']) echo '-1'; ?>.png" height="85" alt="" />
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="clear"></div>
                <?php } ?>

                <?php if ($store_boosts) { ?>
                    <h2 class="storeTitle">Boosts</h2>
                    <?php foreach ($store_boosts as $boost) { ?>
                        <?php if ($boost['price']) { ?>
                            <div class="storeSlot storeModifier">
                                <span class="storeSlotTitle"><?php echo $boost['name']; ?></span>
                                <div class="storePrice">
                                    <img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'>
                                    <span>
                                        <?php if ($boost['price']) echo $boost['price']; ?>
                                        <?php if ($boost['premium_price']) echo $boost['premium_price']; ?>
                                    </span>
                                </div>
                                <div class="storePurchase">
                                    <input class="modifiers blue" value="BUY" onclick="javascript:loadBoosts('<?php echo $boost['id']; ?>')">
                                </div>
                                <img src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['id']; ?>.png" height="85" alt="" />
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="clear"></div>
                <?php } ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

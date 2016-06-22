<script>
$(document).ready(function(){
	$(".cachePopUp").hide();
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

    <div class="tanSectionBtn storeBtn" onclick="javascript:window.location.href = '<?php echo base_url()."store/items"?>';" style="margin-right:15px;">
        ITEMS
    </div>
    <div class="yellowSectionBtn storeBtn" onclick="javascript:window.location.href = '<?php echo base_url()."store/premium_items"?>';">
        PREMIUM ITEMS
    </div>
    <div class="blueSectionBtn storeBtn" onclick="javascript:window.location.href = '<?php echo base_url()."store/cache_items"?>';">
        WEAPONS CACHE
    </div>

    <div class="storeWeaponCashebox section" id="section1">
		<div class="ajax_store_cache">
			<div class="dealsBox">
				 <div class="infoBox">
					<input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
					<h4 class="infoText">1 Weapon Crate</h4>
					<div class="storePrice">
						<img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height="30" width="26" alt="challenge coin">
						<span>25</span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="infoBox">
					<input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
					<h4 class="infoText">3 Weapon Crates</h4>
					<div class="storePrice">
						<img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height="30" width="26" alt="challenge coin">
						<span>70</span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="infoBox">
				   <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
				   <h4 class="infoText">5 Weapon Crates</h4>
				   <div class="storePrice">
						<img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height="30" width="26" alt="challenge coin">
						<span>100</span>
					</div>
					<div class="clear"></div>
					<p class="infoSubText">Save 20%</p>
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

		  
		<?php /*?>
		
			<?php  foreach ($caches as $cache) { ?>
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
	  <?php */?>
	  
		  <div  style="clear:both; width: 685px;"  >
			
		   <div style="float:right; margin-right:14px;">
			   <?php echo $pagination;?>
		   </div>
		   <div class="generateCacheItem">Generate Item</div> 
		   <div class="filterCacheItems">
			View items rarity :
			<select id="items_sort">
				<option value="">--Sort--</option>
				<option value="2">Low</option>
				<option value="3">Medium</option>
				<option value="4">High</option>
				<option value="5">Best in Slot</option>
			</select>
		  </div> 
		
					
	  
			
		   </div>
			<div style="clear:both"></div>
			<?php foreach ($cache_items as $item_cache){?>
			 <div class="storeSlot storeItem storeCachePopUp" id="<?php echo $item_cache->id?>" >
				<span class="storeSlotTitle"><?php echo $item_cache->name; ?></span>
			   <?php  if ($item_cache->price || $item_cache->premium_price ){ ?>
				<div class="storePrice">
					<img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'  >
					<span>
						<?php if ($item_cache->price) echo $item_cache->price; ?>
						<?php if ($item_cache->premium_price) echo $item_cache->premium_price; ?>
					</span>
				</div>
				<?php }?>
				<?php if($item_cache->hasIt==0){
					$class = "opacityCacheItem";
				}else{
					$class="";
				} ?>
				<img class="<?php echo $class;?>" src="<?php echo base_url(); ?>_images/data/items/<?php echo $item_cache->id; ?><?php if ($item_cache->has_quality ) echo '-1'; ?>.png" height="85" alt="">
				
			
			
			 <div class="cachePopUp"  style='display: none; height: auto; min-height:110px;' id="<?php echo "popUp".$item_cache->id?>">
			  <div class="closeBtn closeCachePos" ></div>
			  <div id="purchaseItem">
				 <?php echo $item_cache->description?>
					<table class="list">
						<tr>
							<?php if ($item_cache->attack)  echo '<th>Atk</th>'; ?>
							<?php if ($item_cache->defense)  echo '<th>Def</th>'; ?>
							<?php if ($item_cache->energy)  echo '<th>En</th>'; ?>
							<?php if ($item_cache->stamina) echo '<th>Sta</th>'; ?>
							<?php if ($item_cache->health) echo '<th>HP</th>'; ?>
							<?php if ($item_cache->strike) echo '<th>CS</th>'; ?>	
							<?php if ($item_cache->dodge) echo '<th>Dodge</th>'; ?>
							<?php if ($item_cache->luck) echo '<th>Luck</th>'; ?>
							<?php if ($item_cache->capacity) echo '<th>Capacity</th>'; ?>
						</tr>
						<tr>
							<?php if ($item_cache->attack) echo '<td>' . $item_cache->attack  . '</td>'; ?>
							<?php if ($item_cache->defense) echo '<td>' . $item_cache->defense  . '</td>'; ?>
							<?php if ($item_cache->energy) echo '<td>' . $item_cache->energy  . '</td>'; ?>
							<?php if ($item_cache->stamina) echo '<td>' . $item_cache->stamina  . '</td>'; ?>
							<?php if ($item_cache->health) echo '<td>' . $item_cache->health  . '</td>'; ?>
							<?php if ($item_cache->strike) echo '<td>' . $item_cache->strike  . '</td>'; ?>
							<?php if ($item_cache->dodge) echo '<td>' . $item_cache->dodge  . '</td>'; ?>
							<?php if ($item_cache->luck) echo '<td>' . $item_cache->luck  . '</td>'; ?>
							<?php if ($item_cache->capacity) echo '<td>' . $item_cache->capacity  . '</td>'; ?>
						</tr>
					</table>
					<div class="storePurchase" style="margin-top:0px;" onclick="javascript:loadItem('<?php echo $item_cache->id; ?>')">
					<input class="items blue" value="BUY">
				</div>
			  </div>
			</div>
		
		
			</div>
			
			<?php } //end foreach $item_cache ?>
		</div>
    </div>
</div>


<div class="ajax_store_cache" >
 

<?php if(isset($generated_item)) {?>
 <div class="generatedPopUp" id="<?php echo "gen".$generated_item->id?>">
          <div class="closeBtn closeCachePos"  onclick="javascript:hide('<?php echo "gen".$generated_item->id?>')"></div>
          <?php  if ($generated_item->price || $generated_item->premium_price ){ ?>
            <div class="storePrice" style="margin-top: 3px;">
                <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'  >
                <span>
                    <?php if ($generated_item->price) echo $generated_item->price; ?>
                    <?php if ($generated_item->premium_price) echo $generated_item->premium_price; ?>
                </span>
            </div>
            <?php }else{?>
             <div class="storePrice" style="margin-top: 3px;">
             <img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'  ><span>0</span>
             </div>
            <?php }?>
          <div id="purchaseItem">
         
          <div style="clear: both;margin-top:30px;">
          <?php echo $generated_item->description?>
          </div>
             
          <img alt="<?php echo "Image ".$generated_item->name;?>" src="http://coinimpact.com.d/_images/data/items/<?php echo $generated_item->id;?>" class="item">
                <table class="generatedItemTable"  >
                    <tr>
                        <?php if ($generated_item->attack)  echo '<th>Atk</th>'; ?>
                        <?php if ($generated_item->defense)  echo '<th>Def</th>'; ?>
                        <?php if ($generated_item->energy)  echo '<th>En</th>'; ?>
                        <?php if ($generated_item->stamina) echo '<th>Sta</th>'; ?>
                        <?php if ($generated_item->health) echo '<th>HP</th>'; ?>
                        <?php if ($generated_item->strike) echo '<th>CS</th>'; ?>	
                        <?php if ($generated_item->dodge) echo '<th>Dodge</th>'; ?>
                        <?php if ($generated_item->luck) echo '<th>Luck</th>'; ?>
                        <?php if ($generated_item->capacity) echo '<th>Capacity</th>'; ?>
                    </tr>
                    <tr>
                        <?php if ($generated_item->attack) echo '<td>' . $generated_item->attack  . '</td>'; ?>
                        <?php if ($generated_item->defense) echo '<td>' . $generated_item->defense  . '</td>'; ?>
                        <?php if ($generated_item->energy) echo '<td>' . $generated_item->energy  . '</td>'; ?>
                        <?php if ($generated_item->stamina) echo '<td>' . $generated_item->stamina  . '</td>'; ?>
                        <?php if ($generated_item->health) echo '<td>' . $generated_item->health  . '</td>'; ?>
                        <?php if ($generated_item->strike) echo '<td>' . $generated_item->strike  . '</td>'; ?>
                        <?php if ($generated_item->dodge) echo '<td>' . $generated_item->dodge  . '</td>'; ?>
                        <?php if ($generated_item->luck) echo '<td>' . $generated_item->luck  . '</td>'; ?>
                        <?php if ($generated_item->capacity) echo '<td>' . $generated_item->capacity  . '</td>'; ?>
                    </tr>
                </table>
                <div class="storePurchase" style="margin-top:-12px;"> <?php /* onclick="javascript:loadItem('<?php echo $generated_item->id; ?>')"*/?>
                <input type="hidden" name="generatedItemId" id="generatedItemId" value="<?php echo $generated_item->id?>">
                <input class="items blue" id="getGeneratedItem" value="Get">
                <input class="items red" id ="dropGeneratedItem" value="Drop">
            </div>
          </div>
        </div>
        
     <?php }?>   
     
     <?php if(isset($inventory_full) && $inventory_full==true){?>
           <div id="inventoryFullModal" class="modal" style="display:block;">
                        <p style="color: red">Your inventory is full! You can go to <a href="<?php echo base_url().'profile';?>">Profile</a> and drop some items 
                        or go to your <a href="<?php echo base_url().'base';?>Base</a> to store some items.</p>
                </div>
     <?php }?>
     
        
        <div class="dealsBox">
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">1 crate for 25 Challenge Coins</h4>
            </div>
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">3 crate for 70 Challenge Coins</h4>
            </div>
            <div class="infoBox">
                <input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY">
                <h4 class="infoText">5 crate for 100 Challenge Coins</h4>
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
<script>
$(document).ready(function(){
    $('#buy_premium').click(function(){
        loadItem(<?=$premium['id']?>);
    })
})
</script>

<div class="box" style="text-align: center">
    <img class="round" src="<?php echo base_url(); ?>_images/COINBannerB.jpg" width="689" height="272" alt="" />
<?php if(!$player->hasIntroPassed()) {?>
    <h4 class="infoText"><a href="<?php echo base_url(); ?>intro">Return to Intro Briefing</a></h4>
<?php } ?>
</div>

<div style="width: 49%; float:left;">
	<div class="box">
		<div id="popStore" class="popUp">
			<div class="closeBtn closeStorePos" onclick="javascript:toggle('popStore')">
			</div>
			<div id="purchaseItem"></div>
		</div>
		<h2 class="page"><?php echo $cache->name; ?></h2>
		<!--
		<p>{{cache.description}}</p>
		-->
		<img src="<?php echo base_url(); ?>_images/Cache.png" width="325" height="190" alt="" />

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
		<input class="buyCoinBtn" type="submit" name="submit" value="Buy More">

		<h4 class="infoText">Learn more about this cache by <a href="<?php echo base_url(); ?>store">visiting the store</a>.</h4>
		<div class="clear"></div>
	</div>
	
	<div class="box">
		<!-- 
		<h2 class="timer"> 00:00:00</h2> 
		-->
		<h2 class="pagePremium">Premium Items</h2>

		<div class="premiumBox">
			<img style="position:absolute; margin-top:10px; margin-left:2px" src="<?php echo base_url(); ?>_images/data/items/<?php echo $premium['id']; ?>.png" width="170" height="95" alt="" />
			<img style="position:absolute"  src="<?php echo base_url(); ?>_images/premiumFrame.png" width="174" height="107" alt="" />
		</div>

		<div id="premiumTextBox">
			<h3><?php echo $premium['name']; ?></h3><?php echo $premium['description']; ?>
		</div>

		<div class="infoBox">
			<input style="float: right;" class="blueBtnS blue" type="submit" name="submit" value="BUY" id="buy_premium">
			<h4 class="infoText">1 Weapon Crate</h4>
			<div class="storePrice">
				<img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height="30" width="26" alt="challenge coin">
				<span><?php echo $premium['premium_price']; ?></span>
			</div>
			<div class="clear"></div>
		</div>
		<!--
		<input class="buyCoinBtn" type="submit" name="submit" value="Buy More">

		<h4 class="infoText">Learn more about this premium item by <a href="<?php echo base_url(); ?>store">visiting the store</a></h4>
		-->
	</div>
</div>

<div style="width: 49%; float:right;">
	<div class="box">
		<img class="titleAddBtn" src="<?php echo base_url(); ?>_images/icoAdd.png" width="18" height="18" alt="" />
		<h2 class="addButtonBar">Latest News</h2>

		<?php if (isset($articles)) { ?>
			<?php foreach ($articles as $article) { ?>
				<div class="newsBox">
					<div class="dateMonth"><?php echo date('m', strtotime($article['date'])); ?></div>
					<div class="dateDay"><?php echo date('d', strtotime($article['date'])); ?></div>
					<div class="newsInfo">
						<?php echo $article['title']; ?><br>
						<a href="<?php echo base_url(); ?>articles/<?php echo $article->id ?>">Read more</a><br />
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>

	<div class="clear"></div>

	<div class="box">
		<img class="titleAddBtn" src="<?php echo base_url(); ?>_images/icoAdd.png" width="18" height="18" alt="" />
		<h2 class="addButtonBar">Sponsors</h2>
		<h4 class="infoText">Seeking corporate sponsorships as we launch. Anyone interested is encouraged to contact <a href="mailto:sponsors@coinimpact.com">sponsors@coinimpact.com</a></h4><br />
	</div>
</div>

<div class="clear"></div>

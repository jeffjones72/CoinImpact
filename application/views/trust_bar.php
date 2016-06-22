<?php
/*
 * CI:B0211
* Trust bar with coins left; 
* it is calculated in controller action, function  accept_trader
*/
?>
			<div class="exploreCoinsBar">
				<div class="trustProgress" style="width:<?=floor($player->trustProgress)?>%;"></div>
				<div class="exploreProgressText">
					<!--<strong>Coins </strong> - $<?=$player->balance?> left <?php ?>-->
					<strong>Trust</strong> - <?=floor($player->trustProgress)?>% earned
					<img class="exploreInfo titleAddBtn"
						src="<?php echo base_url(); ?>_images/icoInfo.png" width="18"
						height="18" alt="Add">
					<div class="stat_msg">
						<p>Earn trust to reduce attacks</p>
					</div>
				</div>
			</div>
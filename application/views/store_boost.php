<div id="sell">
    <img class="boost" src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['id']; ?>.png" alt="<?php echo $boost['name']; ?>">
    <h2><?php echo $boost['name']; ?></h2>
    <p><?php echo $boost['description']; ?></p>
    <div class="cost">
        <?php if ($boost['price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <?php if ($boost['premium_price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <p>
            <?php if ($boost['price']) echo $boost['price']; ?>
            <?php if ($boost['premium_price']) echo $boost['premium_price']; ?>
        </p>
    </div>
    <div class="clear"></div>
    <?php if ($boost['buyable']) { ?>
        <form action="<?php echo base_url(); ?>store/purchase_boost" method="post">
            <div>
                <input type="hidden" name="id" value="<?php echo $boost['id']; ?>" />
                <input type="submit" class="button blue" value="Purchase" />
            </div>
        </form>
    <?php } ?>
</div>
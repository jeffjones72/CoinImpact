<div id="sell">
    <img class="item" src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['id']; ?><?php if ($item['has_quality']) echo '-1'; ?>.png" alt="<?php echo $item['name']; ?>">
    <h2><?php echo $item['name']; ?></h2>
    <p><?php echo $item['description']; ?></p>
    <table class="list">
        <tr>
            <?php if ($item['attack']) echo '<th>Atk</th>'; ?>
            <?php if ($item['defense']) echo '<th>Def</th>'; ?>
            <?php if ($item['energy']) echo '<th>En</th>'; ?>
            <?php if ($item['stamina']) echo '<th>Sta</th>'; ?>
            <?php if ($item['health']) echo '<th>HP</th>'; ?>
            <?php if ($item['strike']) echo '<th>CS</th>'; ?>	
            <?php if ($item['dodge']) echo '<th>Dodge</th>'; ?>
            <?php if ($item['luck']) echo '<th>Luck</th>'; ?>
            <?php if ($item['capacity']) echo '<th>Capacity</th>'; ?>
        </tr>
        <tr>
            <?php if ($item['attack']) echo '<td>' . $item['attack'] . '</td>'; ?>
            <?php if ($item['defense']) echo '<td>' . $item['defense'] . '</td>'; ?>
            <?php if ($item['energy']) echo '<td>' . $item['energy'] . '</td>'; ?>
            <?php if ($item['stamina']) echo '<td>' . $item['stamina'] . '</td>'; ?>
            <?php if ($item['health']) echo '<td>' . $item['health'] . '</td>'; ?>
            <?php if ($item['strike']) echo '<td>' . $item['strike'] . '</td>'; ?>
            <?php if ($item['dodge']) echo '<td>' . $item['dodge'] . '</td>'; ?>
            <?php if ($item['luck']) echo '<td>' . $item['luck'] . '</td>'; ?>
            <?php if ($item['capacity']) echo '<td>' . $item['capacity'] . '</td>'; ?>
        </tr>
    </table>
    <div class="cost">
        <?php if ($item['price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <?php if ($item['premium_price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <p>
            <?php if ($item['price']) echo $item['price']; ?>
            <?php if ($item['premium_price']) echo $item['premium_price']; ?>
        </p>
    </div>
    <div class="clear"></div>
    <?php if ($item['buyable']) { ?>
        <form action="<?php echo base_url(); ?>store/purchase_item" method="post">
            <div>
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                <input type="submit" class="button blue" value="Purchase" />
            </div>
        </form>
    <?php } ?>
</div>

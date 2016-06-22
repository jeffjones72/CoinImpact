<div id="sell">
    <img class="item" src="<?php echo base_url(); ?>_images/data/modifiers/<?php echo $modifier['id']; ?><?php if ($modifier['has_quality']) echo '-1'; ?>.png" alt="<?php echo $modifier['name']; ?>">
    <h2><?php echo $modifier['name']; ?></h2>
    <p><?php echo $modifier['description']; ?></p>
    <table class="list">
        <tr>
            <?php if ($modifier['attack']) echo '<th>Atk</th>'; ?>
            <?php if ($modifier['defense']) echo '<th>Def</th>'; ?>
            <?php if ($modifier['energy']) echo '<th>En</th>'; ?>
            <?php if ($modifier['stamina']) echo '<th>Sta</th>'; ?>
            <?php if ($modifier['health']) echo '<th>HP</th>'; ?>
            <?php if ($modifier['strike']) echo '<th>CS</th>'; ?>	
            <?php if ($modifier['dodge']) echo '<th>Dodge</th>'; ?>
            <?php if ($modifier['luck']) echo '<th>Luck</th>'; ?>
        </tr>
        <tr>
            <?php if ($modifier['attack']) echo '<td>' . $modifier['attack'] . '</td>'; ?>
            <?php if ($modifier['defense']) echo '<td>' . $modifier['defense'] . '</td>'; ?>
            <?php if ($modifier['energy']) echo '<td>' . $modifier['energy'] . '</td>'; ?>
            <?php if ($modifier['stamina']) echo '<td>' . $modifier['stamina'] . '</td>'; ?>
            <?php if ($modifier['health']) echo '<td>' . $modifier['health'] . '</td>'; ?>
            <?php if ($modifier['strike']) echo '<td>' . $modifier['strike'] . '</td>'; ?>
            <?php if ($modifier['dodge']) echo '<td>' . $modifier['dodge'] . '</td>'; ?>
            <?php if ($modifier['luck']) echo '<td>' . $modifier['luck'] . '</td>'; ?>
        </tr>
    </table>
    <div class="cost">
        <?php if ($modifier['price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <?php if ($modifier['premium_price']) { ?><img src="<?php echo base_url(); ?>_images/statContainerChalCoins.png" height='30' width='26' alt='challenge coin'><?php } ?>
        <p>
            <?php if ($modifier['price']) echo $modifier['price']; ?>
            <?php if ($modifier['premium_price']) echo $modifier['premium_price']; ?>
        </p>
    </div>
    <div class="clear"></div>

    <?php if ($modifier['buyable']) { ?>
        <form action="<?php echo base_url(); ?>store/purchase_modifier" method="post">
            <div>
                <input type="hidden" name="id" value="<?php echo $modifier['id']; ?>" />
                <input type="submit" class="button blue" value="Purchase" />
            </div>
        </form>
    <?php } ?>
</div>
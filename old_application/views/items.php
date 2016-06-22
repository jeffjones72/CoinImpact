<!-- {{request.player.inventory_count}}/{{request.player.inventory_capacity}} -->

<?php if ($item_count > 0) { ?>
    <h2 class="page"><?php echo $item_count; ?> Items</h2>
    <?php
    unset($items['item_slots']);
    unset($items['item_deltas']);
    ?>
    <?php foreach ($items as $item) { ?>
        <div class="pad" style="float:left;">
            <div style="height:5em;">
                <h3><?php echo $item['item_name']; ?></h3>
                <p><?php echo $item['item_description']; ?></p>
            </div>
            <img src="<?php echo base_url(); ?>_images/data/items/<?php echo $item['item_id']; ?><?php
            if ($item['has_quality'] > 0) {
                echo '-1';
            }
            ?>s.png" alt="" />
            <table class="info">
                <tr>
                    <?php
                    if ($item['attack'] > 0) {
                        echo '<th>attack</th>';
                    }
                    ?>
                    <?php
                    if ($item['defense'] > 0) {
                        echo '<th>defense</th>';
                    }
                    ?>
                    <?php
                    if ($item['energy'] > 0) {
                        echo '<th>energy</th>';
                    }
                    ?>
                    <?php
                    if ($item['stamina'] > 0) {
                        echo '<th>stamina</th>';
                    }
                    ?>
                    <?php
                    if ($item['health'] > 0) {
                        echo '<th>health</th>';
                    }
                    ?>
        <?php
        if ($item['strike'] > 0) {
            echo '<th>strike</th>';
        }
        ?>
                    <?php
                    if ($item['dodge'] > 0) {
                        echo '<th>dodge</th>';
                    }
                    ?>
                    <?php
                    if ($item['luck'] > 0) {
                        echo '<th>luck</th>';
                    }
                    ?>
                    <?php
                    if ($item['capacity'] > 0) {
                        echo '<th>capacity</th>';
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    if ($item['attack'] > 0) {
                        echo '<td>' . $item['attack'] . '</td>';
                    }
                    ?>
                    <?php
                    if ($item['defense'] > 0) {
                        echo '<td>' . $item['defense'] . '</td>';
                    }
                    ?>
            <?php
            if ($item['energy'] > 0) {
                echo '<td>' . $item['energy'] . '</td>';
            }
            ?>
        <?php
        if ($item['stamina'] > 0) {
            echo '<td>' . $item['stamina'] . '</td>';
        }
        ?>
        <?php
        if ($item['health'] > 0) {
            echo '<td>' . $item['health'] . '</td>';
        }
        ?>
        <?php
        if ($item['strike'] > 0) {
            echo '<td>' . $item['strike'] . '</td>';
        }
        ?>
        <?php
        if ($item['dodge'] > 0) {
            echo '<td>' . $item['dodge'] . '</td>';
        }
        ?>
        <?php
        if ($item['luck'] > 0) {
            echo '<td>' . $item['luck'] . '</td>';
        }
        ?>
                    <?php
                    if ($item['capacity'] > 0) {
                        echo '<td>' . $item['capacity'] . '</td>';
                    }
                    ?>
                </tr>
            </table>
            <p>Rarity: <?php echo $item['rarity_name']; ?></p>

                    <?php if ($player['inventory_capacity'] > $player['inventory_count']) { ?>
                <form action="<?php echo base_url(); ?>items/collect_item" method="post">
                    <div>
                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>" />
                        <input type="submit" class="button blue" value="collect" />
                    </div>
                </form>
                    <?php } ?>
        </div>
                <?php } ?>
    <div class="clear"></div>
            <?php } ?>

            <?php if ($modifier_count > 0) { ?>
    <h2 class="page"><?php echo $modifier_count; ?> Modifiers</h2>
                <?php foreach ($modifiers as $modifier) { ?>
        <div class="pad" style="float:left;">
            <div style="height:5em;">
                <h3><?php echo $modifier['modifier_name']; ?></h3>
                <p><?php echo $modifier['modifier_description']; ?></p>
            </div>
            <img src="<?php echo base_url(); ?>_images/data/modifiers/<?php echo $modifier['id']; ?><?php
                    if ($modifier['has_quality'] > 0) {
                        echo '-1';
                    }
                    ?>s.png" alt="" />
            <table class="info">
                <tr>
                    <?php
                    if ($modifier['attack'] > 0) {
                        echo '<th>attack</th>';
                    }
                    ?>
                    <?php
                    if ($modifier['defense'] > 0) {
                        echo '<th>defense</th>';
                    }
                    ?>
        <?php
        if ($modifier['energy'] > 0) {
            echo '<th>energy</th>';
        }
        ?>
        <?php
        if ($modifier['stamina'] > 0) {
            echo '<th>stamina</th>';
        }
        ?>
        <?php
        if ($modifier['health'] > 0) {
            echo '<th>health</th>';
        }
        ?>
        <?php
        if ($modifier['strike'] > 0) {
            echo '<th>strike</th>';
        }
        ?>
        <?php
        if ($modifier['dodge'] > 0) {
            echo '<th>dodge</th>';
        }
        ?>
        <?php
        if ($modifier['luck'] > 0) {
            echo '<th>luck</th>';
        }
        ?>
                </tr>
                <tr>
        <?php
        if ($modifier['attack'] > 0) {
            echo '<td>' . $modifier['attack'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['defense'] > 0) {
            echo '<td>' . $modifier['defense'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['energy'] > 0) {
            echo '<td>' . $modifier['energy'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['stamina'] > 0) {
            echo '<td>' . $modifier['stamina'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['health'] > 0) {
            echo '<td>' . $modifier['health'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['strike'] > 0) {
            echo '<td>' . $modifier['strike'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['dodge'] > 0) {
            echo '<td>' . $modifier['dodge'] . '</td>';
        }
        ?>
        <?php
        if ($modifier['luck'] > 0) {
            echo '<td>' . $modifier['luck'] . '</td>';
        }
        ?>
                </tr>
            </table>
        <?php if ($player['inventory_capacity'] > $player['inventory_count']) { ?>
                <form action="<?php echo base_url(); ?>items/collect_modifier" method="post">
                    <div>
                        <input type="hidden" name="modifier_id" value="<?php echo $modifier['id']; ?>" />
                        <input type="submit" class="button blue" value="collect" />
                    </div>
                </form>
        <?php } ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
<?php } ?>

<?php if ($thing_count > 0) { ?>
    <h2 class="page"><?php echo $thing_count; ?> Things</h2>
    <?php foreach ($things as $thing) { ?>
        <div class="pad" style="float:left;">
            <div style="height:5em;">
                <h3><?php echo $thing['thing_name']; ?></h3>
                <p><?php echo $thing['thing_description']; ?></p>
            </div>
            <img src="<?php echo base_url(); ?>_images/data/things/<?php echo $thing['thing_id']; ?>s.png" alt="" />
        <?php if ($player['inventory_capacity'] > $player['inventory_count']) { ?>
                <form action="<?php echo base_url(); ?>items/collect_thing" method="post">
                    <div>
                        <input type="hidden" name="thing_id" value="<?php echo $thing['thing_id']; ?>" />
                        <input type="submit" class="button blue" value="collect" />
                    </div>
                </form>
        <?php } ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
<?php } ?>

<?php if ($boost_count > 0) { ?>
    <h2 class="page"><?php echo $boost_count; ?> Boosts</h2>
    <?php foreach ($boosts as $boost) { ?>
        <div class="pad" style="float:left;">
            <div style="height:5em;">
                <h3><?php echo $boost['boost_name']; ?></h3>
                <p><?php echo $boost['boost_description']; ?></p>
            </div>
            <img src="<?php echo base_url(); ?>_images/data/boosts/<?php echo $boost['boost_id']; ?>s.png" alt="" />
            <form action="<?php echo base_url(); ?>items//collect_boost" method="post">
                <div>
                    <input type="hidden" name="boost_id" value="<?php echo $boost['boost_id']; ?>" />
                    <input type="submit" class="button blue" value="collect" />
                </div>
            </form>
        </div>
    <?php } ?>
    <div class="clear"></div>
<?php } ?>

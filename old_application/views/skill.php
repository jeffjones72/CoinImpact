<div class="box" style="margin-top:40px;">
    <h2>Skill</h2>
    <?php if (!$confirm) { ?>
        <p>You have <?php echo $player->skill; ?> unused skill points.</p>
        <?php if ($player->skill >= floor(1 / $stamina_rate)) { ?>
            <form action="<?php echo base_url(); ?>skill" method="post">
                <div>
                    <select name="points">
                        <?php for ($i = floor(1 / $stamina_rate); $i <= $player->skill; $i+=floor(1 / $stamina_rate)) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i * $stamina_rate; ?> Stamina Limit (<?php echo $i; ?> Skill Points)</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="stat" value="stamina_limit" />
                    <input class="button blue" type="submit" value="Apply" />
                </div>
            </form>
        <?php } ?>

        <?php if ($player->skill >= floor(1 / $energy_rate)) { ?>
            <form action="<?php echo base_url(); ?>skill" method="post">
                <div>
                    <select name="points">
                        <?php for ($i = floor(1 / $energy_rate); $i <= $player->skill; $i+=floor(1 / $energy_rate)) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i * $energy_rate; ?> Energy Limit (<?php echo $i; ?> Skill Points)</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="stat" value="energy_limit" />
                    <input class="button blue" type="submit" value="Apply" />
                </div>
            </form>
        <?php } ?>

        <?php if ($player->skill >= floor(1 / $health_rate)) { ?>
            <form action="<?php echo base_url(); ?>skill" method="post">
                <div>
                    <select name="points">
                        <?php for ($i = 1; $i <= $player->skill; ++$i) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i * $health_rate; ?> Health Limit (<?php echo $i; ?> Skill Points)</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="stat" value="health_limit" />
                    <input class="button blue" type="submit" value="Apply" />
                </div>
            </form>
        <?php } ?>

        <?php if ($player->skill >= floor(1 / $attack_rate)) { ?>
            <form action="<?php echo base_url(); ?>skill" method="post">
                <div>
                    <select name="points">
                        <?php for ($i = floor(1 / $attack_rate); $i <= $player->skill; $i+=floor(1 / $attack_rate)) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i * $attack_rate; ?> Attack (<?php echo $i; ?> Skill Points)</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="stat" value="attack" />
                    <input class="button blue" type="submit" value="Apply" />
                </div>
            </form>
        <?php } ?>

        <?php if ($player->skill >= floor(1 / $defense_rate)) { ?>
            <form action="<?php echo base_url(); ?>skill" method="post">
                <div>
                    <select name="points">
                        <?php for ($i = floor(1 / $defense_rate); $i <= $player->skill; $i+=floor(1 / $defense_rate)) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i * $defense_rate; ?> Defense (<?php echo $i; ?> Skill Points)</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="stat" value="defense" />
                    <input class="button blue" type="submit" value="Apply" />
                </div>
            </form>
        <?php } ?>
    <?php } else { ?>
        Are you sure you want to spend <?php echo $skill_points; ?> Skill Points on <?php echo $stat; ?>?
        <form action="<?php echo base_url(); ?>skill/apply_skill" method="post">
            <div>
                <input type="hidden" name="points" value="<?php echo $skill_points; ?>" />
                <input type="hidden" name="stat" value="<?php echo $stat; ?>" />
                <input class="button blue" type="submit" value="Yes" />
            </div>
        </form>
    <?php } ?>
</div>

<!DOCTYPE html>
<html>
    <head>
        <meta name="copyright" content="Copyright (C) 2012 Down Range Games, Inc, 1084 Paloma Rd., Mon]terey, CA 93940" />
        <meta content="text/html; charset=utf-8" http-equiv="Content-type" />
        <script type="text/javascript" src="<?php echo base_url(); ?>_scripts/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>_scripts/password_strength_plugin.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>_scripts/functions.js"></script>
        <script src='<?php echo base_url(); ?>_scripts/Stats.js'></script>
        <script src="<?php echo base_url(); ?>jquery-ui/js/jquery-ui-1.10.2.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>_css/styles.css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>jquery-ui/css/ui-lightness/jquery-ui-1.10.2.custom.css">
        <title><?php echo $page_title; ?></title>
        <script type="text/javascript">
            d = new Date();
            stat_info = {};
            stat_info['health'] = <?=$player->health?>;
            stat_info['energy'] = <?=$player->energy?>;
            stat_info['stamina'] = <?=$player->stamina?>;
            stat_info['experience'] = <?=$player->experience?>;

            stat_info['health_limit'] = <?=$player->health_limit?>;
            stat_info['energy_limit'] = <?=$player->energy_limit?>;
            stat_info['stamina_limit'] = <?=$player->stamina_limit?>;
            stat_info['experience_limit'] = <?=$player->getNextLevelXP()?>;
            stat_info['experience_AtLevel'] = <?=$player->getCurrentLevelXP()?>;

            stat_info['health_rate'] = <?=$player->health_rate?>;
            stat_info['energy_rate'] = <?=$player->energy_rate?>;
            stat_info['stamina_rate'] = <?=$player->stamina_rate?>;

            stat_info['health_refill'] = d.getTime()/1000+<?=$player->health_refill-time()?>;
            stat_info['energy_refill'] = d.getTime()/1000+<?=$player->energy_refill-time()?>;
            stat_info['stamina_refill'] = d.getTime()/1000+<?=$player->stamina_refill-time()?>;
            function init_data() {
                place_id = <?=$player->place_id?>;
                base_place = {id: <?=$base_place->id?>, energy_cost: <?=$base_place->energy?>};
                            combatant_Max = 0;
                            combatant_Current = 0;
            }
<?php if($player->isAtBase()) {?>
$(document).ready(function(){
    $('#explore_link').click(function(e){
        e.preventDefault();
    });
});
<?php }?>
        </script>
    </head>
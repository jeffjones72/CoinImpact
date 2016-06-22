<!DOCTYPE html>
<html>
    <head>
        <meta name="copyright" content="Copyright (C) 2012 - <?php echo date("Y"); ?> Down Range Games, Inc, 1084 Paloma Rd., Mon]terey, CA 93940" />
        <meta content="text/html; charset=utf-8" http-equiv="Content-type" />
        
        <script type="text/javascript" src="<?php echo base_url();?>_scripts/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>_scripts/password_strength_plugin.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>_scripts/functions.js"></script>
        <script src="<?php echo base_url();?>jquery-ui/js/jquery-ui-1.10.2.custom.js"></script>
        <script src="/_scripts/Stats.js"></script>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>_css/styles.css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>jquery-ui/css/ui-lightness/jquery-ui-1.10.2.custom.css">
        <link rel="icon" href="<?=base_url()?>_images/favicon.gif" type="image/gif">
        
        <title><?php echo $page_title; ?></title>
        
        <script type="text/javascript">
            d = new Date();            
            function init_data() {
                place_id = <?=$player->place_id?>;
                base_place = {
                    id: <?=$base_place->id?>,
                    energy_cost: <?=$base_place->energy?>
                };
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

            /*
                sq10 | CI:B0107 | 1/3
            
                Stats.js -> PHP-provided data.
            */
            Stats.phpData = {
                balance: <?=$player->balance?>,
                premiumBalance: <?=$player->premium_balance?>,
                health: <?=$player->health?>,
                energy: <?=$player->energy?>,
                stamina: <?=$player->stamina?>,
                experience: <?=$player->experience?>,

                healthLimit: <?=$data['health_limit']?>,
                energyLimit: <?=$data['energy_limit']?>,
                staminaLimit: <?=$data['stamina_limit']?>,
                
                experienceCurrent: <?=$player->getCurrentLevelXP()?>,
                experienceLimit: <?=$player->getNextLevelXP()?>,
                experienceLevel: <?=$player->level_id?>,
                /*
                    sq10 | CI:B0108 | 3/4

                    Add experienceLevels.
                */
                experienceLevels: <?=json_encode($player->getLevelsXP())?>,
                
                healthRate: <?=$player->health_rate?>,
                energyRate: <?=$player->energy_rate?>,
                staminaRate: <?=$player->stamina_rate?>,
                
                healthRefill: <?=$player->health_refill?>,
                energyRefill: <?=$player->energy_refill?>,
                staminaRefill: <?=$player->stamina_refill?>
            };
        </script>
        
    </head>

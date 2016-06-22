<br />
<br />
<br />
<br />
<br />
<?php
    if($player->account->is_staff){

        echo '<pre>';
        print_r($stats);
        echo '</pre>';
        
        echo $stats->base_attack;
        echo $player->account->is_staff;
    
        echo '<pre>';
        print_r($player);
        echo '</pre>';
    } else {
        redirect('profile');
    }
?>
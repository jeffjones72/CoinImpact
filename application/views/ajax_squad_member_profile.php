<?php
//Ticket #58

?>

<script type="text/javascript">
$(document).ready(function() {
    $('#cancel_drop').click(function(e) {
        $('#drop_modal').hide();
        e.stopPropagation();
    });
    $('[data-drop-id]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#drop_id').val($(this).attr('data-drop-id'));
        $('#drop_modal').show();
    });

    $(".selectorSquad").change(function(e){
        var id = $(this).val();
        var player_id = id.split("_");

        if($("#input_id").val() != player_id[1]){

            $.ajax({
                url:"<?php echo base_url()."profile/ajax_squad_members"?>",
                type: "POST",
                data:{user_id : id},
                success:function(result){
                    $(".inventoryBox").html(result);
                    $(".squad_player_id").val(id);
                    $(this).val(id);
                    }
             });
        }else{
            $.ajax({
            	url:"<?php echo base_url()."profile/index"?>"
            })}
    });
    $("input[name='remove_item']").click(function(e){
    	e.preventDefault();
    	var form = $(this).closest("form");
        var player_item_id = $("input[name='player_item_id']", form).val();
        var squadPlayer = $(".selectorSquad").val();
    	var id= squadPlayer.split("_");
    	//alert("<?php echo base_url()."profile/ajax_squad_members?user_id="; ?>"+squadPlayer);
        $.ajax({
        	url:"<?php echo base_url()."action/unequip"?>",
            type: "post",
            data:{ squad_player_id:squadPlayer, player_items_squad_id: player_item_id  },
            success:function(result){
            	//location.reload();

            	window.location.href = "<?php echo base_url()."profile/ajax_squad_members?user_id="; ?>"+squadPlayer;
            	$(".selectorSquad").val(squadPlayer);
                }
            });

        });


/*
    $(".selectorSquad").change(function(e){
        var id = $(this).val();
        $.ajax({
            url:"<?php //echo base_url()."profile/ajax_squad_members"?>",
            type: "POST",
            data:{user_id : id},
            success:function(result){
                $(".inventoryBox").empty().html(result);
                $(".squad_player_id").val(id);
                $(this).val(id);
                }
         });
        });
*/
        $('.small-pad').click(function(event) {
            event.stopPropagation();
            $('.small-pad').children('img').removeClass('equipOpacity');
            if ($(this).children('.equipStats').is(':visible')) {
                $('.equipStats').hide();
            } else {
                $('.equipStats').hide();
                $(this).children('.equipStats').show();
                $(this).children('img').addClass('equipOpacity');
            }
        });
});
</script>



				<img class="profileBorder"
					src="<?php echo base_url(); ?>_images/side_profile_left.jpg" alt="">

				<div class="profileFrame">
					<img class="profileRank<?=$player->gender?>"
						src="<?php echo base_url(); ?>_images/rank.png" height="20"
						width="20" alt="rank"> <img
						src="<?php echo base_url(); ?>_images/male.jpg" height="150"
						width="130" alt="profile image">

				</div>
				<img class="profileBorder"
					src="<?php echo base_url(); ?>_images/side_profile_right.jpg"
					alt="">


				<div class="equippedItems">
<?php
             foreach (Equipment::getSlotTypes() as $eq_slot) {
        	               $slot_id = $eq_slot->getId();
?>
<?php
                        //is companion or vehicle
                     if ($eq_slot->isCompanion() || $eq_slot->isVehicle()) {
                            continue;
                    }
                        ?>
                    <div class="small-pad">
						<span class="smallTitle"><?php echo $eq_slot->getName(); ?></span>
                        <?php
                        $p_item = null;
                        if(isset($squad_items) && count($squad_items)){
                            foreach ($squad_items as $item){
                                if($item->slot_id == $slot_id){
                                    $p_item=$item;
                                }
                            }
                        }



                        if ($p_item) {
                            ?>
                        <?php if($p_item->item->weight == 2) {?>
                        <img class="opacity"
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?php if ($p_item->item->has_quality) {echo '-' . $p_item->quality;}?>.png"
							alt="<?=$p_item->item->name?>"
							title="<?=$p_item->item->name. ' ' . $p_item->durability . '%'; ?>"
							width="85" />
                        <?php }?>
                        <img
							src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?=$p_item->item->has_quality ? '-'.$p_item->quality : ''?>.png"
							alt="<?=$p_item->item->name?>"
							title="<?=$p_item->item->name . ' ' . $p_item->durability . '%'?>"
							width="85">
						<div
							class="equipStats equip<?=ucfirst($eq_slot->getType())?> rarity<?=$p_item->item->rarity_id?>">
							<h1>
								<span><?=$eq_slot->getName()?></span><?=$p_item->item->name?><?php if ($p_item->item->has_quality) {echo '- Q' . $p_item->quality;}?></h1>
							<p><?=$p_item->item->description?></p>
							<img
								src="<?php echo base_url(); ?>_images/data/items/<?=$p_item->item->id?><?php if ($p_item->item->has_quality) {echo '-' . $p_item->quality . 's';}?>.png"
								alt="<?=$p_item->item->name?>"
								title="<?=$p_item->item->name . ' ' . $p_item->durability . '%'?>">
							<div class="statBox">
								<table>
                                    <?php foreach(Item::$stat_fields as $i => $field) { ?>
                                    <?php if($p_item->item->{$field}) {?>
                                    <tr>
										<th><?=Item::$stat_initials[$i]?></th>
										<td><?=$p_item->item->{$field}?></td>
									</tr>
                                    <?php } ?>
                                    <?php } ?>
                                </table>
							</div>
							<form action="<?php echo base_url(); ?>action/unequip"
								method="post">
								<div>
									<input type="hidden" name="player_item_id" value="<?=$p_item->id?>" />
										<input class="unEquip red" name ="remove_item" type="submit" value="Remove">
								</div>
							</form>
						</div>
                        <?php }?>
                    </div>
                    <?php }?>
                </div>

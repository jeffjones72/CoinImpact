<script>
var introPage = <?=$player->getIntroPage()?>;
$(document).ready(function() {
    $('#base-link').removeAttr('onclick');
    $("#navigation, #stats").fadeTo("slow", 0.3);
    $('a').click(function(){
        if($(this).attr('id') != 'logout') {
            return false;
        }
    });
    if(introPage != 1) {
        $('#introStart').animate({marginTop: '-='+((introPage-1)*480)+'px'}, 0);
    }

      $("#add_squad").click(function (e){
        $.ajax({
           type: "post",
           data: {intro : 1},
           url: "<?php echo base_url()."intro/add_squad_npc/"?>",
           success: function(result){
               }

            });
     });

})
</script>
<div id="intro">
    <div class="introNext">
        <p>Next</p>
    </div>
    <div id="introStart" class="introSection">
        <div id="popIntroGear" class="popUp">
            <p>You receive:</p>
            <div class="left">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/36.png" height="70" alt="">
                <p>Tattered Jeans</p>
            </div>
            <div class="right">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/57.png" height="70" alt="">
                <p>Dirty Shirt</p>
            </div>

            <div class="introEquipGear blue confirm">
                <p>Equip</p>
            </div>
        </div>
        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/001EstablishingShotPreview.jpg" alt="">
        <div class="introInfo">
            <p>You sit silently against the wall, hoping that you go unnoticed for at least a short time. The
                beatings are terrible and you get little to no sleep. It has been almost three months now... or was
                it four. Time really plays tricks on you when you can’t see natural light. The only indication has
                been the changing of the guard shifts and the different smells coming from the other room. Is
                this how your life is going to end? Some tattered piece of flesh in a dark room in the desert?
                Surely, there must be some other way...</p>
        </div>
        <div class="introAction blue">
            <p>Find a Way!</p>
        </div>
    </div>
    <div class="introSection">
        <div class="popIntroExplore popUp">
            <p>You receive:</p>
            <div class="center">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/68.png" height="70" alt="">
                <p>Glass Shard</p>
            </div>

            <div class="introEquipWep blue confirm">
                <p>Equip</p>
            </div>
        </div>
        <img class="introCenterImage" src="<?php echo base_url(); ?>_images/Introduction/002CuttingTheRopePreview.jpg" alt="">
        <div class="introInfo">
            <p>You pull and strain against your bonds. No results. But wait... You feel a small shard of glass
                on the ground behind you, within reach.</p>
        </div>
        <div class="introAction blue">
            <p>Take Glass</p>
        </div>
    </div>
    <div class="introSection">
        <div id="introDamage">
            <img class="introCenterImage" src="<?php echo base_url(); ?>_images/Introduction/002CuttingTheRopePreview.jpg" alt="">
            <div class="introCombatStats">
                <div class="itemStrengthBar" style="height:28px;background-color:#7C6B57;width:460px;float: left;">
                    <div class="itemStrength" style="width:100%;height:28px;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#af444c', endColorstr='#682b2b');background: -webkit-gradient(linear, left top, left bottom, from(#AF444C), to(#682B2B));background: -moz-linear-gradient(top, #AF444C, #682B2B);"></div>
                    <div class="itemStrengthText" style="color: #F1ECE5;margin-top: -21px;text-align: center;height: 30px;font-size: 14px;">
                        <strong>Rope Tensile</strong> - <span>10</span>/10
                    </div>
                </div>
                <div class="bossLevelBar">
                    <div class="damageButton blue" style="height: 19px;width: 90px;margin-top: -1px;color: floralWhite;font-weight: bold;font-size: 14px;text-shadow: #355782 0px 1px 2px;text-align: center;padding: 4px 10px;float: left;visibility: hidden;">Cut</div>
                    <p class="bossLevelBarText" style="height: 19px;">NPC level:</p>
                    <div class="bossLevel" style="background: url('/_images/graySkull.png');height: 19px;width: 28px;float: left;">
                        <img class="skull" src="<?php echo base_url(); ?>_images/icons/goldSkull.png" height="28" width="28" alt="">
                    </div>
                    <img src="<?php echo base_url(); ?>_images/icoInfo.png" width="18" style="float:right;padding:6px 5px 0px 0px;" alt="">
                </div>
            </div>
        </div>
        <div class="introInfo">
            <p>Carefully wrapping the glass shard in a strip of cloth, you begin trying to cut the rope. Maybe you can manage... Yes, that’s it... a little more and the ropes will give!</p>
        </div>
        <div class="introItem blue introCut">
            <p>Cut the Rope</p>
        </div>
    </div>

    <div class="introSection">
        <div id="popIntroSquad" class="popUp">
            <p>Hostages have joined your squad:</p>

	   <?php foreach ($squad_npc as $i=> $npc){?>
              <div class="left">
                <img src="<?php echo base_url()."_images/squad/".$npc->picture; ?>" height="100" alt="<?php echo $npc->acc_username?>">
                <p align="center">Member <?php echo $i+1?></p>
            </div>
            <?php }?>


            <div class="introEquipSquad blue confirm">
                <p id ="add_squad">Add to Squad</p>
            </div>
        </div>
        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/003SurveyingPreview.jpg" alt="">
        <div class="introInfo">
            <p>Once freed, you quickly but quietly set about freeing the hostages.  You cover their mouths to keep
                them from making a sound and remove their shrouds and bonds.  You find that two of them are in good shape,
                though beaten, and one is fair but bleeding. The soldier you saw laying down is unfortunately no longer
                alive, so you grab his dog tags and vow vengeance.</p>
        </div>
        <div class="introAction blue">
            <p>Free Hostages</p>
        </div>
    </div>
    <div class="introSection">
        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/004GearingUpPreview.jpg" alt="">
        <div class="popIntroExplore popUp pos1">
            <p>You receive:</p>
            <div class="center">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/76.png" height="70" alt="">
                <p>Electric Cord</p>
            </div>

            <div class="introEquipWep blue confirm">
                <p>Take</p>
            </div>
        </div>
        <div class="popIntroExplore popUp pos2">
            <p>You receive:</p>
            <div class="center">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/65.png" height="70" alt="">
                <p>Broken Broom</p>
            </div>

            <div class="introEquipWep blue confirm">
                <p>Take</p>
            </div>
        </div>
        <div class="popIntroExplore popUp pos3">
            <p>You receive:</p>
            <div class="center">
                <img src="<?php echo base_url() . '_images/data/boosts/'; ?>9.png" height="70" alt="">
                <p>First Aid Kit</p>
            </div>

            <div class="introEquipWep blue confirm">
                <p>Take</p>
            </div>
        </div>
        <div class="popIntroContinue popUp pos4">
            <div class="center">
                <p>You found nothing of value.</p>
            </div>

            <div class="introContinue blue confirm">
                <p>Continue</p>
            </div>
        </div>

        <div class="explorePosition">
            <div class="introProgressBar">
                <div class="introProgress pos1" style="width:0%;"></div>
                <div class="exploreProgressText"><strong>Explore the Room</strong> - <span>0</span>% explored</div>
            </div>

            <div id="exploreBar">
                <div class="exploreButton blue">Explore</div>
            </div>
        </div>

        <div class="introInfo">
            <p>After freeing the prisoners you turn you attention towards the room and its contents hoping that
                you will discover something that you and your squad can use to escape.</p>
        </div>
        <div class="introAction blue">
            <p>Search</p>
        </div>
    </div>
    <div class="introSection">
        <div class="popIntroExplore popUp">
            <p>You receive:</p>
            <div class="center">
                <img src="<?php echo base_url() . '_images/data'; ?>/items/69-1.png" height="70" alt="">
                <p>Rusty Knife</p>
            </div>

            <div class="introEquipWep blue confirm">
                <p>Take</p>
            </div>
        </div>
        <div id="introCombat">
            <img class="guard" src="<?php echo base_url(); ?>_images/Introduction/005SneakAttackPreview.jpg" alt="">
            <div class="introCombatStats">
                <div class="combatantHealthBar" style="height:30px;background-color:#7C6B57;width:460px;float: left;">
                    <div class="combatantHealth" style="width:100%;height:30px;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#af444c', endColorstr='#682b2b');background: -webkit-gradient(linear, left top, left bottom, from(#AF444C), to(#682B2B));background: -moz-linear-gradient(top, #AF444C, #682B2B);"></div>
                    <div class="combatantHealthText" style="color: #F1ECE5;margin-top: -21px;text-align: center;height: 30px;font-size: 14px;">
                        <strong>Guard Health</strong> - <span>50</span>/50
                    </div>
                </div>
                <div class="bossLevelBar">
                    <div class="fightButton blue" style="height: 22px;width: 90px;margin-top: -1px;color: floralWhite;font-weight: bold;font-size: 14px;text-shadow: #355782 0px 1px 2px;text-align: center;padding: 4px 10px;float: left;visibility: hidden;">Fight</div>
                    <p class="bossLevelBarText">NPC level:</p>
                    <div class="bossLevel">
                        <img class="skull" src="<?php echo base_url(); ?>_images/icons/goldSkull.png" height="28" width="28" alt="">
                    </div>
                    <img src="<?php echo base_url(); ?>_images/icoInfo.png" width="18" style="float:right;padding:6px 5px 0px 0px;" alt="">
                </div>
            </div>
        </div>
        <div class="introInfo">
            <p>This is your chance to find freedom. Thoughts from your training stir in your mind... It is a
                soldier’s duty to escape whenever possible. You cannot wait for rescue.</p>
        </div>
        <div class="introAction blue introFight">
            <p>Attack the Guard</p>
        </div>
    </div>
    <div class="introSection">
        <div id="popDeadSquad" class="popUp dead">
            <div class="defeated" style="height:100px;width:100px;">
                <p><h1 class="defeatedText" style="font: 40px BebasNeueRegular;padding:10px 5px;">K.I.A!<h1></p>
                        </div>
                        <img src="<?php echo base_url(); ?>_images/squad/Portraits003SoldierThreePreview.jpg" height="100" alt="" class="combatant">
                        <p>Member 3</p>

                        <div class="introEquipSquad blue confirm" style="width:100px;">
                            <p>Escape!</p>
                        </div>
                        </div>

                        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/006Soldier4Shot.jpg" alt="">
                        <div class="introInfo">
                            <p>You hear more guards running towards you and your squad. Guns begin to fire as they notice what has happened.</p>
                        </div>

                        <div class="introAction blue cover">
                            <p>Take Cover!</p>
                        </div>
                        </div>
                        <div class="introSection">
                            <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/007EscapePreview.jpg" alt="">
                            <div class="introInfo">
                                <p>Now that you have escaped it is time to establish a new base.</p>
                            </div>
                            <div class="reward" style="float: right">
                                <form action="<?php echo base_url(); ?>intro/equip_first_gear" method="post">
                                    <input type="hidden" name="boost_id1" value="9" />
                                    <input type="hidden" name="item_id1" value="36" />
                                    <input type="hidden" name="item_id2" value="57" />
                                    <input type="hidden" name="item_id3" value="65" />
                                    <input type="hidden" name="item_id4" value="68" />
                                    <input type="hidden" name="item_id5" value="69" />
                                    <input type="hidden" name="item_id6" value="76" />
                                    <input class="introAction blue" type="submit" value="Establish Base">
                                </form>
                            </div>
                        </div>
                        </div>

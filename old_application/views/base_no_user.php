<body>
    <div id="container">
        <div id="content">
            <?php echo $content; ?>
            <div class="clear"></div>
        </div>

        <div id="account-menu">
            <div style="float:left;vertical-align:middle;">
                <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>_images/link-home.png" alt="Home" title="Main page" style="float:left;" /></a>

            </div>
            <div style="float:right;">
                <a style="color:orange;" href="<?php echo base_url(); ?>login">Log In</a>
            </div>
            <div class="clear"></div>
        </div>

        <div id="stats">

        </div>

        <!--<div id="intro" style="display:none;">
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
                        <img class="introImageBig" src="<?php echo base_url(); ?>@@/Introduction/001EstablishingShotPreview.jpg" alt="">
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
                        <div id="popIntroWeapon" class="popUp">
                                <p>You receive:</p>
                                <div class="center">
                                        <img src="<?php echo base_url() . '_images/data'; ?>/items/68.png" height="70" alt="">
                                        <p>Glass Shard</p>
                                </div>
                                
                                <div class="introEquipWep blue confirm">
                                        <p>Equip</p>
                                </div>
                        </div>
                        <img class="introCenterImage" src="<?php echo base_url(); ?>@@/Introduction/002CuttingTheRopePreview.jpg" alt="">
                        <div class="introInfo">
                                <p>“You pull and strain against your bonds.  No results.  But wait... You feel a small shard of glass 
                                on the ground behind you, within reach.</p>				
                        </div>
                        <div class="introAction blue">
                                <p>Take Glass</p>
                        </div>
                </div>
                <div class="introSection">
                        <div id="popIntroSquad" class="popUp">
                                <p>Hostages have joined your squad:</p>
                                <div class="left">
                                        <img src="<?php echo base_url(); ?>@@/squad/Portraits001MainPreview.jpg" height="100" alt="">
                                        <p>Member 1</p>
                                </div>
                                <div class="right">
                                        <img src="<?php echo base_url(); ?>@@/squad/Portraits002SoldierTwoPreview.jpg" height="100" alt="">
                                        <p>Member 2</p>
                                </div>
                                <div class="introEquipSquad blue confirm">
                                        <p>Add to Squad</p>
                                </div>
                        </div>
                        <img class="introImageBig" src="<?php echo base_url(); ?>@@/Introduction/003SurveyingPreview.jpg" alt="">
                        <div class="introInfo">
                                <p>Once freed, you quickly but quietly set about freeing the hostages.  You cover their mouths to keep 
                                them from making a sound and remove their shrouds and bonds.  You find that two of them are in good shape, 
                                though beaten, and one is fair but bleeding.  The soldier you saw laying down is unfortunately no longer 
                                alive, so you grab his dog tags and vow vengeance.</p>
                        </div>
                        <div class="introAction blue">
                                <p>Free Hostages</p>
                        </div>
                </div>
                <div class="introSection">
                        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/004GearingUpPreview.jpg" alt="">
                        
                        <div class="explorePosition">
                                <div class="introProgressBar">
                                        <div class="introProgress" style="width:0%;"></div>
                                        <div class="exploreProgressText"><strong>Explore the Room</strong> - 0% explored</div>
                                </div>
                                
                                <div id="exploreBar">
        <?php if (isset($account['id'])) { ?>
                                                {% if request.player.energy >= request.player.location.place.energy and not combatant and not boss and not event and not trader %}
                                                <form id="explore" action="<?php echo base_url(); ?>explore-place" method="post">
                                                        {% csrf_token %}
                                                        <input type="hidden" name="id" value="{{request.player.location.id}}" />
                                                        <input type="submit" id="exploreBtn" class="exploreButton blue" value="Explore" />
                                                </form>
        <?php } ?>
                                </div>
                        </div>
                        
                        <div class="introInfo">
                                <p>You sit silently against the wall, hoping that you go unnoticed for at least a short time. The
beatings are terrible and you get little to no sleep. It has been almost three months now... or was
it four. Time really plays tricks on you when you can’t see natural light. The only indication has
been the changing of the guard shifts and the different smells coming from the other room. Is
this how your life is going to end? Some tattered piece of flesh in a dark room in the desert?
Surely, there must be some other way...</p>
                        </div>
                        <div class="introAction blue">
                                <p>Search</p>
                        </div>
                </div>
                <div class="introSection">
                        <img class="introCenterImage" src="<?php echo base_url(); ?>_images/Introduction/005SneakAttackPreview.jpg" alt="">
                        <div class="introInfo">
                                <p>You sit silently against the wall, hoping that you go unnoticed for at least a short time. The
beatings are terrible and you get little to no sleep. It has been almost three months now... or was
it four. Time really plays tricks on you when you can’t see natural light. The only indication has
been the changing of the guard shifts and the different smells coming from the other room. Is
this how your life is going to end? Some tattered piece of flesh in a dark room in the desert?
Surely, there must be some other way...</p>
                        </div>
                </div>
                <div class="introSection">
                        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/006Soldier4Shot.jpg" alt="">
                        <div class="introInfo">
                                <p>You sit silently against the wall, hoping that you go unnoticed for at least a short time. The
beatings are terrible and you get little to no sleep. It has been almost three months now... or was
it four. Time really plays tricks on you when you can’t see natural light. The only indication has
been the changing of the guard shifts and the different smells coming from the other room. Is
this how your life is going to end? Some tattered piece of flesh in a dark room in the desert?
Surely, there must be some other way...</p>
                        </div>
                </div>
                <div class="introSection">
                        <img class="introImageBig" src="<?php echo base_url(); ?>_images/Introduction/007EscapePreview.jpg" alt="">
                        <div class="introInfo">
                                <p>You sit silently against the wall, hoping that you go unnoticed for at least a short time. The
beatings are terrible and you get little to no sleep. It has been almost three months now... or was
it four. Time really plays tricks on you when you can’t see natural light. The only indication has
been the changing of the guard shifts and the different smells coming from the other room. Is
this how your life is going to end? Some tattered piece of flesh in a dark room in the desert?
Surely, there must be some other way...</p>
                        </div>
                </div>
        </div>-->

        <div id="main-menu">
            <div id="logo">
                <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>_images/coinimpactLogo.png" alt="logo" width="220" /></a>
            </div>

            <div style="margin-right:10px">

            </div>
            <div class="clear"></div>
        </div>

        <div id="submenu">
            <
        </div>
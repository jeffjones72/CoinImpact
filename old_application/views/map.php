<div class="box" style="height: 410px;">
    <img class="hideIcon" onclick="javascript:hide('location');" src="<?php echo base_url(); ?>_images/marker.png" alt="" height="25" width="25"/>

    <div class="locationTitleTab" onclick="javascript:show('location');">Map Info</div>
    <div id="map">
        <img src="<?php echo base_url(); ?>_images/map_color.jpg" alt="" />
        <?php foreach ($places as $place) {?>
            <div class="mapPosition" id="mapPos<?=$place->id?>">
                <div class="mapTravelBtn">
                    <div class="mapTravelBtnHighlight"></div>
                    <div class="mapTravelBtnIcon"></div>
                </div>
                <div class="mapInfo leftarrowdiv" id="map<?=$place->id?>">
                    <p><?=$place->name?></p>
                    <?php if(Place::$ids['enemy_safehouse'] != $place->id){ ?>
                    <p>Travel cost: <?php echo $place->energy; ?> energy</p>
                    <p>Level: 1</p>
                    <?php $req_for_place = $player->getRequirementFor($place);?>
                    <?php $p_place = $player->getPlace($place);?>
                    <?php if($req_for_place && $req_for_place instanceof Place){?>
                    <p style="color:red">You need to complete <?=$req_for_place->name?> in order to go here.</p>
                    <?php }else if($req_for_place && $req_for_place instanceof Boss){?>
                    <p style="color:red">You need to kill <?=$req_for_place->name?> in order to go here.</p>
                    <?php }?>
                    <?php if(!$req_for_place) {?>
                    <div style='height:35px'>
                    <?php if($player->canExplore($place)) {?>
                    <div class="mapProgressBg">
                        <div class="mapProgress" style="width:<?=$p_place->progress?>%;"></div>
                        <div class="exploreProgressText"><?=$p_place->progress?>% explored</div>
                    </div>
                    <?php } ?>
                    <?php if ($place->id != $player->place_id && $player->canGoTo($place)) { ?>
                        <form action="<?php echo base_url(); ?>action/travel" method="post">
                            <input type="hidden" name="place_id" value="<?=$place->id?>" />
                            <input type="submit" class="mapTravel blue" value="Travel" autocomplete="off"/>
                        </form>
                    <?php } else { ?>
                        <input type="button" class="mapTravel gray" value="Travel" autocomplete="off"/>
                    <?php } ?>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="mapNavigation">
            <!--
            <div class="mapPrevious">
                    <a href="">Previous area</a>
            </div>

            <div class="mapNext">
                    <a href="">Next Area</a>
            </div>
            -->

            <div class="mapCenter">
                <a href=""><?php echo $player->p_place->place->name; ?></a>
            </div>
        </div>
    </div>
    <div id="location" class="mapLocation">
        <p class="bold"><?php echo $player->p_place->place->description; ?></p>
    </div>
</div>
<!--
<h2 class="page">Map of {{request.player.location.place.zone}}</h2>
<div class="box">
<img id="img" src="{{SITE_URL}}/explore/image" alt="" onmousemove="move(this)" />

<table class="list">
{% for place in places %}
        <tr>
                <td>{{place.name}}</td>
        </tr>
{% endfor %}
</table>

{% for place in player_places %}
        {% if not place = request.player.location and request.player.energy >= place.required_energy %}
        <form action="{{SITE_URL}}/select-place" method="post">
                {% csrf_token %}
                <input type="hidden" name="id" value="{{place.id}}" />
                <input type="submit" class="button blue" value="{{place.place.name}} ({{place.required_energy}} Energy)" />
        </form>
        {% endif %}
{% endfor %}
</div>
-->

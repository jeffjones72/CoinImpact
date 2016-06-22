<script type="text/javascript">
$(document).ready(function() {
    $('#completedBtn').click(function(){
        showonlyone('completed');
    });
    $('#uncompletedBtn').click(function(){
        showonlyone('uncompleted');
    });
});
</script>
<div class="box" style="min-height: 400px;">
	<h1 align="center">Coming Soon</h1>
	<!--
    <div class="brownSectionBtn rightmostButton" id="completedBtn"> Completed </div>
    <div class="blueSectionBtn" id="uncompletedBtn"> Uncompleted </div>
    <div id="completed" class="section completedMissionsBox missionsBox" style="display: block;">
    <?php foreach($player->getCompletedMissions() as $p_mission) {?>
        <div style="background-color:white;padding:10px;margin-bottom:10px;">
            <img src="<?php echo base_url(); ?>_images/data/missions/<?=$p_mission->mission->id?>s.png" alt="" style="float:right;margin: 0 0 10px 10px;"/>
            <h1><?=$p_mission->mission->name?></h1>
            <p style="height:4em;"><?=$p_mission->mission->description?></p>
        </div>
    <?php } ?>
    <?php if(!sizeof($player->getCompletedMissions())) { ?>
        <div style="background-color:white;padding:10px;margin-bottom:10px;">
        No completed missions
        </div>
    <?php }?>
    </div>
    <div id="uncompleted" class="section uncompletedMissionsBox missionsBox" style="display: none;">
    <?php foreach($player->getUncompletedMissions() as $p_mission) {?>
        <div style="background-color:white;padding:10px">
            <img src="<?php echo base_url(); ?>_images/data/missions/<?=$p_mission->mission->id?>s.png" alt="" style="float:right;margin: 0 0 10px 10px;"/>
            <h1><?=$p_mission->mission->name?></h1>
            <p style="height:4em;"><?=$p_mission->mission->description?></p>
            <div class="progress-box"><?=$p_mission->getProgress()?>%</div>
        </div>
        <div>
            <?php if($p_mission->getEventObjectivesCount()) {?>
            Events: <?=$p_mission->getCompletedEventObjectivesCount()?> / <?=$p_mission->getEventObjectivesCount()?>
            <?php }?>
            <?php if($p_mission->getCombatantObjectivesCount()) {?>
            Combatants: <?=$p_mission->getCompletedCombatantObjectivesCount()?> / <?=$p_mission->getCombatantObjectivesCount()?>
            <?php }?>
            <?php if($p_mission->getItemObjectivesCount()) {?>
            Items: <?=$p_mission->getCompletedItemObjectivesCount()?> / <?=$p_mission->getItemObjectivesCount()?>
            <?php }?>
            <?php if($p_mission->getThingObjectivesCount()) {?>
            Things: <?=$p_mission->getCompletedThingObjectivesCount()?> / <?=$p_mission->getThingObjectivesCount()?>
            <?php }?>
        </div>
    <?php }?>
    <?php if(!sizeof($player->getUncompletedMissions())) { ?>
        <div style="background-color:white;padding:10px">
        No uncompleted missions
        </div>
    <?php }?>
    </div>
    -->
</div>
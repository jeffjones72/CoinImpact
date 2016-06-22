<div class="box team" style="height: 510px;">

	<div class="yellowSectionBtn" style="margin-right: 10px;">
		<a href="<?php echo base_url().'team/'?>"> TEAM</a>
	</div>
	<div class="greenSectionBtn"
		onclick="javascript:showonlyone('section1');">
		<a href="<?php echo base_url().'team#section1'?>">SQUAD</a>
	</div>


	<div id="section2" class="teamSection section" name="section">
		<div class="team_message">
    <?php
      echo $message;
    ?>
</div>

	</div>
</div>
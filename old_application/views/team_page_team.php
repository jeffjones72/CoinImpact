            <?php //foreach ($player->queryFriends() as $teamFriend):?>
            <?php //$teamPlayer = $teamFriend->friend; ?>
            <?php 
            $teamPlayer = $player; 
            ?>
            <?php for($i=0;$i<8;$i++) {?>
            <div class="teamMembersBox">
                <div>
                    <div id="">
                        <p style="margin-top: 0px; margin-bottom: 0px;"><a href="" class="star"></a></p>
                    </div>
                    <form>
                        <input class="teamBtnGreen" value="Add to Squad"></input>
                    </form>
                    <!--
                    <form action="<?php echo base_url(); ?>remove-friend" method="post">
                        <input type="hidden" name="id" value="<?=$teamPlayer->id;?>" />
                        <input type="submit" class="teamBtnRed" value="Remove From Squad"></input>
                    </form>
                    -->
                </div>
                <div style="padding-top:26px;">
                    <div class="squadMemberName" style="clear:both;">
                        <?php
                        $username = $teamPlayer->account->username;
                        if (!$username)
                        {
                            $username = $teamPlayer->first_name . ' ' . $teamPlayer->last_name;
                        }
                        echo $username;
                        ?>
                    </div>

                    <div class="squadMemberName">
                        Lvl: <?=$teamPlayer->level_id?>
                    </div>

                    <div class="squadMemberImage">
                        <img class="squadMemberLevelImage" src="<?php echo base_url(); ?>_images/data/ranks/<?=$player->rank->rank;?>t.png" width="37px" height="50px"/>
                        <img class="squadMemberProfileImage" src="<?php echo base_url(); ?>_images/male.jpg" width="80px" height="80px"/>
                    </div>

                    <form>
                        <input class="teamBtnBlue blue" value="Send Gift"/>
                        <input class="teamBtnBlue blue" value="Profile" />
                        <!--
                        href="<?php echo base_url(); ?>players/<?=$teamPlayer->id;?>">Profile</a>
                    -->
                    </form>
                </div>
            </div>
            <?php }?>
            <?php //endforeach;?>

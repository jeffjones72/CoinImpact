<script>
function loadPageTeam()
{
    var sort = $('#team_sort').val();
    var page = $('#team_page').val();
    $('#teamPageContainer').empty();
    $('#teamPageContainer').load('/team/page_team/' + sort +'/' + page);
    $('#teamPaginationContainer').empty();
    $('#teamPaginationContainer').load('/team/pagination_team/' + sort +'/' + page);    
}
$(document).ready(function(){
    loadPageTeam();
});
</script>
<div class="box team" style="height: 510px;">

    <div class="yellowSectionBtn" onclick="javascript:showonlyone('section2')" style="margin-right:10px;">
        TEAM
    </div>
    <div class="greenSectionBtn" onclick="javascript:showonlyone('section1');">
        SQUAD
    </div>

    <div class="teamSquadSection section" name="section" id="section1" style="display:none">
        <h2>Your Squad</h2>
        <div class="teamNav">
            <form class="filterMenu">
                <div class="filterCenter">
                    Sort by:
                    <select>
                        <option value="">Level</option>
                    </select>
                </div>
            </form>
        </div>
        <div>
            <div class="squadMembersBox">
                <div class="squadMemberName">
                    lol_very_long_player_name_here_lol
                </div>
                <div class="squadMemberName">
                    Lvl: 13
                </div>
                <div class="squadAtk">
                    <b>Attack:</b>
                    <div class="squadStatNumbers">
                        0
                    </div>
                </div>
                <div class="squadDef">
                    <b>Defense:</b>
                    <div class="squadStatNumbers">
                        0
                    </div>
                </div>
            </div>
        </div>
    </div>          

    <div class="teamSection section" name="section" id="section2">			
        <h2>Your Team</h2>
        <div class="teamNav">
            <form class="filterMenu">
                <div class="filterSort">
                    Sort by:
                    <select id="team_sort">
                        <option value="favorite">Favorite</option>
                        <option value="power">Power</option>
                        <option value="newest">Newest</option>
                        <option value="level">Level</option>
                    </select>
                </div>
                <input type="hidden" id="team_page" value="1" />
            </form>
             <form id="teamPaginationContainer" class="filterFormPagination">
                <!-- SEE team_pagination_team -->
            </form>
       </div>
        <div id="teamPageContainer" style="clear:both">
            <!-- SEE team_page_team -->
        </div>
    </div>	

</div>

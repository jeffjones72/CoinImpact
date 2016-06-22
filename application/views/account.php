<script>
function loadInvitation(){
    var id = $('#invitation_account_id').val();
    $('#form_invitation_account_id').val(id);
    $('#invitationAccountInfo').empty();
    $('#invitationAccountInfo').load('/account/load_invitation/' + id);
    $('#popInvitation').show();
    return false;
}
</script>
<div class="box">
    <div style="padding-top: 50px;">
        <h2 class="page">Account Settings</h2>

        <div id="popInvitation" class="popUp">
            <div class="closeBtn closeStorePos" onclick="javascript:toggle('popInvitation')"></div>
            Do you want to save Invited by Account's ID?
            <form action="<?php echo base_url(); ?>account/save_invite_email" method="post">
                <input type="hidden" id="form_invitation_account_id" name="invitation_account_id" value="">
                <input class="left blue" type="submit" value="Yes">
                <input class="right red" type="button" onclick="javascript:toggle('popInvitation')" value="No">
                <div id="invitationAccountInfo"></div>
            </form>
        </div>

        <table class="list">
            <tr>
                <th>Account ID</th>
                <td><?php echo str_pad($account->id,6,'0',STR_PAD_LEFT); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?=$account->username?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $account->first_name . ' ' . $account->last_name; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $account->email; ?></td>
            </tr>
            <tr>
                <th>Joined</th>
                <td><?php echo $account->date_joined; ?></td>
            </tr>
            <tr>
                <th>Last login</th>
                <td><?php echo $account->last_login; ?></td>
            </tr>
            <?php if ($account->facebook_id != '') { ?>
                <tr>
                    <th>Facebook ID</th>
                    <td><?php echo $account->facebook_id; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th>Recruits</th>
                <td><?php echo $account_o->getRecruitCount(); ?> people</td>
            </tr>
            <?php if ($account_o->invitation_account_id): ?>
                <tr>
                    <th>Invited by</th>
                    <td><?php echo str_pad($account_o->invitation_account_id,6,'0',STR_PAD_LEFT); ?></td>
                </tr>
            <?php endif; ?>
        </table>

        <?php if (!$account_o->invitation_account_id): ?>
        <br>
        <span class="error"><?php echo $this->session->flashdata('message');?></span>
        <br>
        <label>Invited by Account's ID</label>
        <div class="infoBox invitationInfoBox">
            <input type="text" id="invitation_account_id" name="invitation_account_id"/>
            <input type="submit" value="Save" name="submit" class="blueBtnS blue" style="float:right" onclick="loadInvitation(); return false;">
        </div>
        <?php endif; ?>

        <a href="<?php echo base_url(); ?>password">change password</a>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#password").passStrength({
            userid:	"#email"
    });
});
</script>
<div class="box" id="accountCreate">
    <div class="splash">
        <div class="round transparent">
            <h1>Registration</h1>

            <?php echo form_open('join'); ?>
            
            <div>
                <label for="username">Username</label>
                <input id="username" name="username" value="<?php echo set_value('username'); ?>" required>
                <span class="error"><?php echo form_error('username'); ?></span>
            </div>
            <div>
                <label for="first_name">First name</label>
                <input id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>" required>
                <span class="error"><?php echo form_error('first_name'); ?></span>
            </div>
            <div>
                <label for="last_name">Last name</label>
                <input id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>" required>
                <span class="error"><?php echo form_error('last_name'); ?></span>
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                <span class="error"><?php echo form_error('email'); ?></span>
            </div>
            <div>
                <label for="password">Password (at least 6 characters)</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?php echo form_error('password'); ?></span>
            </div>
            <div>
                <label for="password2">Confirm password</label>
                <input type="password" id="password2" name="password2" required>
                <span class="error"><?php echo form_error('password2'); ?></span>
            </div>
            <div>
                <input id="submit" class="button blue" name="submit" type="submit" value="Sign up" />
                </form>
            </div>
        </div>
    </div>
</div>

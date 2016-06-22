<div class="box" id="login">
    <div class="splash">
        <div class="round transparent">
            <?php if($just_registered) { ?>
            <p style='color:green'>You have successfully registered</p>
            <?php } ?>
            <?php if ($login_error) { ?>
                <span class="error">*Incorrect login</span>
            <?php } ?>
            <h1>Log In</h1>
            <form name="loginForm" id="loginForm" action="<?php echo base_url(); ?>login/process" method="post">
                <!-- csfr token -->
                <div>
                    <label for="email" style="padding-top: 10px;">Email</label>
                    <input id="email" name="email" value="" />
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" />
                </div>
                <div>
                    <input class="login blue" type="submit" name="submit" value="Log In" />
                    <input class="createAccount blue" value="Create An Account" onclick="location.href = '<?php echo base_url(); ?>join'" />
                </div>
            </form>
            <script>
                $("#email").focus();
            </script>

            <a href="https://www.facebook.com/dialog/oauth?client_id={{FACEBOOK_APP_ID}}&redirect_uri=http://coinimpact.com{{SITE_URL}}/facebook/access_token&scope=email,publish_stream">
                <img src="<?php echo base_url(); ?>_images/fb-connect-large.png" alt="Connect with Facebook" height="40" width="240"></a>
        </div>
    </div>
</div>


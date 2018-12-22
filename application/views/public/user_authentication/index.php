

            <div class="login-box-body">
                <div style="text-align:center;"><img src="<?php echo base_url(); ?>assets/toolcals/images/logo.jpg" width="250px" height="auto" /></div>


<?php //var_dump($userData); ?>
               

                <div style="text-align:center;">
                    <!--<p>ĐĂNG NHẬP HỆ THỐNG</p>-->
                    <!-- Display sign in button -->
                    <?php //echo $loginURL; ?>
                    <p>
                        <a href="<?php echo $google_login_url; ?>"><img src="<?php echo base_url('assets/toolcals/images/google_signin_buttons/web/1x/btn_google_signin_dark_pressed_web.png'); ?>" width="190px" /></a>
                    </p>
                    <p>
                        <a href="<?php echo $facebook_login_url; ?>"><img src="<?php echo base_url('assets/toolcals/images/facebook/flogin.png'); ?>" width="190px" /></a>
                    </p>
                </div>
            </div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-body">
                                    <div class="col-md-3">
                                        <!-- Display Google profile information -->
                                        <?php if(empty($userData['profile_pic'])){ ?>
                                            <img src="<?php echo base_url($avatar_dir . '/m_001.png'); ?>" width="200px"/>
                                        <?php }else{ ?>
                                            <img src="<?php echo $userData['profile_pic']; ?>" width="200px"/>
                                        <?php } ?>
                                        
                                    </div>
                                    <div class="col-md-8">
                                        <!-- <p><b>Google ID: </b><?php echo $userData['oauth_uid']; ?></p> -->
                                        <p><b>Tên: </b><?php echo $userData['name'] ?></p>
                                        <p><b>Email: </b><?php echo $userData['email']; ?></p>
                                        <!-- <p><b>Gender: </b><?php echo $userData['gender']; ?></p> -->
                                        <!-- <p><b>Locale: </b><?php echo $userData['locale']; ?></p> -->
                                        <!-- <p><b>Logged in with Google: </b><a href="<?php echo $userData['link']; ?>" target="_blank">Click to visit Google+</a></p> -->
                                        <!-- <p><b><a href="<?php echo base_url(); ?>">Về trang chủ</a></b></p>
                                        <p><b><a href="<?php echo base_url().'user_authentication/logout'; ?>">Sign out</a></b></p> -->
                                    </div>
                                
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>





<section id="contact-page" style="padding-top: 50px;">
    <div class="container">
        <div class="center">
            <h2>Profile Information of <span style="text-decoration: underline;"><?php echo $info['first_name'] . " " . $info['last_name'] ?></span></h2>
        </div>
        <div class="row wow fadeInDown">
            <div class="status alert alert-success alert-dismissable" style="<?php if (!isset($succes)) echo 'display: none'?>"><?php echo $succes ?></div>
            <div class="status alert alert-danger alert-dismissable" style="<?php if (!isset($error)) echo 'display: none'?>"><?php echo $error ?></div>
            <div class="status alert alert-warning alert-dismissable" style="<?php if (!isset($notification)) echo 'display: none'?>"><?php echo $notification ?></div>
            <div class="status alert alert-info alert-dismissable">For better fit please upload your profile picture of 300*300 dimension.</div>

            <form class="login-form" enctype="multipart/form-data" method="post" action="">
                <div class="col-sm-6">
                    <div class="form-group post_reply">
                        <label for="profile_picture" style="text-decoration: underline;">Update your profile picture:</label><br/>
                        <img src="<?php echo ($info['profile_picture']) ? $info['profile_picture'] : ( base_url() . 'static/images/blog/avatar3.png' ); ?>" class="img-circle" alt="" style="margin-bottom: 10px; height: 200px !important; width: 200px !important;"/>
                        <input type="file" name="profile_picture" placeholder="Update Your Profile Picture" id="profile_picture" class="form-control" style="box-sizing: initial;">
                    </div>
                    <div class="form-group">
                        <label for="password">Current Password *</label>
                        <input type="password" name="currentPassword" id="currentPassword" class="form-control" required="required" placeholder="Enter your current password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password (If you want to update the existing password)</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter your new password or leave blank">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm your New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your new password or leave blank">
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo $info['first_name'] ?>" class="form-control" placeholder="Enter your first name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo $info['last_name'] ?>" class="form-control" placeholder="Enter your last name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email (Changing your email would require email verification)</label>
                        <input type="email" name="email" id="email" value="<?php echo $info['email'] ?>" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg">Update Information</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!--/.container-->
</section><!--/#contact-page-->

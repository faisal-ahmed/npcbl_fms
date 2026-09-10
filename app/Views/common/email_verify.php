<section id="about-us">
    <div class="container">
        <div class="center wow fadeInDown">
            <h2>Welcome to Automated Content-Searching Machine</h2>
            <div class="status alert alert-danger alert-dismissable" style="<?php if (!isset($error)) echo 'display: none'?>"><?php echo $error ?></div>
            <div class="status alert alert-success alert-dismissable" style="<?php if (!isset($success)) echo 'display: none'?>"><?php echo $success ?></div>
            <div class="status alert alert-info alert-dismissable" style="<?php if (!isset($password)) echo 'display: none'?>">
                Please use the following auto generated as your password to <a href="<?php echo base_url() ?>index.php/Auth/login" target="_blank">login</a>. Once you login then don't forget to reset your password.
                Your Code is <strong><?php echo $password; ?></strong>.
            </div>
        </div>

        <!-- about us slider -->
        <div id="about-slider">
            <div id="carousel-slider" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators visible-xs">
                    <li data-target="#carousel-slider" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-slider" data-slide-to="1"></li>
                    <li data-target="#carousel-slider" data-slide-to="2"></li>
                </ol>

                <div class="carousel-inner">
                    <div class="item active">
                        <img src="<?php echo base_url() ?>static/images/slider_one.jpg" class="img-responsive" alt="">
                    </div>
                    <div class="item">
                        <img src="<?php echo base_url() ?>static/images/slider_one.jpg" class="img-responsive" alt="">
                    </div>
                    <div class="item">
                        <img src="<?php echo base_url() ?>static/images/slider_one.jpg" class="img-responsive" alt="">
                    </div>
                </div>

                <a class="left carousel-control hidden-xs" href="#carousel-slider" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>

                <a class=" right carousel-control hidden-xs"href="#carousel-slider" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div> <!--/#carousel-slider-->
        </div><!--/#about-slider-->
    </div>
</section>
<?php
if($this->login == true){
    if(isset($_GET["key"])&& $_GET["key"] == "noman143Nomantufail45"){?> <script> setCookie('zeenomTestingCookie','zeenomTestingCookie',180); </script><?php }
    ?>
    <div class="col-lg-12">
        <ul class="nav nav-pills nav-stacked" style="top: 30px; position: relative;">
            <li class="<?php if($page == 'account'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."account.html"; ?>"style="color: ">Account Info</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'profile'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."profile.html"; ?>"style="color: ">My Profile</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'history'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."history.html"; ?>" style="color: ">My History</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'referrals'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."referrals.html"; ?>" style="color: ">My Referrals</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'withdraw'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."withdraw.html"; ?>" style="color: ">Withdraw Cash</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'viewAds'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."viewAds.html"; ?>" style="color: ">View Ads</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'upgrade'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."upgrade.html"; ?>" style="color: ">Upgrade Account</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'advertise'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."advertise.html"; ?>" style="color: ">Advertise</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'news'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."news.html"; ?>" style="color: ">News</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'faqs'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."faqs.html"; ?>" style="color: ">FAQ's</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'logout'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."logout.html"; ?>" style="color: ">Logout</a></li>
            <li class="divider"></li>
        </ul>
    </div>
    <section class="col-lg-12" style="position: relative; margin-top: 30px;">
        <a href="https://www.facebook.com/pages/Surf4earn/360111967447244#" target="_blank"><img src="<?php echo images()."fb1.png"; ?>"></a>
    </section>
<?php
}else{
    ?>
    <section class="col-lg-12">
        <ul class="nav nav-pills nav-stacked" style="top: 30px; position: relative;">
            <li class="<?php if($page == 'home'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."home.html"; ?>"style="color: ">Home</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'viewAds'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."viewAds.html"; ?>"style="color: ">View Ads</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'login'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."login.html"; ?>"style="color: ">Login</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'register'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."register.html"; ?>" style="color: ">Register</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'advertise'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."advertise.html"; ?>" style="color: ">Advertise</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'news'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."news.html"; ?>" style="color: ">News</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'faqs'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."faqs.html"; ?>" style="color: ">FAQ's</a></li>
            <li class="divider"></li>
            <li class="<?php if($page == 'tos'){echo "active";}?>"><a tabindex="-1" href="<?php echo base_url()."tos.html"; ?>" style="color: ">TOS</a></li>
        </ul>
    </section>
    <section class="col-lg-12" style="position: relative; margin-top: 30px;">
        <a href="https://www.facebook.com/pages/Surf4earn/360111967447244#" target="_blank"><img src="<?php echo images()."fb1.png"; ?>"></a>
    </section>
    <?php

}
<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 5/3/15
 * Time: 8:52 AM
 */
?>

<ul id="myTab" class="nav nav-pills" style="width: 100%;">
    <?php if($trip_master_type == 'primary'){ ?>
        <li class="<?php if($page == 'primary'){echo "active";} ?>"><a href="<?= base_url()."trips/show/primary";?>"><i class="fa fa-eye"></i> View Trips</a></li>
        <li class="<?= (($trip_type == 'self_mail')?'active':'') ?>"><a href="<?= base_url()."trips/make";?>"><i class="fa fa-plus-circle"></i> Self / Mail</a></li>
        <li class="<?= (($trip_type == 'general')?'active':'') ?>"><a href="<?= base_url()."trips/make/local";?>"><i class="fa fa-plus-circle"></i> General Trip</a></li>
        <li class="<?= (($trip_type == 'local_self')?'active':'') ?>"><a href="<?= base_url()."trips/make/local_self";?>"><i class="fa fa-plus-circle"></i> Local Trip(self)</a></li>
        <li class="<?= (($trip_type == 'general_local')?'active':'') ?>"><a href="<?= base_url()."trips/make/general_local";?>"><i class="fa fa-plus-circle"></i> General Local</a></li>
    <?php }else if($trip_master_type == 'secondary'){  ?>
        <li class="<?php if($page == 'secondary'){echo "active";} ?>"><a href="<?= base_url()."trips/show/secondary";?>"><i class="fa fa-eye"></i> View Trips</a></li>
        <li class="<?= (($trip_type == 'local_company')?'active':'') ?>"><a href="<?= base_url()."trips/make/local_cmp";?>"><i class="fa fa-plus-circle"></i> Local Trip(cmp)</a></li>
    <?php }else if($trip_master_type == 'secondary_local'){  ?>
        <li class="<?php if($page == 'secondary_local'){echo "active";} ?>"><a href="<?= base_url()."trips/show/secondary_local";?>"><i class="fa fa-eye"></i> View Trips</a></li>
        <li class="<?= (($trip_type == 'secondary_local')?'active':'') ?>"><a href="<?= base_url()."trips/make/secondary_local";?>"><i class="fa fa-plus-circle"></i> Secondary Local Trip</a></li>
    <?php } ?>
</ul>
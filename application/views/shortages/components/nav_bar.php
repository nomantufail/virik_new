<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/9/2015
 * Time: 12:19 AM
 */
?>
<div class="col-lg-12">
    <ul id="myTab" class="nav nav-pills" style="border-bottom: 0px solid;">
        <li class="<?php if($this->uri->segment(2) == 'create'){echo "active";} ?>">
            <a href="<?= base_url().$this->router->fetch_class()."/create" ?>"><i class="fa fa-fw fa-plus"></i> Add Shortages</a>
        </li>
        <li class="<?php if($this->uri->segment(3) == 'destination'){echo "active";} ?>">
            <a href="<?= base_url().$this->router->fetch_class()."/show/destination" ?>"><i class="fa fa-fw fa-list"></i> Destination</a>
        </li>
        <li class="<?php if($this->uri->segment(3) == 'decanding'){echo "active";} ?>">
            <a href="<?= base_url().$this->router->fetch_class()."/show/decanding" ?>"><i class="fa fa-fw fa-list"></i> Decanding</a>
        </li>
    </ul>
</div>
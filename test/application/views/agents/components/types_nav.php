<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/6/15
 * Time: 12:52 AM
 */
?>

<section class="col-md-12">
    <ul id="myTab" class="nav nav-pills page-header" style="border-bottom: 0px solid;">
        <li class="<?php if($section == 'suppliers'){echo "active";} ?>" ><a href="<?= base_url()."agents/suppliers/?".$_SERVER['QUERY_STRING']?>">Suppliers</a></li>
        <li class="<?php if($section == 'customers'){echo "active";} ?>"><a href="<?= base_url()."agents/customers/?".$_SERVER['QUERY_STRING']?>">Customers</a></li>
    </ul>
</section>
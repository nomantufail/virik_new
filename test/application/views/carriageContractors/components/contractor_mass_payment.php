<div class="white-popup">    <script>        function check_payment()        {            var payable = document.getElementById("payable").value;            if(payable == 0){                return false;            }            return true;        }    </script>    <div class="row">        <div class="col-lg-12">            <form method="post" on onsubmit="return check_payment()">                <div class="col-lg-12">                    <label>Payment Date: </label>                    <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" class="" style="width: 200px;">                    <hr>                </div>                <?php                $total_remaining = 0;                foreach($trips as $trip){                    foreach($trip->trip_related_details as $trip_detail){                        $total_remaining += ($trip_detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()) - $trip_detail->get_paid_to_contractor());                    }                }                ?>                <?php $total_remaining = round($total_remaining, 3) ?>                <h4>Total Cash Payable: <i><?= $this->helper_model->money($total_remaining); ?></i></h4>                <input type="hidden" value="<?= $trip_ids_str ?>" name="trip_ids">                <input type="hidden" value="<?= $total_remaining ?>" name="payable" id="payable">                <hr>                <div class="col-lg-12">                    <input type="submit" name="add_mass_payment" value="Confirm Payment" class="btn btn-success center-block">                </div>            </form>        </div>    </div></div>
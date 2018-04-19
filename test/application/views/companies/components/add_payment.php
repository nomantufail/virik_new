
<div class="white-popup">
    <div class="row">
        <div class="col-lg-12">
            <form action="<?= base_url()."companies/accounts/".$company_id; ?>" method="post">
            <div class="col-sm-5" style="margin-top: 5px;">
                <input type="hidden" name="form_id" value="<?= $form_id; ?>">
                <input type="hidden" name="company_id" value="<?= $company_id; ?>">
                <input type="hidden" name="trip_id" value="<?= $trip_id; ?>">
                <input class="form-control" required="required" type="date" name="payment_date" value="<?= $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateString(); ?>">
            </div>
            <div class="col-sm-5" style="margin-top: 5px;">
                <input class="form-control" required="required" type="text" name="amount" value="" placeholder="amount here">
            </div>
            <div class="col-sm-2" style="margin-top: 5px;"><input type="submit" style="width: 100%;" class="btn btn-success" value="Add"></div>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <tr>
                    <th>Payment Date</th>
                    <th>Amount</th>
                </tr>
                <?php
                    foreach($previous_payments as $previous_payment){
                        ?>
                        <tr>
                            <td><?= $previous_payment->payment_date ?></td>
                            <td><?= $previous_payment->amount ?></td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
    </div>
</div>

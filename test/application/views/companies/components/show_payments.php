

<div class="white-popup">
    <script>
        sorttable.makeSortable(document.getElementById('payments_table'));
    </script>
    <div class="row">
        <section class="text-center">
            <h3><?= ucwords($agent) ?> Payments</h3>
        </section>
    </div>

    <div class="row" style="margin-top: 10px;">

        <div class="col-lg-12">

            <table class="table table-bordered sortable" id="payments_table">
                <thead>
                <tr>

                    <th>Payment Date</th>

                    <th>Amount</th>

                </tr>
                </thead>
                <tbody>
                <?php

                foreach($previous_payments as $previous_payment){

                    ?>

                    <tr>

                        <td><?= $this->carbon->createFromFormat('Y-m-d',$previous_payment->payment_date)->toFormattedDateString() ?></td>

                        <td><?= $this->helper_model->money($previous_payment->amount) ?></td>

                    </tr>

                <?php

                }

                ?>
                </tbody>
            </table>

        </div>

    </div>

</div>


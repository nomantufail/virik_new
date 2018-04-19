

<div class="white-popup" style="max-width: 500px;">

    <div id="">
        <script>

        </script>
        <form method="post">
            <div class="row" style="">
                <div class="col-lg-12 text-center" style="color: #269abc">
                    <h3>Edit Shortage</h3><hr>
                </div>
                <div class="col-md-12 center-block">
                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage ID</label>
                        <div class="col-lg-8">
                            <?= $shortage->id ?>
                            <input type="hidden" required="required" type="number" min="1" name="shortage_id" value="<?= $shortage->id ?>">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage Date</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="shortage_date" type="date" value="<?= $shortage->date ?>" required="required" name="date">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Quantity</label>
                        <div class="col-lg-8">
                            <input type="number" step="any" class="form-control" required="required" name="quantity" value="<?= $shortage->quantity ?>">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Rate</label>
                        <div class="col-md-8">
                            <input type="number" name="rate" step="any" value="<?= $shortage->rate ?>">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-lg-8">
                            <button type="submit" name="update_shortage" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>


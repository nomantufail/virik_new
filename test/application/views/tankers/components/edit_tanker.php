
<div class="white-popup">
    <div class="row">
        <div class="col-lg-12">
            <form action="" method="post">
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">Capacity</label>
                    <input type="hidden" name="form_id" value="<?= $form_id; ?>">
                    <input type="hidden" name="tanker_id" value="<?= $tanker_id; ?>">
                    <div class="col-lg-8">
                        <input class="form-control" required="required" type="text" name="capacity" value="<?= $tanker->capacity ?>" placeholder="Capacity here">
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-lg-8">
                        <div class="col-sm-8" style="margin-top: 5px;"><input type="submit" name="save_tanker" style="width: 100%;" class="btn btn-success" value="Save"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

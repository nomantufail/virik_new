<?php
/**
 * Created by PhpStorm.
 * User: noman
 * Date: 2/10/15
 * Time: 9:04 PM
 */

for($row_counter = 1; $row_counter<7; $row_counter++){
    ?>
    <tr id="row_<?= $row_counter ?>" style="display: <?= ($row_counter != 1)?"none":"table"; ?>; width: 100%;">
        <td>
            <table class="table" style="margin-top: 15px; width: 100%;">
                <tr style="border-bottom: 1px solid lightgray;">
                    <td colspan="3" style="width: ;"><b>Route</b><br>
                        <select name="route_<?= $row_counter ?>" onchange="i_think_route_changed('1',0,<?= $row_counter?>)" class="route route_select" style="width: 100%;">
                            <?php
                            foreach($primary_routes as $route){
                                ?>
                                <option value="<?= $route->sourceId."_".$route->destinationId."_".$route->productId; ?>"><?= $route->source." To ".$route->destination." ( ".$route->product." )"; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <b>Freight/Unit (cmp)</b><br>
                        <input id="company_freight_unit_<?= $row_counter ?>" name="company_freight_unit_<?= $row_counter ?>" <?= (($row_counter < 2)?"required='required'":'') ?> class="company_freight_unit_1" style="width: 80px;" type="number" step="any">
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid lightgray;">
                    <td colspan="2">
                        <b>Quantity</b><br>
                        <input name="initial_product_quantity_<?= $row_counter ?>" class="initial_product_quantity_1" onkeyup="i_think_quantity_changed()"  onchange="i_think_quantity_changed()" style="width: 100px;" type="number" step="any"  size="10">
                    </td>
                    <td>
                        <b>Price/Unit</b><br>
                        <input disabled name="price_unit_<?= $row_counter ?>" class="price_unit_1" onkeyup="i_think_quantity_changed()"  onchange="i_think_quantity_changed()" style="width: 100px;" type="number" step="any"  size="10">
                    </td>
                    <td>
                        <b style="width: 80px;">Freight/Unit (cstm)</b><br>

                        <input id="freight_unit_<?= $row_counter ?>"  name="freight_unit_<?= $row_counter ?>" <?= (($row_counter < 2)?"required='required'":'') ?> class="freight_unit_1" onkeyup="i_think_quantity_changed()"  onchange="i_think_quantity_changed()" style="width: 80px;" type="number" step="any"  size="10"> <br><br>
                        <b>Total-Frt: </b><span class="total_freight_rs_1"></span>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid lightgray;">
                    <td colspan="2">
                        <b>Shrt at DESTIN</b><br>
                        <input disabled name="shortage_at_destination_<?= $row_counter ?>" onkeyup="i_think_quantity_changed()" class="shortage_at_destination_1"  onchange="i_think_quantity_changed()" style="width: 100px;" type="number" step="any"  size="10">
                    </td>
                    <td>
                        <b>Qty at DESTIN: </b><span class="quantity_at_destination_1"></span><br>
                        <b>Exp at Destin: </b><span class="expense_at_destination_rs_1"></span> Rs.
                    </td>
                    <td>
                        <b>Cont-Comsn: </b><span class="contractor_commission_rs_1"></span> Rs.
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid lightgray;">
                    <td colspan="2">
                        <b>Shrt after Decand: </b><br>
                        <input disabled name="shortage_after_decanding_<?= $row_counter ?>" onkeyup="i_think_quantity_changed()" class="shortage_after_decanding_1"  onchange="i_think_quantity_changed()" style="width: 100px;" type="number" step="any"  size="10">
                    </td>
                    <td>
                        <b>Qty after DECAND: </b><span class="quantity_after_decanding_1"></span><br>
                        <b>Exp after Decand: </b><span class="expense_after_decanding_rs_1"></span> Rs.<br>
                    </td>
                    <td>
                        <b>Comp-Comsn: </b><span class="company_commission_1_rs_1"></span> Rs.<br>
                        <b>W.H.T: </b><span class="wht_rs_1"></span> Rs.
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid lightgray;">
                    <td colspan="2">
                        <b>Stn-Number</b><br>
                        <input type="text" name="stn_number_<?= $row_counter ?>" size="10" style="width: 100px;">
                    </td>
                    <td>
                        <b>Total Shrt: </b><span class="total_shortage_1"></span><br>
                        <b>Total Exp: </b><span class="total_expense_rs_1"></span> Rs.
                    </td>
                    <td>
                        <b>Cstm-Frt: </b><span class="remaining_for_customer_rs_1"></span> Rs.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php
}
?>
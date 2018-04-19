<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 5/31/15
 * Time: 9:26 AM
 */
?>

<select name="trip_type">
    <option value="1" <?= ($trip_type == 'self_mail')?'selected':''; ?>>Self/Mail</option>
    <option value="2" <?= ($trip_type == 'general')?'selected':''; ?>>General</option>
    <option value="3" <?= ($trip_type == 'local_company')?'selected':''; ?>>Local Company</option>
    <option value="4" <?= ($trip_type == 'local_self')?'selected':''; ?>>Local Self</option>
    <option value="5" <?= ($trip_type == 'general_local')?'selected':''; ?>>General Local</option>
    <option value="6" <?= ($trip_type == 'secondary_local')?'selected':''; ?>>Secondary Local</option>
</select>
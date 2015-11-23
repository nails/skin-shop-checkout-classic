<!DOCTYPE html>
<html>
    <head>
        <title><?=APP_NAME . ' Order #' . $order->ref?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="<?=NAILS_ASSETS_URL?>bower_components/fontawesome/css/font-awesome.min.css">
        <style type="text/css">

            html,
            body
            {
                padding:0px;
                margin:0px;
                font-size:10px;
            }

            #container
            {
                padding:20px;
                margin:0;
                width:100%;
                background:#FFF;
                font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
                font-weight: 300;
                position:relative;
            }

            #header
            {
                margin-bottom: 0px;
            }

            #invoice-title,
            #invoice-text
            {
                color:#CCC;
                font-weight:bold;
                font-size: 2em;
            }

            #invoice-text
            {
                text-align: right;
            }

            hr
            {
                margin:10px 0;
                border: 0;
                border-top:5px solid #CCC;
            }

            table.styled
            {
                border:1px solid #CCC;
                border-collapse: collapse;
                margin-bottom:0px;
            }

            table.styled th,
            table.styled td
            {
                padding:10px;
                text-align: left;
                border-right:1px dotted #CCC;
                border-bottom:1px dotted #CCC;
                vertical-align:top;
                font-size:0.9em;
            }

            table.styled th
            {
                background:#FAFAFA;
            }

            table.styled td.head
            {
                vertical-align:top;
                width:50px;
                background:#FAFAFA;
            }

            table.styled td
            {
                background:#FEFEFE;
                box-sizing:border-box;
                vertical-align:middle;
                border-bottom:1px dotted #ddd;
            }

            table.styled td.status
            {
                font-weight:bold;
                color:red;
            }

            table.styled td.status.paid
            {
                color:green;
            }

            table.styled.products thead tr:first-of-type th
            {
                border-bottom:2px solid #DDD;

            }

            table.styled.products thead th.barcode,
            table.styled.products tbody td.barcode
            {
                text-align:center;
                width:150px;
            }

            table.styled.products thead th.quantity,
            table.styled.products tbody td.quantity
            {
                text-align:center;
                width:50px;
            }

            table.styled.products thead th.product,
            table.styled.products tbody td.product
            {

            }

            table.styled.products thead th.unit-cost,
            table.styled.products tbody td.unit-cost,
            table.styled.products thead th.unit-tax,
            table.styled.products tbody td.unit-tax,
            table.styled.products tfoot th.total-value
            {
                text-align:center;
                width:75px;
            }

            table.styled.products tfoot tr:first-of-type th
            {
                border-top:2px solid #ddd;

            }

            img.barcode
            {
                /* Compensating for the PHP bug described here: https://bugs.php.net/bug.php?id=67447 */
                border-left:1px solid #000;
                border-top:1px solid #000;
            }

            #invoice-footer
            {
                padding-top:2em;
                font-size:0.8em;
                color:#555;
            }

            .alert
            {
                padding: 7px;
                font-size:0.9em;
                margin: 0;
                border: 1px solid transparent;
                border-radius: 4px;
            }

            .alert.alert-success
            {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }

            .alert.alert-info
            {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }

            .alert.alert-warning
            {
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }

            .alert.alert-danger
            {
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
            }

        </style>
    </head>
    <body>
        <div id="container">
            <header id="header">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td id="invoice-title">
                                <?=APP_NAME?>
                            </td>
                            <td id="invoice-text">
                                INVOICE
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr />
                <?php


                    /**
                     * Show a message if the order is amrked as collection, or if the order is marked
                     * as delivery, but contains collectable items.
                     */

                    $statusSubject = '';
                    $statusMessage = '';

                    $address   = array();
                    $address[] = trim(app_setting('warehouse_addr_addressee', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_line1', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_line2', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_town', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_postcode', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_state', 'shop'));
                    $address[] = trim(app_setting('warehouse_addr_country', 'shop'));

                    $address = array_filter($address);
                    $address = implode(', ', $address);

                    if ($order->delivery_type == 'COLLECT') {

                        $statusSubject =  'This order is for collection';

                        if ($address) {

                            $statusMessage = '<br />Collection from: ' . $address;
                        }

                    } else {

                        $numCollectItems = 0;

                        foreach ($order->items as $item) {
                            if ($item->ship_collection_only) {
                                $numCollectItems++;
                            }
                        }

                        if ($numCollectItems > 0) {

                            if ($numCollectItems === 1) {
                                $plural = '';
                            } else {
                                $plural = 's';
                            }

                            $statusSubject = 'This order contains ' . $numCollectItems . ' item' . $plural . ' which must be collected.';
                            $statusMessage = '<br />Collection from: ' . $address;
                        }
                    }

                    if ($statusSubject || $statusMessage) {

                        echo '<div class="alert alert-warning">';

                            echo $statusSubject ? '<strong>' . $statusSubject . '</strong>' : '';
                            echo $statusMessage ? $statusMessage : '';

                        echo '</div>';
                        echo '<hr />';
                    }

                ?>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td align="left" valign="top">
                                <table class="styled" style="width:450px;">
                                    <tbody>
                                        <tr>
                                            <td class="head">Invoice</td>
                                            <td><?=$order->ref?></td>
                                        </tr>
                                        <tr>
                                            <td class="head">Dated</td>
                                            <td><?=date ('jS M Y, H:i:s', strtotime($order->created))?></td>
                                        </tr>
                                        <tr>
                                            <td class="head">Status</td>
                                            <td class="status <?=strtolower($order->status)?>"><?=$order->status?></td>
                                        </tr>
                                        <tr>
                                            <td class="head">Customer</td>
                                            <td class="customer">
                                            <?php

                                                echo '<strong>' . $order->user->first_name . ' ' . $order->user->last_name . '</strong>';
                                                echo '<br />' . $order->user->email;
                                                echo '<br />' . $order->user->telephone;

                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="head">Ship To</td>
                                            <td>
                                            <?php

                                                $address = array(
                                                    $order->shipping_address->line_1,
                                                    $order->shipping_address->line_2,
                                                    $order->shipping_address->town,
                                                    $order->shipping_address->state,
                                                    $order->shipping_address->postcode,
                                                    $order->shipping_address->country->label
                                                );

                                                $address = array_filter($address);
                                                echo implode('<br />', $address);
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="head">Bill To</td>
                                            <td>
                                            <?php

                                                $address = array(
                                                    $order->billing_address->line_1,
                                                    $order->billing_address->line_2,
                                                    $order->billing_address->town,
                                                    $order->billing_address->state,
                                                    $order->billing_address->postcode,
                                                    $order->billing_address->country->label
                                                );

                                                $address = array_filter($address);
                                                echo implode('<br />', $address);
                                            ?>
                                            </td>
                                        </tr>
                                        <?php if (!empty($order->note)) { ?>
                                        <tr>
                                            <td class="head">Note</td>
                                            <td class="note"><?=$order->note?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td style="position:relative;">
                                <table class="styled" style="position:absolute;right:0;top:0;width:450px;bottom:0;">
                                    <tbody>
                                        <tr>
                                            <td class="head">From</td>
                                            <td>
                                            <?php

                                                $invoiceCompany   = appSetting('invoice_company', 'shop');
                                                $invoiceAddress   = appSetting('invoice_address', 'shop');
                                                $invoiceVatNo     = appSetting('invoice_vat_no', 'shop');
                                                $invoiceCompanyNo = appSetting('invoice_company_no', 'shop');

                                                echo $invoiceCompany   ? '<strong>' . $invoiceCompany . '</strong>' : '<strong>' . APP_NAME . '</strong>';
                                                echo $invoiceAddress   ? '<br />' . nl2br($invoiceAddress) . '<br />' : '';
                                                echo $invoiceVatNo     ? '<br />VAT No.: ' . $invoiceVatNo : '';
                                                echo $invoiceCompanyNo ? '<br />Company No.: ' . $invoiceCompanyNo : '';
                                            ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <hr />

            <table class="styled products" style="width:100%;">
                <thead>
                    <tr>
                        <th class="barcode">SKU</th>
                        <th class="quantity">Quantity</th>
                        <th class="product">Product</th>
                        <th class="unit-cost">Unit Cost</th>
                        <th class="unit-tax">Tax Per Unit</th>
                        <th class="total">Total</th>
                    </tr>
                </thead>

                <tbody>
                <?php

                foreach ($order->items as $item) {

                    ?>
                    <tr>
                        <td class="barcode">
                        <?php

                            if (!empty($item->sku)) {

                                //  @todo Get barcodes working
                                //  echo img(array('src' => 'barcode/' . $item->sku, 'class' => 'barcode'));
                                echo $item->sku;

                            } else {

                                ?>
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-barcode fa-stack-1x"></i>
                                    <i class="fa fa-ban fa-stack-2x text-danger" style="opacity: 0.5"></i>
                                </span>
                                <?php
                            }
                        ?>
                        </td>
                        <td class="quantity">
                            <?=$item->quantity?>
                        </td>
                        <td class="product">
                        <?php

                            echo '<strong>' . $item->product_label . '</strong>';
                            echo '<br>' . $item->variant_label;

                            if (!empty($item->extra_data['to_order']->is_to_order)) {

                                echo '<div class="alert alert-warning" style="margin-top:1em;">';
                                    echo '<strong>Note:</strong> This item is to order. Lead time: ';
                                    echo $item->extra_data['to_order']->lead_time;
                                echo '</div>';
                            }

                            if ($item->ship_collection_only) {

                                echo '<div class="alert alert-warning" style="margin-top:1em;">';
                                    echo '<strong>Note:</strong> This item is collect only.';
                                echo '</div>';
                            }

                        ?>
                        </td>
                        <td class="unit-cost">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $item->price->base_formatted->value_ex_tax;

                            } else {

                                echo $item->price->user_formatted->value_ex_tax;

                            }

                            ?>
                        </td>
                        <td class="unit-tax">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $item->price->base_formatted->value_tax;

                            } else {

                                echo $item->price->user_formatted->value_tax;

                            }

                            ?>
                        </td>
                        <td class="unit-tax">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $item->price->base_formatted->item_total;

                            } else {

                                echo $item->price->user_formatted->item_total;

                            }

                            ?>
                        </td>
                    </tr>
                    <?php

                }

                ?>
                </tbody>

                <tfoot>
                    <tr>
                        <th class="total-text" colspan="5" style="text-align:right;">Sub Total</th>
                        <th class="total-value">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $order->totals->base_formatted->item;

                            } else {

                                echo $order->totals->user_formatted->item;
                            }

                            ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="total-text" colspan="5" style="text-align:right;">Shipping</th>
                        <th class="total-value">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                if ($order->totals->base->shipping) {

                                    echo $order->totals->base_formatted->shipping;
                                } else {

                                    echo 'Free';
                                }

                            } else {

                                if ($order->totals->user->shipping) {

                                    echo $order->totals->user_formatted->shipping;
                                } else {

                                    echo 'Free';
                                }
                            }

                            ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="total-text" colspan="5" style="text-align:right;">Tax</th>
                        <th class="total-value">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $order->totals->base_formatted->tax;

                            } else {

                                echo $order->totals->user_formatted->tax;
                            }

                            ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="total-text" colspan="5" style="text-align:right;">Total</th>
                        <th class="total-value">
                            <?php

                            if (isset($for_user) && $for_user == 'ADMIN') {

                                echo $order->totals->base_formatted->grand;

                            } else {

                                echo $order->totals->user_formatted->grand;
                            }

                            ?>
                        </th>
                    </tr>
                </tfoot>

            </table>
            <?php

                if (app_setting('invoice_footer', 'shop')) {

                    echo '<p id="invoice-footer">';
                        echo appSetting('invoice_footer', 'shop');
                    echo '</p>';
                }

            ?>
        </div>
    </body>
</html>
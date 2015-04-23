<div class="table-responsive">
    <table class="table table-bordered table-striped order-summary">
        <thead>
            <tr>
                <th class="col-xs-6 col-sm-8">Product</th>
                <th class="col-xs-3 col-sm-2 text-center">Quantity</th>
                <th class="col-xs-3 col-sm-2 text-center">Unit Price</th>
            </tr>
        </thead>
        <tbody>
        <?php

            foreach ($items as $item) {

                ?>
                <tr class="basket-item">
                    <td class="vertical-align-middle">
                    <?php

                        if (!empty($item->variant->featured_img)) {

                            $featuredImg = $item->variant->featured_img;

                        } elseif (!empty($item->product->featured_img)) {

                            $featuredImg = $item->product->featured_img;

                        } else {

                            $featuredImg = FALSE;
                        }

                        if ($featuredImg) {

                            echo '<div class="col-xs-2 hidden-xs hidden-sm">';

                                $url = cdn_thumb($featuredImg, 175, 175);
                                echo img(array('src' => $url, 'class' => 'img-thumbnail'));

                            echo '</div>';
                            echo '<div class="col-sm-12 col-md-10">';

                        } else {

                            echo '<div class="col-sm-12">';
                        }

                        // --------------------------------------------------------------------------

                        //  Label
                        echo anchor($item->product->url, '<strong>' . $item->product->label . '</strong>');

                        if ($item->variant->label !== $item->product->label) {

                            echo '<br />';
                            echo '<em>' . $item->variant->label . '</em>';
                        }

                        // --------------------------------------------------------------------------

                        //  SKU
                        if (!empty($item->variant->sku)) {

                            echo '<br />';
                            echo '<small class="text-muted">';
                                echo '<em>' . $item->variant->sku . '</em>';
                            echo '</small>';
                        }

                        // --------------------------------------------------------------------------

                        //  To order?
                        if ($item->variant->stock_status == 'TO_ORDER') {

                            echo '<p class="text-muted">';
                                echo '<small>';
                                    echo '<em>Lead time: ' . $item->variant->lead_time . '</em>';
                                echo '</small>';
                            echo '</p>';
                        }

                        // --------------------------------------------------------------------------

                        //  Collection Only
                        if ($item->variant->shipping->collection_only) {

                            echo '<div class="alert alert-warning">';
                                echo '<strong>Note:</strong> This item is collection only.';
                            echo '</div>';
                        }

                        // --------------------------------------------------------------------------

                        if ($featuredImg) {

                            echo '</div>';
                        }

                    ?>
                    </td>
                    <td class="vertical-align-middle text-center">
                        <div class="row">
                        <?php

                            if (empty($readonly)) {

                                echo '<div class="col-xs-4">';
                                echo anchor($shop_url . 'basket/decrement?variant_id=' . $item->variant->id, '<span class="glyphicon glyphicon-minus-sign text-muted"></span>', 'class="pull-right"');
                                echo '</div>';

                                echo '<div class="col-xs-4">';
                            }

                            echo '<span class="variant-quantity-' . $item->variant->id . '">';
                                echo number_format($item->quantity);
                            echo '</span>';

                            if (empty($readonly)) {

                                echo '</div>';

                                echo '<div class="col-xs-4">';
                                echo anchor($shop_url . 'basket/increment?variant_id=' . $item->variant->id, '<span class="glyphicon glyphicon-plus-sign text-muted"></span>', 'class="pull-left"');
                                echo '</div>';
                            }

                        ?>
                        </div>
                    </td>
                    <td class="vertical-align-middle text-center">
                    <?php

                        if (app_setting('price_exclude_tax', 'shop')) {

                            echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
                                echo $item->variant->price->price->user_formatted->value_ex_tax;
                            echo '</span>';

                            if (!app_setting('omit_variant_tax_pricing', 'shop-' . $skin->slug) && $item->variant->price->price->user->value_tax > 0) {

                                echo '<br />';
                                echo '<small class="text-muted">';
                                    echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
                                        echo $item->variant->price->price->user_formatted->value_inc_tax;
                                    echo '</span>';
                                    echo ' inc. tax';
                                echo '</small>';
                            }

                        } else {

                            echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
                                echo $item->variant->price->price->user_formatted->value_inc_tax;
                            echo '</span>';

                            if (!app_setting('omit_variant_tax_pricing', 'shop-' . $skin->slug) && $item->variant->price->price->user->value_tax > 0) {

                                echo '<br />';
                                echo '<small class="text-muted">';
                                    echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
                                        echo $item->variant->price->price->user_formatted->value_ex_tax;
                                    echo '</span>';
                                    echo ' ex. tax';
                                echo '</small>';
                            }
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
                <th colspan="3"></th>
            </tr>

            <!-- Item Total -->
            <tr class="basket-total-item">
                <th colspan="2" class="text-right">
                    Sub Total
                </th>
                <th class="text-center value">
                    <?=$totals->user_formatted->item?>
                </th>
            </tr>

            <!-- Shipping Total -->
            <tr class="basket-total-shipping">
                <th colspan="2" class="text-right">
                    <?php

                        if (app_setting('warehouse_collection_enabled', 'shop')) {

                            if ($shippingType === 'DELIVER') {

                                echo 'Shipping';

                                if (empty($readonly)) {

                                    echo '<small>';
                                    echo anchor($shop_url . 'basket/set_as_collection', 'Click here to collect your order');
                                    echo '</small>';
                                }

                            } else {

                                echo 'You will collect your order ';

                                if (empty($readonly)) {

                                    echo '<small>';
                                        echo anchor($shop_url . 'basket/set_as_delivery', 'Click here to have your order delivered');
                                    echo '</small>';
                                }

                                $address   = array();
                                $address[] = app_setting('warehouse_addr_addressee', 'shop');
                                $address[] = app_setting('warehouse_addr_line1', 'shop');
                                $address[] = app_setting('warehouse_addr_line2', 'shop');
                                $address[] = app_setting('warehouse_addr_town', 'shop');
                                $address[] = app_setting('warehouse_addr_postcode', 'shop');
                                $address[] = app_setting('warehouse_addr_state', 'shop');
                                $address[] = app_setting('warehouse_addr_country', 'shop');

                                if (!empty($address[0])) {

                                    $addressLabel = $address[0];

                                } elseif (!empty($address[0])) {

                                    $addressLabel = $address[1];

                                } else {

                                    $addressLabel = APP_NAME;

                                }

                                $address = array_filter($address);
                                $address = implode(', ', $address);

                                echo '<small>';
                                    if ($address && $addressLabel) {

                                        $mapsUrl = 'http://maps.google.com/?q=' . urlencode($address);

                                        echo 'Collection from: ';
                                        echo anchor($mapsUrl, $addressLabel, 'target="_blank"');

                                    } elseif ($addressLabel) {

                                        echo 'Collection from:';
                                        echo $addressLabel;
                                    }
                                echo '</small>';
                            }

                        } else {

                            echo 'Shipping';
                        }

                    ?>
                </th>
                <th class="text-center value">
                    <?php

                        if ($totals->user->shipping) {

                            echo $totals->user_formatted->shipping;

                        } else {

                            echo 'Free';
                        }

                    ?>
                </th>
            </tr>

            <!-- Tax Total -->
            <tr class="basket-total-tax">
                <th colspan="2" class="text-right">
                <?php

                    if (app_setting('price_exclude_tax', 'shop')) {

                        echo 'Tax';

                    } else {

                        echo 'Tax (included)';
                    }

                ?>
                </th>
                <th class="text-center value">
                <?php

                    echo $totals->user_formatted->tax;

                ?>
                </th>
            </tr>

            <!-- Grand Total -->
            <tr class="basket-total-grand">
                <th colspan="2" class="text-right">
                    Total
                </th>
                <th class="text-center value">
                    <?=$totals->user_formatted->grand?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
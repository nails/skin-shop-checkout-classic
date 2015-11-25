<div class="hidden-xs hidden-sm table-responsive">
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

        $bPriceExcludeTax       = appSetting('price_exclude_tax', 'shop');
        $bOmitVariantTaxPricing = appSetting('omit_variant_tax_pricing', 'shop-' . $skin->slug);

        foreach ($items as $item) {

            $bIsDiscounted = $item->price->user->discount_item > 0;

            ?>
            <tr class="basket-item <?=$bIsDiscounted ? 'is-discounted' : ''?>">
                <td class="vertical-align-middle">
                <?php

                if (!empty($item->variant->featured_img)) {

                    $featuredImg = $item->variant->featured_img;

                } elseif (!empty($item->product->featured_img)) {

                    $featuredImg = $item->product->featured_img;

                } else {

                    $featuredImg = false;
                }

                if ($featuredImg) {

                    echo '<div class="col-xs-2 hidden-xs hidden-sm">';

                        $url = cdnCrop($featuredImg, 175, 175);
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

                //  To order?
                if ($item->variant->stock_status == 'TO_ORDER') {

                    ?>
                    <p class="text-muted">
                        <small>
                            <em>
                                Lead time: <?=$item->variant->lead_time?>
                            </em>
                        </small>
                    </p>
                    <?php
                }

                // --------------------------------------------------------------------------

                //  Collection Only
                if ($item->variant->shipping->collection_only) {

                    ?>
                    <div class="alert alert-warning">
                        <strong>Note:</strong> This item is collection only.
                    </div>
                    <?php
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

                            ?>
                            <div class="col-xs-4">
                                <?php

                                echo anchor(
                                    $shop_url . 'basket/decrement?variant_id=' . $item->variant->id,
                                    '<span class="glyphicon glyphicon-minus-sign text-muted"></span>',
                                    'class="pull-right"'
                                );

                                ?>
                            </div>

                            <div class="col-xs-4">
                            <?php
                        }

                        echo '<span class="variant-quantity-' . $item->variant->id . '">';
                            echo number_format($item->quantity);
                        echo '</span>';

                        if (empty($readonly)) {

                            echo '</div>';

                            /**
                             * Determine whether the user can increment the product. In order to be
                             * incrementable there must:
                             * - Be sufficient stock (or unlimited)
                             * - not exceed any limit imposed by the product type
                             */

                            if (is_null($item->variant->quantity_available)) {

                                //  Unlimited quantity
                                $sufficient = true;

                            } elseif ($item->quantity < $item->variant->quantity_available) {

                                //  Fewer than the quantity available, user can increment
                                $sufficient = true;

                            } else {

                                $sufficient = false;
                            }

                            if (empty($item->product->type->max_per_order)) {

                                //  Unlimited additions allowed
                                $notExceed = true;

                            } elseif ($item->quantity < $item->product->type->max_per_order) {

                                //  Not exceeded the maximum per order, user can increment
                                $notExceed = true;

                            } else {

                                $notExceed = false;
                            }

                            if ($sufficient && $notExceed) {

                                echo '<div class="col-xs-4">';
                                echo anchor(
                                    $shop_url . 'basket/increment?variant_id=' . $item->variant->id,
                                    '<span class="glyphicon glyphicon-plus-sign text-muted"></span>',
                                    'class="pull-left"'
                                );
                                echo '</div>';
                            }
                        }

                        ?>
                    </div>
                </td>
                <td class="vertical-align-middle text-center">
                    <?php

                    if ($bPriceExcludeTax) {

                        echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
                        echo $item->price->user_formatted->value_ex_tax;
                        echo '</span>';

                        if (!$bOmitVariantTaxPricing && $item->price->user->value_tax > 0) {

                            ?>
                            <br />
                            <small class="text-muted">
                                <span class="variant-unit-price-inc-tax-<?=$item->variant->id?>">
                                    <?=$item->price->user_formatted->value_inc_tax?>
                                </span>
                                 inc. <?=$item->product->tax_rate->rate*100?>% tax
                            </small>
                            <?php
                        }

                    } else {

                        echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
                        echo $item->price->user_formatted->value_inc_tax;
                        echo '</span>';

                        if (!$bOmitVariantTaxPricing && $item->price->user->value_tax > 0) {

                            ?>
                            <br />
                            <small class="text-muted">
                                <span class="variant-unit-price-ex-tax-<?=$item->variant->id?>">
                                <?=$item->price->user_formatted->value_ex_tax?>
                                </span>
                                 ex. <?=$item->product->tax_rate->rate*100?>% tax
                            </small>
                            <?php

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
            <!-- Discount Total -->
            <?php

            if (!empty($totals->base->grand_discount)) {

                ?>
                <tr class="basket-total-discount success text-success">
                    <th colspan="2" class="text-right">
                        Discount
                    </th>
                    <th class="text-center value">
                        -<?=$totals->user_formatted->grand_discount?>
                    </th>
                </tr>
                <?php

            }

            ?>
            <!-- Shipping Total -->
            <?php

            if ($shippingType === 'DELIVER_COLLECT') {

                $rowContext = 'warning';

            } elseif ($shippingType === 'COLLECT') {

                $rowContext = 'info';

            } else {

                $rowContext = '';
            }

            ?>
            <tr class="basket-total-shipping <?=$rowContext?>">
                <th colspan="2" class="text-right">
                    <?php

                    if (appSetting('warehouse_collection_enabled', 'shop')) {

                        $address   = array();
                        $address[] = appSetting('warehouse_addr_addressee', 'shop');
                        $address[] = appSetting('warehouse_addr_line1', 'shop');
                        $address[] = appSetting('warehouse_addr_line2', 'shop');
                        $address[] = appSetting('warehouse_addr_town', 'shop');
                        $address[] = appSetting('warehouse_addr_postcode', 'shop');
                        $address[] = appSetting('warehouse_addr_state', 'shop');
                        $address[] = appSetting('warehouse_addr_country', 'shop');
                        $address   = array_filter($address);
                        $mapsUrl   = 'http://maps.google.com/?q=' . urlencode(implode(', ', $address));

                        if ($shippingType === 'DELIVER') {

                            echo 'Shipping';

                            if (empty($readonly)) {

                                echo '<small>';
                                echo '<br />';
                                echo anchor(
                                    $shop_url . 'basket/set_as_collection',
                                    'Click here to collect your order',
                                    'class="btn btn-default btn-xs"'
                                );
                                echo '</small>';
                            }

                        } elseif ($shippingType === 'DELIVER_COLLECT') {

                            echo 'We will only partially deliver this order';
                            echo '<small>';
                            echo 'Your order contains items which are collect only';
                            echo '</small>';

                            if ($address) {

                                ?>
                                <small>
                                    <br /><strong>Collection from:</strong>
                                    <br /><?=implode('<br />', $address)?><br />
                                    <?php

                                    echo anchor(
                                        $mapsUrl,
                                        '<b class="glyphicon glyphicon-map-marker"></b> Map',
                                        'class="btn btn-xs btn-default" target="_blank"'
                                    );

                                    ?>
                                </small>
                                <?php
                            }

                            ?>
                            <small>
                                <br />
                                <?php

                                echo anchor(
                                    $shop_url . 'basket/set_as_collection',
                                    'Click here to collect your entire order',
                                    'class="btn btn-default btn-xs"'
                                )

                                ?>
                            </small>
                            <?php

                        } else {

                            echo 'You will collect your order ';

                            if ($address) {

                                ?>
                                <small>
                                    <br /><strong>Collection from:</strong>
                                    <br /><?=implode('<br />', $address)?><br />
                                    <?php

                                    echo anchor(
                                        $mapsUrl,
                                        '<b class="glyphicon glyphicon-map-marker"></b> Map',
                                        'class="btn btn-xs btn-default" target="_blank"'
                                    );

                                    ?>
                                </small>
                                <?php

                            }

                            if (empty($readonly) && $basket->shipping->isDeliverable) {

                                ?>
                                <small>
                                    <br />
                                    <?php

                                    echo anchor(
                                        $shop_url . 'basket/set_as_delivery',
                                        'Click here to have your order delivered',
                                        'class="btn btn-default btn-xs"'
                                    );

                                    ?>
                                </small>
                                <?php
                            }
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

                    if ($bPriceExcludeTax) {

                        echo 'Tax';

                    } else {

                        echo 'Tax <small class="text-muted">(Included)</small>';
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
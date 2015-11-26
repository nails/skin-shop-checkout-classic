<div class="nails-shop-skin-checkout-classic basket">
    <?php

    if (!empty($shippingDriverPromo->title) || !empty($shippingDriverPromo->body)) {

        ?>
        <div class="row">
            <div class="col-md-12">
            <?php

            $appliedClass = !empty($shippingDriverPromo->applied) ? ' shipping-driver-promo-applied' : '';
            echo '<div class="shipping-driver-promo' . $appliedClass . ' ">';

            echo !empty($shippingDriverPromo->title) ? '<h4>' . $shippingDriverPromo->title . '</h4>' : '';
            echo !empty($shippingDriverPromo->body) ? '<p>' . $shippingDriverPromo->body . '</p>' : '';

            echo '</div>';

            ?>
            </div>
        </div>
        <?php
    }

    // --------------------------------------------------------------------------

    $headerText = appSetting('basket_header', 'shop-' . $skin->slug);

    if (!empty($headerText)) {

        ?>
        <div class="row">
            <div class="col-xs-12">
                <?=$headerText?>
                <hr/>
            </div>
        </div>
        <?php
    }

    // --------------------------------------------------------------------------

    if (!empty($basket->itemsRemoved)) {

        ?>
        <div class="row items-removed">
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <strong>Some items in your basket have been automatically removed</strong>
                    <br />The items listed below have been removed from your basket because they are no longer available:
                    <ul>
                    <?php

                    foreach ($basket->itemsRemoved as $item) {

                        echo '<li>';
                            echo $item;
                        echo '</li>';
                    }

                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    // --------------------------------------------------------------------------

    if (!empty($basket->itemsAdjusted)) {

        ?>
        <div class="row items-adjusted">
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <strong>Some items in your basket have been automatically adjusted</strong>
                    <br />The quantity you can purchase for following items has been adjusted due to stock availability:
                    <ul>
                    <?php

                    foreach ($basket->itemsAdjusted as $item) {

                        echo '<li>';
                            echo $item;
                        echo '</li>';
                    }

                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    // --------------------------------------------------------------------------

    if (!empty($basket->items)) {

        $tableData                   = array();
        $tableData['items']          = $basket->items;
        $tableData['totals']         = $basket->totals;
        $tableData['shippingOption'] = $basket->shipping->option;

        $this->load->view($skin->path . 'views/basket/table', $tableData);
        $this->load->view($skin->path . 'views/basket/mobile', $tableData);

        ?>
        <hr />
        <div class="row">
            <div class="col-sm-6">
                <label for="voucher">Promotional voucher</label>
                <div class="well well-sm">
                    <?php

                    if (!empty($basket->voucher->id)) {

                        ?>
                        <div class="panel panel-default panel-voucher">
                            <?php

                            echo anchor(
                                $shop_url . 'basket/remove_voucher',
                                '<b class="glyphicon glyphicon-remove text-danger"></b>',
                                'class="pull-right"'
                            )

                            ?>
                            <div class="panel-body text-success">
                                <?=$basket->voucher->code?>
                                &mdash;
                                <?=$basket->voucher->label?>
                            </div>
                        </div>
                        <?php

                    } else {

                        echo form_open($shop_url . 'basket/add_voucher', 'class="add-voucher"');
                        ?>
                        <div class="input-group">
                            <?php

                            echo form_input(
                                'voucher',
                                '',
                                'placeholder="Enter your voucher code, if you have one." class="form-control" id="voucher"'
                            );

                            ?>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    Apply
                                </button>
                            </span>
                        </div><!-- /input-group -->
                        <?php

                        echo form_close();
                    }

                    ?>
                </div>
            </div>
            <div class="col-sm-6">
                <label for="notes">Special instructions</label>
                <div class="well well-sm">
                    <?php

                    if (!empty($basket->note)) {

                        ?>
                        <div class="panel panel-default panel-note">
                            <?php

                            echo anchor(
                                $shop_url . 'basket/remove_note',
                                '<b class="glyphicon glyphicon-remove text-danger"></b>',
                                'class="pull-right"'
                            );

                            ?>
                            <div class="panel-body">
                                <?=$basket->note?>
                            </div>
                        </div>
                        <?php
                    } else {

                        echo form_open($shop_url . 'basket/add_note', 'class="add-note"');

                        ?>
                        <div class="input-group">
                            <?php

                            echo form_input(
                                'note',
                                '',
                                'placeholder="Enter any special instructions or notes about your order." maxlength="150" class="form-control" id="notes"'
                            );

                            ?>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    Save
                                </button>
                            </span>
                        </div><!-- /input-group -->
                        <?php

                        echo form_close();

                    }

                    ?>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-xs-12 col-sm-4 hidden-xs">
                <?php

                echo anchor(
                    $continue_shopping_url,
                    'Continue Shopping',
                    'class="btn btn-block btn-lg btn-default"'
                );

                ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                <?php

                echo anchor(
                    $shop_url . 'checkout',
                    'Checkout Now',
                    'class="btn btn-block btn-lg btn-success pull-right"'
                );

                ?>
            </div>
        </div>
        <hr />
        <?php

    } else {

        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="basket-empty well well-default">
                    <h3 class="text-center">
                        Your basket is empty
                        <br /><br />
                        <?=anchor($shop_url, 'Go Shopping', 'class="btn btn-primary btn-sm"')?>
                    </h3>
                </div>
            </div>
        </div>
        <?php
    }

    // --------------------------------------------------------------------------

    $footerText = appSetting('basket_footer', 'shop-' . $skin->slug);

    if (!empty($footerText)) {

        ?>
        <div class="row">
            <div class="col-md-12">
                <?=$footerText?>
                <hr/>
            </div>
        </div>
        <?php
    }

    if (!empty($recently_viewed)) {

        ?>
        <div class="row">
            <div class="col-md-12">
                <h4>Recently Viewed</h4>
            </div>
        </div>
        <div class="row product-browser">
            <?php

            foreach ($recently_viewed as $product) {

                ?>
                <div class="product col-xs-6 col-sm-4 col-md-3 col-lg-2">
                    <?php

                    if ($product->featured_img) {

                        $url = cdnCrop($product->featured_img, 360, 360);

                    } else {

                        $url = $skin->url . 'assets/img/product-no-image.png';
                    }

                    ?>
                    <div class="product-image">
                        <?php

                        echo anchor(
                            $product->url,
                            img(
                                array(
                                    'src' => $url,
                                    'class' => 'img-responsive img-thumbnail center-block'
                                )
                            )
                        );

                        if (count($product->variations) > 1) {

                            if (appSetting('browse_product_ribbon_mode', 'shop-' . $skin->slug) == 'corner') {

                                ?>
                                <div class="ribbon corner">
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon-text">
                                            <?=count($product->variations)?> options
                                        </div>
                                    </div>
                                </div>
                                <?php

                            } else {

                                ?>
                                <div class="ribbon horizontal">
                                    <?=count($product->variations)?> options available
                                </div>
                                <?php
                            }
                        }

                        ?>
                    </div>
                    <p>
                        <?=anchor($product->url, $product->label)?>
                    </p>
                    <p>
                        <span class="badge">
                            <?php

                            if (appSetting('price_exclude_tax', 'shop')) {

                                echo $product->price->user_formatted->price_string_ex_tax;

                            } else {

                                echo $product->price->user_formatted->price_string_inc_tax;
                            }

                            ?>
                        </span>
                    </p>
                    <hr class="hidden-sm hidden-md hidden-lg" />
                </div>
                <?php

            }

            ?>
        </div>
        <?php
    }

    ?>
</div>
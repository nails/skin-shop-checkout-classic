<div class="nails-shop-skin-checkout-classic basket">
    <?php if (!empty($shippingDriverPromo->title) || !empty($shippingDriverPromo->body)) { ?>
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

    $headerText = app_setting('basket_header', 'shop-' . $skin->slug);

    if (!empty($headerText)) {

        echo '<div class="row">';
            echo '<div class="col-md-12">';
                echo $headerText;
                echo '<hr/>';
            echo '</div>';
        echo '</div>';
    }

    ?>
    <div class="row">
        <div class="col-xs-12">
        <?php

            if (!empty($basket->itemsRemoved)) {

                ?>
                <div class="row items-removed">
                    <div class="col-sm-12">
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

            if (!empty($basket->itemsAdjusted)) {

                ?>
                <div class="row items-adjusted">
                    <div class="col-sm-12">
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

            if (!empty($basket->items)) {

                $tableData                     = array();
                $tableData['items']            = $basket->items;
                $tableData['totals']           = $basket->totals;
                $tableData['shippingType']     = $basket->shipping->type;
                $tableData['shippingTypeUser'] = $basket->shipping->user;

                $this->load->view($skin->path . 'views/basket/table', $tableData);

                ?>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <div class="well well-sm">
                            <?=form_open($shop_url . 'basket/add_voucher', 'class="add-voucher"')?>
                                <div class="input-group">
                                    <?=form_input('voucher', '', 'placeholder="Enter your promotional voucher, if you have one." class="form-control"')?>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            Add Voucher
                                        </button>
                                    </span>
                                </div><!-- /input-group -->
                            <?=form_close()?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="well well-sm">
                            <?=form_open($shop_url . 'basket/add_note', 'class="add-note"')?>
                                <div class="input-group">
                                    <?=form_input('note', set_value('notes', $basket->note), 'placeholder="Enter any special instructions or notes about your order." class="form-control"')?>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            Save
                                        </button>
                                    </span>
                                </div><!-- /input-group -->
                            <?=form_close()?>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-xs-12 col-sm-6 hidden-xs">
                        <?=anchor($continue_shopping_url, 'Continue Shopping', 'class="btn btn-lg btn-default"')?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <?=anchor($shop_url . 'checkout', 'Checkout Now', 'class="btn btn-lg btn-success pull-right"')?>
                    </div>
                </div>
                <hr />
                <?php

            } else {

                ?>
                <div class="basket-empty well well-default">
                    <h3 class="text-center">
                        Your basket is empty
                        <br /><br />
                        <?=anchor($shop_url, 'Go Shopping', 'class="btn btn-primary btn-sm"')?>
                    </h3>
                </div>
                <?php
            }
        ?>
        </div>
    </div>
    <?php

    $footerText = app_setting('basket_footer', 'shop-' . $skin->slug);

    if (!empty($footerText)) {

        echo '<div class="row">';
            echo '<div class="col-md-12">';
                echo $footerText;
                echo '<hr/>';
            echo '</div>';
        echo '</div>';
    }

    if (!empty($recently_viewed)) {

        echo ' <div class="row">';
            echo '<div class="col-md-12">';
                echo '<h4>Recently Viewed</h4>';
            echo '</div>';
        echo '</div>';
        echo '<div class="row product-browser">';

        foreach ($recently_viewed as $product) {

            echo '<div class="product col-sm-2">';

                if ($product->featured_img) {

                    $url = cdn_thumb($product->featured_img, 360, 360);

                } else {

                    $url = $skin->url . 'assets/img/product-no-image.png';
                }

                echo '<div class="product-image">';
                    echo anchor($product->url, img(array('src' => $url, 'class' => 'img-responsive img-thumbnail center-block')));

                    if (count($product->variations) > 1) {

                        if (app_setting('browse_product_ribbon_mode', 'shop-' . $skin->slug) == 'corner') {

                            echo '<div class="ribbon corner">';
                                echo '<div class="ribbon-wrapper">';
                                    echo '<div class="ribbon-text">' . count($product->variations) . ' options' . '</div>';
                                echo '</div>';
                            echo '</div>';

                        } else {

                            echo '<div class="ribbon horizontal">';
                                echo count($product->variations) . ' options available';
                            echo '</div>';
                        }
                    }

                echo '</div>';

                echo '<p>' . anchor($product->url, $product->label) . '</p>';
                echo '<p>';
                    echo '<span class="badge">' . $product->price->user_formatted->price_string . '</span>';
                echo '</p>';
                echo '<hr class="hidden-sm hidden-md hidden-lg" />';

            echo '</div>';
        }

        echo '</div>';
    }

    ?>
</div>
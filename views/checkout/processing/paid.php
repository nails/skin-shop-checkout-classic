<?php

    $this->load->view('structure/header');

        if (!empty($order)) {

            ?>
            <div class="nails-shop-skin-checkout-classic processing paid" id="processing-container" data-order-ref="<?=$order->ref?>">
                <div class="row order-status">
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>
                                    Order: <?=$order->ref?>
                                    <small class="text-muted">
                                        This order was received on the
                                        <?=toUserDatetime($order->created, 'jS \o\f F Y \a\t H:i:s')?>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default order-status-feedback processing">
                            <div class="panel-body">
                                <p class="text-center">

                                    <span class="order-status-feedback-text processing">
                                        <b class="fa fa-spin fa-cog"></b>
                                        <small>Please wait, processing</small>
                                    </span>

                                    <span class="order-status-feedback-text unpaid">
                                        <b class="fa fa-exclamation-triangle"></b>
                                        <small>Unpaid</small>
                                    </span>

                                    <span class="order-status-feedback-text paid">
                                        <b class="fa fa-check-circle-o"></b>
                                        <small>Paid, thank you</small>
                                    </span>

                                    <span class="order-status-feedback-text abandoned">
                                        <b class="fa fa-times-circle"></b>
                                        <small>Abandoned</small>
                                    </span>

                                    <span class="order-status-feedback-text cancelled">
                                        <b class="fa fa-times-circle"></b>
                                        <small>Cancelled</small>
                                    </span>

                                    <span class="order-status-feedback-text failed">
                                        <b class="fa fa-exclamation-triangle"></b>
                                        <small>Failed</small>
                                    </span>

                                    <span class="order-status-feedback-text pending">
                                        <b class="fa fa-clock-o fa-spin"></b>
                                        <small>Pending</small>
                                    </span>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="thankyou-text" style="display:none">
                    <div class="row">
                        <div class="col-xs-12">
                        <?php

                            $title = app_setting('thankyou_title', 'shop-' . $skin->slug);
                            $text  = app_setting('thankyou_text', 'shop-' . $skin->slug);

                            echo '<h4>';
                                echo $title ? $title : '';
                            echo '</h4>';

                            echo $text ? $text : '<p>If you have any questions please don\'t hesitate to contact us.</p>';

                            // --------------------------------------------------------------------------

                            echo '<div class="panel panel-default invoice-actions">';
                                echo '<div class="panel-body">';
                                    echo '<a href="#" onclick="window.print()" class="btn btn-primary"><b class="fa fa-print"></b> Print</a> ';
                                    echo '<a href="' . site_url($shop_url . 'checkout/invoice/' . $order->ref . '/' . md5($order->code)) . '" class="btn btn-primary"><b class="fa fa-download"></b> Download</a> ';
                                echo '</div>';
                            echo '</div>';

                        ?>
                        </div>
                    </div>
                </div>
                <div class="row order-items">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-4 order-customer-details">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>
                                            <strong>
                                                <b class="fa fa-user"></b>
                                                Customer
                                            </strong>
                                        </p>
                                        <?php

                                            $avatarSize = 100;

                                            if ($order->user->profile_img) {

                                                $avatar = cdn_thumb($order->user->profile_img, $avatarSize, $avatarSize);

                                            } else {

                                                $avatar = 'https://secure.gravatar.com/avatar/' . md5($order->user->email) . '?r=pg&d=mm&s=' . $avatarSize;
                                            }

                                            echo img(array('src' => $avatar, 'class' => 'pull-right img-thumbnail'));

                                            echo '<ul class="list-unstyled">';
                                            echo '<li>';
                                                echo $order->user->first_name . ' ' . $order->user->last_name;
                                            echo '</li>';
                                            echo '<li>' . mailto($order->user->email) . '</li>';
                                            echo $order->user->telephone ? '<li><a href="tel:' . $order->user->telephone . '">' . $order->user->telephone . '</a></li>' : '';
                                            echo '</ul>';

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 order-address-delivery">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>
                                            <strong>
                                                <b class="fa fa-home"></b>
                                                Delivery Address
                                            </strong>
                                        </p>
                                        <?php

                                            $order->shipping_address = array_filter((array) $order->shipping_address);

                                            $address = implode(',', $order->shipping_address);

                                            if ($address) {

                                                $address = urlencode($address);
                                                $url = 'http://maps.google.com/maps/api/staticmap?markers=size:mid|color:black|' . $address . '&size=' . $avatarSize . 'x' . $avatarSize . '&sensor=FALSE';
                                                echo img(array('src' => $url, 'class' => 'img-thumbnail pull-right'));
                                            }

                                            echo '<ul class="list-unstyled">';
                                            echo '<li>' . implode('</li><li>', $order->shipping_address) . '</li>';
                                            echo '</ul>';

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 order-address-billing">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>
                                            <strong>
                                                <b class="fa fa-home"></b>
                                                Billing Address
                                            </strong>
                                        </p>
                                        <ul class="list-unstyled">
                                        <?php

                                            $order->billing_address = array_filter((array) $order->billing_address);

                                            $address = implode(',', $order->billing_address);

                                            if ($address) {

                                                $address = urlencode($address);
                                                $url = 'http://maps.google.com/maps/api/staticmap?markers=size:mid|color:black|' . $address . '&size=' . $avatar_size . 'x' . $avatar_size . '&sensor=FALSE';
                                                echo img(array('src' => $url, 'class' => 'img-thumbnail pull-right'));
                                            }

                                            echo '<ul class="list-unstyled">';
                                            echo '<li>' . implode('</li><li>', $order->billing_address) . '</li>';
                                            echo '</ul>';

                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($order->note)) { ?>
                <div class="row order-note">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>
                                    <strong>
                                        <b class="fa fa-pencil"></b>
                                        Notes
                                    </strong>
                                </p>
                                <?php

                                    echo '<p>';
                                    echo $order->note;
                                    echo '</p>';

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="row order-items">
                    <div class="col-xs-12">
                        <?php

                            $tableData                 = array();
                            $tableData['items']        = $order->items;
                            $tableData['totals']       = $order->totals;
                            $tableData['shippingType'] = $order->delivery_type;
                            $tableData['readonly']     = true;

                            $this->load->view($skin->path . 'views/basket/table', $tableData);

                        ?>
                    </div>
                </div>
            </div>
            <?php

        } else {

            ?>
            <div class="nails-shop-skin-checkout-classic processing-no-order">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p class="alert alert-danger">
                                    <strong>There was an error</strong>
                                    <br />I'm having trouble looking up your order.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        }

    $this->load->view('structure/footer');
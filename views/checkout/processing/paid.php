<?php

    $this->load->view('structure/header');

        if (!empty($order)) {

            ?>
            <div class="nails-skin-shop-checkout-classic processing paid" id="processing-container" data-order-ref="<?=$order->ref?>">
                <div class="alert alert-danger" id="processing-error" style="display: none;">
                    <strong>An error occurred.</strong>
                    <br /><span></span>
                </div>
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
                                        <b class="glyphicon glyphicon-cog spin"></b>
                                        <small>Please wait, processing</small>
                                    </span>

                                    <span class="order-status-feedback-text unpaid">
                                        <b class="glyphicon glyphicon-exclamation-sign"></b>
                                        <small>Unpaid</small>
                                    </span>

                                    <span class="order-status-feedback-text paid">
                                        <b class="glyphicon glyphicon-ok-sign"></b>
                                        <small>Paid, thank you</small>
                                    </span>

                                    <span class="order-status-feedback-text abandoned">
                                        <b class="glyphicon glyphicon-remove-sign"></b>
                                        <small>Abandoned</small>
                                    </span>

                                    <span class="order-status-feedback-text cancelled">
                                        <b class="glyphicon glyphicon-remove-sign"></b>
                                        <small>Cancelled</small>
                                    </span>

                                    <span class="order-status-feedback-text failed">
                                        <b class="glyphicon glyphicon-exclamation-sign"></b>
                                        <small>Failed</small>
                                    </span>

                                    <span class="order-status-feedback-text pending">
                                        <b class="glyphicon glyphicon-time"></b>
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

                            $title = appSetting('thankyou_title', 'shop-' . $skin->name);
                            $text  = appSetting('thankyou_text', 'shop-' . $skin->name);

                            echo '<h4>';
                                echo $title ? $title : '';
                            echo '</h4>';

                            echo $text ? $text : '<p>If you have any questions please don\'t hesitate to contact us.</p>';

                            // --------------------------------------------------------------------------

                            echo '<div class="panel panel-default invoice-actions">';
                                echo '<div class="panel-body">';
                                    echo '<a href="#" onclick="window.print()" class="btn btn-primary">';
                                        echo '<b class="glyphicon glyphicon-print"></b> Print';
                                    echo '</a> ';
                                    echo '<a href="' . site_url($shop_url . 'checkout/invoice/' . $order->ref . '/' . md5($order->code)) . '" class="btn btn-primary">';
                                        echo '<b class="glyphicon glyphicon-cloud-download"></b> Download';
                                    echo '</a>';
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
                                                <b class="glyphicon glyphicon-user"></b>
                                                Customer
                                            </strong>
                                        </p>
                                        <?php

                                            $avatarSize = 100;

                                            if ($order->user->profile_img) {

                                                $avatar = cdnCrop($order->user->profile_img, $avatarSize, $avatarSize);

                                            } else {

                                                $avatar = 'https://secure.gravatar.com/avatar/' . md5($order->user->email) . '?r=pg&d=mm&s=' . $avatarSize;
                                            }

                                            echo img(array('src' => $avatar, 'class' => 'pull-right img-thumbnail'));

                                            echo '<ul class="list-unstyled">';
                                            echo '<li>';
                                                echo $order->user->first_name . ' ' . $order->user->last_name;
                                            echo '</li>';
                                            echo '<li>' . mailto($order->user->email) . '</li>';
                                                if ($order->user->telephone) {

                                                    echo '<li>';
                                                        echo tel($order->user->telephone);
                                                    echo '<li>';

                                                }
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
                                                <b class="glyphicon glyphicon-home"></b>
                                                Delivery Address
                                            </strong>
                                        </p>
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

                                            if ($address) {

                                                $url = 'http://maps.google.com/maps/api/staticmap?markers=size:mid|color:black|' . urlencode(implode(', ', $address)) . '&size=' . $avatarSize . 'x' . $avatarSize . '&sensor=FALSE';
                                                echo img(array('src' => $url, 'class' => 'img-thumbnail pull-right'));
                                            }

                                            echo '<ul class="list-unstyled">';
                                                echo '<li>' . implode('</li><li>', $address) . '</li>';
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
                                                <b class="glyphicon glyphicon-home"></b>
                                                Billing Address
                                            </strong>
                                        </p>
                                        <ul class="list-unstyled">
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

                                            if ($address) {

                                                $url = 'http://maps.google.com/maps/api/staticmap?markers=size:mid|color:black|' . urlencode(implode(', ', $address)) . '&size=' . $avatarSize . 'x' . $avatarSize . '&sensor=FALSE';
                                                echo img(array('src' => $url, 'class' => 'img-thumbnail pull-right'));
                                            }

                                            echo '<ul class="list-unstyled">';
                                                echo '<li>' . implode('</li><li>', $address) . '</li>';
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
                                        <b class="glyphicon glyphicon-pencil"></b>
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

                            $tableData             = array();
                            $tableData['items']    = $order->items;
                            $tableData['totals']   = $order->totals;
                            $tableData['readonly'] = true;

                            $this->load->view($skin->path . 'views/basket/table', $tableData);

                        ?>
                    </div>
                </div>
            </div>
            <?php

        } else {

            ?>
            <div class="nails-skin-shop-checkout-classic processing-no-order">
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
<div class="nails-shop-skin-checkout-classic maintenance">
    <div class="row">
        <div class="col-md-12">
        <?php

            if (app_setting('maintenance_title', 'shop')) {

                echo '<h1>' . app_setting('maintenance_title', 'shop') . '</h1>';

            } else {

                echo '<h1 class="text-center">';
                    echo 'Down for maintenance';
                echo '</h1>';

            }

            if (app_setting('maintenance_body', 'shop')) {

                echo app_setting('maintenance_body', 'shop');

            } else {

                echo '<p class="text-center">';
                    echo 'Please bear with us as we bring improvements to the shop.';
                echo '</p>';

            }

        ?>
        </div>
    </div>
</div>
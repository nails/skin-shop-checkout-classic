<div class="nails-skin-shop-checkout-classic maintenance">
    <div class="row">
        <div class="col-md-12">
        <?php

            if (appSetting('maintenance_title', 'nailsapp/module-shop')) {

                echo '<h1>' . appSetting('maintenance_title', 'nailsapp/module-shop') . '</h1>';

            } else {

                echo '<h1 class="text-center">';
                    echo 'Down for maintenance';
                echo '</h1>';

            }

            if (appSetting('maintenance_body', 'nailsapp/module-shop')) {

                echo appSetting('maintenance_body', 'nailsapp/module-shop');

            } else {

                echo '<p class="text-center">';
                    echo 'Please bear with us as we bring improvements to the shop.';
                echo '</p>';

            }

        ?>
        </div>
    </div>
</div>
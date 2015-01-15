<div class="row">
	<div class="col-md-12">
		<p>
			We support multiple payment processors, please choose which one you would like to use to complete your order:
		</p>
		<ul>
		<?php

			foreach ( $payment_gateways as $gateway ) :

				echo '<li>';
					echo anchor( $shop_url . 'checkout/payment/' . $order_ref . '/' . $order_code . '/' . $gateway, $gateway );
				echo '</li>';

			endforeach;

		?>
		</ul>
	</div>
</div>
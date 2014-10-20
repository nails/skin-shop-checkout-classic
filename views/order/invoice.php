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
				font-size:14px;
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
											<td><?=date ( 'jS M Y, H:i:s', strtotime( $order->created ) )?></td>
										</tr>
										<tr>
											<td class="head">Status</td>
											<td class="status <?=strtolower( $order->status )?>"><?=$order->status?></td>
										</tr>
										<tr>
											<td class="head">To</td>
											<td>
											<?php

												echo '<strong>' . $order->user->first_name . ' ' . $order->user->last_name . '</strong>';

												// --------------------------------------------------------------------------

												$_shipping = $order->shipping_address;
												$_shipping = array_filter( (array) $_shipping );
												echo '<br />' . implode( '<br />', $_shipping );

												// --------------------------------------------------------------------------

												echo '<br /><br />' . $order->user->email;
												echo '<br />' . $order->user->telephone;
											?>
											</td>
										</tr>
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

												$_invoice_company		= app_setting( 'invoice_company',		'shop' );
												$_invoice_address		= app_setting( 'invoice_address',		'shop' );
												$_invoice_vat_no		= app_setting( 'invoice_vat_no',		'shop' );
												$_invoice_company_no	= app_setting( 'invoice_company_no',	'shop' );

												echo $_invoice_company		? '<strong>' . $_invoice_company . '</strong>' : '<strong>' . APP_NAME . '</strong>';
												echo $_invoice_address		? '<br />' . nl2br( $_invoice_address ) . '<br />' : '';
												echo $_invoice_vat_no		? '<br />VAT No.: ' . $_invoice_vat_no : '';
												echo $_invoice_company_no	? '<br />Company No.: ' . $_invoice_company_no : '';
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
					</tr>
				</thead>

				<tbody>
				<?php

				foreach ( $order->items AS $item ) :

					?>
					<tr>
						<td class="barcode">
						<?php

							if ( ! empty( $item->sku ) ) :

								//	TODO: Get barcodes working
								//	echo img( array( 'src' => 'barcode/' . $item->sku, 'class' => 'barcode' ) );
								echo $item->sku;

							else :

								?>
								<span class="fa-stack fa-lg">
									<i class="fa fa-barcode fa-stack-1x"></i>
									<i class="fa fa-ban fa-stack-2x text-danger" style="opacity: 0.5"></i>
								</span>
								<?php

							endif;
						?>
						</td>
						<td class="quantity">
							<?=$item->quantity?>
						</td>
						<td class="product">
						<?php

							echo '<strong>' . $item->product_label . '</strong>';
							echo '<br>' . $item->variant_label;
							if ( ! empty( $item->extra_data['to_order']->is_to_order) ) :

								echo '<div class="alert alert-warning" style="margin-top:1em;">';
								echo 'This item is to order. Lead time: ' . $item->extra_data['to_order']->lead_time;
								echo '</div>';

							endif;

						?>
						</td>
						<td class="unit-cost">
							<?=$item->price->base_formatted->value?>
						</td>
					</tr>
					<?php

				endforeach;

				?>
				</tbody>

				<tfoot>
					<tr>
						<th class="total-text" colspan="3" style="text-align:right;">Sub Total</th>
						<th class="total-value"><?=$order->totals->base_formatted->item?></th>
					</tr>
					<tr>
						<th class="total-text" colspan="3" style="text-align:right;">Shipping</th>
						<th class="total-value"><?=$order->totals->base_formatted->shipping?></th>
					</tr>
					<tr>
						<th class="total-text" colspan="3" style="text-align:right;">Tax</th>
						<th class="total-value"><?=$order->totals->base_formatted->tax?></th>
					</tr>
					<tr>
						<th class="total-text" colspan="3" style="text-align:right;">Total</th>
						<th class="total-value"><?=$order->totals->base_formatted->grand?></th>
					</tr>
				</tfoot>

			</table>
			<?php

				if ( app_setting( 'invoice_footer', 'shop' ) ) :

					echo '<p id="invoice-footer">';
						echo app_setting( 'invoice_footer', 'shop' );
					echo '</p>';

				endif;

			?>
		</div>
	</body>
</html>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="col-xs-6 col-sm-8">Product</th>
								<th class="col-xs-3 col-sm-2 text-center">Quantity</th>
								<th class="col-xs-3 col-sm-2 text-center">Unit Price</th>
							</tr>
						</thead>
						<tbody>
						<?php

							foreach ( $items AS $item ) :

								?>
								<tr class="basket-item">
									<td class="vertical-align-middle">
									<?php

										if ( ! empty( $item->variant->featured_img ) ) :

											$_featured_img = $item->variant->featured_img;

										elseif ( ! empty( $item->product->featured_img ) ) :

											$_featured_img = $item->product->featured_img;

										else :

											$_featured_img = FALSE;

										endif;

										if ( $_featured_img ) :

											echo '<div class="col-xs-2 hidden-xs hidden-sm">';

												$_url = cdn_thumb( $_featured_img, 175, 175 );
												echo img( array( 'src' => $_url, 'class' => 'img-thumbnail' ) );

											echo '</div>';
											echo '<div class="col-sm-12 col-md-10">';

										else :

											echo '<div class="col-sm-12">';

										endif;

										// --------------------------------------------------------------------------

										//	Label
										echo anchor( $item->product->url, '<strong>' . $item->product->label . '</strong>' );

										if ( $item->variant->label !== $item->product->label ) :

											echo '<br />';
											echo '<em>' . $item->variant->label . '</em>';

										endif;

										// --------------------------------------------------------------------------

										//	SKU
										if ( ! empty( $item->variant->sku ) ) :

											echo '<br />';
											echo '<small class="text-muted">';
												echo '<em>' . $item->variant->sku . '</em>';
											echo '</small>';

										endif;

										// --------------------------------------------------------------------------

										//	Collection Only
										if ( $item->variant->shipping->collection_only ) :

											echo '<div class="alert alert-warning">';
												echo '<strong>Note:</strong> This item is collection only.';
											echo '</div>';

										endif;

										// --------------------------------------------------------------------------

										if ( $_featured_img ) :

											echo '</div>';

										endif;

									?>
									</td>
									<td class="vertical-align-middle text-center">
										<div class="row">
										<?php

											if ( empty( $readonly ) ) :

												echo '<div class="col-xs-4">';
												echo anchor( $shop_url . 'basket/decrement?variant_id=' . $item->variant->id, '<span class="fa fa-minus-circle fa-lg text-muted"></span>', 'class="pull-right"' );
												echo '</div>';

												echo '<div class="col-xs-4">';

											endif;

											echo '<span class="variant-quantity-' . $item->variant->id . '">';
												echo number_format( $item->quantity );
											echo '</span>';

											if ( empty( $readonly ) ) :

												echo '</div>';

												echo '<div class="col-xs-4">';
												echo anchor( $shop_url . 'basket/increment?variant_id=' . $item->variant->id, '<span class="fa fa-plus-circle fa-lg text-muted"></span>', 'class="pull-left"' );
												echo '</div>';

											endif;

										?>
										</div>
									</td>
									<td class="vertical-align-middle text-center">
									<?php

										if ( app_setting( 'price_exclude_tax', 'shop' ) ) :

											echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
												echo $item->variant->price->price->user_formatted->value_ex_tax;
											echo '</span>';

											if ( ! app_setting( 'omit_variant_tax_pricing', 'shop-' . $skin_checkout->slug ) && $item->variant->price->price->user->value_tax > 0 ) :

												echo '<br />';
												echo '<small class="text-muted">';
													echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
														echo $item->variant->price->price->user_formatted->value_inc_tax;
													echo '</span>';
													echo ' inc. tax';
												echo '</small>';

											endif;

										else :

											echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
												echo $item->variant->price->price->user_formatted->value_inc_tax;
											echo '</span>';

											if ( ! app_setting( 'omit_variant_tax_pricing', 'shop-' . $skin_checkout->slug ) && $item->variant->price->price->user->value_tax > 0 ) :

												echo '<br />';
												echo '<small class="text-muted">';
													echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
														echo $item->variant->price->price->user_formatted->value_ex_tax;
													echo '</span>';
													echo ' ex. tax';
												echo '</small>';

											endif;

										endif;

									?>
									</td>
								</tr>
								<?php

							endforeach;

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
									Shipping
								</th>
								<th class="text-center value">
									<?=$totals->user_formatted->shipping?>
								</th>
							</tr>

							<!-- Tax Total -->
							<tr class="basket-total-tax">
								<th colspan="2" class="text-right">
								<?php

									if ( app_setting( 'price_exclude_tax', 'shop' ) ) :

										echo 'Tax';

									else :

										echo 'Tax (included)';

									endif;

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
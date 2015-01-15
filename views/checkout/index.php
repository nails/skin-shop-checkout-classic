<noscript>
	 <style> .jsonly { display: none } </style>
</noscript>
<div class="nails-shop-skin-checkout-classic checkout">
	<?=form_open( NULL, 'id="checkout-form"')?>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-12">
					<h1>Checkout</h1>
				</div>
			</div>
			<div class="row">
			<?php

				echo  '<div class="col-md-12">';

					$_intro_text = cms_render_block( 'shop_checkout_intro' );

					if ( ! empty( $_intro_text ) ) :

						echo $_intro_text;

					else :

						echo '<p>Simply complete the forms below and then click or tap the "Place Order &amp; Pay" button.</p>';

						if ( ! $this->user->is_logged_in() ) :

							echo '<p>You are welcome to checkout as a guest, however we recommend creating an account so that you can track your order and have a quicker checkout experience next time.</p>';

						else :

							echo '<p>';
								echo 'You are currently logged in as: <strong>' . active_user( 'first_name,last_name' ) . ' (' . active_user( 'email' ) . ')</strong>. ';
								echo anchor( 'auth/logout', 'Not you?' );
							echo '</p>';

						endif;

					endif;

				echo '</div>';

			?>
			</div>

			<noscript>

				<p class="alert alert-warning">
					<strong><b class="fa fa-exclamation-triangle"></b> Please enable JavaScript</strong>
					<br />The checkout procedure requires that you enable JavaScript.
				</p>
			</noscript>

			<div class="jsonly">

				<div class="progress hidden" id="progress-bar">
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
				</div>

				<hr id="progress-bar-hr" />

				<div class="panel panel-default" id="checkout-step-1">
					<div class="panel-heading">
						<h3 class="panel-title">
							Step 1 of 3: Contact &amp; Delivery Details
							<b class="validate-ok fa fa-check-circle fa-lg pull-right text-success hidden"></b>
							<b class="validate-fail fa fa-times-circle fa-lg pull-right text-danger hidden"></b>
						</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-6">
							<h4>Delivery address</h4>
							<hr>
							<div role="form">
							<?php

								$_options		= array();
								$_options[]		= array(
									'key'		=> 'delivery_address_line_1',
									'label'		=> 'Address Line 1',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'delivery_address_line_2',
									'label'		=> 'Address Line 2',
									'required'	=> false
								);
								$_options[]		= array(
									'key'		=> 'delivery_address_town',
									'label'		=> 'City/Town',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'delivery_address_state',
									'label'		=> 'Region/State',
									'required'	=> false
								);
								$_options[]		= array(
									'key'		=> 'delivery_address_postcode',
									'label'		=> 'Postal Code',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'delivery_address_country',
									'label'		=> 'Country',
									'required'	=> true,
									'select'	=> $countries_flat
								);

								foreach ( $_options as $opt ) :

									$_error				= form_error( $opt['key'], '<p class="help-block">', '</p>' );
									$_has_error			= $_error ? 'has-error' : '';
									$_has_feedback		= $_error ? 'has-feedback' : '';
									$_required			= $opt['required'] ? '*' : '';
									$_feedback_hidden	= $_has_feedback ? '' : 'hidden';
									$_active_user		= active_user( $opt['key'] );
									$_active_user		= is_string( $_active_user ) ? $_active_user : '';
									$_value				= set_value( $opt['key'], $_active_user );

									echo '<div class="form-group ' . $_has_error . ' ' . $_has_feedback . '">';
										echo '<label class="control-label" for="' . $opt['key'] . '">';
											echo $opt['label'];
											echo $_required;
										echo '</label>';

										if ( ! empty( $opt['select'] ) ) :

											echo '<select name="' . $opt['key'] . '" class="form-control select2" id="' . $opt['key'] . '">';
											echo '<option value="">Please Choose...</option>';
											foreach ( $opt['select'] as $value => $label ) :

												$_selected = $value == $_value ? 'selected="selected"' : '';
												echo '<option value="' . $value . '" ' . $_selected . '>' . $label .'</option>';

											endforeach;
											echo '</select>';

										else :

											echo '<input name="' . $opt['key'] . '" type="text" class="form-control" id="' . $opt['key'] . '" value="' . $_value . '">';

										endif;

										echo '<span class="glyphicon glyphicon-remove form-control-feedback ' . $_feedback_hidden . '"></span>';
										echo $_error;
									echo '</div>';

								endforeach;

							?>
							</div>
						</div>
						<div class="col-md-6">
							<h4>Contact information</h4>
							<hr>
							<div role="form">
							<?php

								$_options		= array();
								$_options[]		= array(
									'key'		=> 'first_name',
									'label'		=> 'First Name',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'last_name',
									'label'		=> 'Surname',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'email',
									'label'		=> 'Email address',
									'required'	=> true
								);
								$_options[]		= array(
									'key'		=> 'telephone',
									'label'		=> 'Telephone',
									'required'	=> true
								);

								foreach ( $_options as $opt ) :

									$_error				= form_error( $opt['key'], '<p class="help-block">', '</p>' );
									$_has_error			= $_error ? 'has-error' : '';
									$_has_feedback		= $_error ? 'has-feedback' : '';
									$_required			= $opt['required'] ? '*' : '';
									$_type				= $opt['key'] == 'email' ? 'email' : 'text';
									$_feedback_hidden	= $_has_feedback ? '' : 'hidden';
									$_active_user		= active_user( $opt['key'] );
									$_active_user		= is_string( $_active_user ) ? $_active_user : '';
									$_value				= set_value( $opt['key'], $_active_user );

									echo '<div class="form-group ' . $_has_error . ' ' . $_has_feedback . '">';
										echo '<label class="control-label" for="' . $opt['key'] . '">';
											echo $opt['label'];
											echo $_required;
										echo '</label>';
										echo '<input name="' . $opt['key'] . '" type="' . $_type . '" class="form-control" id="' . $opt['key'] . '" value="' . $_value . '">';
										echo '<span class="glyphicon glyphicon-remove form-control-feedback ' . $_feedback_hidden . '"></span>';
										echo $_error;
									echo '</div>';

								endforeach;

							?>
							</div>
						</div>
					</div>
					<div class="panel-footer hidden">
						<button class="btn action-continue btn-primary btn-success pull-right">Continue</button>
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="panel panel-default" id="checkout-step-2">
					<div class="panel-heading">
						<h3 class="panel-title">
							Step 2 of 3: Billing Details
							<b class="validate-ok fa fa-check-circle fa-lg pull-right text-success hidden"></b>
							<b class="validate-fail fa fa-times-circle fa-lg pull-right text-danger hidden"></b>
						</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-12">
							<h4>Billing address</h4>
							<hr>
							<label>
								<input name="same_billing_address" type="checkbox" checked="checked" id="same-billing-address">
								My billing address is the same as my delivery address
							</label>

							<div class="row billing-address" id="billing-address">
								<div class="col-md-6">
									<hr />
									<div role="form">
									<?php

										$_options		= array();
										$_options[]		= array(
											'key'		=> 'billing_address_line_1',
											'label'		=> 'Address Line 1',
											'required'	=> true
										);
										$_options[]		= array(
											'key'		=> 'billing_address_line_2',
											'label'		=> 'Address Line 2',
											'required'	=> false
										);
										$_options[]		= array(
											'key'		=> 'billing_address_town',
											'label'		=> 'City/Town',
											'required'	=> true
										);
										$_options[]		= array(
											'key'		=> 'billing_address_state',
											'label'		=> 'Region/State',
											'required'	=> false
										);
										$_options[]		= array(
											'key'		=> 'billing_address_postcode',
											'label'		=> 'Postal Code',
											'required'	=> true
										);
										$_options[]		= array(
											'key'		=> 'billing_address_country',
											'label'		=> 'Country',
											'required'	=> true,
											'select'	=> $countries_flat
										);

										foreach ( $_options as $opt ) :

											$_error				= form_error( $opt['key'], '<p class="help-block">', '</p>' );
											$_has_error			= $_error ? 'has-error' : '';
											$_has_feedback		= $_error ? 'has-feedback' : '';
											$_required			= $opt['required'] ? '*' : '';
											$_feedback_hidden	= $_has_feedback ? '' : 'hidden';
											$_active_user		= active_user( $opt['key'] );
											$_active_user		= is_string( $_active_user ) ? $_active_user : '';
											$_value				= set_value( $opt['key'], $_active_user );

											echo '<div class="form-group ' . $_has_error . ' ' . $_has_feedback . '">';
												echo '<label class="control-label" for="' . $opt['key'] . '">';
													echo $opt['label'];
													echo $_required;
												echo '</label>';

												if ( ! empty( $opt['select'] ) ) :

													echo '<select name="' . $opt['key'] . '" class="form-control select2" id="' . $opt['key'] . '">';
													echo '<option value="">Please Choose...</option>';
													foreach ( $opt['select'] as $value => $label ) :

														$_selected = $value == $_value ? 'selected="selected"' : '';
														echo '<option value="' . $value . '" ' . $_selected . '>' . $label .'</option>';

													endforeach;
													echo '</select>';

												else :

													echo '<input name="' . $opt['key'] . '" type="text" class="form-control" id="' . $opt['key'] . '" value="' . $_value . '">';

												endif;

												echo '<span class="glyphicon glyphicon-remove form-control-feedback ' . $_feedback_hidden . '"></span>';
												echo $_error;
											echo '</div>';

										endforeach;

									?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<button class="btn action-back btn-primary btn-warning">Back</button>
						<button class="btn action-continue btn-primary btn-success pull-right">Continue</button>
						<div class="clearfix"></div>
					</div>

				</div>

				<div class="panel panel-default" id="checkout-step-3">
					<div class="panel-heading">
						<h3 class="panel-title">
							Step 3 of 3: Payment Details
							<b class="validate-ok fa fa-check-circle fa-lg pull-right text-success hidden"></b>
							<b class="validate-fail fa fa-times-circle fa-lg pull-right text-danger hidden"></b>
						</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-12">
							<p>
								Please choose how you wish to pay.
							</p>
							<hr />
							<div class="row">
								<div class="col-sm-5">
									<p id="payment-gateway-choose-error" class="alert alert-danger hidden">
										Please choose how you'd like to pay.
									</p>
									<ul class="list-unstyled">
									<?php

										foreach ( $payment_gateways as $gateway ) :

											//	Forgive me Gods of CSS.
											?>
											<li>
												<table class="checkout-payment-gateway-layout" data-is-redirect="<?=(int) $gateway->is_redirect?>">
													<tbody>
														<tr>
															<td class="pg-radio" rowspan="2">
																<?=form_radio( 'payment_gateway', $gateway->slug, set_radio( 'payment_gateway', $gateway->slug ), 'data-is-redirect="' . (int) $gateway->is_redirect . '"' )?>
															</td>
															<td class="pg-img"><?=$gateway->img ? img( array( 'src' => cdn_serve( $gateway->img ), 'class' => 'img-responsive' ) ) : '' ?></td>
														</tr>
														<tr>
															<td class="pg-label">
																<?=$gateway->label?>
															</td>
														</tr>
													</tbody>
												</table>
											</li>
											<?php

										endforeach;

									?>
									</ul>
								</div>
								<div class="col-sm-7">
									<?php

										$_chosen_gateway = set_value( 'payment_gateway' );

										if ( $_chosen_gateway ) :

											if ( $this->shop_payment_gateway_model->is_redirect( $_chosen_gateway ) ) :

												$_active = '';

											else :

												$_active = 'active';

											endif;


										else :

											$_active = '';

										endif;

									?>
									<div id="card-form" class="clearfix <?=$_active?>">
										<p id="payment-card-error" class="alert alert-danger hidden">
											Please verify all details are correct.
										</p>
										<?php

											echo ! empty( $payment_error ) ? '<p class="alert alert-danger">' . $payment_error . '</p>' : '';

										?>
										<div class="credit-card-input no-js" id="skeuocard">
											<label for="cc_type">Card Type</label>
											<select name="cc_type">
												<option value="">...</option>
												<option value="visa">Visa</option>
												<option value="discover">Discover</option>
												<option value="mastercard">MasterCard</option>
												<option value="maestro">Maestro</option>
												<option value="jcb">JCB</option>
												<option value="unionpay">China UnionPay</option>
												<option value="amex">American Express</option>
												<option value="dinersclubintl">Diners Club</option>
											</select>
											<label for="cc_number">Card Number</label>
											<input type="text" name="cc_number" id="cc_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" size="19">
											<label for="cc_exp_month">Expiration Month</label>
											<input type="text" name="cc_exp_month" id="cc_exp_month" placeholder="00">
											<label for="cc_exp_year">Expiration Year</label>
											<input type="text" name="cc_exp_year" id="cc_exp_year" placeholder="00">
											<label for="cc_name">Cardholder's Name</label>
											<input type="text" name="cc_name" id="cc_name" placeholder="John Doe">
											<label for="cc_cvc">Card Validation Code</label>
											<input type="text" name="cc_cvc" id="cc_cvc" placeholder="123" maxlength="3" size="3">
										</div>
										<div class="mask"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<button class="btn action-back btn-primary btn-warning">Back</button>
						<button type="submit" class="btn action-continue btn-primary btn-primary pull-right">Place Order &amp; Pay</button>
						<div class="clearfix"></div>
					</div>
				</div>
				<?php

				$footerText	= app_setting('checkout_footer', 'shop-' . $skin_checkout->slug);

				if(!empty($footerText)) {

					echo $footerText;
				}

				?>
			</div>

		</div>

	</div>
	<?=form_close()?>
</div>
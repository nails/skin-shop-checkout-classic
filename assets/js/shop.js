/**
 * Javascript for the "Classic" shop skin
 */

var _nails_skin_shop_checkout_classic;
_nails_skin_shop_checkout_classic = function() {
    this._checkout_skeuocard = null;

    // --------------------------------------------------------------------------

    /**
     * Constructs the shop JS. Conditionally initiates items depending on the
     * actively viewed page.
     * @return void
     */
    this.__construct = function() {

        var breakpoint;

        breakpoint = this.bsCurrentBreakpoint();

        if ($('.nails-shop-skin-checkout-classic.basket').length > 0) {
            //  Mobile JS
            if (breakpoint === 'xs' || breakpoint === 'sm') {
                this._basket_init();
            }
        }

        // --------------------------------------------------------------------------

        if ($('.nails-shop-skin-checkout-classic.checkout').length > 0) {
            this._checkout_init();
        }

        // --------------------------------------------------------------------------

        if ($('.nails-shop-skin-checkout-classic.processing').length > 0) {
            this._processing_init();
        }
    };

    // --------------------------------------------------------------------------

    this._basket_init = function() {
        /*
         * Switch pages depending on delivery option selected
         * Options are standard delivery and collection
        */
        $('#selectDeliveryOption').on('change', function() {
            var url = $(this).find(':selected').data('url');
            window.location = url;
        });
    };

    // --------------------------------------------------------------------------

    this._checkout_init = function() {
        //  Show hidden elements, as JS is enabled
        $('#checkout-step-1 .panel-footer').removeClass('hidden');
        $('#checkout-step-2 .panel-body').hide();
        $('#checkout-step-2 .panel-footer').hide();
        $('#checkout-step-2 .panel-footer').removeClass('hidden');
        $('#checkout-step-3 .panel-body').hide();
        $('#checkout-step-3 .panel-footer').hide();
        $('#progress-bar').removeClass('hidden');
        $('#progress-bar-hr').remove();

        this._checkout_set_progress(1);

        /*
         * If the "My billing address is the same as my delivery address" checkbox
         * is checked, hide the billing address fields
         */

        if ($('#same-billing-address').prop('checked')) {
            $('#billing-address').hide();
        }
        else {
            $('#billing-address').show();
        }

        //  Skeumorphic card entry
        this._checkout_skeuocard = new Skeuocard($('#skeuocard'), {
            dontFocus: true
        });

        // --------------------------------------------------------------------------

        //  Bind listeners
        var _this = this;

        //  Step 1
        $('#checkout-step-1 .panel-footer .action-continue').on('click', function() {
            if (_this._checkout_validate_step_1())
            {
                $('#checkout-step-1 .panel-body').slideUp();
                $('#checkout-step-1 .panel-footer').slideUp();

                $('#checkout-step-2 .panel-body').slideDown();
                $('#checkout-step-2 .panel-footer').slideDown();

                _this._checkout_set_progress(2);

            } else {


            }

            return false;
        });

        // --------------------------------------------------------------------------

        //  Step 2
        $('#checkout-step-2 .panel-footer .action-continue').on('click', function() {
            if (_this._checkout_validate_step_2())
            {
                $('#checkout-step-2 .panel-body').slideUp();
                $('#checkout-step-2 .panel-footer').slideUp();

                $('#checkout-step-3 .panel-body').slideDown();
                $('#checkout-step-3 .panel-footer').slideDown();

                _this._checkout_set_progress(3);

            } else {

            }

            return false;
        });

        $('#checkout-step-2 .panel-footer .action-back').on('click', function() {
            $('#checkout-step-1 .panel-body').slideDown();
            $('#checkout-step-1 .panel-footer').slideDown();

            $('#checkout-step-2 .panel-body').slideUp();
            $('#checkout-step-2 .panel-footer').slideUp();

            $('#checkout-step-1 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-1 .panel-heading .validate-fail').addClass('hidden');

            $('#checkout-step-2 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-2 .panel-heading .validate-fail').addClass('hidden');

            _this._checkout_set_progress(1);

            return false;

        });

        //  Billing address checkbox
        $('#same-billing-address').on('change', function() {
            if ($(this).prop('checked')) {
                $('#billing-address').slideUp();
            }
            else {
                $('#billing-address').slideDown();
            }
        });

        // --------------------------------------------------------------------------

        //  Step 3
        $('#checkout-step-3 .panel-footer .action-continue').on('click', function() {
            if (_this._checkout_validate_step_3()) {
                $('#progress-bar .progress-bar')
                    .attr('data-originaltext', $('#progress-bar .progress-bar').text())
                    .text('Please wait while we get things started...')
                    .addClass('active');

                $('#progress-bar').addClass('please-wait');
                $('#checkout-step-3 .panel-body').slideUp();
                $('#checkout-step-3 .panel-footer').slideUp(function() {
                    // Different payment gateways handle things differently
                    switch ($('input[name=payment_gateway]:checked').val().toLowerCase()) {
                        case 'stripe' :

                            var _publishableKey = window.NAILS.SHOP_Checkout_Stripe_publishableKey;
                            Stripe.setPublishableKey(_publishableKey);

                            Stripe.card.createToken({
                                number:     $('#card-form input[name=cc_number]').val(),
                                cvc:        $('#card-form input[name=cc_cvc]').val(),
                                exp_month:  $('#card-form input[name=cc_exp_month]').val(),
                                exp_year:   $('#card-form input[name=cc_exp_year]').val()
                            }, function(status, response) {
                                if (response.error) {
                                    //  Show the form again
                                    $('#checkout-step-3 .panel-body').slideDown();
                                    $('#checkout-step-3 .panel-footer').slideDown();

                                    //  Reset the status bar
                                    $('#progress-bar').removeClass('please-wait');
                                    $('#progress-bar .progress-bar')
                                        .text($('#progress-bar .progress-bar').attr('data-originaltext'))
                                        .attr('data-originaltext', '')
                                        .removeClass('active');

                                    $('#processing-error')
                                        .show()
                                        .find('span')
                                        .text(response.error.message);
                                }
                                else {
                                    // response contains id and card, which contains additional card details
                                    var token = response.id;

                                    // Insert the token into the form so it gets submitted to the server
                                    var _hidden = $('#stripe-hidden-token');

                                    if (_hidden.length === 0) {
                                        var _input = $('<input>')
                                            .attr('type', 'hidden')
                                            .attr('name', 'stripe_token')
                                            .attr('id', 'stripe-hidden-token');

                                        $('#card-form').prepend(_input);

                                        _hidden = $('#stripe-hidden-token');
                                    }

                                    _hidden.val(token);

                                    //  Null out the form fields (so Card details aren't passed to the server)
                                    $('#card-form input[name=cc_number]').val('');
                                    $('#card-form input[name=cc_cvc]').val('');
                                    $('#card-form input[name=cc_exp_month]').val('');
                                    $('#card-form input[name=cc_exp_year]').val('');

                                    // and submit
                                    $('#checkout-form').submit();
                                }
                            });

                        break;
                        default:

                            //  Submit to server
                            $('#checkout-form').submit();

                        break;
                    }
                });

            } else {

            }

            return false;
        });

        $('#checkout-step-3 .panel-footer .action-back').on('click', function() {
            $('#checkout-step-2 .panel-body').slideDown();
            $('#checkout-step-2 .panel-footer').slideDown();

            $('#checkout-step-3 .panel-body').slideUp();
            $('#checkout-step-3 .panel-footer').slideUp();

            $('#checkout-step-2 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-2 .panel-heading .validate-fail').addClass('hidden');

            $('#checkout-step-3 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-3 .panel-heading .validate-fail').addClass('hidden');

            _this._checkout_set_progress(2);

            return false;

        });

        $('table.checkout-payment-gateway-layout input').on('click', function(e) {
            //  Allows for the inputs themselves to behave as expected when clicked
            e.stopPropagation();

            if ($(this).data('is-redirect')) {
                $('#card-form').removeClass('active');
            }
            else {
                $('#card-form').addClass('active');
            }
        });

        //  Trigger a click so the layout is setup properly.
        $('table.checkout-payment-gateway-layout input:checked').trigger('click');
    };

    // --------------------------------------------------------------------------

    /**
     * Sets the progress bar to a particular step
     * @param  {int} step The step to go to
     * @return {void}
     */
    this._checkout_set_progress = function(step) {
        var _steps  = 3;
        var _text   = 'Step ' + step + ' of ' + _steps;
        var _width  = 100/_steps*step;

        $('#progress-bar .progress-bar').animate({ 'width' : _width + '%' }).text(_text);
    };

    // --------------------------------------------------------------------------

    /**
     * Validates the data entered in step 1
     * @return {boolean}
     */
    this._checkout_validate_step_1 = function() {
        var _valid  = true;
        var _value  = '';

        // --------------------------------------------------------------------------

        //  Address Line 1
        _value = $('input[name=delivery_address_line_1]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=delivery_address_line_1]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=delivery_address_line_1]').next('.help-block').remove();
        $('input[name=delivery_address_line_1]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=delivery_address_line_1]').closest('.form-group').addClass('has-error has-feedback');

            $('input[name=delivery_address_line_1]').after('<p class="help-block">This field is required.</p>');
            $('input[name=delivery_address_line_1]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  City
        _value = $('input[name=delivery_address_town]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=delivery_address_town]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=delivery_address_town]').next('.help-block').remove();
        $('input[name=delivery_address_town]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=delivery_address_town]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=delivery_address_town]').after('<p class="help-block">This field is required.</p>');
            $('input[name=delivery_address_town]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  Postcode
        _value = $('input[name=delivery_address_postcode]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=delivery_address_postcode]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=delivery_address_postcode]').next('.help-block').remove();
        $('input[name=delivery_address_postcode]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=delivery_address_postcode]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=delivery_address_postcode]').after('<p class="help-block">This field is required.</p>');
            $('input[name=delivery_address_postcode]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  Country
        _value = $('select[name=delivery_address_country]').val();
        _value = $.trim(_value);

        //  Reset
        $('select[name=delivery_address_country]').closest('.form-group').removeClass('has-error has-feedback');
        $('select[name=delivery_address_country]').next('.help-block').remove();
        $('select[name=delivery_address_country]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('select[name=delivery_address_country]').closest('.form-group').addClass('has-error has-feedback');
            $('select[name=delivery_address_country]').after('<p class="help-block">This field is required.</p>');
            $('select[name=delivery_address_country]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  First name
        _value = $('input[name=first_name]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=first_name]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=first_name]').next('.help-block').remove();
        $('input[name=first_name]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=first_name]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=first_name]').after('<p class="help-block">This field is required.</p>');
            $('input[name=first_name]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  Surname
        _value = $('input[name=last_name]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=last_name]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=last_name]').next('.help-block').remove();
        $('input[name=last_name]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=last_name]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=last_name]').after('<p class="help-block">This field is required.</p>');
            $('input[name=last_name]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  Telephone
        _value = $('input[name=telephone]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=telephone]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=telephone]').next('.help-block').remove();
        $('input[name=telephone]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=telephone]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=telephone]').after('<p class="help-block">This field is required.</p>');
            $('input[name=telephone]').siblings('.form-control-feedback').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        //  Email
        _value = $('input[name=email]').val();
        _value = $.trim(_value);

        //  Reset
        $('input[name=email]').closest('.form-group').removeClass('has-error has-feedback');
        $('input[name=email]').next('.help-block').remove();
        $('input[name=email]').siblings('.form-control-feedback').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('input[name=email]').closest('.form-group').addClass('has-error has-feedback');
            $('input[name=email]').after('<p class="help-block">This field is required.</p>');
            $('input[name=email]').siblings('.form-control-feedback').removeClass('hidden');
        }
        else {
            var _regex = /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i;

            if (_regex.test(_value) === false) {
                _valid = false;
                $('input[name=email]').closest('.form-group').addClass('has-error has-feedback');
                $('input[name=email]').after('<p class="help-block">A valid email must be given.</p>');
                $('input[name=email]').siblings('.form-control-feedback').removeClass('hidden');
            }
        }

        // --------------------------------------------------------------------------

        //  Visual feedback
        if (_valid === true) {
            $('#checkout-step-1 .panel-heading .validate-ok').removeClass('hidden');
            $('#checkout-step-1 .panel-heading .validate-fail').addClass('hidden');
        }
        else {
            $('#checkout-step-1 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-1 .panel-heading .validate-fail').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        return _valid;
    };

    // --------------------------------------------------------------------------

    /**
     * Validates the data entered in step 2
     * @return {boolean}
     */
    this._checkout_validate_step_2 = function() {
        var _valid  = true;
        var _value  = '';

        // --------------------------------------------------------------------------

        if ($('#same-billing-address').prop('checked') === false) {
            //  Address Line 1
            _value = $('input[name=billing_address_line_1]').val();
            _value = $.trim(_value);

            //  Reset
            $('input[name=billing_address_line_1]').closest('.form-group').removeClass('has-error has-feedback');
            $('input[name=billing_address_line_1]').next('.help-block').remove();
            $('input[name=billing_address_line_1]').siblings('.form-control-feedback').addClass('hidden');

            if (_value.replace(/\s/g, '').length === 0) {
                _valid = false;
                $('input[name=billing_address_line_1]').closest('.form-group').addClass('has-error has-feedback');

                $('input[name=billing_address_line_1]').after('<p class="help-block">This field is required.</p>');
                $('input[name=billing_address_line_1]').siblings('.form-control-feedback').removeClass('hidden');
            }

            // --------------------------------------------------------------------------

            //  City
            _value = $('input[name=billing_address_town]').val();
            _value = $.trim(_value);

            //  Reset
            $('input[name=billing_address_town]').closest('.form-group').removeClass('has-error has-feedback');
            $('input[name=billing_address_town]').next('.help-block').remove();
            $('input[name=billing_address_town]').siblings('.form-control-feedback').addClass('hidden');

            if (_value.replace(/\s/g, '').length === 0) {
                _valid = false;
                $('input[name=billing_address_town]').closest('.form-group').addClass('has-error has-feedback');
                $('input[name=billing_address_town]').after('<p class="help-block">This field is required.</p>');
                $('input[name=billing_address_town]').siblings('.form-control-feedback').removeClass('hidden');
            }

            // --------------------------------------------------------------------------

            //  Postcode
            _value = $('input[name=billing_address_postcode]').val();
            _value = $.trim(_value);

            //  Reset
            $('input[name=billing_address_postcode]').closest('.form-group').removeClass('has-error has-feedback');
            $('input[name=billing_address_postcode]').next('.help-block').remove();
            $('input[name=billing_address_postcode]').siblings('.form-control-feedback').addClass('hidden');

            if (_value.replace(/\s/g, '').length === 0) {
                _valid = false;
                $('input[name=billing_address_postcode]').closest('.form-group').addClass('has-error has-feedback');
                $('input[name=billing_address_postcode]').after('<p class="help-block">This field is required.</p>');
                $('input[name=billing_address_postcode]').siblings('.form-control-feedback').removeClass('hidden');
            }

            // --------------------------------------------------------------------------

            //  Country
            _value = $('select[name=billing_address_country]').val();
            _value = $.trim(_value);

            //  Reset
            $('select[name=billing_address_country]').closest('.form-group').removeClass('has-error has-feedback');
            $('select[name=billing_address_country]').next('.help-block').remove();
            $('select[name=billing_address_country]').siblings('.form-control-feedback').addClass('hidden');

            if (_value.replace(/\s/g, '').length === 0) {
                _valid = false;
                $('select[name=billing_address_country]').closest('.form-group').addClass('has-error has-feedback');
                $('select[name=billing_address_country]').after('<p class="help-block">This field is required.</p>');
                $('select[name=billing_address_country]').siblings('.form-control-feedback').removeClass('hidden');
            }

        }

        // --------------------------------------------------------------------------

        if (_valid === true) {
            $('#checkout-step-2 .panel-heading .validate-ok').removeClass('hidden');
            $('#checkout-step-2 .panel-heading .validate-fail').addClass('hidden');
        }
        else {
            $('#checkout-step-2 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-2 .panel-heading .validate-fail').removeClass('hidden');
        }

        return _valid;
    };

    // --------------------------------------------------------------------------

    /**
     * Validates the data entered in step 3
     * @return {boolean}
     */
    this._checkout_validate_step_3 = function() {
        var _valid  = true;
        var _value  = '';

        // --------------------------------------------------------------------------

        //  Payment gateway set?
        _value = $('input[name=payment_gateway]:checked').val();
        _value = $.trim(_value);

        //  Reset
        $('#payment-gateway-choose-error').addClass('hidden');
        $('#payment-card-error').addClass('hidden');

        if (_value.replace(/\s/g, '').length === 0) {
            _valid = false;
            $('#payment-gateway-choose-error').removeClass('hidden');
        }
        else {
            //  Card
            if (!$('input[name="payment_gateway"]:checked').data('is-redirect') && !this._checkout_skeuocard.isValid()) {
                _valid = false;
                $('#payment-card-error').removeClass('hidden');
            }
        }

        // --------------------------------------------------------------------------

        //  Visual feedback
        if (_valid === true) {
            $('#checkout-step-3 .panel-heading .validate-ok').removeClass('hidden');
            $('#checkout-step-3 .panel-heading .validate-fail').addClass('hidden');
        }
        else {
            $('#checkout-step-3 .panel-heading .validate-ok').addClass('hidden');
            $('#checkout-step-3 .panel-heading .validate-fail').removeClass('hidden');
        }

        // --------------------------------------------------------------------------

        return _valid;
    };

    // --------------------------------------------------------------------------

    this._processing_init = function() {
        var _this = this;
        setTimeout(function() { _this._processing_get_status(); }, 250);
    };

    // --------------------------------------------------------------------------

    this._processing_get_status = function() {
        var _order_ref = $('#processing-container').data('order-ref');
        var _this = this;

        //  Send request to shop's API
        var _call = {
            'controller'    : 'shop/order',
            'method'        : 'status',
            'data'          :
            {
                'ref': _order_ref
            },
            success : function(data)
            {
                if (data.status === 200)
                {
                    _this._processing_get_status_ok(data);
                }
                else
                {
                    _this._processing_get_status_fail(data.error);
                }
            },
            error: function(data)
            {
                var _data;

                try
                {
                    _data = JSON.parse(data.responseText);
                }
                catch(err)
                {
                    _data = {};
                }

                var _error = typeof _data.error === 'string' ? _data.error : '';

                _this._processing_get_status_fail(_error);
            }
        };

        _nails_api.call(_call);
    };

    // --------------------------------------------------------------------------

    this._processing_get_status_ok = function(data) {
        var _this = this;

        $('.order-status-feedback')
            .removeClass('unpaid paid abandoned cancelled failed pending')
            .addClass('processing');

        switch(data.order.status) {
            case 'UNPAID' :

                $('#thankyou-text').slideUp();

                if (data.order.is_recent)
                {
                    //  Keep trying
                    setTimeout(function() { _this._processing_get_status(); }, 750);
                }
                else
                {
                    $('.order-status-feedback').removeClass('processing');
                    $('.order-status-feedback').addClass(data.order.status.toLowerCase());
                }

            break;
            case 'PAID' :

                $('.order-status-feedback').removeClass('processing');
                $('.order-status-feedback').addClass(data.order.status.toLowerCase());
                $('#thankyou-text').slideDown();

            break;
            case 'ABANDONED' :
            case 'CANCELLED' :
            case 'FAILED' :
            case 'PENDING' :

                $('#thankyou-text').slideUp();
                $('.order-status-feedback').removeClass('processing');
                $('.order-status-feedback').addClass(data.order.status.toLowerCase());

            break;
        }
    };

    // --------------------------------------------------------------------------

    this._processing_get_status_fail = function(error) {
        $('#processing-error')
            .show()
            .find('span')
            .text(error);
    };

    // --------------------------------------------------------------------------

    /**
     * Gets the current Bootstrap environment.
     * Hat-tip: http://stackoverflow.com/a/24884634/789224
     * @return string
     */
    this.bsCurrentBreakpoint = function() {
        var envs = ["xs", "sm", "md", "lg"],
            doc = window.document,
            temp = doc.createElement("div");

        doc.body.appendChild(temp);

        for (var i = envs.length - 1; i >= 0; i--) {

            var env = envs[i];

            temp.className = "hidden-" + env;

            if (temp.offsetParent === null) {

                doc.body.removeChild(temp);
                return env;
            }
        }
        return "";
    };

    // --------------------------------------------------------------------------

    return this.__construct();
};
var slicewp_front_end = function() {

    /**
     * Event handler for dynamically added elements.
     * 
     * @param {string}   event_types
     * @param {string}   selector
     * @param {function} callback
     * 
     */
    function on( event_types, selector, callback ) {

        var event_types = event_types.split( ' ' );

        for ( var i = 0; i < event_types.length; i++ ) {

            document.body.addEventListener( event_types[i], function( event ) {

                for ( var target = event.target; target && target != this; target = target.parentNode ) {
    
                    if ( target.matches( selector ) ) {
    
                        callback.call( target, event );
                        break;
    
                    }
    
                }
    
            }, false );

        }

    }

    /**
     * Strips one query argument from a given URL string.
     *
     */
    function remove_query_arg( key, sourceURL ) {

        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

        if ( queryString !== "" ) {
            params_arr = queryString.split("&");
            for ( var i = params_arr.length - 1; i >= 0; i -= 1 ) {
                param = params_arr[i].split("=")[0];
                if ( param === key ) {
                    params_arr.splice(i, 1);
                }
            }

            rtn = rtn + "?" + params_arr.join("&");

        }

        if ( rtn.split("?")[1] == "" ) {
            rtn = rtn.split("?")[0];
        }

        return rtn;

    }

    /**
     * Adds an argument name, value pair to a given URL string.
     *
     */
    function add_query_arg( param, value, url ) {

        var re   = new RegExp("[\\?&]" + param + "=([^&#]*)"), match = re.exec(url), delimiter, newString;
        var hash = url.split('#')[1];

        url = url.split('#')[0];

	    if ( match === null ) {

	        var hasQuestionMark = /\?/.test(url);
	        delimiter = hasQuestionMark ? "&" : "?";
	        newString = url + delimiter + param + "=" + value;

	    } else {

	        delimiter = match[0].charAt(0);
	        newString = url.replace(re, delimiter + param + "=" + value);

	    }

        if ( hash ) {
            newString += '#' + hash;
        }

	    return newString;

    }

    /**
     * Converts the given bytes into KB, MB etc.
     *
     */
    function bytes_to_size( bytes ) {

        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        if ( bytes == 0 )
            return '0 Byte';
        
        var i = parseInt( Math.floor( Math.log( bytes ) / Math.log( 1024 ) ) );
        
        return Math.round( bytes / Math.pow( 1024, i ), 2 ) + ' ' + sizes[i];

    }

    /**
	 * Checks if the provided URL is valid.
     *
     * @param {string} url
     *
     * @return bool
	 *
	 */
    function is_valid_url( url ) {

        var regex = new RegExp( /^(https?|s):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i );

        return regex.test( url );

    }

    /**
     * Checks if the two provided URLs are from the same domain.
     *
     * @param {string} base_url
     * @param {string} custom_url
     *
     * @return bool
     *
     */
    function is_same_domain( base_url, custom_url ) {

        base_url   = base_url.replace('http://','').replace('https://','').replace('www.','').split(/[/?#]/)[0];
        custom_url = custom_url.replace('http://','').replace('https://','').replace('www.','').split(/[/?#]/)[0];

        return ( base_url == custom_url || base_url.indexOf( custom_url ) != -1 || custom_url.indexOf( base_url ) != -1 );

    }


    /**
     * Removes the "slicewp-updated" query argument on page load.
     *
     */
    url = window.location.href;

    if ( url.indexOf( 'slicewp-updated' ) != -1 ) {

        url = remove_query_arg( 'slicewp-updated', url );
    
        window.history.replaceState( {}, '', url );

    }


    /**
     * Prevents double form submit requests.
     *
     */
    on( 'submit', '.slicewp-form', function( e ) {
        
        if ( e.target.classList.contains( 'slicewp-is-submitting' ) ) {

            e.preventDefault();

        } else {

            e.target.classList.add( 'slicewp-is-submitting' );

        }

    });


    /**
	 * Tab navigation.
	 *
	 */
    on( 'click', '.slicewp-nav-tab', function( e ) {

        var nav_tab = this.getAttribute( 'data-slicewp-tab' );

        if ( ! nav_tab )
            return true;

        e.preventDefault();

        var nav_tabs = document.querySelectorAll( '.slicewp-nav-tab' );
        var tabs     = document.querySelectorAll( '.slicewp-tab' );

        // Remove the "slicewp-active" class from nav tabs.
        for ( var j = 0; j < nav_tabs.length; j++ ) {

            nav_tabs[j].classList.remove( 'slicewp-active' );

        }

        // Add the "slicewp-active" class to the current nav tab.
        this.classList.add( 'slicewp-active' );

        // Remove the "slicewp-active" class from the tabs.
        for ( var j = 0; j < tabs.length; j++ ) {

            tabs[j].classList.remove( 'slicewp-active' );

        }

        // Add the "slicewp-active" class to the correct tab.
        document.querySelector( '.slicewp-tab[data-slicewp-tab="' + nav_tab + '"]' ).classList.add( 'slicewp-active' );

        // Change "affiliate-account-tab" query var.
        url = window.location.href;
        url = remove_query_arg( 'affiliate-account-tab', url );
        url = add_query_arg( 'affiliate-account-tab', nav_tab, url );

        window.history.replaceState( {}, '', url );

    });


    /**
	 * Generate affiliate link.
	 *
	 */
    on( 'click', '.slicewp-generate-affiliate-link', function( e ) {

        e.preventDefault();

        var site_url   = ( document.querySelector( '#slicewp-affiliate-link' ) ? document.querySelector( '#slicewp-affiliate-link' ).value : window.location.origin.replace( /(^\w+:|^)\/\//, '' ) );
        var custom_url = document.querySelector( '#slicewp-affiliate-custom-link-input' ).value;

        document.querySelector( '#slicewp-affiliate-custom-link-input-empty' ).style.display = 'none';
        document.querySelector( '#slicewp-affiliate-custom-link-input-invalid-url' ).style.display = 'none';
        document.querySelector( '.slicewp-affiliate-custom-link-output' ).style.display = 'none';

        if ( ! custom_url ) {
            
            document.querySelector( '#slicewp-affiliate-custom-link-input-empty' ).style.display = '';

        } else if ( ( ! is_valid_url( custom_url ) ) || ( ! is_same_domain( site_url, custom_url ) ) ) {

            document.querySelector( '#slicewp-affiliate-custom-link-input-invalid-url' ).style.display = '';

        } else {

			var query_arg              = document.querySelector( '#slicewp-affiliate-account' ).getAttribute( 'data-affiliate-keyword' );
            var query_arg_value        = document.querySelector( '#slicewp-affiliate-account' ).getAttribute( 'data-affiliate-keyword-value' );
			var affiliate_friendly_url = document.querySelector( '#slicewp-affiliate-account' ).getAttribute( 'data-affiliate-friendly-url' );

			// pretty urls
			if ( affiliate_friendly_url )
				document.querySelector( '#slicewp-affiliate-custom-link-output' ).value = process_friendly_url( query_arg, query_arg_value, custom_url );
			else
            	document.querySelector( '#slicewp-affiliate-custom-link-output' ).value = add_query_arg( query_arg, query_arg_value, custom_url );

            document.querySelector( '.slicewp-affiliate-custom-link-output' ).style.display = '';

        }

    });


    /**
     * Opens the creative overlay when clicking on the "View" creative action.
     *
     */
    on( 'click', '.slicewp-show-creative', function( e ) {

        e.preventDefault();

        var overlay = this.closest( '.slicewp-card' ).querySelector( '.slicewp-creative-overlay' );

        overlay.classList.add( 'slicewp-prepare-open' );

        setTimeout( function() {

            overlay.classList.remove( 'slicewp-prepare-open' );
            overlay.classList.add( 'slicewp-opened' );

        }, 100 );

    });


    /**
     * Closes the creative overlay when clicking on the close overlay button.
     *
     */
    on( 'click', '.slicewp-creative-overlay-close', function( e ) {

        e.preventDefault();

        var overlay = this.closest( '.slicewp-creative-overlay' );

        overlay.classList.remove( 'slicewp-opened' );
        overlay.classList.add( 'slicewp-prepare-close' );

        setTimeout( function() {

            overlay.classList.remove( 'slicewp-prepare-close' );

        }, 100 );

    });


    /**
	 * Copy inputs or creative textarea.
	 *
	 */
    on( 'click', '.slicewp-input-copy, .slicewp-copy-creative', function( e ) {

        e.preventDefault();

        var target = this;

        if ( target.classList.contains( 'slicewp-copy-creative' ) ) {

            var elem = target.closest( '.slicewp-card' ).querySelector( 'textarea, input[type="text"]' ).cloneNode( true );

            elem.style.position = 'absolute';
            elem.style.top      = '-10000px';
            elem.style.left     = '-10000px';

            document.querySelector( 'html' ).appendChild( elem );

            elem.select();

            document.execCommand( 'copy' );

            elem.parentNode.removeChild( elem );

        } else {

            target.parentNode.querySelector( 'textarea, input[type=text]' ).select();
            document.execCommand( 'copy' );

        }

        target.querySelector( '.slicewp-input-copy-label' ).style.display = 'none';
        target.querySelector( '.slicewp-input-copy-label-copied' ).style.display = 'inline';

        setTimeout( function() {

            target.querySelector( '.slicewp-input-copy-label' ).style.display = '';
            target.querySelector( '.slicewp-input-copy-label-copied' ).style.display = 'none';

        }, 2000 );

    });


    /**
     * Changes the "state" field to match the value of the previous "country" field.
     *
     */
    on( 'change refresh', '.slicewp-field-wrapper[data-type="country"] select', function( e ) {

        // Bail if the next field isn't a "state" type field.
        if ( this.closest( '.slicewp-field-wrapper' ).nextElementSibling.getAttribute( 'data-type' ) != 'state' ) {
            return false;
        }

        // Bail if we don't have the country select data.
        if ( typeof slicewp_country_select == 'undefined' ) {
            return false;
        }

        var country_code = this.value;
        var state_field  = this.closest( '.slicewp-field-wrapper' ).nextElementSibling.querySelector( 'input, select' );

        var field_id    = ( state_field.getAttribute( 'id' ) ? state_field.getAttribute( 'id' ) : '' ),
            field_name  = ( state_field.getAttribute( 'name' ) ? state_field.getAttribute( 'name' ) : '' ),
            field_class = ( state_field.getAttribute( 'class' ) ? state_field.getAttribute( 'class' ) : '' ),
            field_data_value    = ( state_field.getAttribute( 'data-value' ) ? state_field.getAttribute( 'data-value' ) : '' ),
            field_data_required = ( state_field.getAttribute( 'data-required' ) ? state_field.getAttribute( 'data-required' ) : '' ),
            field_is_disabled   = state_field.disabled;

        if ( slicewp_country_select[country_code] ) {

            if ( Object.keys( slicewp_country_select[country_code] ).length > 0 ) {

                // Create new state select.
                var new_state_field = document.createElement( 'select' );

                new_state_field.setAttribute( 'id', field_id );
                new_state_field.setAttribute( 'name', field_name );
                new_state_field.setAttribute( 'class', field_class );
                new_state_field.setAttribute( 'data-value', field_data_value );
                new_state_field.setAttribute( 'data-required', field_data_required );
                new_state_field.disabled = field_is_disabled;

                // Create and append empty first option.
                var option = document.createElement( 'option' );

                option.value     = '';
                option.innerHTML = 'Select...'

                new_state_field.appendChild( option );

                // Create and append states options.
                for ( var state_code in slicewp_country_select[country_code] ) {

                    var option = document.createElement( 'option' );

                    option.value     = state_code;
                    option.innerHTML = slicewp_country_select[country_code][state_code];

                    if ( state_code == field_data_value )
                        option.selected = true;

                    new_state_field.appendChild( option );

                }

                state_field.closest( '.slicewp-field-wrapper' ).style.display = '';

            } else {

                // Create hidden input.
                var new_state_field = document.createElement( 'input' );

                new_state_field.setAttribute( 'type', 'hidden' );
                new_state_field.setAttribute( 'id', field_id );
                new_state_field.setAttribute( 'name', field_name );
                new_state_field.setAttribute( 'class', field_class );
                new_state_field.setAttribute( 'value', field_data_value );
                new_state_field.setAttribute( 'data-value', field_data_value );
                new_state_field.setAttribute( 'data-required', field_data_required );
                new_state_field.disabled = field_is_disabled;

                state_field.closest( '.slicewp-field-wrapper' ).style.display = 'none';

            }

        } else {

            // Create text input.
            var new_state_field = document.createElement( 'input' );

            new_state_field.setAttribute( 'type', 'text' );
            new_state_field.setAttribute( 'id', field_id );
            new_state_field.setAttribute( 'name', field_name );
            new_state_field.setAttribute( 'class', field_class );
            new_state_field.setAttribute( 'value', field_data_value );
            new_state_field.setAttribute( 'data-value', field_data_value );
            new_state_field.setAttribute( 'data-required', field_data_required );
            new_state_field.disabled = field_is_disabled;

            state_field.closest( '.slicewp-field-wrapper' ).style.display = '';

        }

        state_field.parentNode.replaceChild( new_state_field, state_field );

    });

    if ( typeof document.body.dispatchEvent === 'function' ) {

        var country_fields = document.querySelectorAll( '.slicewp-field-wrapper[data-type="country"] select' );

        for ( var i = 0; i < country_fields.length; i++ ) {

            country_fields[i].dispatchEvent( new Event( 'change', { 'bubbles' : true } ) );

        }

    }


    /**
     * Trigger browse files on file upload drag and drop area click.
     *
     */
    on( 'click', '.slicewp-field-drag-drop-area', function( e ) {

        e.stopPropagation();

        if ( typeof document.body.dispatchEvent === 'function' ) {

            this.querySelector( 'input[type="file"]' ).click();

        }

    });


    /**
     * Handle files on drag and drop.
     *
     */
    on( 'dragenter dragover dragleave drop', '.slicewp-field-drag-drop-area', function( e ) {

        e.preventDefault();
        e.stopPropagation();

    });

    on( 'dragenter dragover', '.slicewp-field-drag-drop-area', function() {

        this.classList.add( 'slicewp-highlight' );

    });

    on( 'dragleave drop', '.slicewp-field-drag-drop-area', function() {

        this.classList.remove( 'slicewp-highlight' );

    });

    on( 'drop', '.slicewp-field-drag-drop-area', function( e ) {

        var input = this.querySelector( 'input[type="file"]' );

        input.files = e.dataTransfer.files;

        if ( typeof document.body.dispatchEvent === 'function' ) {

            input.dispatchEvent( new Event( 'change', { 'bubbles' : true } ) );
    
        }

    });


    /**
     * @todo mandle maximum files count on the front-end. a span with 2/5 and block new uploads if 5/5
     *       if unlimited, don't add the span
     *
     */


    /**
     * Append new selected files to the "file" field's files list.
     *
     */
    var fields = [];

    on( 'change', '.slicewp-field-drag-drop-area input[type="file"]', function() {

        var name = this.name.replace( '[]', '' );

        // Add file item DOM elements.
        for ( var i = 0; i < this.files.length; i++ ) {

            var html  = '<div class="slicewp-field-file-item" data-new="true">';
                    html += '<a href="#" class="slicewp-field-file-item-remove"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg></a>';
                    html += '<span class="slicewp-field-file-item-name">' + this.files.item(i).name + '</span>';
                    html += '<span class="slicewp-field-file-item-size">(' + bytes_to_size( this.files.item(i).size ) + ')</span>';
                html += '</div>';

            this.closest( '.slicewp-field-inner' ).querySelector( '.slicewp-field-file-list' ).innerHTML += html;

        }

        // Add the file to the DataTransfer object.
        fields[name] = fields[name] || new DataTransfer();

        for ( var file of this.files ) {

            fields[name].items.add( file );

        }

        this.files = fields[name].files;

    });


    /**
     * Remove a file from the "file" field's file list.
     *
     */
    on( 'click', '.slicewp-field-file-item-remove', function( e ) {

        e.preventDefault();

        var input = this.closest( '.slicewp-field-inner' ).querySelector( 'input[type="file"]' );
        
        // If the item is freshly added through the uploader, remove it from the files from the datatransfer.
        if ( this.closest( '.slicewp-field-file-item' ).querySelectorAll( 'input[type="hidden"]' ).length == 0 ) {

            // Get the name of the input file field.
            var name = input.getAttribute( 'name' ).replace( '[]', '' );

            // Remove the file from the DataTransfer object.
            var index = Array.prototype.slice.call( this.closest( '.slicewp-field-file-list' ).querySelectorAll( '.slicewp-field-file-item[data-new="true"]' ) ).indexOf( this.closest( '.slicewp-field-file-item' ) );

            fields[name].items.remove( index );

            input.files = fields[name].files;

        }

        // Remove DOM element.
        this.closest( '.slicewp-field-file-item' ).parentNode.removeChild( this.closest( '.slicewp-field-file-item' ) );

        // If the file field is a single one, show the input field.
        if ( ! input.getAttribute( 'multiple' ) ) {

            input.style.display = '';

        }

    });


    /**
     * Initialize date range pickers.
     * 
     */
    var date_picker_elements = document.querySelectorAll( '.slicewp-date-picker' );
    var pickers = [];

    for ( var i = 0; i < date_picker_elements.length; i++ ) {

        var wrapper = date_picker_elements[i].closest( '.slicewp-date-picker-wrapper' );

        wrapper.setAttribute( 'data-index', i );

        var picker = new SliceWP_Litepicker({
            element         : date_picker_elements[i],
            inlineMode      : true,
            singleMode      : false,
            numberOfMonths  : 2,
            numberOfColumns : 2,
            format          : 'D MMM, YYYY',
            showTooltip     : false,
            buttonText      : { 'previousMonth' : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>', 'nextMonth' : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>' },
            setup           : function( picker ) {

                picker.on( 'before:show', function() {
                    
                    var date = picker.getDate();

                    if ( ! date ) {
                        
                        date = new Date();

                        date.setMonth( date.getMonth() - 1 );
                        picker.gotoDate( date );

                    }
                    
                });

                picker.on( 'selected', function( date_start, date_end ) {
                    
                    var options = { year: 'numeric', month: 'short', day: 'numeric' };
                    var locale  = document.querySelector( 'html' ).getAttribute( 'lang' );
                    var wrapper = this.options.element.closest( '.slicewp-date-picker-wrapper' );

                    // Set span input value.
                    wrapper.querySelector( '.slicewp-date-picker-input span' ).innerHTML = date_start.dateInstance.toLocaleDateString( locale, options ) + ' - ' + date_end.dateInstance.toLocaleDateString( locale, options );

                    // Hide the date picker modal.
                    wrapper.classList.remove( 'slicewp-is-open' );

                    // Add the dates to the hidden inputs.
                    var formatted_date_start = date_start.toLocaleString( 'default', { year: 'numeric' } ) + '-' + 
                                               date_start.toLocaleString( 'default', { month: '2-digit' } ) + '-' +
                                               date_start.toLocaleString( 'default', { day: '2-digit' } );
                    
                    var formatted_date_end   = date_end.toLocaleString( 'default', { year: 'numeric' } ) + '-' +
                                               date_end.toLocaleString( 'default', { month: '2-digit' } ) + '-' +
                                               date_end.toLocaleString( 'default', { day: '2-digit' } );
                    
                    wrapper.querySelector( '.slicewp-date-picker-input-date-start' ).value = formatted_date_start;
                    wrapper.querySelector( '.slicewp-date-picker-input-date-end' ).value = formatted_date_end;

                    // Sync all date range pickers on the page.
                    for ( var i in pickers ) {

                        if ( i == parseInt( wrapper.getAttribute( 'data-index' ) ) ) {
                            continue;
                        }

                        var _formatted_date_start = 0;
                        var _formatted_date_end   = 0;

                        if ( pickers[i].getStartDate() && pickers[i].getEndDate() ) {

                            _formatted_date_start = pickers[i].getStartDate().toLocaleString( 'default', { year: 'numeric' } ) + '-' +
                                                    pickers[i].getStartDate().toLocaleString( 'default', { month: '2-digit' } ) + '-' +
                                                    pickers[i].getStartDate().toLocaleString( 'default', { day: '2-digit' } );

                            _formatted_date_end   = pickers[i].getEndDate().toLocaleString( 'default', { year: 'numeric' } ) + '-' +
                                                    pickers[i].getEndDate().toLocaleString( 'default', { month: '2-digit' } ) + '-' +
                                                    pickers[i].getEndDate().toLocaleString( 'default', { day: '2-digit' } );
                            
                        }

                        if ( formatted_date_start != _formatted_date_start || formatted_date_end != _formatted_date_end ) {
                            pickers[i].setDateRange( date_start, date_end );
                        }

                    }

                });

                picker.on( 'clear:selection', function() {

                    var wrapper = this.options.element.closest( '.slicewp-date-picker-wrapper' );

                    // Set span input value.
                    wrapper.querySelector( '.slicewp-date-picker-input span' ).innerHTML = wrapper.querySelector( '.slicewp-date-picker-predefined-date-range[data-range="all_time"]' ).innerHTML;

                    // Hide the date picker modal.
                    wrapper.classList.remove( 'slicewp-is-open' );

                    wrapper.querySelector( '.slicewp-date-picker-input-date-start' ).value = '';
                    wrapper.querySelector( '.slicewp-date-picker-input-date-end' ).value = '';

                    // Sync all date range pickers on the page.
                    for ( var i in pickers ) {

                        if ( i == parseInt( wrapper.getAttribute( 'data-index' ) ) ) {
                            continue;
                        }

                        if ( pickers[i].getStartDate() && pickers[i].getEndDate() ) {
                            pickers[i].clearSelection();
                        }

                    }

                });

            }

        });

        // Set date start and end.
        var date_start = wrapper.querySelector( '.slicewp-date-picker-input-date-start' ).value;
        var date_end   = wrapper.querySelector( '.slicewp-date-picker-input-date-end' ).value;
        
        if ( date_start != '' && date_end != '' ) {
            picker.setDateRange( date_start, date_end );
        }

        pickers.push( picker );
        
    }


    /**
     * Select date start and date end based on predefined options.
     * 
     */
    on( 'click', '.slicewp-date-picker-predefined-date-range', function(e) {

        e.preventDefault();

        if ( ! this.getAttribute( 'data-range' ) ) {
            return false;
        }

        var day_in_ms   = 24 * 60 * 60 * 1000;

        var today       = new Date();
        var today_year  = today.toLocaleString( 'default', { year: 'numeric' } );
        var today_month = today.toLocaleString( 'default', { month: '2-digit' } );
        var today_day   = today.toLocaleString( 'default', { day: '2-digit' } );

        var date_start = '';
        var date_end   = '';

        var range = this.getAttribute( 'data-range' );

        if ( range == 'past_7_days' ) {

            date_start = new Date( today - day_in_ms * 6 );
            date_end   = today;

        }

        if ( range == 'past_30_days' ) {

            date_start = new Date( today - day_in_ms * 29 );
            date_end   = today;

        }

        if ( range == 'week_to_date' ) {

            var day_of_week = today.getDay();
            var diff        = today.getDate() - day_of_week + ( day_of_week === 0 ? -6 : 1 );

            date_start = new Date();
            
            date_start = new Date( date_start.setDate( diff ) );
            date_end   = today;

        }

        if ( range == 'month_to_date' ) {

            date_start = new Date( today_year + '-' + today_month + '-01' );
            date_end   = today;

        }

        if ( range == 'year_to_date' ) {

            date_start = new Date( today_year + '-01-01' );
            date_end = today;

        }

        if ( range == 'last_week' ) {

            var last_week_date = new Date( today - day_in_ms * 7 );
            var day_of_week    = last_week_date.getDay();
            var diff           = last_week_date.getDate() - day_of_week + ( day_of_week === 0 ? -6 : 1 );

            date_start = new Date();
            date_start = new Date( date_start.setDate( diff ) );

            date_end   = new Date( date_start );
            date_end   = new Date( date_end.setDate( date_end.getDate() + 6 ) );

        }

        if ( range == 'last_month' ) {

            date_end   = new Date( today - day_in_ms * today_day );
            date_start = new Date( date_end.getFullYear(), date_end.getMonth() );

        }

        if ( range == 'last_year' ) {

            date_start = new Date( ( today_year - 1 ) + '-01-01' );
            date_end   = new Date( ( today_year - 1 ) + '-12-31' );

        }

        var index = parseInt( this.closest( '.slicewp-date-picker-wrapper' ).getAttribute( 'data-index' ) );

        if ( date_start != '' && date_end != '' ) {

            pickers[index].setDateRange( date_start, date_end );
            pickers[index].gotoDate( date_end.setMonth( date_end.getMonth() - 1, 1 ) );

        } else {

            pickers[index].clearSelection();
            pickers[index].gotoDate( today.setMonth( today.getMonth() - 1, 1 ) );

        }

    });


    /**
     * Opens and closes the date picker when clicking on the date picker span field.
     * 
     */
    on( 'click', '.slicewp-date-picker-input', function(e) {

        e.preventDefault();

        var date_picker_wrapper = this.closest( '.slicewp-date-picker-wrapper' );

        if ( date_picker_wrapper.classList.contains( 'slicewp-is-open' ) ) {

            date_picker_wrapper.classList.remove( 'slicewp-is-open' );

        } else {

            date_picker_wrapper.classList.add( 'slicewp-is-open' );

        }
        

    });


    /**
     * Submits the wrapping form when "items_per_page" select field is changed.
     * 
     */
    on( 'change', '.slicewp-list-table-per-page-selector select[name="list_table_items_per_page"]', function(e) {

        e.preventDefault();

        this.closest( 'form' ).submit();
        this.setAttribute( 'disabled', true );

    });


	/**
	 * Adds the friendly affiliate parameters to the url
	 *
	 */
	function process_friendly_url( param, value, url ) {

        // Save the hash, if it's present.
        var hash = url.split('#')[1];

        url = url.split('#')[0];

		// Check if this is already an affiliate friendly url
		var re = new RegExp( "([\/]" + param + "[\/][^?]*)" ), match = re.exec( url );

		// Check if we have any parameters in the url
		var re2 = new RegExp( "([?].*)" ), match2 = re2.exec( url );

		// Remove the affiliate friendly endpoint
		if ( match && match[0] ) {
            url = url.replace( match[0], '' );
        }

		// Remove the url parameters
		if ( match2 && match2[0] ) {
            url = url.replace( match2[0], '' );
        }

		// Check if we have the affiliate parameter without affiliate id in the url
		var re3 = new RegExp( "([\/]" + param + "$)" ), match3 = re3.exec( url );

		// Remove the affiliate parameter
		if ( match3 && match3[0] ) {
            url = url.replace( match3[0], '' );
        }

		// Remove the trailing slash
		url = url.replace( /\/+$/, '' );

		// Add the affiliate friendly endpoint
		url = url + '/' + param + '/' + value + '/';

		// Add back the parameters to the url
		if ( match2 && match2[0] ) {
            url = url + match2[0];
        }

        // Add back the hash if it exists.
        if ( hash ) {
            url += '#' + hash;
        }

		return url;

	}

}


if ( document.readyState === "complete" || ( document.readyState !== "loading" && ! document.documentElement.doScroll ) ) {

	slicewp_front_end();

} else {

  document.addEventListener( "DOMContentLoaded", slicewp_front_end );

}
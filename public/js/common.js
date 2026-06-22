//This file contains all common functionality for the application

$(document).ready(function () {

	$.ajaxSetup({
		beforeSend: function (jqXHR, settings) {
			if (settings.url.indexOf('http') === -1) {
				settings.url = base_path + settings.url;
			}
		}
	});

	update_font_size();
	if ($('#status_span').length) {

		var status = $('#status_span').attr('data-status');
		if (status === '1') {
			toastr.success($('#status_span').attr('data-msg'));
		} else if (status === '0') {
			toastr.error($('#status_span').attr('data-msg'));
		}
	}

	//Default setting for select2
	$.fn.select2.defaults.set("minimumResultsForSearch", 6);
	if ($('html').attr('dir') == 'rtl') {
		$.fn.select2.defaults.set("dir", "rtl");
	}
	$.fn.datepicker.defaults.todayHighlight = true;

	//Toastr setting
	toastr.options.preventDuplicates = true;

	//Play notification sound on success, error and warning
	toastr.options.onShown = function () {
		if ($(this).hasClass('toast-success')) {
			var audio = $("#success-audio")[0];
			if (audio !== undefined) {
				audio.play();
			}
		} else if ($(this).hasClass('toast-error')) {
			var audio = $("#error-audio")[0];
			if (audio !== undefined) {
				audio.play();
			}
		} else if ($(this).hasClass('toast-warning')) {
			var audio = $("#warning-audio")[0];
			if (audio !== undefined) {
				audio.play();
			}
		}
	}

	//Default setting for jQuey validator
	jQuery.validator.setDefaults({
		errorPlacement: function (error, element) {
			if (element.hasClass('select2') && element.parent().hasClass('input-group')) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span.select2-container'));
			} else if (element.parent().hasClass('input-group')) {
				error.insertAfter(element.parent());
			} else if (element.parent().hasClass('multi-input')) {
				error.insertAfter(element.closest('.multi-input'));
			} else {
				error.insertAfter(element);
			}
		},

		invalidHandler: function () {
			toastr.error(LANG.some_error_in_input_field);
		}
	});

	jQuery.validator.addMethod("max-value", function (value, element, param) {
		return this.optional(element) || !(param < __number_uf(value));
	}, function (params, element) {
		return $(element).data('msg-max-value');
	});

	jQuery.validator.addMethod("abs_digit", function (value, element) {
		return this.optional(element) || Number.isInteger(Math.abs(__number_uf(value)));
	});

	//Set global currency to be used in the application
	__currency_symbol = $('input#__symbol').val();
	__currency_thousand_separator = $('input#__thousand').val();
	__currency_decimal_separator = $('input#__decimal').val();
	__currency_symbol_placement = $('input#__symbol_placement').val();
	if ($('input#__precision').length > 0) {
		__currency_precision = $('input#__precision').val();
	} else {
		__currency_precision = 2;
	}

	//Set page level currency to be used for some pages. (Purchase page)
	if ($('input#p_symbol').length > 0) {
		__p_currency_symbol = $('input#p_symbol').val();
		__p_currency_thousand_separator = $('input#p_thousand').val();
		__p_currency_decimal_separator = $('input#p_decimal').val();
	}

	__currency_convert_recursively($(document), $('input#p_symbol').length);

	//Datables
	jQuery.extend($.fn.dataTable.defaults, {
		dom: '<"row margin-bottom-12"<"col-sm-12"<"pull-left"l><"pull-right margin-left-10"B><"pull-right"fr>>>tip',
		buttons: [
			{
				extend: 'collection',
				text: '<i class="fa fa-list" aria-hidden="true"></i> &nbsp;' + LANG.action,
				className: 'btn-info',
				init: function (api, node, config) {
					$(node).removeClass('btn-default')
				},
				buttons: [
					{
						extend: 'copy',
						text: '<i class="fa fa-files-o" aria-hidden="true"></i> ' + LANG.copy,
						className: 'bg-info',
						exportOptions: {
							columns: ':visible'
						},
						action: function (e, dt, button, config) {
							// Get the default copy data
							var data = dt.buttons.exportData(config.exportOptions);
							
							// Calculate totals with specific detection for your columns
							var columnTotals = {};
							var currencyColumnNames = [
								'cash', 'hand', 'sale', 'purchase', 'total', 'amount', 'value', 
								'grand', 'subtotal', 'sum', 'price', 'cost'
							];
							
							// Check headers and calculate totals
							for (var col = 0; col < data.header.length; col++) {
								var headerText = data.header[col].toLowerCase().trim();
								var isCurrencyColumn = false;
								
								// Specific matching for your table columns
								if (headerText.includes('cash') || headerText.includes('sale') || 
									headerText.includes('purchase') || headerText.includes('total') || 
									headerText.includes('grand')) {
									// Exclude slip columns
									if (!headerText.includes('slip')) {
										isCurrencyColumn = true;
									}
								}
								
								if (isCurrencyColumn) {
									var columnTotal = 0;
									for (var row = 0; row < data.body.length; row++) {
										var cellValue = data.body[row][col];
										if (cellValue !== undefined && cellValue !== null && cellValue !== '') {
											var numValue = __number_uf(cellValue.toString());
											if (!isNaN(numValue) && numValue !== null) {
												columnTotal += numValue;
											}
										}
									}
									columnTotals[col] = columnTotal;
								}
							}
							
							// Add empty row for separation
							data.body.push(new Array(data.header.length).fill(''));
							
							// Add total row
							var totalRow = [];
							for (var j = 0; j < data.header.length; j++) {
								if (j === 0) {
									totalRow.push('Total');
								} else if (columnTotals.hasOwnProperty(j)) {
									var formattedTotal = __currency_trans_from_en(columnTotals[j], false, false, __currency_precision);
									totalRow.push(formattedTotal);
								} else {
									totalRow.push('');
								}
							}
							
							data.body.push(totalRow);
							
							// Create the text to copy
							var output = data.header.join('\t') + '\n';
							for (var i = 0; i < data.body.length; i++) {
								output += data.body[i].join('\t') + '\n';
							}
							
							// Copy to clipboard
							if (navigator.clipboard && window.isSecureContext) {
								navigator.clipboard.writeText(output).then(function() {
									if (typeof toastr !== 'undefined') {
										toastr.success('Copied to clipboard with totals!');
									} else {
										alert('Copied to clipboard with totals!');
									}
								}).catch(function(err) {
									console.error('Failed to copy: ', err);
									fallbackCopyTextToClipboard(output);
								});
							} else {
								fallbackCopyTextToClipboard(output);
							}
						}
					},
					{
						extend: 'csv',
						text: '<i class="fa fa-file-text-o" aria-hidden="true"></i> ' + LANG.export_to_csv,
						className: 'bg-info',
						exportOptions: {
							columns: ':visible'
						},
						customize: function(csv) {
							return addTotalsToCSV(csv);
						}
					},
					{
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> ' + LANG.export_to_excel,
						className: 'bg-info',
						exportOptions: {
							columns: ':visible'
						},
						// FIX: use customizeData instead of customize
						customizeData: function(data) {
							var currencyColumnNames = [
								'cash','hand','sale','purchase','total','amount','value',
								'grand','subtotal','sum','price','cost','paid','due',
								'balance','profit','revenue','income'
							];

							var columnTotals = {};

							// Identify currency columns
							data.header.forEach(function(header, index) {
								var headerText = header.toLowerCase().trim();
								currencyColumnNames.forEach(function(name) {
									if (headerText.includes(name)) {
										columnTotals[index] = 0;
									}
								});
							});

							// Sum each column
							data.body.forEach(function(row) {
								Object.keys(columnTotals).forEach(function(col) {
									var val = row[col];
									if (val !== undefined && val !== null && val !== '') {
										var num = __number_uf(val.toString());
										if (!isNaN(num)) {
											columnTotals[col] += num;
										}
									}
								});
							});

							// Add totals row at the end
							var totalRow = [];
							for (var i = 0; i < data.header.length; i++) {
								if (i === 0) {
									totalRow.push('Total');
								} else if (columnTotals.hasOwnProperty(i)) {
									totalRow.push(__currency_trans_from_en(columnTotals[i], false, false, __currency_precision));
								} else {
									totalRow.push('');
								}
							}

							data.body.push(totalRow); // append totals row
						}
					},
					{
						extend: 'pdf',
						text: '<i class="fa fa-file-pdf-o" aria-hidden="true"></i> ' + LANG.export_to_pdf,
						className: 'bg-info',
						exportOptions: {
							columns: ':visible'
						},
						customize: function(doc) {
							var body = doc.content[1].table.body;
							var numCols = body[0].length;
							
							// Identify currency columns by header names and calculate totals
							var columnTotals = {};
							var currencyColumnNames = [
								'price', 'amount', 'total', 'cost', 'value', 'subtotal', 
								'tax', 'discount', 'paid', 'due', 'sum',
								'unit_price', 'line_total', 'grand_total', 'net_amount',
								'cash', 'hand', 'sale', 'purchase', 
								'grand',   
								'balance', 'profit', 'revenue', 'income'
							];
							
							// Check each column
							for (var col = 0; col < numCols; col++) {
								var headerText = getPdfCellText(body[0][col]).toLowerCase();
								var isCurrencyColumn = false;
								var columnTotal = 0;
								
								// Check if header indicates a currency column
								for (var name of currencyColumnNames) {
									if (headerText.includes(name)) {
										isCurrencyColumn = true;
										break;
									}
								}
								
								// If identified as currency column, calculate total
								if (isCurrencyColumn) {
									for (var row = 1; row < body.length; row++) {
										var cellValue = getPdfCellText(body[row][col]);
										var numValue = __number_uf(cellValue);
										
										if (!isNaN(numValue)) {
											columnTotal += numValue;
										}
									}
									columnTotals[col] = columnTotal;
								}
							}
							
							// Create total row
							var firstDataCol = getFirstDataColumnIndex(body); 
							var totalRow = [];
							for (var j = 0; j < numCols; j++) {
								if (j === firstDataCol) {
									totalRow.push({
										text: 'Total',
										bold: true,
										color: '#000000'
									});
								} else if (columnTotals.hasOwnProperty(j)) {
									var formattedTotal = __currency_trans_from_en(columnTotals[j], false, false, __currency_precision);
									totalRow.push({
										text: formattedTotal,
										bold: true,
										color: '#000000',
										alignment: 'right'
									});
								} else {
									totalRow.push({
										text: ''
									});
								}
							}
							
							// Add total row with separator
							var separatorRow = new Array(numCols).fill({
								text: '',
								border: [false, true, false, false]
							});
							
							body.push(separatorRow);
							body.push(totalRow);
						}
					},
					{
						extend: 'print',
						text: '<i class="fa fa-print" aria-hidden="true"></i> ' + LANG.print,
						className: 'bg-info',
						exportOptions: {
							columns: ':visible'
						},
						customize: function(win) {
							addTotalsToPrint(win);
						}
					},
					{
						extend: 'colvis',
						text: '<i class="fa fa-columns" aria-hidden="true"></i> ' + LANG.col_vis,
						className: 'bg-info',
					},
				]
			}
		],
		aLengthMenu: [
			[25, 50, 100, 200, -1],
			[25, 50, 100, 200, LANG.all]
		],
		iDisplayLength: 25,
		language: {
			"search": LANG.search + ":",
			"lengthMenu": LANG.show + " _MENU_ " + LANG.entries,
			"emptyTable": LANG.table_emptyTable,
			"info": LANG.table_info,
			"infoEmpty": LANG.table_infoEmpty,
			"loadingRecords": LANG.table_loadingRecords,
			"processing": LANG.table_processing,
			"zeroRecords": LANG.table_zeroRecords,
			"paginate": {
				"first": LANG.first,
				"last": LANG.last,
				"next": LANG.next,
				"previous": LANG.previous,
			},
		}
	});

	if ($('input#iraqi_selling_price_adjustment').length > 0) {
		iraqi_selling_price_adjustment = true;
	} else {
		iraqi_selling_price_adjustment = false;
	}

	//Input number
	$(document).on('click', '.input-number .quantity-up, .input-number .quantity-down', function () {
		var input = $(this).closest('.input-number').find('input');
		var qty = __read_number(input);
		var step = 1;
		if (input.data('step')) {
			step = input.data('step');
		}
		var min = parseFloat(input.data('min'));
		var max = parseFloat(input.data('max'));

		if ($(this).hasClass('quantity-up')) {
			//if max reached return false
			if (typeof max != 'undefined' && qty + step > max) {
				return false;
			}
			__write_number(input, qty + step);
			input.change();
		} else if ($(this).hasClass('quantity-down')) {
			//if max reached return false
			if (typeof min != 'undefined' && qty - step < min) {
				return false;
			}

			__write_number(input, qty - step);
			input.change();
		}
	});
});

//Default settings for daterangePicker
var ranges = {};
ranges[LANG.today] = [moment(), moment()];
ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
ranges[LANG.last_month] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
ranges[LANG.this_financial_year] = [financial_year.start, financial_year.end];
var dateRangeSettings = {
	ranges: ranges,
	startDate: financial_year.start,
	endDate: financial_year.end,
	locale: {
		cancelLabel: LANG.clear,
		applyLabel: LANG.apply,
		customRangeLabel: LANG.custom_range,
		format: moment_date_format,
		toLabel: "~",
	}
};

//Check for number string in input field, if data-decimal is 0 then don't allow decimal symbol
$(document).on('keypress', 'input.input_number', function (event) {
	var is_decimal = $(this).data('decimal');

	if (is_decimal == 0) {
		if (__currency_decimal_separator == '.') {
			var regex = new RegExp(/^[0-9,-]+$/);
		} else {
			var regex = new RegExp(/^[0-9.-]+$/);
		}
	} else {
		var regex = new RegExp(/^[0-9.,-]+$/);
	}

	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
		event.preventDefault();
		return false;
	}
});

//Select all input values on click
$(document).on('click', 'input, textarea', function (event) {
	$(this).select();
});

$(document).on('click', '.toggle-font-size', function (event) {
	localStorage.setItem("upos_font_size", $(this).data('size'));
	update_font_size();
});
$(document).on('click', '.sidebar-toggle', function () {
	var sidebar_collapse = localStorage.getItem("upos_sidebar_collapse");
	if ($('body').hasClass('sidebar-collapse')) {
		localStorage.setItem("upos_sidebar_collapse", 'false');
	} else {
		localStorage.setItem("upos_sidebar_collapse", 'true');
	}
});

//Ask for confirmation for links
$(document).on('click', 'a.link_confirmation', function (e) {
	e.preventDefault();
	swal({
		title: LANG.sure,
		icon: "warning",
		buttons: true,
		dangerMode: true,
	}).then((confirmed) => {
		if (confirmed) {
			window.location.href = $(this).attr('href');
		}
	});
});

/////////////////////////////////////

function getPdfCellText(cell) {
	if (cell === null || cell === undefined) return '';

	// Plain string or number
	if (typeof cell === 'string' || typeof cell === 'number') {
		return String(cell);
	}

	// pdfMake object with text
	if (typeof cell === 'object') {
		if (typeof cell.text === 'string' || typeof cell.text === 'number') {
			return String(cell.text);
		}

		// pdfMake stacked content (array)
		if (Array.isArray(cell.text)) {
			return cell.text.map(t => typeof t === 'string' ? t : '').join(' ');
		}
	}

	return '';
}


function getFirstDataColumnIndex(body) {
    for (var i = 0; i < body[0].length; i++) {
        var headerText = getPdfCellText(body[0][i]).trim();
        if (headerText !== '') {
            return i;
        }
    }
    return 0;
}


///////////////////////////////////////

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        var successful = document.execCommand('copy');
        if (successful) {
            if (typeof toastr !== 'undefined') {
                toastr.success('Copied to clipboard with totals!');
            } else {
                alert('Copied to clipboard with totals!');
            }
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error('Failed to copy to clipboard');
            } else {
                alert('Failed to copy to clipboard');
            }
        }
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        if (typeof toastr !== 'undefined') {
            toastr.error('Failed to copy to clipboard');
        } else {
            alert('Failed to copy to clipboard');
        }
    }
    
    document.body.removeChild(textArea);
}

///////////////////////////////////////

// Replace your existing functions with these corrected versions that handle column alignment properly

// Enhanced function with better column detection and alignment
function getCurrencyColumnTotalsFromExportData(exportData, isCSV = false) {
    var lines = exportData.split('\n');
    if (lines.length < 2) return {totals: {}, columnCount: 0};
    
    var headerLine = lines[0];
    var headers = isCSV ? parseCSVLine(headerLine) : headerLine.split('\t');
    var columnTotals = {};
    
    // More specific currency column detection
    var currencyColumnNames = [
        'cash', 'hand', 'sale', 'purchase', 'total', 'amount', 'value', 
        'grand', 'subtotal', 'sum', 'price', 'cost', 'paid', 'due', 
        'balance', 'profit', 'revenue', 'income', 'expense'
    ];
    
    console.log('Headers found:', headers); // Debug log
    
    // Identify currency columns by header names (more precise matching)
    for (var col = 0; col < headers.length; col++) {
        var headerText = headers[col].toLowerCase().replace(/"/g, '').trim();
        console.log('Checking header:', headerText, 'at column:', col); // Debug log
        
        // Check for exact matches and partial matches
        var isCurrencyColumn = false;
        
        for (var i = 0; i < currencyColumnNames.length; i++) {
            // Check if header contains currency keywords
            if (headerText.includes(currencyColumnNames[i])) {
                // Additional check: exclude non-monetary columns like "card slips", "cheques slips" etc.
                if (!headerText.includes('slip') && !headerText.includes('count') && 
                    !headerText.includes('qty') && !headerText.includes('quantity')) {
                    isCurrencyColumn = true;
                    break;
                }
            }
        }
        
        // Special cases for your table headers
        if (headerText.includes('cash') || headerText.includes('sale') || 
            headerText.includes('purchase') || headerText.includes('total') || 
            headerText.includes('grand')) {
            isCurrencyColumn = true;
        }
        
        if (isCurrencyColumn) {
            columnTotals[col] = 0;
            console.log('Currency column identified:', col, headerText); // Debug log
        }
    }
    
    console.log('Currency columns identified:', Object.keys(columnTotals)); // Debug log
    
    // Calculate totals from data rows
    for (var row = 1; row < lines.length; row++) {
        if (lines[row].trim() === '') continue;
        
        var cells = isCSV ? parseCSVLine(lines[row]) : lines[row].split('\t');
        
        Object.keys(columnTotals).forEach(function(colIndex) {
            var col = parseInt(colIndex);
            if (cells[col] !== undefined) {
                var cellValue = cells[col].replace(/"/g, '').trim();
                var numValue = __number_uf(cellValue);
                
                console.log('Processing cell at col', col, ':', cellValue, 'parsed as:', numValue); // Debug log
                
                if (!isNaN(numValue) && numValue !== null) {
                    columnTotals[col] += numValue;
                }
            }
        });
    }
    
    console.log('Final column totals:', columnTotals); // Debug log
    return {totals: columnTotals, columnCount: headers.length};
}

// Updated Copy button action - replace your existing copy button with this:
function updateCopyButtonAction() {
    return {
        extend: 'copy',
        text: '<i class="fa fa-files-o" aria-hidden="true"></i> ' + LANG.copy,
        className: 'bg-info',
        exportOptions: {
            columns: ':visible'
        },
        action: function (e, dt, button, config) {
            console.log('Copy button clicked'); // Debug log
            
            // Get the default copy data
            var data = dt.buttons.exportData(config.exportOptions);
            
            console.log('Copy data headers:', data.header); // Debug log
            console.log('Copy data sample row:', data.body[0]); // Debug log
            
            // Calculate totals with better column detection
            var columnTotals = {};
            var currencyColumnNames = [
                'cash', 'hand', 'sale', 'purchase', 'total', 'amount', 'value', 
                'grand', 'subtotal', 'sum', 'price', 'cost', 'paid', 'due', 
                'balance', 'profit', 'revenue', 'income', 'expense'
            ];
            
            // Check headers and calculate totals
            for (var col = 0; col < data.header.length; col++) {
                var headerText = data.header[col].toLowerCase().trim();
                var isCurrencyColumn = false;
                
                console.log('Copy checking header:', headerText, 'at column:', col); // Debug log
                
                for (var i = 0; i < currencyColumnNames.length; i++) {
                    if (headerText.includes(currencyColumnNames[i])) {
                        // Exclude non-monetary columns
                        if (!headerText.includes('slip') && !headerText.includes('count') && 
                            !headerText.includes('qty') && !headerText.includes('quantity')) {
                            isCurrencyColumn = true;
                            break;
                        }
                    }
                }
                
                // Special cases for your specific headers
                if (headerText.includes('cash') || headerText.includes('sale') || 
                    headerText.includes('purchase') || headerText.includes('total') || 
                    headerText.includes('grand')) {
                    isCurrencyColumn = true;
                }
                
                if (isCurrencyColumn) {
                    var columnTotal = 0;
                    console.log('Copy processing currency column:', col, headerText); // Debug log
                    
                    for (var row = 0; row < data.body.length; row++) {
                        var cellValue = data.body[row][col];
                        if (cellValue !== undefined && cellValue !== null && cellValue !== '') {
                            var numValue = __number_uf(cellValue.toString());
                            
                            console.log('Copy cell value:', cellValue, 'parsed as:', numValue); // Debug log
                            
                            if (!isNaN(numValue) && numValue !== null) {
                                columnTotal += numValue;
                            }
                        }
                    }
                    columnTotals[col] = columnTotal;
                    console.log('Copy total for column', col, ':', columnTotal); // Debug log
                }
            }
            
            console.log('Copy final totals:', columnTotals); // Debug log
            
            // Add empty row for separation
            data.body.push(new Array(data.header.length).fill(''));
            
            // Add total row with proper alignment
            var totalRow = [];
            for (var j = 0; j < data.header.length; j++) {
                if (j === 0) {
                    totalRow.push('Total');
                } else if (columnTotals.hasOwnProperty(j)) {
                    var formattedTotal = __currency_trans_from_en(columnTotals[j], false, false, __currency_precision);
                    totalRow.push(formattedTotal);
                    console.log('Copy adding total for column', j, ':', formattedTotal); // Debug log
                } else {
                    totalRow.push('');
                }
            }
            
            data.body.push(totalRow);
            
            // Create the text to copy with proper tab alignment
            var output = data.header.join('\t') + '\n';
            
            for (var i = 0; i < data.body.length; i++) {
                output += data.body[i].join('\t') + '\n';
            }
            
            console.log('Copy output (first 500 chars):', output.substring(0, 500)); // Debug log
            
            // Copy to clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(output).then(function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Copied to clipboard with totals!');
                    } else {
                        alert('Copied to clipboard with totals!');
                    }
                }).catch(function(err) {
                    console.error('Failed to copy: ', err);
                    fallbackCopyTextToClipboard(output);
                });
            } else {
                fallbackCopyTextToClipboard(output);
            }
        }
    };
}

// Enhanced CSV function
function addTotalsToCSV(csv) {
    console.log('CSV processing started'); // Debug log
    
    var lines = csv.split('\n');
    if (lines.length < 2) return csv;
    
    var headerLine = lines[0];
    var headers = parseCSVLine(headerLine);
    var columnTotals = {};
    
    // Better currency column detection for CSV
    var currencyColumnNames = [
        'cash', 'hand', 'sale', 'purchase', 'total', 'amount', 'value', 
        'grand', 'subtotal', 'sum', 'price', 'cost', 'paid', 'due', 
        'balance', 'profit', 'revenue', 'income'
    ];
    
    console.log('CSV Headers:', headers); // Debug log
    
    // Identify currency columns
    for (var col = 0; col < headers.length; col++) {
        var headerText = headers[col].toLowerCase().replace(/"/g, '').trim();
        
        for (var i = 0; i < currencyColumnNames.length; i++) {
            if (headerText.includes(currencyColumnNames[i])) {
                if (!headerText.includes('slip') && !headerText.includes('count')) {
                    columnTotals[col] = 0;
                    console.log('CSV currency column:', col, headerText); // Debug log
                    break;
                }
            }
        }
    }
    
    // Calculate totals
    for (var row = 1; row < lines.length; row++) {
        if (lines[row].trim() === '') continue;
        
        var cells = parseCSVLine(lines[row]);
        
        Object.keys(columnTotals).forEach(function(colIndex) {
            var col = parseInt(colIndex);
            if (cells[col] !== undefined) {
                var cellValue = cells[col].replace(/"/g, '').trim();
                var numValue = __number_uf(cellValue);
                
                if (!isNaN(numValue) && numValue !== null) {
                    columnTotals[col] += numValue;
                }
            }
        });
    }
    
    console.log('CSV totals:', columnTotals); // Debug log
    
    // Create total row
    var totalRow = [];
    for (var j = 0; j < headers.length; j++) {
        if (j === 0) {
            totalRow.push('"Total"');
        } else if (columnTotals.hasOwnProperty(j)) {
            var formattedTotal = __currency_trans_from_en(columnTotals[j], false, false, __currency_precision);
            totalRow.push('"' + formattedTotal + '"');
        } else {
            totalRow.push('""');
        }
    }
    
    csv += '\n'; // Empty line
    csv += totalRow.join(',');
    return csv;
}

// Enhanced Print function with better column detection
function addTotalsToPrint(win) {
    console.log('Print processing started'); // Debug log
    
    var doc = win.document;
    var table = doc.querySelector('table');
    
    if (!table) return;
    
    var headerRow = table.querySelector('thead tr') || table.querySelector('tr');
    var dataRows = table.querySelectorAll('tbody tr') || table.querySelectorAll('tr:not(:first-child)');
    
    if (!headerRow || dataRows.length === 0) return;
    
    var headers = headerRow.querySelectorAll('th, td');
    var columnTotals = {};
    
    var currencyColumnNames = [
        'cash', 'hand', 'sale', 'purchase', 'total', 'amount', 'value', 
        'grand', 'subtotal', 'sum', 'price', 'cost', 'paid', 'due', 
        'balance', 'profit', 'revenue', 'income', 'expense'
    ];
    
    console.log('Print headers found:', headers.length); // Debug log
    
    // Identify currency columns
    for (var col = 0; col < headers.length; col++) {
        var headerText = headers[col].textContent.toLowerCase().trim();
        
        console.log('Print checking header:', headerText); // Debug log
        
        for (var n = 0; n < currencyColumnNames.length; n++) {
            if (headerText.includes(currencyColumnNames[n])) {
                if (!headerText.includes('slip') && !headerText.includes('count')) {
                    columnTotals[col] = 0;
                    console.log('Print currency column:', col, headerText); // Debug log
                    break;
                }
            }
        }
    }
    
    // Calculate totals
    for (var r = 0; r < dataRows.length; r++) {
        var cells = dataRows[r].querySelectorAll('td');
        
        for (var c = 0; c < cells.length; c++) {
            if (columnTotals.hasOwnProperty(c)) {
                var cellValue = cells[c].textContent.trim();
                var numValue = __number_uf(cellValue);
                
                if (!isNaN(numValue) && numValue !== null) {
                    columnTotals[c] += numValue;
                }
            }
        }
    }
    
    console.log('Print totals:', columnTotals); // Debug log
    
    // Add separator row
    var separatorRow = doc.createElement('tr');
    var separatorCell = doc.createElement('td');
    separatorCell.colSpan = headers.length;
    separatorCell.style.borderTop = '1px solid #000';
    separatorCell.innerHTML = '&nbsp;';
    separatorRow.appendChild(separatorCell);
    table.appendChild(separatorRow);
    
    // Add total row
    var totalRow = doc.createElement('tr');
    totalRow.style.fontWeight = 'bold';
    
    for (var j = 0; j < headers.length; j++) {
        var cell = doc.createElement('td');
        
        if (j === 0) {
            cell.textContent = 'Total';
        } else if (columnTotals.hasOwnProperty(j)) {
            var formattedTotal = __currency_trans_from_en(columnTotals[j], false, false, __currency_precision);
            cell.textContent = formattedTotal;
            cell.style.textAlign = 'right';
        } else {
            cell.textContent = '';
        }
        
        totalRow.appendChild(cell);
    }
    
    table.appendChild(totalRow);
}

// Add these missing functions to your common.js file

// Helper function to parse CSV line (handles quoted fields better)
function parseCSVLine(line) {
    var result = [];
    var current = '';
    var inQuotes = false;
    
    for (var i = 0; i < line.length; i++) {
        var char = line[i];
        
        if (char === '"') {
            inQuotes = !inQuotes;
        } else if (char === ',' && !inQuotes) {
            result.push(current);
            current = '';
        } else {
            current += char;
        }
    }
    
    result.push(current);
    return result;
}
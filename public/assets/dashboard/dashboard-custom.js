// Add active to current selected menu on sidebar
$(document).ready(function() {
	var listMenu = $(".main-sidebar .sidebar .sidebar-menu li > a");
	var currentURL = window.location.href;
	for (var i = 0; i < listMenu.length; i++) {
		if (currentURL == $(listMenu[i]).attr('href') ||
			currentURL.startsWith($(listMenu[i]).attr('href') + '/') ||
			currentURL.startsWith($(listMenu[i]).attr('href') + '?')) {
			var menu = $(listMenu[i]);
			while (menu.length > 0 && menu.parent().is('body') == false) {
				var menu = menu.parent();
				if (menu.is('li')) {
					menu.addClass('active');
				}
			}
		}
	}
})


// Format data table for responsice
function makeDataTableResponsive(tableId, columnIndex = 0, direction = 'asc', _disp) {
	tableId = '#' + tableId;
	$(document).ready(function() {
	    $(tableId).DataTable({
			paging: true,
			lengthChange: true,
			searching: true,
			scrollY:'55vh',
			scrollX:true,
        	scrollCollapse: true,
			ordering: true,
			info: true,
			autoWidth: false,
	        responsive: true,
	        bDestroy: true,
	        order: [[columnIndex, direction]],
	        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
	        iDisplayLength: _disp,
	    });
	});
}

function makeDataTableResponsiveFixed(tableId, columnIndex = 0, direction = 'asc', _disp, fixedLeft = 1) {
    tableId = '#' + tableId;

    $(document).ready(function() {
        $(tableId).DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            scrollY: '55vh',
            scrollX: true,
            scrollCollapse: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: false,
            bDestroy: true,
            order: [[columnIndex, direction]],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
            iDisplayLength: _disp,

            fixedColumns: {
                leftColumns: fixedLeft
            }
        });
    });
}

// Format data table for responsice
function makeDataTableResponsiveWithFilter(tableId, columnIndex = 0, direction = 'asc', filter, _disp) {
	tableId = '#' + tableId;
	$(document).ready(function() {
	    $(tableId).DataTable({
			paging: true,
			lengthChange: true,
			searching: true,
			scrollY:'55vh',
			scrollX:true,
        	scrollCollapse: true,
			ordering: true,
			info: true,
			autoWidth: false,
	        responsive: true,
	        bDestroy: true,
	        order: [[columnIndex, direction]],
	        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
	        iDisplayLength: _disp,
	    });
	});
}

// Format data table for responsice
function makeDataTableResponsiveAutoWidth(tableId, columnIndex = 0, direction = 'asc', _disp) {
	tableId = '#' + tableId;
	$(document).ready(function() {
	    $(tableId).DataTable({
			paging: true,
			lengthChange: true,
			searching: true,
			scrollY:'55vh',
			scrollX:true,
        	scrollCollapse: true,
			ordering: true,
			info: true,
			autoWidth: true,
	        responsive: true,
	        bDestroy: true,
	        order: [[columnIndex, direction]],
	        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
	        iDisplayLength: _disp,
			ajax: {
				url: "{{ route('/dashboard/lhpexecutive/lhpRestanPanenBlmAngkut') }}",
				data: function (d) {
					  d.SITE_ID = $('#status').val()
				  }
			  }
	    });
	});
}


// Validate input on form
function setValidationConfirmationPassword(passwordId, confirmationId) {
	$('#' + passwordId).onchange = validationConfirmationPassword(passwordId, confirmationId);
	$('#' + confirmationId).onkeyup = validationConfirmationPassword(passwordId, confirmationId);
}

function validationConfirmationPassword(passwordId, confirmationId) {
	var password = $('#' + passwordId);
	var konfirmasi = $('#' + confirmationId);

	if(password.val() != konfirmasi.val()) {
		konfirmasi[0].setCustomValidity("Passwords did not match.");
	}
	else {
		konfirmasi[0].setCustomValidity('');
	}
}

var datepicker_option = {
    autoclose: true,
    minViewMode: 0,
    format: 'dd/mm/yyyy',
    forceParse: true,
    todayHighlight: true
};

function setValidationDatePicker(datePickerId) {
    $('#' + datePickerId).datepicker(datepicker_option);
}

function setValidationRangeDatePicker(fromDateId, toDateId) {
    $('#' + fromDateId).datepicker(datepicker_option);
    $('#' + toDateId).datepicker(datepicker_option);
}


// Generate graph
var chartColors = [
	"rgb(54, 162, 235)",	// blue
	"rgb(255, 99, 132)",	// red
	"rgb(75, 200, 200)",	// green
	"rgb(255, 159, 64)",	// orange
	"rgb(153, 102, 255)",	// purple
	"rgb(255, 205, 86)",	// yellow
	"rgb(201, 203, 207)",
	"rgb(234, 46, 162)",	// custom
	"rgb(225, 199, 152)",	// custom
	"rgb(95, 210, 100)",	// custom
	"rgb(155, 149, 164)",	// custom
	"rgb(123, 172, 205)",	// custom
	"rgb(235, 235, 186)",	// custom
	"rgb(101, 103, 227)",
	"rgb(54, 162, 235)",	// blue
	"rgb(255, 99, 132)",	// red
	"rgb(75, 200, 200)",	// green
	"rgb(255, 159, 64)",	// orange
	"rgb(153, 102, 255)",	// purple
	"rgb(255, 205, 86)",	// yellow
	"rgb(201, 203, 207)",
	"rgb(74, 132, 225)",	// custom
	"rgb(225, 199, 152)",	// custom
	"rgb(95, 210, 100)",	// custom
	"rgb(155, 149, 164)",	// custom
	"rgb(123, 172, 205)",	// custom
	"rgb(235, 235, 186)",	// custom
	"rgb(101, 103, 227)"	// custom
]

function convertToRGBA(rgb, alpha) {
    return rgb.replace(')', ', ' + alpha + ')').replace('rgb', 'rgba');
}

function formatNumberWithFormat(value, decimalPoint = -1) {
	if (decimalPoint != -1) {
		value = parseFloat(value).toFixed(decimalPoint);
	}
	var parts = parseFloat(value).toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(",");
}

function generateChartJS(chartType, elementId, labels, datasets, customOptions = {}) {
	new Chart($('#' + elementId).get(0).getContext('2d'), {
		type : chartType,
		data : {
			'labels' : labels,
			'datasets' : datasets
		},
		options : Object.assign({
	            title : {
	                display : false,
	                text : "Title"
	            },
	            legend : {
	                position : "top"
	            },
	            tooltips : {
	                mode : 'label',
	                titleMarginBottom : 8,
	                bodySpacing : 6,
	                callbacks: {
	                    label : function(tooltipItems, data) {
	                        return data.datasets[tooltipItems.datasetIndex].label + ' : ' + formatNumberWithFormat(tooltipItems.yLabel);
	                    }
	                }
	            },
	            scales : {
	                xAxes : [{
	                    ticks : {}
	                }],
	                yAxes : [{
	                    ticks : {
							beginAtZero : true,
	                        userCallback : function(value, index, values) {
								return formatNumberWithFormat(value, (labels.length == 0 ? 1 : -1));
							}
	                    }
	                }]
	            },
	            responsive : true
	        },
	        customOptions
	    )
	});
}

function generateChartJSCustom(chartType, elementId, labels, datasets, customOptions = {}) {
	new Chart($('#' + elementId).get(0).getContext('2d'), {
		type : chartType,
		data : {
			'labels' : labels,
			'datasets' : datasets
		},
		options : Object.assign({
	            title : {
	                display : false,
	                text : "Title"
	            },
	            legend : {
	                position : "top"
	            },
	            tooltips : {
	                mode : 'label',
	                titleMarginBottom : 8,
	                bodySpacing : 6,
	                callbacks: {
	                    label : function(tooltipItems, data) {
	                        return data.datasets[tooltipItems.datasetIndex].label + ' : ' + formatNumberWithFormat(tooltipItems.yLabel);
	                    }
	                }
	            },
	            responsive : true
	        },
	        customOptions
	    )
	});
}



function generateAreaChartJS(elementId, labels, datasets, autoGenerateColors = true) {
	if (autoGenerateColors == true) {
		for (var i = 0; i < datasets.length; i++) {
			datasets[i]['backgroundColor'] = convertToRGBA(window.chartColors[i], 0.6);
			datasets[i]['borderColor'] = window.chartColors[i];
			datasets[i]['borderWidth'] = 2;
		}
	}
	generateChartJS('line', elementId, labels, datasets, {
        elements: {
		    line: {
		    	cubicInterpolationMode: 'monotone'
		    }
		}
	});
}

function generateLineChartJS(elementId, labels, datasets, autoGenerateColors = true, yStepSize = null) {
	if (autoGenerateColors == true) {
		for (var i = 0; i < datasets.length; i++) {
			datasets[i]['backgroundColor'] = convertToRGBA(window.chartColors[i], 1);
			datasets[i]['borderColor'] = window.chartColors[i];
			datasets[i]['borderWidth'] = 3;
			datasets[i]['fill'] = false;
		}
	}

	var options = {
		legend : {
            position : "top"
        },
        elements: {
		    line: {
		    	cubicInterpolationMode: 'monotone'
		    }
		}
	};

	if (yStepSize != null) {
		options.scales = {
			yAxes: [{
				ticks: {
					beginAtZero: true,
					stepSize: yStepSize
				}
			}]
		};
	}

	generateChartJS('line', elementId, labels, datasets, options);
}

function generateLineChartJSCustom(elementId, labels, datasets, customOptions = {} , autoGenerateColors = true) {
	if (autoGenerateColors == true) {
		for (var i = 0; i < datasets.length; i++) {
			datasets[i]['backgroundColor'] = convertToRGBA(window.chartColors[i], 1);
			datasets[i]['borderColor'] = window.chartColors[i];
			datasets[i]['borderWidth'] = 3;
			datasets[i]['fill'] = false;
		}
	}
	generateChartJSCustom('line', elementId, labels, datasets, customOptions);
}

function generateLineChartJSCustom2(elementId, labels, datasets, datasets2 , autoGenerateColors = true) {
	if (autoGenerateColors == true) {
		for (var i = 0; i < datasets.length; i++) {
			datasets[i]['backgroundColor'] = convertToRGBA(window.chartColors[i], 1);
			datasets[i]['borderColor'] = window.chartColors[i];
			datasets[i]['borderWidth'] = 3;
			datasets[i]['fill'] = false;
		}
	}
	generateChartJSCustom2('line', elementId, labels, datasets, datasets2);
}

function generateChartJSCustom2(chartType, elementId, labels, datasets, datasets2) {
	new Chart($('#' + elementId).get(0).getContext('2d'), {
		type : chartType,
		data : {
			'labels' : labels,
			'datasets' : datasets,
			'datasets2' : datasets2
		},
		options : Object.assign({
	            title : {
	                display : false,
	                text : "Title"
	            },
	            legend : {
	                position : "top"
	            },
	            tooltips : {
	                mode : 'label',
	                titleMarginBottom : 8,
	                bodySpacing : 6,
	                callbacks: {
	                    label : function(tooltipItems, data) {
	                        return data.datasets[tooltipItems.datasetIndex].label + ' : ' + formatNumberWithFormat(tooltipItems.yLabel) 
							+ ' ' + data.datasets2[tooltipItems.datasetIndex].category +' : ' + data.datasets2[tooltipItems.datasetIndex].data2[tooltipItems.index] 
							+ ' ' + data.datasets2[tooltipItems.datasetIndex].category2 +' : ' + data.datasets2[tooltipItems.datasetIndex].data3[tooltipItems.index];
	                    }
	                }
	            },
	            responsive : true
	        },
	        
	    )
	});
}

function generateBarChartJS(elementId, labels, datasets, autoGenerateColors = true) {
	if (autoGenerateColors == true) {
		for (var i = 0; i < datasets.length; i++) {
			datasets[i]['backgroundColor'] = convertToRGBA(window.chartColors[i], 0.6);
			datasets[i]['borderColor'] = window.chartColors[i];
			datasets[i]['borderWidth'] = 2;
		}
	}
	generateChartJS('bar', elementId, labels, datasets);
}

function generateTooltipforClass (classname, urlparam, position) {
	$(document).ready(function(){
		$('.' + classname).tooltip({
			title: fetchData,
			html: true,
			placement: position
		});

		function fetchData()
		{
			var fetch_data = '';
			var element = $(this);
			var id = element.attr("id");
			$.ajax({
				url: urlparam,
				method: "GET",
				async: false,
				data:{id:id},
				success: function(data)
				{
				fetch_data = data;
				console.log(data);
				}
			});
			return fetch_data;
		}
	});
}



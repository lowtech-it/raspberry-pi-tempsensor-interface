var dataPoints_day = [];
var dataPoints_week = [];
var dataPoints_month = [];
var dataPoints_year = [];
var chart1_check = 0;
var chart2_check = 0;
var chart3_check = 0;
var chart4_check = 0;
var myChart = '';
var myChart2 = '';
var myChart3 = '';
var myChart4 = '';
//var timeFormat = 'DD MM YYYY HH:mm';

updateData();

setInterval(updateData,20000);

function updateData() {

	$.getJSON("http://temperature.ash.to/index.php?tempshow=1", addData);

}

function addData(data) {

    dataPoints_day.length = 0;
    dataPoints_week.length = 0;
    dataPoints_month.length = 0;
    dataPoints_year.length = 0;

    //console.log(dataPoints_day.length);
    if(data['alarm_min'] != 0) {

        $('#alarm').text('ALARM TEMP MIN');

    } else if(data['alarm_max'] != 0) {

        $('#alarm').text('ALARM TEMP MAX');

    } else {

        $('#alarm').text('');

    }
    
    /*
     $('#alarm').each(function() {
        var elem = $(this);
        setInterval(function() {
            if (elem.css('visibility') == 'hidden') {
                elem.css('visibility', 'visible');
            } else {
                elem.css('visibility', 'hidden');
            }    
        }, 1000);
    });
    */
    
    $.each(data['day'], function(key, value) {

        dataPoints_day.push({x: value[0], y: value[1]});

    });
    
    $.each(data['week'], function(key, value) {

        dataPoints_week.push({x: value[0], y: value[1]});

    });
    
    $.each(data['month'], function(key, value) {

        dataPoints_month.push({x: value[0], y: value[1]});

    });
    
    $.each(data['year'], function(key, value) {

        dataPoints_year.push({x: value[0], y: value[1]});

    });

    drawchart_day();
    drawchart_week();
    drawchart_month();
    drawchart_year();
}


function drawchart_day() {

    var ctx = document.getElementById("myChart");
    
    if (chart1_check == 1) {

        myChart.update();

    } else {

        chart1_check = 1;
        
        myChart = new Chart(ctx, {

            type: 'line',
            data: {
                datasets: [{
                    label: 'Temperature ultime 24 ore - °C',
                    data: dataPoints_day,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'minute',
                            displayFormats: {
                                'minute': 'll HH:mm'
                            }
                        },
                            
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'value'
                        }
                    }]
                },
		elements: { 
				point: {
					radius: 0,
					hitRadius: 3,
					hoverRadius: 3,
				}
		},  

            }

        })
    }
}

function drawchart_week() {

    var ctx2 = document.getElementById("myChart2");

    if (chart2_check == 1) {

        myChart2.update();

    } else {

        chart2_check = 1;
        
        myChart2 = new Chart(ctx2, {

            type: 'line',
            data: {
                datasets: [{
                    label: 'Temperature ultimi 7 gg - °C',
                    data: dataPoints_week,
                    backgroundColor: [
                        'rgba(30,144,255,0.2)',
                    ],
                    borderColor: [
                        'rgba(30,144,255,1)',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    'day': 'll HH:mm'
                                }
                            },
                                
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'value'
                            }
                        }]
                },
		elements: { 
				point: {
					radius: 0,
					hitRadius: 3,
					hoverRadius: 3
				}
		},  
            }

        });
    }
}

function drawchart_month() {

    var ctx3 = document.getElementById("myChart3");    

    if (chart3_check == 1) {

        myChart3.update();

    } else {

        chart3_check = 1;
        
        myChart3 = new Chart(ctx3, {

            type: 'line',
            data: {
                datasets: [{
                    label: 'Temperature ultimo mese - °C',
                    data: dataPoints_month,
                    backgroundColor: [
                        'rgba(98,197,79,0.2)',
                    ],
                    borderColor: [
                        'rgba(98,197,79,1)',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    'day': 'll HH:mm'
                                }
                            },
                                
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'value'
                            }
                        }]
                },
		elements: { 
				point: {
					radius: 0,
					hitRadius: 3,
					hoverRadius: 3
				}
		},  
            }
        });
    }
}


function drawchart_year() {

    console.log('asd');

    var ctx4 = document.getElementById("myChart4");    

    if (chart4_check == 1) {

        myChart4.update();

    } else {

        chart4_check = 1;
        
        myChart4 = new Chart(ctx4, {

            type: 'line',
            data: {
                datasets: [{
                    label: 'Temperature ultimi 365 gg - °C',
                    data: dataPoints_year,
                    backgroundColor: [
                        'rgba(77,77,77,0.2)',
                    ],
                    borderColor: [
                        'rgba(77,77,0,1)',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'month',
                                displayFormats: {
                                    'day': 'll HH:mm'
                                }
                            },
                                
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'value'
                            }
                        }]
                },
		elements: { 
				point: {
					radius: 0,
					hitRadius: 3,
					hoverRadius: 3
				}
		},  
            }
        });
    }
}


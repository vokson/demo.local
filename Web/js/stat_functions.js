function makeMonthLabel(date, count) {
    var m_names = new Array("January", "February", "March",
            "April", "May", "June", "July", "August", "September",
            "October", "November", "December");

    var curr_month = date.getMonth();
    var curr_year = date.getFullYear();
    return 'Total ' + count.toString() + ' in ' + m_names[curr_month] + " " + curr_year;
}

function makeDayLabel(date, count) {
    return 'Total ' + count.toString() + ' in ' + date.toDateString();
}

function getData(period, offset, callback) {
//                console.log('PERIOD = %s, OFFSET = %s', period, offset);
    $.ajax({
        type: "POST",
        url: "index.php",
        dataType: "json",
        async: false,
        data: {
            cmd: 'getStatData',
            period: period,
            offset: offset
        },
        success: function(data) {
//                        console.log('DATA = %s', data);
//                        window.day_data_by_hour = data[0];
            callback(data);
        }
    });
}

function createDonut(element, data) {
    //Рисуем круговую диаграмма
    Morris.Donut({
        element: element,
        data: data,
        formatter: function(x) {
            return x + "%"
        }
    }).on('click', function(i, row) {
        console.log(i, row);
    });
}

function createChart(element) {
    //Рисуем график
    return Morris.Line({
        element: element,
        xkey: 'time',
        ykeys: ['value'],
        labels: ['Value']

    });
}

/*
 * Delete all rows from table except 1st
 */
function cleanCommandTable() {
    $("#commandTable").find("tr:gt(0)").remove();
}

/*
 * Add new command to table
 */
function addNewCommandToTable(e) {
    currentCommandNumber++;
    var s = '<tr id="commandLine_' + currentCommandNumber + '" ';
    if (e.command !== 'IMPORT') {
        s += 'class="ready"';
    }
    s += '>';
    s += '<td class="command">' + e.command + '</td>';
    s += '<td class="object">' + e.object + '</td>';
    s += '<td class="format">' + e.format + '</td>';
    s += '<td class="file">' + e.file + '</td>';
    s += '<td class="complete"></td>';
    s += '</tr>';
    $('#commandTable tr:last').after(s);
}

/*
 * Update command table after file's uploading
 */
function updateCommandTable(filename) {
    console.log('updateCommandFile(' + filename + ')');
    var tableRow = $("td").filter(function () {
        return $(this).text() === filename;
    }).closest("tr");
    // Add class if <td> is found
    if (tableRow) {
        tableRow.addClass('ready');
    }
}

/*
 * Check command table ready for execution ot not
 */
function isCommandTableReady() {
    // Get all rows of command table
    var rows = $('#commandTable').children('tbody').children('tr');
    // Check each row is ready or not
    for (var i = 1; i < rows.length; i++) {
        if ($(rows[i]).hasClass('ready') === false) {
            return false;
        }
    }

    return true;
}
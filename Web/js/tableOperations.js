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
    var s = '';
    if (e.command === 'IMPORT') {
        s += '<tr>';
    } else {
        s += '<tr class="ready">';
    }
    s += '<td>' + e.command + '</td>';
    s += '<td>' + e.object + '</td>';
    s += '<td>' + e.format + '</td>';
    s += '<td>' + e.file + '</td>';
    s += '<td class="complete"></td>';
    s += '</tr>';
    $('#commandTable tr:last').after('<tr>' + s + '</tr>');
}

//аналог функции var_dump в PHP
function var_dump(obj) {
    var out = "";
    if (obj && typeof (obj) === "object") {
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    alert(out);
}

function saveFile(url) {
    // Get file name from url.
    var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function () {
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
        a.download = filename; // Set the file name.
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        delete a;
    };
    xhr.open('GET', url);
    xhr.send();
}

function download() {
    $.ajax({
        type: "POST",
        url: "index.php",
        dataType: 'json',
        async: true,
        data: {
            cmd: 'GetTempFileNames'
        },
        success: function (path_to_file_array) {
            //Скачиваем файлы в цикле
            path_to_file_array.forEach(function (url) {
                saveFile(url);
            });
        }
    });
}

/*
 * Execute command from command Table
 */
function executeCommands() {
    console.log("executeCommands()");
    // Get all rows of command table
    var rows = $('#commandTable').children('tbody').children('tr');
    // Check each row is ready or not

    commandList = new Array();

    for (var i = 1; i < rows.length; i++) {
        commandList[i] = {
            command: $(rows[i]).find('td.command').html(),
            object: $(rows[i]).find('td.object').html(),
            format: $(rows[i]).find('td.format').html(),
            file: $(rows[i]).find('td.file').html()
        };
    }

    // Execute 1st command
    doCommand(1);
}

/*
 * Execute single command
 */
function doCommand(commandNumber) {
    //Check command with number exist or not
    if (commandNumber >= commandList.length) {
        return;
    }
    
    //Send request
    $.ajax({
        type: "POST",
        url: "index.php",
        dataType: 'json',
        async: true,
        data: {
            cmd: 'ExecuteCommand',
            timestamp: timeStamp,
            command: commandList[commandNumber].command,
            object: commandList[commandNumber].object,
            format: commandList[commandNumber].format,
            file: commandList[commandNumber].file
        },
        success: function (feedback) {
            var is_OK = feedback[0];

            if (is_OK === true) {
                // Command executed successfully
                $('tr#commandLine_' + commandNumber).find('td.complete').html(feedback[1] + ' sec.');
                $('tr#commandLine_' + commandNumber).find('td.complete').removeClass('fail');
            } else {
                // Command executed NOT successfully
                $('tr#commandLine_' + commandNumber).find('td.complete').html("ERROR");
                $('tr#commandLine_' + commandNumber).find('td.complete').addClass('fail');
            }
            
            // If command successful execute new command
            if (is_OK) {
                doCommand(commandNumber + 1);
            }
        }
    });
}

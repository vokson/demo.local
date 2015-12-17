
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

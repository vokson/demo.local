
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

function download() {
    //Функция для загрузки файлов
    var downloadURL = function downloadURL(url) {
        var hiddenIFrameID = 'hiddenDownloader',
                iframe = document.getElementById(hiddenIFrameID);
        if (iframe === null) {
            iframe = document.createElement('iframe');
            iframe.id = hiddenIFrameID;
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
        }
        iframe.src = url;
    };

    $.ajax({
        type: "POST",
        url: "index.php",
        async: true,
        data: {
            cmd: 'DownloadTempFiles'
        },
        success: function (json_data) {
            // Декодируем информацию
            // Array of temp filenames
            console.log(json_data);
            path_to_file_array = $.parseJSON(json_data);
            console.log(path_to_file_array[0]);
            console.log(path_to_file_array[1]);

            //Скачиваем файл
//            path_to_file_array.forEach(function (url) {
                $.fileDownload('http://demo.local/Temp/Model.rar')
                        .done(function () {
                            alert('File download a success!');
                        })
                        .fail(function () {
                            alert('File download failed!');
                        });
//                downloadURL(url);
//            });
        }
    });
}

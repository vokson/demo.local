
//аналог функции var_dump в PHP
function var_dump(obj) {
    var out = "";
    if (obj && typeof (obj) == "object") {
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    alert(out);
}


function clear() {
    $("#search_form").find("input:text").val('');
    $("#drawing").focus();
}

function check_server() {
    $("#check_bar").html('Please, wait...');
    $.ajax({
        type: "POST",
        url: "index.php",
        async: true,
        data: {
            cmd: 'NewTransmittalCheck'
        },
        success: function(data) {
            $("#check_bar").html(data);
            //Внесение изменений в свойства
            $('a#transmittal').click(function(s) {
                s.preventDefault();
                var transmit = $(this).attr('href');
                get_T_from_server(transmit);
            });
        }
    });
}

function get_T_from_server(transmit) {
    $("#get_bar").html('Please, wait...');
    $.post("index.php", {cmd: 'getTransmittal', transmit: transmit})
            .done(function(data) {
                $("#get_bar").html(data);
            });
}

//Проверка на пустой запрос   
function is_request_empty() {
    if ($("#project").val() === "" &&
            $("#drawing").val() === "" &&
            $("#revision").val() === "" &&
            $("#part").val() === "" &&
            $("#status").val() === "" &&
            $("#title").val() === "" &&
            $("#date_in_beg").val() === "" &&
            $("#date_in_end").val() === "" &&
            $("#transmit_in").val() === "")
        return true;
    else
        return false;
}

function search() {
//Проверка на пустой запрос 
    if (is_request_empty() === true)
        $("#search_results").html('Empty request is restricted. Please, add search condition.<br/>');
    else if (is_search_request_in_progress === true)
        $("#search_results").html('Please, wait result of your previous request!<br/>Loading..<br/>');
    else {
        //Запрет на повторный запрос
        is_search_request_in_progress = true;
        $("#search_results").html('Loading..<br/>');
        if ($('input#only_last_rev').attr('checked'))
            only_last_rev = 1;
        else
            only_last_rev = 0;
        $.ajax({
            type: "POST",
            url: "index.php",
            async: true,
            data: {
                cmd: 'find',
                project: $("#project").val(),
                drawing: $("#drawing").val(),
                revision: $("#revision").val(),
                part: $("#part").val(),
                status: $("#status").val(),
                title: $("#title").val(),
                date_in_beg: $("#date_in_beg").val(),
                date_in_end: $("#date_in_end").val(),
                transmit_in: $("#transmit_in").val(),
                only_last_rev: only_last_rev
            },
            success: function(data) {
                if (data.length > 0)
                    $("#search_results").html(data);
                //Подключаем plugin для сортировки
                $("#doc_table").tablesorter({
                    headers: {0: {sorter: false}}
                });
                //Разрешение на следующий запрос
                is_search_request_in_progress = false;
            }
        });
    }

}

//создает список найденных чертежей
function create_drawings_list(type) {
    var mas_drawings = [];
    if (type === 'PDF')
        $('.td_drawing').children("a").each(function() {
            mas_drawings.push($(this).attr('href'));
        });
    if (type === 'DWG')
        $('.td_icon.dwg').children("a").each(function() {
            mas_drawings.push($(this).attr('href'));
        });
    return mas_drawings;
}


//zip all drawings
function zip_drawings(type) {
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

//Проверка на пустой запрос   
    if (!$('.td_drawing').children("a").length)
        $("#get_bar").html('Document list is empty. Please, find something first.<br/>');
    else if (is_zip_request_in_progress === true)
        $("#get_bar").html('Please, wait result of your previous request!<br/>Loading..<br/>');
    else {
        //Запрет на повторный запрос
        is_zip_request_in_progress = true;

        max_count = 0;
        //Запрашиваем максимальное количество файлов для ZIP
        $.ajax({
            type: "POST",
            url: "index.php",
            async: false,
            data: {
                cmd: 'MaxCountOfFilesToDownload',
            },
            success: function(json_data) {
                //Декодируем информацию
                //data[0] = Count of files (int)
                max_count = $.parseJSON(json_data)[0];
            }
        });

        // Создаем массив файлов для скачивания
        drawings = create_drawings_list(type);

        $("#get_bar").show();
        //Если файлов <= max_count
        if ((max_count > 0) && (drawings.length <= max_count)) {

            $("#get_bar").html('Please, wait...<br/>');
            //Формируем файл
            $.ajax({
                type: "POST",
                url: "index.php",
                async: true,
                data: {
                    cmd: 'ZipCreate',
                    drawings: drawings
                },
                success: function(json_data) {
//                $("#get_bar").html(json_data);
                    //Декодируем информацию
                    //data[0] = BOOLEAN
                    //data[1] = array (count of files, filename)
                    data = $.parseJSON(json_data);
//                var_dump(data);

                    if (data[0] === true) {
                        numFiles = data[1].numFiles; //кол-во файлов
                        path_to_file = data[1].path_to_file; //имя файла
                        $("#get_bar").html("ZIP archieve has been packed - " + numFiles + " file(s).");
                        //Скачиваем файл
                        downloadURL(path_to_file);

//                    $("#get_bar").html(path_to_file);

                    } else {
                        $("#get_bar").html('Sorry, ERROR during creating ZIP archieve.<br/>');
                    }

                    //Разрешение на следующий запрос
                    is_zip_request_in_progress = false;
                }


            });
            //Если файлов > max_count
        } else
        {
            $("#get_bar").html('Count of documents ' + drawings.length + ' is more than ' + max_count + '. Please, add search condition.<br/>');
            //Разрешение на следующий запрос
            is_zip_request_in_progress = false;
        }
    }
}
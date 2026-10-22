function isSupportedFileAPI() {
    return window.File && window.FileReader && window.FileList && window.Blob;
}

function formatEmail(data) {
    if (data.email == null) {
        email = '';
    } else {
        email = data.email;
    }
    return data.name ? data.name + " " + email + "" : email;
}

function parseHeaders(headers) {
    var parsedHeaders = {};
    if (!headers) {
        return parsedHeaders;
    }
    var headerRegEx = /(.*)\: (.*)/g;
    while (m = headerRegEx.exec(headers)) {
        // todo: Pay attention! Header can be presented many times (e.g. Received). Handle it, if needed!
        parsedHeaders[m[1]] = m[2];
    }
    return parsedHeaders;
}

function getMsgDate(rawHeaders) {
    // Example for the Date header
    var headers = parseHeaders(rawHeaders);
    if (!headers['Date']) {
        return '-';
    }
    var options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };

    return new Date(headers['Date']).toLocaleDateString("es-MX", options);
}

function getMsgFrom(rawHeaders) {
    // Example for the Date header
    var headers = parseHeaders(rawHeaders);
    if (!headers['From']) {
        return '-';
    }

    return headers['From'].replace('<', '(').replace('>', ')');
}

function getMsgTo(rawHeaders) {
    // Example for the Date header
    var headers = parseHeaders(rawHeaders);
    if (!headers['To']) {
        return '-';
    }

    return headers['To'].replace('<', '(').replace('>', ')');
}

function getMsgCC(rawHeaders) {
    // Example for the Date header
    var headers = parseHeaders(rawHeaders);
    if (!headers['Cc']) {
        return '-';
    }

    return headers['Cc'].replace('<', '(').replace('>', ')');
}

$(function () {
    if (isSupportedFileAPI()) {
        $('.src-file').change(function () {
            console.log(this.files[0]);
            var selectedFile = this.files[0];
            if (!selectedFile) {
                $('.msg-info, .incorrect-type').hide();
                return;
            }
            if (selectedFile.name.indexOf('.msg') == -1) {
                $('.msg-info').hide();
                $('.incorrect-type').show();
                return;
            }
            $('.msg-oport .msg-file-name').html(selectedFile.name);
            $('.incorrect-type').hide();

            // read file...
            var fileReader = new FileReader();
            fileReader.onload = function (evt) {

                var buffer = evt.target.result;
                var msgReader = new MSGReader(buffer);
                var fileData = msgReader.getFileData();
                if (!fileData.error) {
                    $('.msg-oport .msg-from').html(formatEmail({ name: fileData.senderName, email: fileData.senderEmail }));
                    $('.msg-oport .msg-to').html(jQuery.map(fileData.recipients, function (recipient, i) {
                        return formatEmail(recipient);
                    }).join('<br/>'));
                    $('.msg-oport .msg-date').html(getMsgDate(fileData.headers));
                    $('.msg-oport .msg-subject').html(fileData.subject);
                    $('.msg-oport .msg-body').html(
                        fileData.body ? fileData.body.substring(0, Math.min(1000, fileData.body.length))
                            + (fileData.body.length > 1000 ? '...' : '') : '');
                    if (fileData.bodyHTML) {
                        $('.msg-oport .msg-body-html').html(fileData.bodyHTML).closest('div.field-block').show();
                    } else {
                        // $('.msg-oport .msg-body-html').html(fileData.body).closest('div.field-block').show();
                        $('.msg-oport .msg-body-html').closest('div.field-block').hide();
                    }
                    $('.msg-oport .msg-attachment').html(jQuery.map(fileData.attachments, function (attachment, i) {
                        var file = msgReader.getAttachment(i);
                        var fileUrl = URL.createObjectURL(new File([file.content], attachment.fileName,
                            { type: attachment.mimeType ? attachment.mimeType : "application/octet-stream" }));

                        if (fileUrl != null) {
                            return '<i class="fas fa-paperclip text-md mr-1"></i><a href="' + fileUrl + '" target="_blank">' + attachment.fileName + ' [' + attachment.contentLength + 'bytes]' +
                                (attachment.pidContentId ? '; ID = ' + attachment.pidContentId : '') + '</a>';
                        }
                    }).join('<br/>'));
                    $('.msg-info').show();

                    // Use msgReader.getAttachment to access attachment content ...
                    // msgReader.getAttachment(0) or msgReader.getAttachment(fileData.attachments[0])
                } else {
                    $('.msg-info').hide();
                    $('.incorrect-type').show();
                }
            };
            fileReader.readAsArrayBuffer(selectedFile);
        });

    } else {
        $('.msg-oport').hide();
        $('.file-api-not-available').show();
    }
});

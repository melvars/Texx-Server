$("#avatarFile").on("change", () => {
    var preview = document.querySelector('#image-preview');
    var file = document.querySelector("#avatarFile").files[0];
    var reader = new FileReader();

    reader.addEventListener("load", () => {
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
});

$("#avatarForm").submit(function (e) {
    var url = "/avatar";
    var data = new FormData();
    $.each(jQuery('#avatarFile')[0].files, (i, file) => {
        data.append('avatar', file);
    });

    e.preventDefault();
    $.ajax({
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: (data) => {
            $("#avatarUploadSucceededAlert").show();
            $("#avatarUploadSucceededMessage").text(data.success);
        },
        error: () => {
            $("#avatarUploadFailedAlert").show();
        }
    });
});
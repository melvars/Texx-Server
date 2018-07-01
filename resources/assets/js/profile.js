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
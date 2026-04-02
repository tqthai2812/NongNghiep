function triggerUpload() {
    document.querySelector('input[name="avatar"]').click();
}

function handleUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const box = document.getElementById("avatarBox");
        const placeholder = document.getElementById("uploadPlaceholder");

        // Xóa ảnh hiện tại (nếu có)
        const oldImgs = box.querySelectorAll("img");
        oldImgs.forEach((img) => img.remove());

        placeholder.style.display = "none";

        const img = document.createElement("img");
        img.src = e.target.result;
        box.appendChild(img);

        if (!box.querySelector(".delete-btn")) {
            const deleteBtn = document.createElement("div");
            deleteBtn.className = "delete-btn";
            deleteBtn.innerHTML = '<span class="material-icons">close</span>';
            deleteBtn.onclick = (e) => removeImage(e);
            box.appendChild(deleteBtn);
        }
    };
    reader.readAsDataURL(file);
}

function removeImage(e) {
    e.stopPropagation();
    const box = document.getElementById("avatarBox");
    const placeholder = document.getElementById("uploadPlaceholder");

    const imgs = box.querySelectorAll("img");
    imgs.forEach((img) => img.remove());

    placeholder.style.display = "block";
    const deleteBtn = box.querySelector(".delete-btn");
    if (deleteBtn) deleteBtn.remove();

    document.querySelector('input[name="avatar"]').value = "";
}

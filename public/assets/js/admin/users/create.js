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

        // Xóa ảnh cũ nếu có
        const oldImg = box.querySelector("img");
        if (oldImg) oldImg.remove();

        // Ẩn placeholder
        placeholder.style.display = "none";

        // Thêm ảnh mới
        const img = document.createElement("img");
        img.src = e.target.result;
        box.appendChild(img);

        // Thêm nút xóa nếu chưa có
        if (!box.querySelector(".delete-btn")) {
            const deleteBtn = document.createElement("div");
            deleteBtn.className = "delete-btn";
            deleteBtn.innerHTML = '<span class="material-icons">close</span>';
            deleteBtn.onclick = (e) => {
                e.stopPropagation();
                img.remove();
                placeholder.style.display = "block";
                deleteBtn.remove();
                document.querySelector('input[name="avatar"]').value = "";
            };
            box.appendChild(deleteBtn);
        }
    };
    reader.readAsDataURL(file);
}

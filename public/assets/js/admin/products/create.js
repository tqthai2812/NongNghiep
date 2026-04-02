const MAX_IMAGES = 9;

function triggerUpload(box) {
    if (box.querySelector("img")) return;
    box.querySelector("input").click();
}

function handleUpload(event, input) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const box = input.parentElement;

        // Ẩn span thêm ảnh
        const span = box.querySelector("span");
        if (span) span.style.display = "none";

        // Tạo img
        const img = document.createElement("img");
        img.src = e.target.result;
        box.appendChild(img);

        // Tạo nút xoá
        const deleteBtn = document.createElement("div");
        deleteBtn.className = "delete-btn";
        deleteBtn.innerHTML = '<span class="material-icons">close</span>';
        deleteBtn.onclick = (ev) => removeImage(ev, deleteBtn);
        box.appendChild(deleteBtn);

        if (!document.querySelector(".cover-badge")) {
            const badge = document.createElement("div");
            badge.className = "cover-badge";
            badge.innerText = "Ảnh bìa";
            box.appendChild(badge);
        }

        addNewUploadBox();
    };

    reader.readAsDataURL(file);
}

function addNewUploadBox() {
    const container = document.getElementById("imageContainer");
    const boxes = container.querySelectorAll(".image-box");

    if (boxes.length >= MAX_IMAGES) return;

    const hasEmpty = [...boxes].some((b) => !b.querySelector("img"));
    if (hasEmpty) return;

    const box = document.createElement("div");
    box.className = "image-box";
    box.onclick = () => triggerUpload(box);

    box.innerHTML = `
    <input name="images[]" type="file" accept="image/*" hidden onchange="handleUpload(event, this)">
    <span>
      <span class="material-icons">add_photo_alternate</span><br>
      Thêm ảnh
    </span>
  `;

    container.appendChild(box);
}

function removeImage(event, btn) {
    event.stopPropagation();

    const box = btn.parentElement;
    const container = document.getElementById("imageContainer");
    const wasCover = box.querySelector(".cover-badge");

    box.remove();

    if (wasCover) {
        const nextImage = container.querySelector(".image-box img");
        if (nextImage) {
            const nextBox = nextImage.parentElement;
            const badge = document.createElement("div");
            badge.className = "cover-badge";
            badge.innerText = "Ảnh bìa";
            nextBox.appendChild(badge);
        }
    }

    addNewUploadBox();
}

function autoResize(textarea) {
    // 1. Reset về auto để tính toán lại chiều cao thực của nội dung
    textarea.style.height = "auto";

    // 2. Lấy chiều cao nội dung thực tế
    let newHeight = textarea.scrollHeight;

    // 3. Giới hạn trong CSS là 300,
    // Nếu nội dung nhỏ hơn 300 thì giãn theo nội dung, ngược lại giữ 300
    if (newHeight > 300) {
        textarea.style.height = "300px";
    } else {
        textarea.style.height = newHeight + "px";
    }
}

// Xử lý phân loại sản phẩm
function updateTableHeader() {
    document.getElementById("headerName").innerText =
        document.getElementById("groupTitleInput").value || "Phân loại";
}

function checkNewField(input) {
    input.parentElement.querySelector(".char-count").innerText =
        `${input.value.length}/20`;
    const wrapper = document.getElementById("variantWrapper");
    const inputs = wrapper.querySelectorAll(".variant-input");
    if (input === inputs[inputs.length - 1] && input.value.trim() !== "") {
        const newCol = document.createElement("div");
        newCol.className = "col-md-6";
        newCol.innerHTML = `
                <div class="variant-item">
                    <input name="packages_size[]" type="text" class="inner-input variant-input" placeholder="Nhập" oninput="updateTable(); checkNewField(this)">
                    <span class="text-muted small ms-auto char-count">0/20</span>
                    <i class="bi bi-trash ms-2 text-muted" onclick="removeField(this)" style="cursor:pointer"></i>
                </div>`;
        wrapper.appendChild(newCol);
    }
}

function removeField(icon) {
    const wrapper = document.getElementById("variantWrapper");
    if (wrapper.querySelectorAll(".col-md-6").length > 1) {
        icon.closest(".col-md-6").remove();
        updateTable();
    }
}

function updateTable() {
    const inputs = document.querySelectorAll(".variant-input");
    const tableBody = document.getElementById("variantTableBody");
    const tableSection = document.getElementById("tableSection");
    const values = Array.from(inputs)
        .map((i) => i.value.trim())
        .filter((v) => v !== "");

    if (values.length === 0) {
        tableSection.style.display = "none";
        return;
    }

    tableSection.style.display = "flex";
    const oldData = {};
    tableBody.querySelectorAll("tr").forEach((tr) => {
        const key = tr.getAttribute("data-key");
        oldData[key] = {
            price: tr.querySelector(".row-price").value,
            stock: tr.querySelector(".row-stock").value,
        };
    });

    tableBody.innerHTML = "";
    values.forEach((val) => {
        const row = document.createElement("tr");
        row.setAttribute("data-key", val);
        row.innerHTML = `
                <td class="text-center fw-medium">${val}<input type="hidden" name="packages[${val}][size]" value="${val}"></td>
                <td>
                    <div class="currency-input">
                        <span>₫</span>
                        <input type="number" name="packages[${val}][price]" class="row-price" placeholder="Nhập vào" value="${oldData[val]?.price || ""}">
                    </div>
                </td>
                <td>
                    <input type="number" name="packages[${val}][stock]" class="input-table row-stock" placeholder="0" value="${oldData[val]?.stock || "0"}">
                </td>
            `;
        tableBody.appendChild(row);
    });
}

function applyToAll() {
    const bPrice = document.getElementById("batchPrice").value;
    const bStock = document.getElementById("batchStock").value;
    if (bPrice !== "")
        document
            .querySelectorAll(".row-price")
            .forEach((i) => (i.value = bPrice));
    if (bStock !== "")
        document
            .querySelectorAll(".row-stock")
            .forEach((i) => (i.value = bStock));
}

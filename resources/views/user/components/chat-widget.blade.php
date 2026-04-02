<div id="ai-chat-button" onclick="toggleChat()" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background-color: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; cursor: pointer; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 9999; transition: transform 0.3s;">
    <i class="fa-brands fa-bots"></i>
</div>

<div id="ai-chat-container" style="display: none; position: fixed; bottom: 3px; right: 3px; width: 350px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); z-index: 99999; flex-direction: column; overflow: hidden; border: 1px solid #ddd;">

    <div style="background: #28a745; color: white; padding: 12px 15px; display: flex; justify-content: space-between; align-items: center;">
        <h4 style="margin: 0; font-size: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-robot"></i> AI Tư Vấn Nông Nghiệp
        </h4>
        <button onclick="toggleChat()" style="background: transparent; border: none; color: white; font-size: 18px; cursor: pointer; padding: 0;"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div id="chat-box" style="height: 350px; overflow-y: auto; padding: 15px; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px;">
        <div style="align-self: flex-start; background: #e9ecef; padding: 10px 14px; border-radius: 15px 15px 15px 0; max-width: 85%; font-size: 14px;">
            Chào bạn, mình là AI tư vấn. Mình có thể giúp gì cho mùa vụ của bạn hôm nay?
        </div>
    </div>

    <div style="display: flex; gap: 10px; padding: 15px; background: white; border-top: 1px solid #eee;">
        <input type="text" id="user-input" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 20px; outline: none; font-size: 14px;" placeholder="Nhập câu hỏi...">
        <button id="send-button" onclick="sendMessage()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 20px; cursor: pointer; font-weight: bold; transition: background 0.3s;">Gửi</button>
    </div>
</div>

<script>
    // Hàm bật/tắt khung chat
    function toggleChat() {
        let chatContainer = document.getElementById('ai-chat-container');
        let chatButton = document.getElementById('ai-chat-button');

        if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
            chatContainer.style.display = 'flex';
            chatButton.style.transform = 'scale(0)'; // Giấu nút tròn đi
        } else {
            chatContainer.style.display = 'none';
            chatButton.style.transform = 'scale(1)'; // Hiện lại nút tròn
        }
    }

    async function sendMessage() {
        let inputField = document.getElementById('user-input');
        let sendButton = document.getElementById('send-button');
        let message = inputField.value.trim();

        if (!message) return;

        let chatBox = document.getElementById('chat-box');

        // 1. Vô hiệu hóa nút gửi & ô nhập liệu, đổi màu nút thành xám
        sendButton.disabled = true;
        sendButton.style.background = '#cccccc';
        sendButton.style.cursor = 'not-allowed';
        inputField.disabled = true;

        // Render tin nhắn của User
        chatBox.innerHTML += `
        <div style="align-self: flex-end; background: #28a745; color: white; padding: 10px 14px; border-radius: 15px 15px 0 15px; max-width: 85%; font-size: 14px; margin-bottom: 5px;">
            ${message}
        </div>
    `;
        inputField.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Render hiệu ứng AI đang gõ
        let typingId = 'typing-' + Date.now();
        chatBox.innerHTML += `
        <div id="${typingId}" style="align-self: flex-start; background: #e9ecef; padding: 10px 14px; border-radius: 15px 15px 15px 0; max-width: 85%; font-size: 14px; margin-bottom: 5px; color: #666;">
            <i>Đang suy nghĩ...</i>
        </div>
    `;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            let response = await fetch('{{ route("chat.ask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message
                })
            });

            let data = await response.json();
            document.getElementById(typingId).remove(); // Xóa chữ đang suy nghĩ

            if (response.ok) {
                let formattedReply = data.reply.replace(/\n/g, '<br>');
                chatBox.innerHTML += `
                <div style="align-self: flex-start; background: #e9ecef; padding: 10px 14px; border-radius: 15px 15px 15px 0; max-width: 85%; font-size: 14px; margin-bottom: 5px;">
                    ${formattedReply}
                </div>
            `;
            } else {
                chatBox.innerHTML += `
                <div style="align-self: flex-start; background: #ffebee; color: #c62828; padding: 10px 14px; border-radius: 15px; max-width: 85%; font-size: 14px; margin-bottom: 5px;">
                    <b>Lỗi:</b> ${data.reply}
                </div>
            `;
            }
        } catch (error) {
            document.getElementById(typingId).remove();
            chatBox.innerHTML += `
            <div style="align-self: flex-start; background: #ffebee; color: #c62828; padding: 10px 14px; border-radius: 15px; max-width: 85%; font-size: 14px; margin-bottom: 5px;">
                <b>Lỗi:</b> Không thể kết nối tới máy chủ.
            </div>
        `;
        } finally {
            // 2. Bất kể thành công hay lỗi, khôi phục lại trạng thái nút và ô nhập liệu
            sendButton.disabled = false;
            sendButton.style.background = '#28a745';
            sendButton.style.cursor = 'pointer';

            inputField.disabled = false;
            inputField.focus(); // Tự động đưa con trỏ chuột quay lại ô nhập để gõ tiếp

            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    // Cho phép ấn Enter để gửi
    document.getElementById('user-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
</script>
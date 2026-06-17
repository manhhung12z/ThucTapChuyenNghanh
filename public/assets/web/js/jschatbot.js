function toggleChatbot(){
    const box = document.getElementById('chatbotBox');
    box.style.display = box.style.display === 'flex' ? 'none' : 'flex';
}
async function sendMessage(){
    const input =document.getElementById('chatInput');
    const messages= document.getElementById('chatMessages');
    const message = input.value.trim();
    if (message === '') return;
    messages.innerHTML += `<div class="user-message">${message}</div>`;
    input.value='';
    messages.innerHTML += `<div class="bot-message" id="typing">Dạ shop đang trả lời...</div>`;
    const response = await fetch(CHATBOT_URL,{
        method:'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "message=" + encodeURIComponent(message)

    });
    const data = await response.json();

    document.getElementById('typing').remove();

    messages.innerHTML += `<div class="bot-message">${data.answer}</div>`;
    messages.scrollTop = messages.scrollHeight;
}
document.getElementById('chatInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

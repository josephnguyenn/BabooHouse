function openLightbox(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const price = button.getAttribute('data-price');
    const area = button.getAttribute('data-area');
    const status = button.getAttribute('data-status');

    document.getElementById('room_id').value = id;
    document.getElementById('room_name').value = name;
    document.getElementById('room_price').value = price;
    document.getElementById('room_area').value = area;
    document.getElementById('room_status').value = status;
    document.getElementById('lightboxroom').style.display = 'flex';
}

function markAsRead(notificationId, message, createdAt) {
    fetch('../admin/mark_as_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + notificationId
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            let lightbox = document.getElementById('lightboxview');
            lightbox.style.display = 'flex';

            // Set formatted HTML content
            document.getElementById('notif-message').innerHTML = message;
            document.getElementById('notif-time').innerText = createdAt;
            document.getElementById('notif-' + notificationId).classList.remove('unread');

            setTimeout(() => {
                lightbox.style.opacity = "1";
                document.querySelector(".lightbox-content").style.transform = "scale(1)";
            }, 10);

            let notifbox = document.getElementById("notification-box");
            notifbox.style.display = "none";
        }
    });
}


function closeLightbox() {
    let lightbox = document.getElementById("lightboxview");
    lightbox.style.opacity = "0";
    document.querySelector(".lightbox-content").style.transform = "scale(0.8)";
    setTimeout(() => {
        lightbox.style.display = "none";
    }, 300);
}
import asyncRequest from "./default-ajax.js";
import {generateElements} from "./elementGenerators.js";

const notificationsDiv = document.getElementById('notificationsSpace');
let offset = 0;

function pushNotification(user, content) {
    asyncRequest(`request-notifications.php`, args={type: 'add', user, content});
}

// Render notifications elements
function renderNotifications() {
    asyncRequest(`request-notifications.php`, (response) => {
        if (response.length == 0) {
            const noNotifications = Object.assign(document.createElement('p'), {className: 'no-result', innerText: 'No notifications to show'});
            notificationsDiv.appendChild(noNotifications);
            return;
        }
        generateElements(response, notificationsDiv, 'notification');
    }, {type: 'get', offset, limit: 50});
}

window.onload = function () {
    // Load more notifications when scrolling to the bottom
    notificationsDiv.addEventListener('scroll', () => {
        const endOfPage = notificationsDiv.scrollTop + notificationsDiv.clientHeight >= notificationsDiv.scrollHeight;
        if (endOfPage) {
            offset += 50;
            renderNotifications();
        }
    });

    // Mark all notifications of the logged user as read
    document.getElementById('readAllNotifications').addEventListener('click', () => {
        asyncRequest(`request-notifications.php`, (response) => {
            if (response) {
                document.querySelectorAll('.notification').forEach((notification) => {
                    notification.classList.add('read');
                });
            }
        }, {type: 'read'});
    });

    // Render notifications on page load
    renderNotifications();
}

export default pushNotification;

import asyncRequest from "./default-ajax.js";
import { throttle } from "./throttle.js";
import {generateElements} from "./elementGenerators.js";

const notificationsDiv = document.getElementById('notificationsSpace');
let offset = 0;

function pushNotification(receiver, content, postId = null, commentId = null) {
    asyncRequest(`request-notifications.php`, () => {}, {type: 'add', receiver, content, postId, commentId});
}

// Render notifications elements
function renderNotifications() {
    asyncRequest(`request-notifications.php`, (response) => {
        if (response.length == 0) {
            let noNotifications = document.getElementById('no-more-notifications');
            if (noNotifications) return;
            noNotifications = Object.assign(document.createElement('p'), {id: 'no-more-notifications',className: 'no-result', innerText: 'No more notifications to show'});
            notificationsDiv.appendChild(noNotifications);
            return;
        }
        generateElements(response, notificationsDiv, 'notification');
    }, {type: 'get', offset, limit: 50});
}

window.onload = function () {

    window.onscroll = function () {
        if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
            throttle(() => {
                offset += 50;
                renderNotifications();
            }, 1000);
        }
    };

    // Mark all notifications of the logged user as read
    document.getElementById('readAllNotifications').addEventListener('click', () => {
        asyncRequest(`request-notifications.php`, (response) => {
            if (response) {
                document.querySelectorAll('.notification').forEach((notification) => {
                    notification.classList.add('seen');
                });
            }
        }, {type: 'read'});
    });

    // Render notifications on page load
    renderNotifications();
}

export default pushNotification;

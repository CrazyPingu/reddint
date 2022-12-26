import asyncCall from "./default-ajax.js";

//show initial posts
window.onload = function() {
    asyncCall('random-posts.php', (response) => {
        spacePosts.innerHTML = response;
    });
}

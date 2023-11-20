require("./bootstrap");

axios.defaults.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");


import { createApp } from "vue";
import store from "./store";
import UploadComponent from "./components/UploadComponent.vue";
import FullCalendar from "./components/FullCalendar.vue";
import CategoryTab from "./components/CategoryTab.vue";
import FriendAddition from "./components/FriendAddition.vue";
import LikeButton from "./components/LikeButton.vue";


const app = createApp({});

app.use(store);

app.component("UploadComponent", UploadComponent);
app.component("FullCalendar", FullCalendar);
app.component("CategoryTab", CategoryTab);
app.component("FriendAddition", FriendAddition);
// 他のコンポーネントも同様に登録
app.mount("#app");



const likeApp = createApp({});

likeApp.component("LikeButton", LikeButton);

document.querySelectorAll("[id^='like-app-']").forEach((element) => {
    const likeApp = createApp({});
    likeApp.component("LikeButton", LikeButton);
    likeApp.mount(`#${element.id}`);
});




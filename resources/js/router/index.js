import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Friend from "../components/Friend.vue"; // Friendコンポーネントをインポート

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/friend/:friendId", // パラメータを含むルート
        name: "Friend",
        component: Friend,
        props: true, // URLパラメータをpropsとしてコンポーネントに渡す
    },
    // 他のルート...
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;

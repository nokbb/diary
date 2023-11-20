import { createStore } from "vuex";
import axios from "axios";


export default createStore({
    state: {
        // 初期状態
        activeMemoryIndex: 0, // 現在のアクティブなメモリーのインデックス
        memories: [],
        selectedMemories: [], // 選択されたすべてのメモリーを保存するためのデータ
        activeCategory: 1,
        categoryPrivacy: {
            1: true, // 初期値は公開
        },
        categories: [
            // カテゴリーデータの初期値
            { id: 1, name: "ALL" },
        ],
        // ユーザーに紐づくカテゴリーデータを保存するためのプロパティ
        userCategories: [],

        //ここからFriend.vue
        friendRequestStatus: "none", // 'pending', 'accepted'
        friendId: null, // 初期値を設定
        friendMemories: [],
    },
    mutations: {
        // 状態を変更するためのメソッド
        SET_ACTIVE_MEMORY_INDEX(state, index) {
            state.activeMemoryIndex = index;
        },
        SET_SELECTED_MEMORIES(state, memories) {
            state.selectedMemories = memories;
        },
        SET_FRIEND_MEMORIES(state, memories) {
            state.friendMemories = memories;
        },
        SET_CATEGORIES(state, categories) {
            state.categories = categories;
        },
        SET_ACTIVE_CATEGORY(state, categoryId) {
            state.activeCategory = categoryId;
        },
        ADD_CATEGORY(state, category) {
            state.categories.push(category);
        },
        REMOVE_CATEGORY(state, categoryId) {
            state.categories = state.categories.filter(
                (category) => category.id !== categoryId
            );
        },
        SET_MEMORIES(state, memories) {
            state.memories = memories;
        },
        FILTER_MEMORIES(state, categoryId) {
            if (categoryId === 1) {
                // カテゴリIDが1の場合は全てのメモリーを表示
                state.selectedMemories = state.memories;
            } else {
                state.selectedMemories = state.memories.filter(
                    (memory) => memory.category_id === categoryId
                );
            }
        },
        // ユーザーに紐づくカテゴリーデータを設定するミューテーション
        SET_USER_CATEGORIES(state, categories) {
            state.userCategories = categories;
        },

        //ここからFriend.vue
        setFriendRequestStatus(state, status) {
            state.friendRequestStatus = status;
        },
        setFriendId(state, id) {
            state.friendId = id;
        },
    },
    actions: {
        // 非同期処理や複数のmutationを扱うメソッド
        fetchCategories({ commit }) {
            axios
                .get("/api/categories")
                .then((response) => {
                    commit("SET_CATEGORIES", response.data);
                    if (response.data.length > 0) {
                        commit("SET_ACTIVE_CATEGORY", response.data[0].id);
                    }
                })
                .catch((error) => {
                    console.error("カテゴリの取得に失敗しました。", error);
                });
        },
        fetchMemories({ commit }) {
            axios
                .get("/api/memories")
                .then((response) => {
                    commit("SET_MEMORIES", response.data);
                    commit("FILTER_MEMORIES", 1); // 初期状態では全てのメモリーを表示
                })
                .catch((error) => {
                    console.error("メモリーの取得に失敗しました。", error);
                });
        },

        // ユーザーに紐づくカテゴリーデータを取得するアクション
        fetchUserCategories({ commit }) {
            axios
                .get("/api/users/categories")
                .then((response) => {
                    commit("SET_USER_CATEGORIES", response.data);
                })
                .catch((error) => {
                    console.error(
                        "ユーザーに紐づくカテゴリーの取得に失敗しました。",
                        error
                    );
                });
        },

        selectCategory({ commit }, categoryId) {
            commit("SET_ACTIVE_CATEGORY", categoryId);
            commit("FILTER_MEMORIES", categoryId);
        },
        addCategory({ commit, dispatch }, categoryName) {
            axios
                .post("/api/categories", { name: categoryName })
                .then((response) => {
                    commit("ADD_CATEGORY", response.data);
                    commit("SET_ACTIVE_CATEGORY", response.data.id);
                    dispatch("fetchUserCategories"); // ユーザーカテゴリを再取得
                })
                .catch((error) => {
                    console.error("カテゴリの追加に失敗しました。", error);
                });
        },
        removeCategory({ commit, dispatch }, categoryId) {
            if (confirm("このカテゴリーを削除してもよろしいですか？")) {
                axios
                    .delete(`/api/categories/${categoryId}`)
                    .then(() => {
                        commit("REMOVE_CATEGORY", categoryId);
                        dispatch("fetchUserCategories"); // ユーザーカテゴリを再取得
                    })
                    .catch((error) => {
                        console.error("カテゴリの削除に失敗しました。", error);
                    });
            }
        },

        //ここからFriend.vue
        sendFriendRequest({ commit }, friendId) {
            axios
                .post("/api/friend/request", { friend_id: friendId })
                .then(() => {
                    commit("setFriendRequestStatus", "pending");
                })
                .catch((error) => console.error(error));
        },
        checkFriendRequestStatus({ commit }, friendId) {
            axios
                .get(`/api/friend/request/status/${friendId}`)
                .then((response) => {
                    commit("setFriendRequestStatus", response.data.status);
                })
                .catch((error) => console.error(error));
        },
        deleteFriend({ commit }, friendId) {
            if (confirm("本当に削除しますか？")) {
                axios
                    .post("/api/friend/delete", { friend_id: friendId })
                    .then(() => {
                        commit("setFriendRequestStatus", "none");
                    })
                    .catch((error) => console.error(error));
            }
        },
        fetchFriendMemories({ commit, state }) {
            axios
                .get("/api/friend-memories/" + state.friendId)
                .then((response) => {
                    commit("SET_FRIEND_MEMORIES", response.data);
                })
                .catch((error) => {
                    console.error("Error fetching friend memories:", error);
                });
        },
        updateFriendId({ commit }, id) {
            commit("setFriendId", id);
        },
    },
    getters: {
        // 状態の派生値を計算するメソッド
        selectedMemories: (state) => {
            return state.activeCategory === 1
                ? [...state.memories]
                : state.memories.filter(
                      (memory) => memory.category_id === state.activeCategory
                  );
        },
    },
});

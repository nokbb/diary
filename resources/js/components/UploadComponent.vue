<template>
  <div class="content">
    <button @click="openFileDialog" class="upload-button"></button>
    <input type="file" @change="fileSelected" accept="image/*" capture="camera" ref="fileInput" style="display: none;" />
  
    <!-- プレビュー画像 -->
    <div v-if="previewImage" class="previewImage">
      <img :src="previewImage" alt="Preview Image" />
      <!-- ユーザーカテゴリーの選択 -->
      <div v-for="category in combinedCategories" :key="category.id">
        <label>
          <input type="radio" :name="'category'" :value="category.id" v-model="activeCategory" @change="updateCategory(category.id)">
          {{ category.name }}
        </label>
      </div>
      <!-- カテゴリー追加アイコン -->
        <button @click="toggleCategoryAddition" class="category-add-icon">
          <i class="fa" :class="showCategoryAddition ? 'fa-minus' : 'fa-plus'"></i>
        </button>

        <!-- カテゴリー追加UI -->
        <div v-if="showCategoryAddition">
          <input type="text" v-model="newCategoryName" placeholder="新しいカテゴリー">
          <button @click="addCategory" class="addCategory">カテゴリーを追加</button>
        </div>
      <!-- キャプションとエントリーの入力 -->
      <div class="textarea">
        <input v-model="caption" placeholder="Caption...">
        <textarea v-model="entry" placeholder="Text..." class="entry"></textarea>
      </div>
      <button @click="fileUpload" class="fileUpload">アップロード</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";


const store = useStore();
const fileInfo = ref("");
const memory = ref(null);
const showUserImage = ref(false);
const imagePath = ref(""); // アップロードされた画像のパスを保存するための変数
const previewImage = ref(""); // プレビュー画像のURLを保存するための変数
const caption = ref(""); // キャプションテキストを保存するための変数
const entry = ref("");   // エントリーテキストを保存するための変数
const uploadedCaption = ref(''); // キャプションの初期値を空文字列に設定
const uploadedEntry = ref(''); // キャプションの初期値を空文字列に設定
const category_id = ref(1); // 選択されたカテゴリーを保存するための変数
// const categories = ref([]); // カテゴリーのリストをリアクティブな配列で管理
// const activeCategory = ref(null); // 選択されたカテゴリーを保存するための変数
const newCategoryName = ref(''); // 新しいカテゴリー名のためのリアクティブな変数を定義
const showCategoryAddition = ref(false);

// Vuexストアからカテゴリーデータを取得
const categories = computed(() => store.state.categories);
const userCategories = computed(() => store.state.userCategories);
const activeCategory = computed({
  get: () => store.state.activeCategory,
  set: (newValue) => store.commit('SET_ACTIVE_CATEGORY', newValue)
});

// 全カテゴリ（'ALL' を含む）とユーザーカテゴリを結合
const combinedCategories = computed(() => {
  return [...categories.value, ...userCategories.value];
});

const updateCategory = (categoryId) => {
  store.commit('SET_ACTIVE_CATEGORY', categoryId);
};



// ユーザーに紐づくカテゴリを取得する関数
const fetchCategories = async () => {
  try {
    // ユーザー固有のカテゴリを取得するためのAPIエンドポイントにリクエストを送る
    const response = await axios.get('/api/users/categories');
    store.commit('SET_USER_CATEGORIES', response.data);
    // ユーザーに紐づくカテゴリの有無に関わらず、ALL (id: 1) を初期値とする
    store.commit('SET_ACTIVE_CATEGORY', 1);
  } catch (error) {
    console.error('ユーザーに紐づくカテゴリの取得に失敗しました。', error.response.data);
  }
};


// コンポーネントがマウントされた時に実行
onMounted(async () => {
  await fetchCategories();
});

const toggleCategoryAddition = () => {
  showCategoryAddition.value = !showCategoryAddition.value;
};


// 新しいカテゴリーを追加する関数
const addCategory = async () => {
  if (!newCategoryName.value) return;

  try {
    const response = await axios.post('/categories/add', { name: newCategoryName.value });
    categories.value.push(response.data); // `.value` を使用してリアクティブな配列にアクセス
    newCategoryName.value = ""; // 入力フィールドをリセット
  } catch (error) {
    console.error("カテゴリーの追加エラー:", error);
  }
};


// 初期化時にカテゴリーのリストを取得
fetchCategories();

// 他のリアクティブ変数とメソッド
const fileInput = ref(null);

// ファイル入力を開くメソッド
const openFileDialog = () => {
  fileInput.value.click();
};

const fileSelected = (event) => {
  fileInfo.value = event.target.files[0];
  // プレビュー画像のURLを作成
  previewImage.value = URL.createObjectURL(fileInfo.value);
};

const fileUpload = async () => {
  if (!fileInfo.value) return;

  const formData = new FormData();
  formData.append('file', fileInfo.value);
  formData.append('caption', caption.value);  // キャプションをformDataに追加
  formData.append('entry', entry.value);      // エントリーをformDataに追加
  // console.log(activeCategory.value); // フォームデータに追加する前にログを出力
  formData.append('category_id', activeCategory.value);   // 選択されたカテゴリーIDをformDataに追加


  try {
    const response = await axios.post('/fileupload', formData);
    if (response.data.file_path) {
      memory.value = response.data; // レスポンスデータ全体を保存
      // showUserImage.value = true; // 画像を表示するためのフラグをtrueに設定
      imagePath.value = '/storage/' + response.data.file_path; // imagePathを更新してアップロードされた画像のパスを保存

      previewImage.value = ""; // プレビュー画像のURLをクリア
      caption.value = "";
      entry.value = "";

      // ページをリロード
      window.location.reload();

      // プレビューがない場合はフォームをリセット
      if (!previewImage.value) {
        fileInfo.value = ""; // ファイル情報をクリア
        document.querySelector('input[type="file"]').value = ''; // inputファイルフィールドをリセット
      }
    }
  } catch (error) {
    console.error("File upload error:", error);
  }
};

</script>

<style>
.previewImage {
  height: 100%;
}

.previewImage img {
  height: 480px;
  object-fit: contain;
}

.category-add-icon {
  border-radius: 50%;
  padding: 2px;
  width: 20px;
  height: 20px;
}

.addCategory {
  margin-left: 1rem;
  padding: 2px;
}

.textarea {
  display: flex;
  flex-direction: column;
  margin-top: 2rem;
}

.upload-button {
  position: fixed;
  bottom: 0;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100px;
  height: 100px;
  border: 5px solid #000;
  border-radius: 50%;
  background: transparent;
  z-index: 1;
  margin-top: 1rem;
}

.entry {
  margin-top: 1rem;
}

.fileUpload {
  display: block;
  margin: 1rem auto 0;
  padding: 5px;
}
</style>

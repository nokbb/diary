<template>
  <div>
    <!-- タブUI -->
    <div id="tab">
      <ul class="tabMenu">
        <li v-for="category in allCategories" :key="category.id" class="tabMenu-list" @click="handleSelectCategory(category.id)" :class="{ 'active': computedActiveCategory === category.id }">
          {{ category.name }}
          <button @click.stop="handleRemoveCategory(category.id)">×</button>
        </li>
        <li v-for="category in userCategories" :key="category.id" class="tabMenu-list" @click="handleSelectCategory(category.id)" :class="{ 'active': computedActiveCategory === category.id }">
          {{ category.name }}
          <button @click.stop="handleRemoveCategory(category.id)">×</button>
        </li>
      </ul>
      <button @click="handleAddCategory" class="categoryAdd-btn">カテゴリー追加</button>
    </div>

    <div class="private">
      <span class="lock-open" v-if="categoryPrivacy[computedActiveCategory]"><i class="fa-solid fa-lock-open"></i></span>
      <span class="lock" v-else><i class="fa-solid fa-lock"></i></span>
      <label for="toggle" class="toggle-button">
        <input type="checkbox" id="toggle" v-model="categoryPrivacy[computedActiveCategory]" />
        <div class="slider"></div>
      </label>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex';

export default {
  computed: {
    ...mapState(['userCategories', 'categoryPrivacy', 'selectedMemories', 'categories']),
    ...mapState({ computedActiveCategory: 'activeCategory' }),
  },
  created() {
    this.fetchCategories(); // コンポーネント生成時にカテゴリを取得
    this.allCategories = [
      {
        id: 1,
        name: 'ALL',
      },
    ];
    this.fetchUserCategories(); // ユーザーに紐づくカテゴリを取得
    this.fetchMemories();
  },
  methods: {
    ...mapActions(['selectCategory', 'fetchCategories', 'addCategory', 'removeCategory', 'fetchMemories', 'fetchUserCategories']),
    // カテゴリ追加時の処理
    handleAddCategory() {
      const newCategoryName = prompt('新しいカテゴリー名を入力してください:');
      if (newCategoryName) {
        this.addCategory(newCategoryName).then(() => {
          this.fetchUserCategories(); // カテゴリー追加後に再取得
        });
      }
    },
    // カテゴリ削除時の処理
    handleRemoveCategory(categoryId) {
      this.removeCategory(categoryId).then(() => {
        this.fetchUserCategories(); // カテゴリー削除後に再取得
      });
    },
    handleSelectCategory(categoryId) {
      console.log('Selected category ID:', categoryId);
      this.selectCategory(categoryId);
    },
    // その他のメソッド
  }
}
</script>

<style>
/* 追加ボタンのスタイル */
button {
  margin-left: 10px;
  background-color: #6299e6;
  border: none;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

/* 削除ボタンのスタイル */
.tabMenu li button {
  margin-left: 5px;
  color: red;
  cursor: pointer;
  padding: 0 5px;
}

.tabMenu li.active {
  /* アクティブなタブのスタイル */
  color: #fff;
  background-color: #6299e6;
  border-radius: 5px;
}

.categoryAdd-btn {
  padding: 5px;
}
</style>
<template>
  <div>
    <FullCalendar :options="calendarOptions" @event-click="handleEventClick" />
    <transition name="slide-in">
      <div v-if="showModal"  class="memory-detail-modal">
        <header class="header">
          <ul class="header-lists">
            <li class="header-list">  
              <a @click="closeModal"><i class="fa-solid fa-chevron-left"></i></a>
            </li>
            <li class="header-list">
              {{ selectedMemories.length > 0 ? formatDate(selectedMemories[activeMemoryIndex].created_at) : '' }}
            </li>
            <li @click="openModal" class="header-list vue-memory-meatball-menu">
              <transition name="fade">
              <div v-show="showContent" @click.stop="closeModalMenu" class="vue-modal-container">
                  <div class="vue-modal-body">
                      <div @click="startEditing(activeMemoryIndex)" class="vue-edit-menu">
                        編集<i class="fa-solid fa-pen-to-square"></i>
                      </div>
                      <div @click="deleteMemory(activeMemoryIndex)" class="vue-edit-menu">
                        削除<i class="fa-solid fa-trash"></i>
                      </div>
                  </div>
                </div>
              </transition>
              <i class="fa-solid fa-ellipsis"></i>
            </li>
          </ul>
        </header>
        <div class="memory-images-container">
          <Splide :options="thumbnailSplideOptions" ref="thumbnailSplide"  id="thumbnail-carousel" aria-label="お気に入りの写真">
            <SplideSlide v-for="(memory, index) in selectedMemories" :key="'main-' + memory.id">
              <div class="memory-item">
                <div class="memory-item-img">
                  <img :src="'/storage/' + memory.file_path" alt="Memory Image">
                  <div class="like-profilePics">
                    <div v-for="pic in memory.likeStatus.profile_pics" :key="pic" class="like-profilePic">
                      <img v-if="pic" :src="`/storage/profile_pics/${pic}`" alt="Profile Picture">
                      <i v-else class="fa-solid fa-circle-user"></i> <!-- デフォルトアイコン -->
                    </div>
                  </div>
                </div>
                <!-- 編集モードと通常モードの切り替え -->
                  <div v-if="isEditing" class="editing-wrap">
                    <input type="text" v-model="editableCaption" class="caption">
                    <textarea v-model="editableEntry" class="entry"></textarea>
                    <button @click="saveEdits(index)">保存</button>
                    <button @click="cancelEdits">キャンセル</button>
                  </div>
                  <div v-else>
                    <h3 class="caption">{{ memory.caption }}</h3>
                    <p class="entry">{{ memory.entry }}</p>
                  </div>
              </div>
            </SplideSlide>
          </Splide>
          <Splide @click="cancelEdits" :options="mainSplideOptions" ref="mainSplide"  id="main-carousel" aria-label="お気に入りの写真">
            <SplideSlide v-for="(memory, index) in selectedMemories" :key="'main-' + memory.id">
              <img :src="'/storage/' + memory.file_path" alt="Memory Image">
            </SplideSlide>
          </Splide>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

import axios from "axios";
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import jaLocale from '@fullcalendar/core/locales/ja';
import { ref } from 'vue';

export default {
  components: {
    FullCalendar,
    Splide,
    SplideSlide
  },
  computed: {
    ...mapState({
      memories: state => state.memories,
      categories: state => state.categories,
      categoryPrivacy: state => state.categoryPrivacy,
      activeMemoryTime: state => state.activeMemoryTime,
      activeMemoryIndex: state => state.activeMemoryIndex,
      activeCategory: state => state.activeCategory,
    }),
    ...mapGetters({ selectedMemories: 'selectedMemories'}),


    selectedMemoryImageUrl() {
      if (this.selectedMemory && this.selectedMemory.file_path) {
        return "/storage/" + this.selectedMemory.file_path;
      }
      return null;
    },
    
    selectedMemories() {
      return this.$store.state.selectedMemories;
    },
    showModal() {
      return this.selectedMemories.length > 0;
    }

  },

  props: {
    mypageView: {
      type: Boolean,
      default: false // デフォルトはフルビュー
    },
    memories: {
      type: Array,
      default: () => [] // デフォルトは空の配列
    },
  },

  data() {
    return {
      editingIndex: null, // 編集しているアイテムのインデックスを保持する
      isEditing: false,
      editableCaption: '',
      editableEntry: '',
      showContent: false, // 初期値を設定
      showModal: false,
      mainSplideOptions: {
        fixedWidth: 120, // サムネイルの幅
        pagination: false, // ページネーション非表示
        isNavigation: true, // 他のスライダーのナビゲーションとしてそれぞれのスライドをクリック可能にする
        breakpoints: {
          400: { // 幅400px未満の設定
            fixedWidth: 80, // サムネイルの幅
          },
        },

      },
      thumbnailSplideOptions: {
        type: "fade", // フェード
        rewind: true, // スライダーの終わりまで行ったら先頭に巻き戻す
        pagination: false, // ページネーション非表示
        arrows: false, // 矢印非表示
      },


      calendarOptions: {
        eventClick: this.handleEventClick, // eventClickを定義
        locale: 'ja',
        height: 'auto',
        dayCellContent: function (arg) {
          return arg.date.getDate(); //日を消す
        },
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        // events: this.memoriesToEvents(),
        events: [],
        fixedWeekCount: false, // 週の数を6に固定する設定を解除
        eventContent: function (arg) {
          let imageUrl = arg.event.extendedProps.image_url;
          return {
            html: `<img src="${imageUrl}" alt="Event Image" width="100%">`
          };
        },
        headerToolbar: {
          right: this.mypageView ? '' : 'prev,next', // mypageの時はページネーションボタンを表示しない
          center: '',
          left: 'title'
        }
      },
    };
  },

  mounted() {
    axios.get('/api/memories').then(response => {
      this.updateMemories = response.data;
      this.calendarOptions.events = this.memoriesToEvents();
    }).catch(error => {
      console.error("Error fetching memories:", error);
    });
  },

  watch: {
    // selectedMemories を監視し、変更があったら呼ばれる
    selectedMemories(newValue, oldValue) {
      if (newValue.length > 0) {
        // データが非同期で到着した後、nextTick を使用して DOM 更新を待つ
        this.$nextTick(() => {
          // ここで Splide コンポーネントの存在を確認
          if (this.$refs.mainSplide && this.$refs.mainSplide.splide) {
            this.$refs.mainSplide.splide.on('move', (newIndex) => {
              this.updateActiveMemoryIndex(newIndex);
            });
          } else {
            console.error('Splide reference is undefined.');
          }
        });
      }
    },
    activeMemoryIndex(newIndex) {
      // activeMemoryIndex が変更されたときに実行される
      this.syncSliders(newIndex);
    },
    activeCategory(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.calendarOptions.events = this.memoriesToEvents();
      }
    }
  },

  created() {
    this.fetchCategories();
  },

  methods: {
    updateSelectedMemories(newMemories) {
      this.$store.commit('SET_SELECTED_MEMORIES', newMemories);
    },

    updateMemories(newMemories) {
      this.$store.commit('SET_MEMORIES', newMemories);
    },

    updateCategories(newCategories) {
      this.$store.commit('SET_CATEGORIES', newCategories);
    },
    updateActiveCategory(newCategoryId) {
      this.$store.commit('SET_ACTIVE_CATEGORY', newCategoryId);
    },

    openModal() {
      this.showContent = true;
    },
    closeModalMenu() {
      this.showContent = false;
    },

    syncSliders(index) {
      // 新しいインデックスに基づいてメインスライダーとサムネイルスライダーを同期
      if (this.$refs.mainSplide && this.$refs.thumbnailSplide) {
        const mainSplide = this.$refs.mainSplide.splide;
        const thumbnailSplide = this.$refs.thumbnailSplide.splide;

        // メインスライダーを指定されたインデックスに移動
        mainSplide.go(index);
        // サムネイルスライダーも指定されたインデックスに移動
        thumbnailSplide.go(index);
      }
    },

    // 日付のフォーマットを整えるメソッド
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString(); // または好みの日付フォーマットを適用
    },
    // Splideのmoveイベントに対応するメソッド
    updateActiveMemoryIndex(newIndex) {
      this.$store.commit('SET_ACTIVE_MEMORY_INDEX', newIndex);
    },

    selectCategory(categoryId) {
      this.activeCategory = categoryId;
      this.calendarOptions.events = this.memoriesToEvents(); // カレンダーイベントを更新
    },

    memoriesToEvents() {
      if (!this.memories || this.memories.length === 0) return [];
      // フィルタリングされたメモリーを日付ごとにグループ化
      const groupedByDate = {};
        this.memories.forEach(memory => {
        if (memory.category_id === this.activeCategory || this.activeCategory === 1) {
          const date = new Date(memory.created_at).toISOString().split('T')[0];
          if (!groupedByDate[date]) {
            groupedByDate[date] = [];
          }
          groupedByDate[date].push(memory);
        }
      });

      // 各日付の最初のメモリーをイベントとして追加
      const events = [];
      for (const date in groupedByDate) {
        const firstMemory = groupedByDate[date][0];
        events.push({
          title: firstMemory.caption || 'No Caption',
          start: firstMemory.created_at,
          image_url: '/storage/' + firstMemory.file_path,
          category_id: firstMemory.category_id // ここでcategory_idをセット
        });
      }
      return events;
    },



    handleEventClick(info) {
      const clickedEventDate = info.event.startStr.split('T')[0];
      const memoriesForDateAndCategory = this.memories.filter(memory => {
        const memoryDateLocal = new Date(memory.created_at).toLocaleDateString();
        const eventDateLocal = new Date(clickedEventDate).toLocaleDateString();
        const isSameDate = memoryDateLocal === eventDateLocal;
        const isSameCategory = memory.category_id === this.activeCategory;
        return isSameDate && isSameCategory;
      });

      if (memoriesForDateAndCategory.length) {
        this.$store.commit('SET_SELECTED_MEMORIES', memoriesForDateAndCategory);
      } else {
        this.$store.commit('SET_SELECTED_MEMORIES', []);
      }
        this.showModal = true;
    },

    closeModal() {
      this.showModal = false;

      this.$store.commit('SET_ACTIVE_MEMORY_INDEX', 0);

      // 編集状態をリセット
      this.isEditing = false;
      this.editableCaption = '';
      this.editableEntry = '';

      // スライダーが存在すればリセットする
      if (this.$refs.mainSplide && this.$refs.mainSplide.splide) {
        this.$refs.mainSplide.splide.go(0); // メインスライダーを最初のメモリーにリセット
      }
      if (this.$refs.thumbnailSplide && this.$refs.thumbnailSplide.splide) {
        this.$refs.thumbnailSplide.splide.go(0); // サムネイルスライダーを最初のメモリーにリセット
      }
    },

    //編集をクリック
    startEditing(index) {
      // インデックスからメモリーIDを取得
      const memoryId = this.selectedMemories[index].id;
      this.editingIndex = index; // 編集中のアイテムのインデックスを保存
      this.editableCaption = this.selectedMemories[index].caption;
      this.editableEntry = this.selectedMemories[index].entry;
      this.isEditing = true;
    },

    saveEdits(index) {
      const memoryId = this.selectedMemories[this.editingIndex].id;
      const updatedData = {
        caption: this.editableCaption,
        entry: this.editableEntry
      };

      // ここでAPIを呼び出して変更を保存
      axios.patch(`/api/update/${memoryId}`, updatedData)
        .then(response => {
          // レスポンスが成功したらフロントエンドの状態を更新
          this.selectedMemories[index].caption = response.data.memory.caption;
          this.selectedMemories[index].entry = response.data.memory.entry;

          // 編集モードを終了
          this.isEditing = false;
          this.editingIndex = null; // 編集状態をリセット
        })
        .catch(error => {
          // エラーがあればここで処理
          console.error('メモリーの更新に失敗しました。', error.response.data);
        });
    },
    cancelEdits() {
      this.isEditing = false;
    },

    //削除をクリック
    deleteMemory(index) {
      // インデックスからメモリーIDを取得
      const memoryId = this.selectedMemories[index].id;
      if (!memoryId) {
        console.error('Memory ID is undefined.');
        return;
      }
      // ユーザーに確認を求める
      if (!confirm('このメモリーを削除してもよろしいですか？')) {
        return;
      }

      // DELETEリクエストを送信
      axios.delete(`/api/delete/${memoryId}`)
        .then(() => {
          // レスポンスが成功したらメモリー配列から削除
          this.memories.splice(index, 1);

          // 全体のmemories配列からも削除する
          const globalIndex = this.memories.findIndex(memory => memory.id === memoryId);
          if (globalIndex !== -1) {
            this.memories.splice(globalIndex, 1);
          }

          // 選択されたメモリーをクリアする
          if (this.activeMemoryIndex === index) {
            this.closeModal();
          }
          // それ以降のインデックスを持つメモリーが存在する場合、activeMemoryIndexを更新
          else if (index < this.activeMemoryIndex) {
            this.activeMemoryIndex -= 1;
          }
          // カレンダーのイベントを更新
          this.calendarOptions.events = this.memoriesToEvents();
        })
        .catch(error => {
          // エラーがあればここで処理
          console.error('メモリーの削除に失敗しました。', error.response.data);
          // ここにエラーメッセージを表示するロジックを追加するかもしれません
        });
    },

    fetchCategories() {
      // ユーザーに紐づくカテゴリを取得
      axios.get('/api/categories')
        .then(response => {
          this.updateCategories = response.data;
          if (this.updateCategories.length > 0) {
            this.activeCategory = this.updateCategories[0].id;
          }
        })
        .catch(error => {
          console.error('カテゴリの取得に失敗しました。', error.response.data);
        });
    },

    addCategory() {
      // 新しいカテゴリーの追加
      const newCategoryName = prompt('新しいカテゴリー名を入力してください:');
      if (newCategoryName) {
        // POSTリクエストでバックエンドに新しいカテゴリを送信
        axios.post('/api/categories', { name: newCategoryName })
          .then(response => {
            // レスポンスからカテゴリを取得して、フロントエンドの状態を更新
            const addedCategory = response.data;
            this.categories.push(addedCategory);
            // 新しく追加されたカテゴリをアクティブに設定
            this.activeCategory = addedCategory.id;
            // 新しいカテゴリのプライバシー設定を初期化
            this.$set(this.categoryPrivacy, addedCategory.id, true);
          })
          .catch(error => {
            console.error('カテゴリの追加に失敗しました。', error.response.data);
          });
      }
    },
    
    removeCategory(categoryId) {
      // カテゴリーの削除
      if (confirm('このカテゴリーを削除してもよろしいですか？')) {
        // DELETEリクエストでバックエンドにカテゴリの削除を要求
        axios.delete(`/api/categories/${categoryId}`)
          .then(() => {
            // 削除に成功したらフロントエンドのカテゴリリストからも削除
            this.categories = this.categories.filter(category => category.id !== categoryId);
            // 削除されたカテゴリがアクティブだった場合、別のカテゴリをアクティブにする
            if (this.activeCategory === categoryId) {
              this.activeCategory = this.categories[0]?.id || null;
              // カレンダーイベントを更新する
              this.calendarOptions.events = this.memoriesToEvents();
            }
            // 削除されたカテゴリのプライバシー設定を削除
            this.$delete(this.categoryPrivacy, categoryId);
          })
          .catch(error => {
            console.error('カテゴリの削除に失敗しました。', error.response.data);
          });
      }
    },

  }
}
</script>

<style scoped>
.splide__slide {
  opacity: .6;
}
/* 選択されているサムネイルだけ透過しない */
.splide__slide.is-active {
  opacity: 1;
}
/* 画像サイズ調整 */
.splide__slide img {
  height: auto;
  width: 100%;
  object-fit: cover;
}

.splide__list {
  height: auto;
}


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

.vue-memory-meatball-menu i {
  font-size: 1.5rem;
}

.vue-modal-container {
  position: absolute;
  z-index: 1;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  max-width: 900px;
  width: 100%;
  height: 100%;
  transition: all 0.3s;
}

.vue-modal-body {
  position: relative;
  right: 1rem;
  top: 8%;
  margin-left: auto;
  border: 1px solid #000;
  width: 26%;
  text-align: center;
  border-radius: 8px;
  background: #eaeaea;
}

.vue-edit-menu i {
  padding-left: 5px;
}


.memory-detail-modal {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  background-color: #fff;
  z-index: 10;
  width: 100%;
  height: 100%;
  max-width: 900px;
  overflow-y: auto;
}

.memory-images-container {
  margin: 20px;
}

.memory-item {
  text-align: center;
}

.memory-item-img {
  position: relative;
  height: auto;
  border: 2px solid #000;
  border-radius: 8px;
  box-shadow: 0 0 10px rgb(0 0 0 / 20%);
}

.memory-item-img img {
  border-radius: 8px;
}

.like-profilePic {
  border: 1px solid #000;
  border-radius: 50%;
}

.like-profilePic img {
  border-radius: 50%;
}

.editing-wrap {
  margin: 1rem auto;
}
.caption {
  margin: 1rem auto;
  display: block;
  text-align: center;
  width: 100%;
}

.entry {
  margin: 1rem auto;
  display: block;
  text-align: center;
  width: 100%;
}

.slide-in-enter-active, .slide-in-leave-active {
  transition: transform 225ms cubic-bezier(0, 0, 0.2, 1) 0ms;
}
.slide-in-enter, .slide-in-leave-to {
  transform: translateY(100%);

}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

</style>

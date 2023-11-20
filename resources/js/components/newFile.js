import axios from "axios";
import dayGridPlugin from '@fullcalendar/daygrid';

export default (await import('vue')).defineComponent({
components: {
FullCalendar,
Splide,
SplideSlide,
},
computed: {
selectedMemoryImageUrl() {
if (this.selectedMemory && this.selectedMemory.file_path) {
return "/storage/" + this.selectedMemory.file_path;
}
return null;
}
},

props: {
mypageView: {
type: Boolean,
default: false // デフォルトはフルビュー
}
},

data() {
return {
mainSplideOptions: {
type: 'fade',
rewind: true,
pagination: false,
arrows: false,
},
thumbnailSplideOptions: {
fixedWidth: 100,
fixedHeight: 64,
isNavigation: true,
focus: 'center',
gap: 10,
rewind: true,
pagination: false,
cover: true,
drag: 'free',
breakpoints: {
600: {
perPage: 3,
fixedWidth: 60,
fixedHeight: 44,
},
},
// Other options...
},

activeMemoryIndex: 0,
activeMemoryTime: '',
splideOptions: {
perPage: 3,
rewind: true,
gap: '1rem',
},
memories: [],
selectedMemories: [],
activeCategory: 1,
categoryPrivacy: {
1: true, // 初期値は公開
},
categories: [
{ id: 1, name: 'ALL' },
],

calendarOptions: {
eventClick: this.handleEventClick,
locale: 'ja',
height: 'auto',
dayCellContent: function (arg) {
return arg.date.getDate(); //日を消す
},
plugins: [dayGridPlugin],
initialView: 'dayGridMonth',
events: [],
fixedWeekCount: false,
eventContent: function (arg) {
let imageUrl = arg.event.extendedProps.image_url;
return {
html: `<img src="${imageUrl}" alt="Event Image" width="100%">`
};
},
headerToolbar: {
right: this.mypageView ? '' : 'prev,next',
center: '',
left: 'title'
}
},
};
main.sync(thumbnails);
main.mount();
thumbnails.mount();

},

mounted() {
axios.get('/api/memories').then(response => {
this.memories = response.data;
this.calendarOptions.events = this.memoriesToEvents();
}).catch(error => {
console.error("Error fetching memories:", error);
});
// // Splideインスタンスを作成
// const main = new Splide(this.$refs.mainSplide, this.mainSplideOptions);
// const thumbnails = new Splide(this.$refs.thumbnailSplide, this.thumbnailSplideOptions);
// // Splideインスタンスを同期
// main.sync(thumbnails);
// // Splideインスタンスをマウント
// main.mount();
// thumbnails.mount();
},

created() {
this.fetchCategories();
},

methods: {
selectCategory(categoryId) {
this.activeCategory = categoryId;
this.calendarOptions.events = this.memoriesToEvents(); // カレンダーイベントを更新
},

memoriesToEvents() {
if (!this.memories || this.memories.length === 0) return [];

// アクティブなカテゴリIDに対応するメモリーをフィルタリング
const filteredMemories = this.memories.filter(memory => memory.category_id === this.activeCategory);

// フィルタリングされたメモリーを日付ごとにグループ化
const groupedByDate = {};
filteredMemories.forEach(memory => {
const date = new Date(memory.created_at).toISOString().split('T')[0];
if (!groupedByDate[date]) {
groupedByDate[date] = [];
}
groupedByDate[date].push(memory);
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
const clickedEventDate = info.event.startStr.split('T')[0]; // 時間情報を除去



// console.log("Before setting selectedMemory:", this.selectedMemory);
// 選択された日付とアクティブなカテゴリーに属するメモリーをフィルタリングします
const memoriesForDateAndCategory = this.memories.filter(memory => {
const isSameDate = new Date(memory.created_at).toISOString().split('T')[0] === clickedEventDate;
const isSameCategory = memory.category_id === this.activeCategory;
return isSameDate && isSameCategory;
});

if (memoriesForDateAndCategory.length) {
this.selectedMemories = memoriesForDateAndCategory;
} else {
this.selectedMemories = []; // 該当するメモリーがない場合は空にする
}
},

closeModal() {
this.selectedMemories = [];
},

deleteMemory(index) {
// メモリーを削除するロジック
},


fetchCategories() {
// ユーザーに紐づくカテゴリを取得
axios.get('/api/categories')
.then(response => {
this.categories = response.data;
if (this.categories.length > 0) {
this.activeCategory = this.categories[0].id;
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
});

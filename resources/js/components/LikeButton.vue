<template>
  <button @click="toggleLike" class="like-btn">
    <!-- アイコンやスタイルは調整 -->
    <i class="fa" :class="{ 'fa-heart': isLiked, 'fa-heart-o': !isLiked }"></i>
  </button>
  <div class="like-profilePics">
    <div v-for="pic in profilePics" :key="pic" class="like-profilePic">
      <img v-if="pic" :src="`/storage/profile_pics/${pic}`" alt="Profile Picture">
      <i v-else class="fa-solid fa-circle-user"></i> <!-- デフォルトアイコン -->
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ['memory'],
  data() {
    return {
      isLiked: false,
      profilePics: [],
    }
  },
  mounted() {
    this.checkLikeStatus();
  },
  methods: {
    checkLikeStatus() {
      const url = `/memories/${this.memory.id}/like-status`;
      axios.get(url).then(response => {
        this.isLiked = response.data.liked;
        if (this.isLiked) {
          this.profilePics = response.data.profile_pics;
        }
      });
    },

    toggleLike() {
      const url = `/memories/${this.memory.id}/like`;
      if (this.isLiked) {
        // いいねを削除する
        axios.delete(url).then(response => {
          this.isLiked = false;
          // 現在のユーザーのプロフィール画像を削除
          if (response.data.currentUserPic) {
            this.removeProfilePic(response.data.currentUserPic);
          }
        });
      } else {
        // いいねを追加する
        axios.post(url).then(response => {
          this.isLiked = true;
          // プロフィール画像の配列に現在のユーザーの画像を追加
          this.profilePics = response.data.profile_pics;
        }).catch(error => {
          console.error('Error:', error);
        });
      }
    },
    removeProfilePic(currentUserPic) {
      // 現在のユーザーのプロフィール画像を配列から削除する
      this.profilePics = this.profilePics.filter(pic => pic !== currentUserPic);
    },
  }
}
</script>

<style>
.like-btn {
  position: absolute;
  bottom: 5px;
  right: 10px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  z-index: 2;
  cursor: pointer;
}

.like-profilePics {
  position: absolute;
  bottom: 5px;
  left: 10px;
  display: flex;
}

.like-profilePic {
  width: 40px;
  height: 40px;
  font-size: 40px;
}

.fa-circle-user {
  color: #666;
}
</style>
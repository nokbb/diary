<template>
  <!-- エラーメッセージの表示 -->
  <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

  <button @click="addFriend" :disabled="isProcessing" :class="{ 'following': isFollowing }">
    {{ isFollowing ? 'フォロー中' : 'フォロー' }}
  </button>
</template>

<script>
export default {
  props: ['friendId'],
  data() {
    return {
      isProcessing: false,
      isFollowing: false,
      errorMessage: null,
    };
  },
  mounted() {
    this.checkFollowingStatus();
  },
  methods: {
    async addFriend() {
      if (this.isFollowing) {
        // フォロー中の場合は、フォロー解除の確認を行う
        if (confirm('フォローを解除しますか？')) {
          await this.deleteFriend();
        }
      } else {
        // フォローしていない場合は、通常のフォロー処理を行う
        this.isProcessing = true;
        this.errorMessage = null;
        try {
          await axios.post('/api/friend/add', { friend_id: this.friendId });
          this.isFollowing = true;
        } catch (error) {
          // エラー処理...
        } finally {
          this.isProcessing = false;
        }
      }
    },

    async deleteFriend() {
      this.isProcessing = true;
      try {
        await axios.post('/api/friend/delete', { friend_id: this.friendId });
        this.isFollowing = false;
        // 解除成功時の処理...
      } catch (error) {
        // エラー処理...
      } finally {
        this.isProcessing = false;
      }
    },
    
    async checkFollowingStatus() {
      try {
        // サーバーにフォロー状態を確認するリクエストを送る
        const response = await axios.get('/api/friend/check-status', { params: { friend_id: this.friendId } });
        this.isFollowing = response.data.isFollowing;
      } catch (error) {
        // エラー処理...
      }
    },

  },
};
</script>

<style scoped>
.following {
  background-color: #6299e6;
}
</style>
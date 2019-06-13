<template>
  <div class="login flex-center">
    <div class="wrap">
      <el-input placeholder="用户名" v-model="params.username"></el-input>
      <el-input placeholder="密码" type="password" v-model="params.password"></el-input>
      <el-button type="primary" @click="login">登录</el-button>
    </div>
  </div>
</template>
<script>
import api from '@/api'
import { saveUserInfo } from '@/utils'
export default {
  name: 'Login',
  data() {
    return {
      params: {
        username: '',
        password: '',
        platform: 1,
      }
    }
  },
  methods: {
    login() {
      api.post('login', this.params).then((userInfo) => {
        saveUserInfo(userInfo.data.id, userInfo.data.username, userInfo.data.token, userInfo.data.power);
        this.$router.push({ name: 'Index' })
      })
    }
  },
  mounted(){
    document.onkeyup  = (event)=> {
      if(event.keyCode === 13) {
        this.login();
      }
    }
  },
  destroyed(){
    document.onkeyup = null;
  }
}

</script>
<style lang="scss" scoped>
.login {
  height: 100%;
  background: url(./bg.jpg);
  .wrap {
    max-width: 300px;
    &>div {
      margin-bottom: 2.5rem;
    }
    &>button {
      width: 100%;
    }
  }
}

</style>

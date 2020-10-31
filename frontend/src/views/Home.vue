<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png" />
    <HelloWorld msg="Welcome to Your Vue.js App" />
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from "@/components/HelloWorld.vue";
import UserService from "@/services/UserService.js";
export default {
  name: "Home",
  components: {
    HelloWorld
  },
  created() {
    if (!UserService.isLoggedIn()) {
      console.log("Not logged in");
      this.$router.replace({name: "Login"});
    }

  }
};
</script>

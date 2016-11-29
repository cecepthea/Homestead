<template>
  <div id="app">
    <img src="./assets/logo.png">
    <h1>{{ msg }}</h1>
    <el-button @click.native="startHacking">Let's do it</el-button>

        <el-row>
          <el-col :span="24"><div class="grid-content bg-purple-dark"></div></el-col>
        </el-row>
        <el-row>
          <el-col :span="12"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="12"><div class="grid-content bg-purple-light"></div></el-col>
        </el-row>
        <el-row>
          <el-col :span="8"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple-light"></div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple"></div></el-col>
        </el-row>
        <el-row>
          <el-col :span="6"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="6"><div class="grid-content bg-purple-light"></div></el-col>
          <el-col :span="6"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="6"><div class="grid-content bg-purple-light"></div></el-col>
        </el-row>
        <el-row>
          <el-col :span="4"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="4"><div class="grid-content bg-purple-light"></div></el-col>
          <el-col :span="4"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="4"><div class="grid-content bg-purple-light"></div></el-col>
          <el-col :span="4"><div class="grid-content bg-purple"></div></el-col>
          <el-col :span="4"><div class="grid-content bg-purple-light"></div></el-col>
        </el-row>

  </div>
</template>

<script>
export default {
  data () {
    return {
      msg: 'Use Vue 2.0 Today!'
    }
  },

  methods: {
    startHacking () {
      this.$notify({
        title: 'It Works',
        message: 'We have laid the groundwork for you. Now it\'s your time to build something epic!',
        duration: 6000
      })
    }
  }
}
</script>

<style>
body {
  font-family: Helvetica, sans-serif;
}

.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.el-col {
  border-radius: 4px;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>

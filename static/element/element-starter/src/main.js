import Vue from 'vue'
import ElementUI from 'element-ui'
import '../theme/index.css'
// node_modules/.bin/et -i [可以自定义变量文件目录]
// node_modules/.bin/et 编译主题
import App from './App.vue'

Vue.use(ElementUI)

new Vue({
  el: '#app',
  render: h => h(App)
})

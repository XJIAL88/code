// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'babel-polyfill'; // 兼容IE
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import filters from './filters'
import components from './components'

Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
});
Object.keys(components).forEach(key => {
    Vue.component(key, components[key])
});

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';


Vue.use(ElementUI);

Vue.config.productionTip = false;

Vue.prototype.$bus = new Vue(); // 实现非父子组件间的简单事件传递
/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    components: {
        App
    },
    template: '<App/>'
})

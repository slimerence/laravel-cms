require('./bootstrap');
import './bulma/carousel';
import './bulma/accordion';
import './bulma/tagsinput';

window.Vue = require('vue');
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en';
// import { Loading } from 'element-ui';
Vue.use(ElementUI, { locale });

// 导入子定义的 vue js editor组件
Vue.component('VuejsEditor', require('./components/vuejs-editor/VuejsEditor.vue'));

// Slick 幻灯动画库
require('!style-loader!css-loader!animate.css/animate.min.css');    // 导入animate动画库
require('!style-loader!css-loader!slick-carousel/slick/slick.css');
require('!style-loader!css-loader!slick-carousel/slick/slick-theme.css');
import 'slick-carousel';

import SlideOutTrigger from './components/slideout-trigger/slideout-trigger';
SlideOutTrigger();

$(document).ready(function(){
    if(jQuery('.btn-delete').length > 0){
        jQuery('.btn-delete').on('click',function(e){
            e.preventDefault();
            if(confirm('Are you sure to remove this record?')){
                window.location.href = $(this).attr('href');
            }
        });
    }
});

/**
 * 让 Laravel + Vuejs 可以支持 jsx 语法的实现方法
 * 到 https://github.com/vuejs/babel-plugin-transform-vue-jsx 去安装插件
 * 然后项目的根目录中创建 .babelrc 文件
 * 在文件中输入下面的内容即可
 {
   "presets": ["es2015"],
   "plugins": ["transform-vue-jsx"]
 }
 *
 */

/**
 * 由于在页面嵌入jsx无法解析,因此在这里提供一个全局方法,用来在后台可以对category tree的node的输出进行定制化
 * @param h
 * @param node
 * @param data
 * @param store
 * @returns {boolean}
 */
window.categoryNoteRender = function(h, { node, data, store }){
    if(data.as_link && data.include_in_menu){
        return (
            <div>{data.name}&nbsp;<span class="tag is-link">L</span>&nbsp;<span class="tag is-primary">M</span></div>
    )
    }else if(data.as_link){
        return (
            <div>{data.name}&nbsp;<span class="tag is-link">L</span></div>
    )
    }else if(data.include_in_menu){
        return (
            <div>{data.name}&nbsp;<span class="tag is-primary">M</span></div>
    )
    }else{
        return (
            <div>{data.name}</div>
    )
    }
};

/**
 * 获取一个指定长度为length的字符串的全局方法
 * @param length
 * @returns {string}
 * @private
 */
window._str_random = function(length=6) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

/**
 * 把传入的字符串中的空格换成 -
 * @param str
 */
window._replace_space_with_dash = function(str) {
    return str.replace(/(^\s+|[^a-zA-Z0-9 ]+|\s+$)/g,"").replace(/\s+/g, "-");
}

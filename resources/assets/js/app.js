require('./bootstrap');

// 导入 bulma 所需的Plugin
import './bulma/carousel';
import './bulma/accordion';

// 导入 Slideout 库
import Slideout from 'slideout';

// 导入 fastclick 库
import fastclick from 'fastclick';

// 导入FancyBox库
require('!style-loader!css-loader!@fancyapps/fancybox/dist/jquery.fancybox.css')
require('@fancyapps/fancybox');

// 导入 videojs 库
require('!style-loader!css-loader!video.js/dist/video-js.css');
import videojs from 'video.js';

// 导入 Fotorama
// import './fotorama/fotorama';

// 导入 PhotoSwipe
require('!style-loader!css-loader!photoswipe/dist/photoswipe.css');
require('!style-loader!css-loader!photoswipe/dist/default-skin/default-skin.css');
import 'photoswipe';

// 导入 Slick Carousel
require('!style-loader!css-loader!slick-carousel/slick/slick.css');
require('!style-loader!css-loader!slick-carousel/slick/slick-theme.css');
import 'slick-carousel';

window.Vue = require('vue');
// 加载Element UI 库
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
// import { Loading } from 'element-ui';
Vue.use(ElementUI);

// 导入子定义的 vue js editor组件
Vue.component('CatalogViewer', require('./components/catalog-viewer/catalogviewer.vue'));
Vue.component('VuejsSlider', require('./components/vuejs-slider/VuejsSlider.vue'));

fastclick.attach(document.body);

/**
 * 全局可用的通知函数
 * @param vm
 * @param type
 * @param title
 * @param msg
 * @private
 */
window._notify = function(vm, type, title, msg){
    // 显示弹出消息的方法
    vm.$notify({title:title,message:msg,type:type,position:'bottom-right'});
    return;
}

// 导航菜单的应用
let naviAppEl = document.getElementById('navigation-app');
if(naviAppEl){
    let NavigationApp = new Vue({
        el: '#navigation-app',
        data(){
            return {
                searchKeyword: '',
                result:[]
            }
        },
        methods:{
            handleSelect(item){
                window.location.href = item.uri;
            },
            querySearchAsync(queryString, cb){
                if(queryString.length < 2){
                    return;
                }
                axios.post(
                    '/api/page/search_ajax',
                    {q:queryString}
                ).then(res=>{
                    if(res.status==200 && res.data.error_no == 100){
                        // 表示找到了结果
                        cb(res.data.data.result)
                    }
                });
            }
        }
    });
}

if(document.getElementById('menu')){
    var slideout = new Slideout({
        'panel': document.getElementById('panel'),
        'menu': document.getElementById('menu'),
        'padding': 256,
        'tolerance': 70
    });

    document.querySelector('.toggle-button').addEventListener('click', function() {
        slideout.toggle();
    });
}

// 模仿京东的目录浏览控件
if(document.getElementById('catalog-viewer-app')){
    let catalogViewerApp = new Vue({
        el: '#catalog-viewer-app',
        data(){
            return {
                
            }
        }
    });
}

// Homepage 上播放的视频
if(document.getElementById('homepage-video')){
    let containerWidth = $('.container').width();
    let maskHeight = containerWidth * 0.5625;
    $('#homepage-video-mask').css('height', maskHeight + 'px').css('margin-top', -maskHeight + 'px');

    let videoPlayerObject = videojs('homepage-video',{
        controls: true,
        autoplay: true,
        preload: 'auto',
        loop: true,
        width: containerWidth + 'px'
    });

    videoPlayerObject.ready(function(){
        this.removeClass('is-invisible');
    });
}

$(document).ready(function(){
    // 联系我们功能
    if($('#submit-contact-us-btn').length > 0){
        let theSubmitButton = $('#submit-contact-us-btn');
        theSubmitButton.on('click',function(e){
            e.preventDefault();
            let inputs = $('input');
            let names = [];
            let values = [];
            $.each(inputs, function(idx, input){
                let theInput = $(input);
                if(theInput.attr('name')){
                    names.push(theInput.attr('name'));
                    values.push(theInput.val());
                }
            });

            theSubmitButton.addClass('is-loading');
            axios.post('/contact-us',{
                lead:{
                    name: $('#input-name').val(),
                    email: $('#input-email').val(),
                    phone: $('#input-phone').val(),
                    message: $('#input-message').val()
                }
            }).then(function(res){
                if(res.data.error_no == 100){
                    // 成功
                    $('#txt-on-success').css('display','block');
                    theSubmitButton.css('display','none');
                }else{
                    $('#txt-on-fail').css('display','block');
                }
                theSubmitButton.removeClass('is-loading');
                // 检查是否需要把最新的留言放到Testimonials中
                if($('.testimonials-list').length > 0){
                    let testimonials = $('.testimonials-list');
                    let h = '<p><span class="has-text-link">' +$('#input-name').val()+ ':</span> ' + $('#input-message').val() + '</p>';
                    testimonials.prepend($(h));
                }
            })
        });
    }

    // 检查是否有Slick Carousel
    if($('.slick-carousel-el').length > 0){
        $('.slick-carousel-el').slick();
    }

    // tabs 功能
    if($('.tab-trigger-btn').length > 0){
        $('.tab-trigger-btn').on('click',function(e){
            e.preventDefault();
            $('#tab-contents .tab-pane').addClass('hidden');
            $('.tab-trigger-btn').removeClass('is-active');
            $(this).addClass('is-active');
            let targetTabContent = $(this).children('a').eq(0).attr('href');
            $(targetTabContent).removeClass('hidden');
        });
    }

    // Customer Register
    if($('#general-customer-register-btn').length>0){
        $('#general-customer-register-btn').on('click',function(event){
            event.preventDefault();
            // check if email has existed
            let email = $('#inputEmail').val();
            $('#checkingEmailIcon').removeClass('is-invisible');
            axios.post(
                '/frontend/customer/is_email_exist',
                {email:email}
            ).then(res=>{
                if(res.data.error_no == 100){
                    if(res.data.msg == 'ok'){
                        // 可以注册
                        $('#general-customer-register-form').submit();
                    }else{
                        $('#checkingEmailIcon').addClass('is-invisible');
                        $('#inputEmail').addClass('is-invalid');
                        $('#inputEmailErrorMessage').html('This email has been registered. If this email is yours but you can\'t remember the password, please <a href="/password/reset" style="color:blue;">click here</a> to reset your password.');
                    }
                }
            });
        })
    }

    // 一个特效控件
    if($('.show-mask-on-hover').length > 0){
        $('.show-mask-on-hover').on('mouseover',function(e){
            $(this).children('.mask').eq(0).css('top','0');
        });
        $('.show-mask-on-hover').on('mouseout',function(e){
            $(this).children('.mask').eq(0).css('top','-201px');
        });
    }
});

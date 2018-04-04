<template>
 <div class="vue-js-slider-wrap">
  <div class="vue-js-slider">
   <div class="vuejs-slider-item" :style="{width:width+'px', height:height+'px'}" :class="{'is-pulled-left':thumbnailsAtRightSide}">
    <img :src="currentImageUrl" alt="image" :style="{height:height+'px'}" @click="moveToNext">
   </div>
   <div :class="{'thumbnails-on-right':thumbnailsAtRightSide, 'thumbnails-wrapper':!thumbnailsAtRightSide}" :style="{width:thumbnailsWrapperWidth}">
    <div class="move-nav move-nav-left" @click="moveNav(1)"><i class="fas" :class="{'fa-angle-left':!thumbnailsAtRightSide, 'fa-angle-up':thumbnailsAtRightSide}"></i></div>
    <div class="navs" :style="{height: thumbnailsWrapperHeight, width: thumbnailsNavWidth}">
     <div class="all-nav-items" :style="{top: currentTop+'px', left: currentLeft + 'px'}">
      <img :src="thumb" alt="" class="thumbnail-nav" v-for="(thumb, idx) in images" @click="thumbNavClicked(idx)" :class="{blur:idx==currentIndex}">
     </div>
    </div>
    <div class="move-nav move-nav-right" @click="moveNav(2)"><i class="fas" :class="{'fa-angle-right':!thumbnailsAtRightSide, 'fa-angle-down':thumbnailsAtRightSide}"></i></div>
   </div>
  </div>
 </div>
</template>

<script type="text/ecmascript-6">
 export default {
  name:'VuejsSlider',
  props:{
   // 大尺寸图片
   images: {
    type: Array,
    required: true
   },
   // 小图片, 可选
   thumbnails: {
    type: Array,
    required: false
   },
   // hover的Action
   hoverAction: Function,
   // click的Action
   clickAction: Function,
   slickOption: {
    type: Object,
    require: false,
    default: function(){
     return {
      autoplay: true, // 自动播放
      autoplaySpeed: 5000,
      centerMode: true,
      adaptiveHeight: false,
      infinite: true
     }
    }
   },
   // 导航的位置
   navPosition:{
    type: String,
    default: 'bottom'
   },
   // 是否需要Dots
   needDotsNav:{
    type: Boolean,
    default: false
   },
   width: Number,
   height: Number,
   wraperId: String,
   autoplaySpeed: {
    type: Number,
    default: 5000
   }
  },
  data: function(){
   return {
    currentIndex: 0,
    currentImageUrl: '',
    thumbnailsAtRightSide: false,
    // 导航 thumbnails 的容器的宽度
    thumbnailsWrapperWidth: '',
    thumbnailsWrapperHeight: '60px',
    // 和 .navs 相关
    thumbnailsNavWidth: '60px',
    // 滚动interval
    slidingInterval: null,
    // 滚动的当前坐标
    currentTop: 0,
    currentLeft: 0
   }
  },
  watch: {
  },
  created() {

   this.currentImageUrl = this.images[this.currentIndex];
   this.thumbnailsAtRightSide = this.navPosition == 'right';

   // 判定导航 thumbnails 的容器的宽度
   if(this.thumbnailsAtRightSide){
    this.thumbnailsWrapperWidth = '60px';
    this.thumbnailsWrapperHeight = this.height - 48 + 'px';
   }else{
    this.thumbnailsWrapperWidth = this.width + 'px';
    this.thumbnailsNavWidth = this.width - 40 + 'px';
   }
  },
  mounted() {
    this.initSliding(0);
  },
  methods:{
   moveNav: function(direction){
    let step = 60;
    if(direction == 1){
      // 表示向左或向上
      if(this.thumbnailsAtRightSide){
//       console.log('up');
       this.currentTop = this.currentTop - step;
      }else{
//       console.log('left');
       this.currentLeft = this.currentLeft - step;
      }
    }else{
      if(this.thumbnailsAtRightSide){
//       console.log('down');
       this.currentTop = this.currentTop + step;
      }else{
//       console.log('right')
       this.currentLeft = this.currentLeft + step;
      }
    }
   },
   thumbNavClicked: function(idx){
    this.currentIndex = idx;
    clearInterval(this.slidingInterval);
    this.slidingInterval = null;
    this.initSliding(idx);
   },
   // 当点击大图片的时候, 相当于跳转到下一张图片
   moveToNext: function(){
    clearInterval(this.slidingInterval);
    this.slidingInterval = null;

    if(this.currentIndex == this.images.length -1){
     this.currentIndex = 0;
    }else{
     this.currentIndex++;
    }
    this.initSliding(this.currentIndex);
   },
   // 单独的初始化sliding interval的方法
   initSliding: function(idx){
    let that = this;
    let sliderLength = this.images.length;
    this.currentImageUrl = this.images[idx];
    if(!this.slidingInterval){
     this.slidingInterval = setInterval(function(){

       if(that.currentIndex == sliderLength -1 ){
        that.currentIndex = 0;
       }else{
        that.currentIndex++;
       }
       that.currentImageUrl = that.images[that.currentIndex];
      }, that.autoplaySpeed);
    }
   }
  }
 }
</script>
<style scoped lang="scss" rel="stylesheet/scss">
 // css rule here
 .vue-js-slider-wrap{
  .vue-js-slider{
   display: block;
   .vuejs-slider-item{
    display: block;
    text-align:center;
    img{
     margin: 0 auto;
    }
   }

   .thumbnails-wrapper{
    margin-top: 2px;
    display: block;
    cursor: pointer;
    .move-nav{
     width: 20px;
     height: 60px;
     float: left;
     background-color: #f7f7f7;
     text-align: center;
     svg{
      margin-top: 20px;
     }
    }
    .move-nav-right{
     float: right;
    }
    .navs{
     overflow: hidden;
     float: left;
     position: relative;
     .thumbnail-nav{
      width: 60px;
      height: 60px;
      float: left;
     }
     .all-nav-items{
      position: absolute;
      top: 0;
      left: 0;
      .blur{
       opacity: 0.4;
      }
     }
    }
   }

   .thumbnails-on-right{
    float: left;
    margin-left: 2px;
    cursor: pointer;
    .move-nav{
     text-align: center;
     background-color: #f7f7f7;
    }
    .navs{
     overflow: hidden;
     position: relative;
     .thumbnail-nav{
      width: 60px;
      height: 60px;
     }
     .all-nav-items{
      position: absolute;
      top: 0;
      left: 0;
      .blur{
       opacity: 0.4;
      }
     }
    }
   }
  }
 }
</style>
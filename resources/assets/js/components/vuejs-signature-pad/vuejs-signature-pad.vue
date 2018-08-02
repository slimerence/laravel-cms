<template>
  <div class="vue-signature-wrapper">
    <div class="wrapper">
      <canvas id="vue-signature-pad" class="vue-signature-pad" width=400 height=200></canvas>
    </div>
    <div class="control-panel">
      <p class="msg-before-sign">{{ messageBeforeSigning }}</p>
    </div>
    <div class="control-panel">
      <div class="columns">
        <div class="column">
          <button v-on:click.stop.prevent="clearCanvas" class="button btn-clear">{{ clearButtonText }}</button>
        </div>
        <div class="column">
          <button v-on:click.stop.prevent="persistent" class="button is-success btn-confirm">{{ confirmButtonText }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import SignaturePad from 'signature_pad';

  export default {
    name: "vuejs-signature-pad",
    props:{
      resizable: {
        type: Boolean,
        required: false,
        default: false
      },
      errorMessage: {
        type: String,
        required: false,
        default: 'Please provide a signature first'
      },
      confirmButtonText:{
        type: String,
        required: false,
        default: 'Confirm'
      },
      clearButtonText:{
        type: String,
        required: false,
        default: 'Clear'
      },
      download:{
        type: Boolean,
        required: false,
        default: false
      },
      messageBeforeSigning:{
        type: String,
        required: true
      }
    },
    data (){
      return {
        signaturePad: null,
        canvas: null,
        dataURL: null
      };
    },
    created: function(){

    },
    mounted: function(){
      this.canvas = document.getElementById(
          'vue-signature-pad',{
            backgroundColor: 'rgb(255, 255, 255)'
          }
        );
      if(this.canvas){
        this.signaturePad = new SignaturePad(this.canvas);
        this.signaturePad.toDataURL("image/jpeg");
      }
    },
    methods: {
      // 清空
      clearCanvas: function(){
        if(this.signaturePad){
          this.signaturePad.clear();
        }
      },
      // Confirm signature event handler
      // 用户点击之后, 生成图片并下载, 然后emit一个事件
      persistent: function(){
        if (this.signaturePad.isEmpty()) {
          return alert(this.errorMessage);
        }
        this.dataURL = this.signaturePad.toDataURL(); // 默认将使用png格式

        // 如果需要下载, 则下载该签名图片
        if(this.download){
          this._download('signature.png');
        }

        this.$emit('sign-confirmed',this.dataURL);
      },
      _download: function(fileName){
        let blob = this._dataURLToBlob();
        if(!blob){
          return false;
        }
        // 模拟动态产生一个a链接并点击下载该图片到本地
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement("a");
        a.style = "display: none";
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
      },
      // One could simply use Canvas#toBlob method instead, but it's just to show
      // that it can be done using result of SignaturePad#toDataURL.
      _dataURLToBlob: function(){
        if(!this.dataURL){
          return false;
        }
        // Code taken from https://github.com/ebidel/filer.js
        let parts = this.dataURL.split(';base64,');
        let contentType = parts[0].split(":")[1];
        let raw = window.atob(parts[1]);
        let rawLength = raw.length;
        let uInt8Array = new Uint8Array(rawLength);

        for (let i = 0; i < rawLength; ++i) {
          uInt8Array[i] = raw.charCodeAt(i);
        }
        return new Blob([uInt8Array], { type: contentType });
      },
      // Adjust canvas coordinate space taking into account pixel ratio,
      // to make it look crisp on mobile devices.
      // This also causes canvas to be cleared.
      _resizeCanvas: function(){
        // When zoomed out to less than 100%, for some very strange reason,
        // some browsers report devicePixelRatio as less than 1
        // and only part of the canvas is cleared then.
        let ratio =  Math.max(window.devicePixelRatio || 1, 1);
        this.canvas.width = this.canvas.offsetWidth * ratio;
        this.canvas.height = this.canvas.offsetHeight * ratio;
        this.canvas.getContext("2d").scale(ratio, ratio);
      }
    }
  }
</script>

<style scoped lang="scss" rel="stylesheet/scss">
.vue-signature-wrapper{
  margin: 10px;
  width: 410px;
  display: block;
  .wrapper {
    position: relative;
    width: 100%;
    height: 210px;
    border: solid 1px #ccc;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    .vue-signature-pad{
      position: absolute;
      left: 0;
      top: 0;
      width:400px;
      height:200px;
      background-color: white;
    }
  }
  .control-panel{
    margin-top:20px;
    .msg-before-sign{
      font-size: 11px;
      color: #989898;
      text-align: center;
    }
    .btn-confirm{
      float: right;
    }
  }
}
</style>
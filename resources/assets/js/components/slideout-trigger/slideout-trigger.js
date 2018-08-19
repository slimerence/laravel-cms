// 导入 Slideout 库
import Slideout from 'slideout';

export default function(){
  if(document.getElementById('menu')){
    let slideout = new Slideout({
      'panel': document.getElementById('panel'),
      'menu': document.getElementById('menu'),
      'padding': 256,
      'tolerance': 70
    });

    document.querySelector('.toggle-button').addEventListener('click', function() {
      slideout.toggle();
    });

    // 菜单滑出后的蒙版响应点击事件的处理
    function slideOutClose(eve) {
      eve.preventDefault();
      slideout.close();
    }

    slideout.on('beforeopen', function() {
      this.panel.classList.add('panel-mask-open');
    })
        .on('open', function() {
          this.panel.addEventListener('click', slideOutClose);
        })
        .on('beforeclose', function() {
          this.panel.classList.remove('panel-mask-open');
          this.panel.removeEventListener('click', slideOutClose);
        });
  }
}
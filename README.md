# An e-commerce framework build upon Laravel + VueJS + Bulma
You might have been tired with Magento or Shopify, Checkout this one to work with your loved Laravel framework and build a e-commerce website in seconds.

# Installation
- composer install
- npm install ( For instance, in Ubuntu, if this command failed, please try sudo apt-get install autoconf libtool pkg-config nasm build-essential )
- Copy .env.example as .env
- Setup database config
- Setup APP_URL
- php artisan key:generate
- php artisan migrate
- Setup symlink for storage: php artisan storage:link
- Copy webpack.mix.js.example as webpack.mix.js
- 创建 webpack.mix.js.example 的拷贝并重命名为 webpack.mix.js, 该文件根据您的需求自行修改, 以便通过 npm 来生成所需的前端 CSS/JS 文件

# Customization And Develop
1: Setup your own theme folder
- I have provide a default theme for you, but you might need something special.
- Please create your own theme by running following command:
- php artisan smartbro:scaffold {your_theme_name}
- Please don't change the framework source code, but only coding inside of your theme folder. ( You can change the framework source code, for sure, but you might have trouble when you want to pull the updates of this framework. )
- In your theme root folder, init a new git repo and start building something awesome.
-
- In Chinese:
- 创建你自己的主题目录文件夹, 请运行下面的命令
- php artisan smartbro:scaffold {your_theme_name}
- 当然在 .env 文件里给 frontend_theme 项设定相同的值 custom.{your_theme_name}. 请参考 .env.example 文件.
- 请不要修改框架的任何文件中的代码 (当然你可以自己修改, 只是在以后pull更新的框架时, 恐怕会很麻烦). 
- 可以参照 resources/views/frontend/default 目录中的文件, 开发自己的主题.
- 在 resources/views/frontend/custom 目录下初始化Git仓库. git init  ...

2: Frontend Development
- Define your own frontend theme path in .env:
- Frontend css file: resources/frontend/custom/_custom.scss ( Or less/stylus but you need to update webpack.mix.js )
- Frontend javascript: resources/frontend/custom/_custom.js
- Customized Frontend layout files root folder: resources/frontend/custom/{your_theme_name}/layouts/frontend
- Customized view files root folder: resources/frontend/custom/{your_theme_name}
- Customized controllers, models, migrations and routes, please use: resources/views/frontend/custom/app

3: Backend Development
- Backend styles: resources/assets/sass/backend/_styles.scss ( Please scss )
- Backend javascript: resources/assets/js/backend.js

4: npm run watch

5: How to create your own routes/controller/model ... 如何创建自己业务需要的路由, 控制器, 模型类
- 像在 Laravel 下面开发一样, 在 custom 中, 你可以创建一个名字叫 app 的文件夹, 里面可以包含自己创建的各种 PHP 类 ( 最好使用命令 php artisan smartbro:scaffold {your_theme_name} 全部创建好)
- 定义自己的路由: 在 app 文件夹中, 创建 routes 文件夹, 里面创建 web.php 或者 api.php 文件, 像 Laravel 框架的路由一样, 创建框架没有提供的路由即可. 这些自己创建的路由, 在程序启动之后会被合并到 web 和 api 路由的尾部, 因此与框架同名的路由会覆盖框架中已经定义的路由 (你可以通过这个方式, 改变框架的行为, 但是我们不建议您这么做)
- 例如: web.php 中的路由 Route::get('/results', '\Smartbro\Controllers\CustomController@index') 会调用 custom/app/Controllers/CustomController.php 文件中的 index 方法.
- 凡是在 custom/app 文件夹中创建的类, 都会被框架自动加载, 但是 Namespace 都是以 Smartbro 开头的. 从上面一行的实例, 您可以看到这种加载的方式. 注意命名空间的开头是 Smartbro.

6: Create your own table in database and do 'migrate' 创建自己的数据表定义文件并通过artisan命令来创建数据库表
- php artisan make:migration --path=resources/views/frontend/custom/app/migrations/ your_migration_name
- php artisan migrate --path=resources/views/frontend/custom/app/migrations/

7: Happy coding ...

# Deployment
- composer install
- npm install ( You might need install autoconf first, run "sudo apt-get install autoconf" in Ubuntu for instance)
- Edit .env file
- php artisan key:generate
- php artisan migrate
- php artisan migrate --path=resources/views/frontend/custom/app/migrations/
- php artisan storage:link
- Go to resources/view/frontend folder, remove custom folder ( rm -rf custom )
- Git clone your-awesome-theme-repo-url custom
- Copy your webpack.mix.js file from your theme folder to the project root folder. Or you need to copy the example webpack.mix.js file if you don't have one in your own theme.
- npm run prod

# For SEO
- Give APP_SEO_DEFAULT_DESCRIPTION a value as the default site description
- Use APP_NAME as the default site title
- Update config/seotools.php for your own purpose
- 更新config/seotools.php文件做好你自己的SEO; 在自己开发的Theme中做好SEO工作
- 参考 https://github.com/artesaos/seotools

# For Payment
- Use Stripe as the default credit card payment gateway. Put the stripe api token in .env file, please refer to .env.example
- 使用Stripe作为默认的信用卡支付网关. Stripe要求的token放到.env 文件中. 参照 .env.example文件
- There are some switches in .env file, the system will render the payment options automatically, refer .env.example
- 打开哪些支付方式， 取决于 .env 文件中的配置开关, 参照 .env.example
- For Stripe frontend, use a new Vue component: <stripe-payment>. For props, please refer component source file.
- Stripe的前端, 使用Vue组件<stripe-payment> 即可。 使用方式参照组件的源代码即可
- 更多的Stripe的错误处理会逐步提交
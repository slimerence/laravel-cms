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
- I have provide a default theme for you, but you might need something special. Please create your own theme root folder in resources/frontend/custom/{your_theme_name}.
- Please don't change the framework source code, but only coding inside of your theme folder. ( You can change the framework source code, for sure, but you might have trouble when you want to pull the updates. )
- In your theme root folder, init a new git repo and start building something awesome.
- In Chinese:
- 在 resources/frontend/custom/{your_theme_name} 目录下创建你自己的主题目录文件夹, 当然在 .env 文件里给 frontend_theme 项设定相同的值 custom.{your_theme_name}. 请参考 .env.example 文件.
- 请不要修改框架的任何文件中的代码 (当然你可以自己修改, 只是在以后pull更新的时候, 恐怕会很麻烦). 
- 请在您自己的文件夹中创建自己的主题即可.

2: Frontend Development
- Define your own frontend theme path in .env:
- Frontend css file: resources/frontend/custom/_custom.scss ( Or less/stylus but you need to update webpack.mix.js )
- Frontend javascript: resources/frontend/custom/_custom.js
- Customized Frontend layout files root folder: resources/frontend/custom/{your_theme_name}/layouts/frontend
- Customized view files root folder: resources/frontend/custom/{your_theme_name}

3: Backend Development
- Backend styles: resources/assets/sass/backend/_styles.scss ( Please scss )
- Backend javascript: resources/assets/js/backend.js

4: npm run watch
5: Happy coding ...

# Deployment
- composer install
- npm install ( You might need install autoconf first, run "sudo apt-get install autoconf" in Ubuntu for instance)
- Edit .env file
- php artisan key:generate
- php artisan migrate
- php artisan storage:link
- npm run prod
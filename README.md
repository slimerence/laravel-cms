# Installation
composer install
npm install
Setup symlink for storage: php artisan storage:link
npm run prod

# Setup and Customization
1: Frontend Development
- Define your own frontend theme path in: app/config/system.php
- Frontend styles: resources/assets/sass/frontend/_styles.scss ( Please scss )
- Frontend javascript: resources/assets/js/app.js

2: Backend Development
- Backend styles: resources/assets/sass/backend/_styles.scss ( Please scss )
- Backend javascript: resources/assets/js/backend.js

npm run watch or npm run dev
<head>
    <?php
        // For AMP
        echo \App\Models\Utils\AMP\HeadUtil::getInstance()->output($pageTitle,$metaKeywords,$metaDescription );
        // For Other use: crsf, css, js
    /**
     *
        echo file_get_contents(app_path().'/../resources/assets/sass/frontend/fotorama.css'); // version 4.6.4
        echo file_get_contents(app_path().'/../public/css/app.css');
     */
    ?>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <style amp-custom>
        .container {
        <?php
            if($agentObject->isPhone()){
                foreach (config('system.layout.container.mobile.styles') as $key=>$value) {
                    echo !empty($value) ? $key.':'.$value.' !important;' : null;
                }
            }else{
                foreach (config('system.layout.container.desktop.styles') as $key=>$value) {
                    echo !empty($value) ? $key.':'.$value.' !important;' : null;
                }
            }
        ?>
        }
    </style>
    <meta property="og:title" content="{{ $pageTitle }}" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ isset($siteConfig) ? $siteConfig->logo : null }}" />
</head>
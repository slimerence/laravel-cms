@extends(_get_frontend_layout_path('frontend'))
@section('content')
    <div class="columns mb-20">
        <div class="column is-one-quarter left-side-bar-wrap">
            <?php
                $blocks = \App\Models\Widget\Block::GetBlocksByType(\App\Models\Widget\Block::$TYPE_LEFT);
            ?>
            @foreach($blocks as $key=>$block)
                <div class="box" id="left-side-block-{{ $key }}">
                    {!! $block->content !!}
                </div>
            @endforeach
        </div>
        <div class="column is-three-quarter">
            <div class="page-title-wrap">
                <h1 class="is-size-1-desktop is-size-1-mobile">{{ $page->title }}</h1>
            </div>
            <div class="content page-content-wrap">
                {!! $page->rebuildContent() !!}
            </div>
        </div>
    </div>
@endsection
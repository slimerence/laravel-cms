<?php
    $priceLowToHigh = $direction=='asc' && $orderBy=='price';
    $priceHighToLow = $direction=='desc' && $orderBy=='price';
    $nameLowToHigh = $direction=='asc' && $orderBy=='product_name';
    $nameHighToLow = $direction=='desc' && $orderBy=='product_name';
?>
<div class="dropdown mb-10 is-hoverable is-pulled-left" id="filter-btn">
    <div class="dropdown-trigger">
        <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
            <span>Filter</span>
            <span class="icon is-small">
                <i class="fas fa-angle-down" aria-hidden="true"></i>
            </span>
        </button>
    </div>
    <div class="dropdown-menu" id="dropdown-menu" role="menu">
        <div class="dropdown-content">
            <a href="{{ $priceLowToHigh ? '#' : url('category/view/'.$category->uri.'?orderBy=price&dir=asc') }}" class="dropdown-item {{ $priceLowToHigh ? 'is-active' : null }}">
                Price low to high
            </a>
            <a href="{{ $priceHighToLow ? '#' : url('category/view/'.$category->uri.'?orderBy=price&dir=desc') }}" class="dropdown-item {{ $priceHighToLow ? 'is-active' : null }}">
                Price high to low
            </a>
            <hr class="dropdown-divider">
            <a href="{{ $nameLowToHigh ? '#' : url('category/view/'.$category->uri.'?orderBy=product_name&dir=asc') }}" class="dropdown-item {{ $nameLowToHigh ? 'is-active' : null }}">
                Product Name A->Z
            </a>
            <a href="{{ $nameHighToLow ? '#' : url('category/view/'.$category->uri.'?orderBy=product_name&dir=desc') }}" class="dropdown-item {{ $nameHighToLow ? 'is-active' : null }}">
                Product Name Z->A
            </a>
        </div>
    </div>
</div>

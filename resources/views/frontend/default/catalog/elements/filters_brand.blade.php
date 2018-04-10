<?php
    $priceLowToHigh = $direction=='asc' && $orderBy=='default_price';
    $priceHighToLow = $direction=='desc' && $orderBy=='default_price';
    $nameLowToHigh = $direction=='asc' && $orderBy=='name';
    $nameHighToLow = $direction=='desc' && $orderBy=='name';
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
            <a href="{{ $priceLowToHigh ? '#' : url('catalog/brand/load?name='.$brand->name.'&orderBy=default_price&dir=asc') }}" class="dropdown-item {{ $priceLowToHigh ? 'is-active' : null }}">
                Price low to high
            </a>
            <a href="{{ $priceHighToLow ? '#' : url('catalog/brand/load?name='.$brand->name.'&orderBy=default_price&dir=desc') }}" class="dropdown-item {{ $priceHighToLow ? 'is-active' : null }}">
                Price high to low
            </a>
            <hr class="dropdown-divider">
            <a href="{{ $nameLowToHigh ? '#' : url('catalog/brand/load?name='.$brand->name.'&orderBy=name&dir=asc') }}" class="dropdown-item {{ $nameLowToHigh ? 'is-active' : null }}">
                Product Name A->Z
            </a>
            <a href="{{ $nameHighToLow ? '#' : url('catalog/brand/load?name='.$brand->name.'&orderBy=name&dir=desc') }}" class="dropdown-item {{ $nameHighToLow ? 'is-active' : null }}">
                Product Name Z->A
            </a>
        </div>
    </div>
</div>

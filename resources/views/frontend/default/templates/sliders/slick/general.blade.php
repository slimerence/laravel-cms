<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/3/18
 * Time: 10:17 PM
 * Slick的 Options{
 *  accessibility: boolean -> true      -> Enables tabbing and arrow key navigation
 *  adaptiveHeight: boolean -> false    -> Enables adaptive height for single slide horizontal carousels.
 *  autoplay: boolean                   -> false
 *  autoplaySpeed: int                  -> 3000
 *  arrows: boolean                     -> true
 *  asNavFor: string -> null            -> Set the slider to be the navigation of other slider (Class or ID Name) 可以跳转到其他slider, 根据ID指定
 *  appendArrows: string -> $(element)  -> Change where the navigation arrows are attached (Selector, htmlString, Array, Element, jQuery object)
 *  appendDots: string -> $(element)    -> Change where the navigation dots are attached (Selector, htmlString, Array, Element, jQuery object)
 *  prevArrow: -> string (html|jQuery selector) | object (DOM node|jQuery object) -> <button type="button" class="slick-prev">Previous</button> -> Allows you to select a node or customize the HTML for the "Previous" arrow.
 *  nextArrow: -> string (html|jQuery selector) | object (DOM node|jQuery object) -> <button type="button" class="slick-prev">Next</button>
 *  centerMode: boolean                 -> false    Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts.
 *  centerPadding: string               -> '50px'   Side padding when in center mode (px or %)
 *  cssEase: string                     -> 'ease'   CSS3 Animation Easing
 *  customPaging: function              -> n/a      Custom paging templates. See source for use example.
 *  dots: boolean                 -> false    Show dot indicators
 *  dotsClass: string                 -> 'slick-dots'    Class for slide indicator dots container
 *  draggable: boolean                 -> true    Enable mouse dragging
 *  fade: boolean                       -> false    Enable fade
 *  focusOnSelect: boolean              -> false    Enable focus on selected element (click)
 *  easing: string                      -> 'linear' Add easing for jQuery animate. Use with easing libraries or default easing methods
 *  edgeFriction: integer               -> 0.15     Resistance when swiping edges of non-infinite carousels 在滑动非无限传送带的边缘时存在阻力
 *  infinite: boolean                   -> true     Infinite loop sliding
 *  initialSlide: integer               -> 0        Slide to start on
 *  lazyLoad: string                    -> 'ondemand'   Set lazy loading technique. Accepts 'ondemand' or 'progressive'
 *  mobileFirst: boolean                -> false    Responsive settings use mobile first calculation
 *  pauseOnFocus: boolean               -> true
 *  pauseOnHover: boolean               -> true
 *  pauseOnDotsHover: boolean           -> false
 *  respondTo: string                   -> 'window'     Width that responsive object responds to. Can be 'window', 'slider' or 'min' (the smaller of the two)
 *  responsive: object                  -> none      Object containing breakpoints and settings objects (see demo). Enables settings sets at given screen width. Set settings to "unslick" instead of an object to disable slick at a given breakpoint.
 *  rows: int                           -> 1        Setting this to more than 1 initializes grid mode. Use slidesPerRow to set how many slides should be in each row.
 *  slide: element                      -> ''       Element query to use as slide
 *  slidesPerRow: int                   -> 1        With grid mode intialized via the rows option, this sets how many slides are in each grid row. dver
 *  slidesToShow: int                   -> 1        # of slides to show
 *  slidesToScroll: int                 -> 1        # of slides to scroll
 *  speed:  int                         -> 300      Slide/Fade animation speed
 *  swipe:  boolean                     -> true     Enable swiping
 *  swipeToSlide: boolean               -> false    Allow users to drag or swipe directly to a slide irrespective of slidesToScroll
 *  touchMove: boolean                  -> true     Enable slide motion with touch
 *  touchThreshold: int                 -> 5        To advance slides, the user must swipe a length of (1/touchThreshold) * the width of the slider
 *  useCSS: boolean                     -> true     Enable/Disable CSS Transitions
 *  useTransform: boolean               -> true     Enable/Disable CSS Transforms
 *  variableWidth: boolean              -> false    Variable width slides
 *  vertical: boolean                   -> false    Vertical slide mode
 *  verticalSwiping: boolean            -> false    Vertical swipe mode
 *  rtl: boolean                        -> false    Change the slider's direction to become right-to-left
 *  waitForAnimate: boolean             -> true     Ignores requests to advance the slide while animating
 *  zIndex: number                      -> 1000     Set the zIndex values for slides, useful for IE9 and lower
 *
 *  Events List:
 *  afterChange(slick, currentSlide)
 *  beforeChange(slick, currentSlide, nextSlide)
 *  breakpoint(event, slick, breakpoint)        Fires after a breakpoint is hit.
 *  destroy(event, slick)                       When slider is destroyed, or unslicked.
 *  edge(slick, direction)                      Fires when an edge is overscrolled in non-infinite mode.
 *  init(slick)                                 Fires after first initialization.
 *  reInit(slick)                               Fires after every re-initialization
 *  setPosition(slick)                          Fires after position/size changes
 *  swipe(slick, direction)                     Fires after swipe/drag
 *  lazyLoaded(event, slick, image, imageSource)    Fires after image loads lazily
 *  lazyLoadError(event, slick, image, imageSource) Fires after image fails to load
 *
 *  // Manually refresh positioning of slick :          $('.your-element').slick('setPosition');
 *  Methods
 *  slickCurrentSlide()                 Returns the current slide index
 *  slickGoTo(int : slide number, boolean: dont animate)    Navigates to a slide by index
 *  slickNext()                         Navigates to the next slide
 *  slickPrev()                         Navigates to the prev slide
 *  slickPause()                        Starts autoplay
 *  slickAdd(html or DOM object, index, addBefore)  Add a slide. If an index is provided, will add at that index, or before if addBefore is set. If no index is provided, add to the end or to the beginning if addBefore is set. Accepts HTML String || Object
 *  slickRemove(index, removeBefore)    Remove slide by index. If removeBefore is set true, remove slide preceding index, or the first slide if no index is specified. If removeBefore is set to false, remove the slide following index, or the last slide if no index is set.
 *  slickFilter(Selector or Function)   Filters slides using jQuery .filter()
 *  slickUnfilter(index)   Removes applied filtering
 *  slickGetOption(option : string)     Gets an individual option value.
 *  slickSetOption(option : string, value : depends on option, refresh : boolean)   Sets an individual value live. Set refresh to true if it's a UI update.
 *  unslick()                           Deconstructs slick
 *  getSlick()                          Get Slick Object
 * }
 */

/**
 * 检查是否需要输出 Slick Carousel
 */
?>
@foreach ($sliders as $sliderShortCode=>$slider)
<div class="{{ $slider->wrapper_classes }}">
    <div class="slick-carousel-el" id="slick-carousel-el-{{ $sliderShortCode }}" {{ $slider->attributes_text }}>
        <?php $sliderImages = $slider->getSliderImages(); ?>
        @foreach($sliderImages as $idx=>$sliderImage)
            <img class="{{ $sliderImage->classes_name }}" src="{{ $sliderImage->media->url }}" alt="{{ $slider->name.$idx }}" />
        @endforeach
    </div>
</div>
@endforeach

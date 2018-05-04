<script>
    // 支付方式选择框功能的改善
    $(document).ready(function(){
        $('.payment-method-item').on('click',function(e){
            $('.payment-method-item').removeClass('bg-primary');
            $(this).addClass('bg-primary');
            $('#payment-method-input').val($(this).attr('id'));
        });
    });
</script>
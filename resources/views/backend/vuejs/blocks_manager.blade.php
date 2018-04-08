<script type="application/javascript">
    var BlocksManger = new Vue({
        el:'#blocks-manager-app',
        data: {
            block:{
                id: '{{ $block->id }}',
                name:'{{ $block->name }}',
                short_code:'{{ $block->short_code }}',
                type: {{ $block->type ? $block->type : 3 }},
                position: '{{ $block->position }}',
                content: '{!! str_replace(PHP_EOL,'',$block->content) !!}'
            }
        },
        created: function(){

        },
        methods:{
            saveBlock: function(e){
                e.preventDefault();
                this.block.content = this.$refs.blockContentEditor.getContent();
                axios.post('/backend/blocks/save',{block: this.block})
                    .then(function(res){
                        if(res.data.error_no == 100){
                            window.location.href = '/backend/widgets/blocks';
                        }
                    });
            }
        }
    });
</script>
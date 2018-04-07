<script>
    var ProfileManagerApp = new Vue({
        el: '#user-profile-manager-app',
        data: {
            id: '{{ session('user_data.uuid') }}'
        },
        created: function(){

        },
        methods: {

        }
    });
</script>
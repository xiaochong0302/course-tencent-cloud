<script>

    window.koogua = {
        user: {
            id: '{{ auth_user.id }}',
            name: '{{ auth_user.name }}',
            avatar: '{{ auth_user.avatar }}',
            locked: '{{ auth_user.locked }}',
            vip: '{{ auth_user.vip }}'
        },
        im: {
            title: '',
            cs_user1_id: '',
            cs_user2_id: '',
            cs_user3_id: '',
            socket_url: ''
        }
    };

</script>
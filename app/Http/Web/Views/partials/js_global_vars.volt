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
            socket_url: '{{ im.socket_url }}'
        }
    };

</script>
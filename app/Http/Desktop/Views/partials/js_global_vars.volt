<script>

    window.user = {
        id: '{{ auth_user.id }}',
        name: '{{ auth_user.name }}',
        avatar: '{{ auth_user.avatar }}',
        locked: '{{ auth_user.locked }}',
        vip: '{{ auth_user.vip }}'
    };

    window.im = {
        title: '{{ im_info.title }}',
        cs: {
            enabled: '{{ im_info.cs.enabled }}'
        },
        websocket: {
            url: '{{ im_info.websocket.url }}'
        }
    };

</script>
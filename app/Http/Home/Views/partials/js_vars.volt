<script>

    window.user = {
        id: '{{ auth_user.id }}',
        name: '{{ auth_user.name }}',
        avatar: '{{ auth_user.avatar }}',
        locked: '{{ auth_user.locked }}',
        vip: '{{ auth_user.vip }}'
    };

    window.contact = {
        enabled: '{{ contact_info.enabled }}',
        qq: '{{ contact_info.qq }}',
        wechat: '{{ contact_info.wechat }}',
        toutiao: '{{ contact_info.toutiao }}',
        douyin: '{{ contact_info.douyin }}',
        weibo: '{{ contact_info.weibo }}',
        zhihu: '{{ contact_info.zhihu }}',
        phone: '{{ contact_info.phone }}',
        email: '{{ contact_info.email }}',
        address: '{{ contact_info.address }}'
    };

    window.websocket = {
        connect_url: '{{ websocket_info.connect_url }}',
        ping_interval: '{{ websocket_info.ping_interval }}'
    };

</script>
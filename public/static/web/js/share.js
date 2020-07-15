var Share = {};

Share.qq = function (title, url, pic) {
    var shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
    shareUrl += 'title=' + encodeURIComponent(title || document.title);
    shareUrl += '&url=' + encodeURIComponent(url || document.location);
    shareUrl += '&pics=' + pic;
    window.open(shareUrl, '_blank');
};

Share.weibo = function (title, url, pic) {
    var shareUrl = 'http://v.t.sina.com.cn/share/share.php?';
    shareUrl += 'title=' + encodeURIComponent(title || document.title);
    shareUrl += '&url=' + encodeURIComponent(url || document.location);
    shareUrl += '&pic=' + encodeURIComponent(pic || '');
    window.open(shareUrl, '_blank');
};
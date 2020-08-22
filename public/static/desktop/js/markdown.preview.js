var element = document.getElementById('preview');
var markdown = element.innerHTML;
var options = {
    lazyLoadImage: true,
    markdown: {
        autoSpace: true,
        chinesePunct: true
    }
};

Vditor.preview(element, markdown, options);
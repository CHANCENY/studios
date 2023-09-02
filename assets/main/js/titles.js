const titleMetaTag = document.querySelector("meta[name='title']");

if(titleMetaTag !== null)
{
    let title = titleMetaTag.getAttribute('content');
    const t = document.getElementById('titlepage').textContent;
    document.getElementById('titlepage').textContent = t+" - " + title;
}
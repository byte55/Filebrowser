$(function(){
	$.contextMenu({
        selector: '#viewer .dir',
        trigger: 'right',
        items: {
            "left": {name: "Choose for left monitor", icon: "add", callback: function (key, options) {galleryClick(options.$trigger[0],'Left');}},
            "right": {name: "Choose for right monitor", icon: "add", callback: function (key, options) {galleryClick(options.$trigger[0],'Right');}},
            "zoom": {name: "View picture in orignial size", icon: "zoom", callback: function (key, options) {window.open(website_basedir + 'img/gallery/single/original/'+options.$trigger[0].src.substring(options.$trigger[0].src.lastIndexOf('/')+1));}},
        }
    });
});
/*$(function(){
	$.contextMenu({
        selector: '#viewer .dir',
        trigger: 'right',
        items: {
            "left": {name: "Choose for left monitor", icon: "add", callback: function (key, options) {galleryClick(options.$trigger[0],'Left');}},
            "right": {name: "Choose for right monitor", icon: "add", callback: function (key, options) {galleryClick(options.$trigger[0],'Right');}},
            "zoom": {name: "View picture in orignial size", icon: "zoom", callback: function (key, options) {window.open(website_basedir + 'img/gallery/single/original/'+options.$trigger[0].src.substring(options.$trigger[0].src.lastIndexOf('/')+1));}},
        }
    });
});*/

$(function(){
    /*$.contextMenu({
        selector: '#viewer .dir', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
        },
        items: {
            "edit": {name: "Edit", icon: "edit"},
            "cut": {name: "Cut", icon: "cut"},
            "copy": {name: "Copy", icon: "copy"},
            "paste": {name: "Paste", icon: "paste"},
            "delete": {name: "Delete", icon: "delete"},
            "sep1": "---------",
            "quit": {name: "Quit", icon: "quit"}
        }
    });
    $.contextMenu({
        selector: '#viewer', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
        },
        items: {
            "edit": {name: "Edit", icon: "edit"}
        }
    });*/
    $.contextMenu({
        selector: '#viewer, #viewer .dir, #viewer .file', 
        build: function($trigger, e)
		{
			//console.log($trigger);
			//console.log(e);
			contextMenu($trigger,e);
			//console.log($(this));
            return{
                callback: function(key, options) {
                    var m = "clicked: " + key;
                    window.console && console.log(m) || alert(m); 
                },
                items: items
            };
        }
    });
});
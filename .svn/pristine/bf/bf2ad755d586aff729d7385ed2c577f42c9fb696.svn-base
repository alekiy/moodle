function customise_dock_for_theme() {
	
	
	/**
	 * Configuration parameters used during the initialisation and setup
	 * of dock and dock items.
	 * This is here specifically so that themers can override core parameters and
	 * design aspects without having to re-write navigation
	 * @namespace
	 */
	M.core_dock.cfg = {
	    buffer:50,                          // Buffer used when containing a panel
	    position:'left',                    // position of the dock
	    orientation:'vertical',             // vertical || horizontal determines if we change the title
	    spacebeforefirstitem: 190,           // Space between the top of the dock and the first item
	    removeallicon: M.util.image_url('t/besen', 'moodle')
	};
	
//    Methode überschrieben, damit Bilder angezeigt werden, für das Dock, wenn gewollt
    M.core_dock.fixTitleOrientation = function(item, title, text) {
    	// replace ALL white spaces with underlines
    	var replacer = new RegExp(" ","g");
    	var styletext= text.replace(replacer,"_");
    	// for question marks in title (e.g. polish language)
    	replacer = new RegExp("\\?","g");
    	styletext = styletext.replace(replacer,"");
        var Y = this.Y;
        var title = Y.one(title); 
        var stylefound = getStyle('.dock_item_'+styletext+'_picture');
        //alert(styletext);
        if (stylefound) {        
            //wenn ein style existiert, dann Bild anzeigen
            var test = Y.Node.create('<div class=dock_item_'+styletext+'_picture alt="'+styletext+'"></div>');        
            title.append(test);             
            return title;        
        } else {
            //wenn kein style existiert, dann svg ausgeben
            if(M.core_dock.cfg.orientation != 'vertical') {
                // If the dock isn't vertical don't adjust it!
                title.setContent(text);
                return title
            }

            if (Y.UA.ie > 0 && Y.UA.ie < 8) {
                // IE 6/7 can't rotate text so force ver
                M.str.langconfig.thisdirectionvertical = 'ver';
            }

            var clockwise = false;
            switch (M.str.langconfig.thisdirectionvertical) {
                case 'ver':
                    // Stacked is easy
                    return title.setContent(text.split('').join('<br />'));
                case 'ttb':
                    clockwise = true;
                    break;
                case 'btt':
                    clockwise = false;
                    break;
            }

            if (Y.UA.ie > 7) {
                // IE8 can flip the text via CSS but not handle SVG
                title.setContent(text);
                title.setAttribute('style', 'writing-mode: tb-rl; filter: flipV flipH;display:inline;');
                title.addClass('filterrotate');
                return title;
            }

            // Cool, we can use SVG!
            var test = Y.Node.create('<h2><span style="font-size:10px;">'+text+'</span></h2>');
            this.nodes.body.append(test);
            var height = test.one('span').get('offsetWidth')+4;
            var width = test.one('span').get('offsetHeight')*2;
            var qwidth = width/4;
            test.remove();

            // Create the text for the SVG
            var txt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            txt.setAttribute('font-size','10px');
            if (clockwise) {
                txt.setAttribute('transform','rotate(90 '+(qwidth/2)+' '+qwidth+')');
            } else {
                txt.setAttribute('y', height);
                txt.setAttribute('transform','rotate(270 '+qwidth+' '+(height-qwidth)+')');
            }
            txt.appendChild(document.createTextNode(text));

            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('version', '1.1');
            svg.setAttribute('height', height);
            svg.setAttribute('width', width);    
            svg.appendChild(txt);

            title.append(svg);
            title.append(Y.Node.create('<span class="accesshide">'+text+'</span>'));

            item.on('dockeditem:drawcomplete', function(txt, title){
                txt.setAttribute('fill', Y.one(title).getStyle('color'));
            }, item, txt, title);
            return title;
        }
    };        
}

// gibt true zurück, falls ein Style existiert
function getStyle(className) {
	var re = new RegExp("^.dock_item","m");
    for (var y = 0; y<document.styleSheets.length;y++) {
        var classes = document.styleSheets[y].rules || document.styleSheets[y].cssRules
        for(var x=0;x<classes.length;x++) {
        	if(re.test(classes[x].selectorText)) {
        		var rules = classes[x].selectorText.split(',');
        		for (var z = 0;z<rules.length;z++) {
        			if(rules[z].trim() == className.trim()) {
        				return true;
        			}
        		}
            }
        }
    }
    return false;
}


var Player = {
	init : function(video,pb,slides,tabSlides){
		this.video = video;
		this.pb = pb;
		this.slides = slides;
		this.tabSlides = tabSlides;
		var that = this;
		this.fs = false;
		this.currentView;
		
		that.cuePlayerStyle = that.getStyleObject($('#cuePlayer'));
		that.subtitlesStyle = that.getStyleObject($('#subtitles'));
		that.textStyle      = that.getStyleObject($('#text'));
		that.contentStyle   = that.getStyleObject($('#content'));
		that.totalStyle     = that.getStyleObject($('#total'));
		that.divPrincStyle  = that.getStyleObject($('#divprinc'));
		that.backgroundStyle  = that.getStyleObject($('#background'));
		
		that.KEY_SPACE	= 32;
		that.KEY_LEFT	= 37;
		that.KEY_RIGHT	= 39;
		
		var imageObject = new Image();
		for (s in that.tabSlides){
			if (that.tabSlides[s].src)
				imageObject.src = that.tabSlides[s].src;
		}
		
		//buttons management	
		$('#playbutton').click(function(){
			that.playControl()
		});
		$("input[type=button]").hover(function(){
			if(this.id == 'playbutton'){
				if (that.video.paused){
					$(this).css('backgroundImage', 'url("pix/play_roll.png")');
				}
				else {
					$(this).css('backgroundImage', 'url("pix/pause_roll.png")');
				}
			}
			else if (this.id == 'closed'){
				if (locked){
					$(this).css('backgroundImage', 'url("pix/closed_hover.png")');
				}
				else {
					$(this).css('backgroundImage', 'url("pix/opened_hover.png")');
				}
			}
			else {
				$(this).css('backgroundImage', 'url("pix/'+ this.id +'_roll.png")');
			}
		});	
		$("input[type=button]").mouseout(function(){
			if(this.id == 'playbutton'){
				if (that.video.paused){
					$(this).css('backgroundImage', 'url("pix/play.png")');
				}
				else {
					$(this).css('backgroundImage', 'url("pix/pause.png")');
				}
			}
			else if (this.id == 'closed'){
				if (locked){
					$(this).css('backgroundImage', 'url("pix/closed_normal.png")');
				}
				else {
					$(this).css('backgroundImage', 'url("pix/opened_normal.png")');
				}
			}	
			else {
				$(this).css('backgroundImage', 'url("pix/'+ this.id +'.png")');
			}
		});
		//clic on prev button
		$('#prev').click(function(){
			that.prev();
		});
		
		//clic on next button
		$('#next').click(function(){
			that.next();
		});	

		//clic on next button
		$('#closed').click(function(){
			locked = !locked;
			if (locked){
				$(this).css('backgroundImage', 'url("pix/closed_hover.png")');
				$('#selectview').attr('disabled','disabled');
			}
			else {
				$(this).css('backgroundImage', 'url("pix/opened_hover.png")');
				$('#selectview').removeAttr("disabled");
			}
		});
		
		//clic on fullscreen button
		$('#fullscreen').click(function(){
			that.fullscreen();
		});
		
		$('#subtitles').draggable();
		$('#cuePlayer').draggable();
		$('#text').draggable();
		
		cuepoint.init(that.slides);

		document.onkeydown = function(e){
			that.applyKey(e);
		}	
		if (autoplay){
			that.video.play();
		}
	},
	is_int : function(input){
		return typeof(input)=='number'&&parseInt(input)==input;
	},
	convert : function(nbsecondes){
		var temp = nbsecondes % 3600;
		var time0 = ( nbsecondes - temp ) / 3600 ;
		var time2 = temp % 60 ;
		var time1 = ( temp - time2 ) / 60;
		
		if (time1 == 0 || (this.is_int(time1) &&  time1 < 10)){
			time1 = '0' + time1;
		}
		if (this.is_int(time2) &&  time2 < 10){
			time2 = '0' + time2;
		}
		return time1 + ':' + time2;
	},
	changeDisplay : function(id){
		if (this.currentView != id){
			this.currentView = id;
			if (id == 1){
				this.defaultDisplay();
			}
			else if (id == 2){
				this.slideFullScreen();
			}
			else if (id == 3){
				this.videoFullScreen();
			}
		}	
	},
	videoFullScreen : function(){
		$('#cuePlayer').show();
		$('#cuePlayer').css('width','100%');
		$('#cuePlayer').css('height','100%');
		$('#cuePlayer').css('position','absolute');
		$('#cuePlayer').css('top','0');
		$('#cuePlayer').css('left','0');
		$('#cuePlayer').css('margin','0');
		$('#video').css('width','100%');
		$('#video').css('height','100%');
		$('#subtitles').css(this.subtitlesStyle);
		$('#subtitles').css('position','relative');
		$('#subtitles').css('width','25%');
		$('#subtitles').css('height','25%');
		$('#text').hide();
		$('#cuePlayer').draggable("disable");
		$('#subtitles').draggable("enable");
		$('#subtitles').css('z-index',100);
		$('#cuePlayer').css('z-index',0);
	},
	slideFullScreen : function(){
		$('#subtitles').show();
		$('#subtitles').css('width','100%');
		$('#subtitles').css('height','100%');
		$('#subtitles').css('vertical-align','center');
		$('#subtitles').css('position','absolute');
		$('#subtitles').css('top','0');
		$('#subtitles').css('left','0');
		$('#subtitles').css('margin','0');
		$('#cuePlayer').css(this.cuePlayerStyle);
		$('#cuePlayer').css('position','absolute').css('z-index',100);
		$('#text').hide();
		$('#subtitles').draggable("disable");
		$('#cuePlayer').draggable("enable");
		$('#cuePlayer').css('z-index',100);
		$('#subtitles').css('z-index',0);
	},
	defaultDisplay : function(){
		$('#cuePlayer').css(this.cuePlayerStyle);
		$('#subtitles').css(this.subtitlesStyle);
		$('#subtitles').css(this.subtitlesStyle);
		$('#text').css(this.textStyle);
		$('#text').css('height','100%');
		$('#text').css('position','relative');
		$('#text').show();
		$('#cuePlayer').show();
		$('#subtitles').show();
		if (this.fs){
			$('#cuePlayer').css('width','400px').css('height','222px');
			$('#subtitles').css('height','480px');
			$('#subtitles').css('width','640px');
		}
		else {
			$('#cuePlayer').css('width','300px').css('height','167px');
			$('#subtitles').css('height','450px');
			$('#subtitles').css('width','600px');
		}
		$('#subtitles').draggable("disable");
		$('#cuePlayer').draggable("disable");
	},
	prev : function(){
		var tabtime = parseInt(this.video.currentTime);
		var keys = new Array();
		var tabprev = new Array();
		var prev = 0;
		for (key in slides){
			if(key < tabtime){
				tabprev.push(key);
			}
		}
		if(tabprev.length-2 > 0){
			prev = tabprev[tabprev.length-2];
		}
		cuepoint.setTime(prev);
	},
	next : function(){
		var tabtime = parseInt(this.video.currentTime);
		var keys = new Array();
		for (key in slides){
			if(key > tabtime){
				cuepoint.setTime(key);
				return;
			}
	   }
	},
	playControl : function(){
		if (this.video.paused == false) {
			this.pb.style.backgroundImage = "url('pix/play.png')";
			cuepoint.pause();
		} else {
			this.pb.style.backgroundImage = "url('pix/pause.png')";
			cuepoint.play();
		}
	},
	checkEventObj : function( _event_ ){
		if ( window.event )
			return window.event;
		else
			return _event_;
	},
	applyKey : function(e){
		var winObj = this.checkEventObj(e);
		var intKeyCode = winObj.keyCode;
		if (intKeyCode == this.KEY_RIGHT){
			this.next();
		}
		else if (intKeyCode == this.KEY_LEFT){
			this.prev();
		}
		else if (intKeyCode == this.KEY_SPACE){
			this.playControl();
		}
	},
	playVideo : function(){
		this.pb.style.backgroundImage = "url('pix/pause.png')";
	},
	
	pauseVideo : function(){
		this.pb.style.backgroundImage = "url('pix/play.png')";
	},
	
	showCredits : function(){
		Ext.Msg.show({
			title : 'About RichMedia plugin for Moodle...',
			style : "background-color : #FFF;",
			minWidth : 180,
			bodyStyle : "background-color : #FFF;",
			msg : '<a style="margin-left : 106px;" href="http://www.elearning-symetrix.fr/produits/moodle_20-1/" target="_blank"><img width=61px height=52px src="pix/logo_rm.png" /></a><br />RichMedia Player version 2.0 (revised 21/06/2012)<br/ >For help and support, please contact<br/><br/><a style="margin-left : 77px;" href="mailto:richmedia@symetrix.fr">richmedia@symetrix.fr</a><br /><a style="margin-left : 93px;" href="http://www.elearning-symetrix.fr/produits/moodle_20-1/" target="_blank">www.symetrix.fr</a>'
		});
	},
	
	displaySlides : function(titleSummary){
		var that = this;
		if(!Ext.getCmp('window')){
			var x = document.getElementById('divprinc').offsetLeft + 5;
			var storeSlides = new Ext.data.JsonStore({
				fields : [{name: 'slide', type:'string'},{name: 'framein', type:'int'}],
				data : that.tabSlides
			});
			
			var gridSlides = new Ext.grid.GridPanel({
				store : storeSlides,
				autoScroll : true,
				id : 'gridSlides',
				frame : true,
				height : 295,
				border : false,
				columns : [{
					dataIndex : 'slide',
					width : 250,
					sortable : true
				},{
					dataIndex : 'framein',
					width : 50,
					sortable : true,
					renderer : function(value){
						return that.convert(value);
					}
				}],
				tbar :{}
			});
			
			gridSlides.on('rowclick', function(grid, rowIndex, e){
				cuepoint.setTime(grid.getSelectionModel().getSelected().data.framein);
				window.close();
			});
			
			var window = new Ext.Window ({
				id : 'window',
				items : [gridSlides],
				height : 311,
				layout : 'fit',
				border : false,
				bodyBorder : false,
				width : 350,
				title : titleSummary,
				collapsible : false,
				resizable : false,
				x : x,
				y : 290
			});
			window.show();
		}
		else {
			Ext.getCmp('window').close();
		}
	},
	getStyleObject : function(elem){
		var dom = elem.get(0);
		var style;
		var returns = {};
		if(window.getComputedStyle){
			var camelize = function(a,b){
				return b.toUpperCase();
			};
			style = window.getComputedStyle(dom, null);
			for(var i = 0, l = style.length; i < l; i++){
				var prop = style[i];
				var camel = prop.replace(/\-([a-z])/g, camelize);
				var val = style.getPropertyValue(prop);
				returns[camel] = val;
			};
			return returns;
		};
		if(style = dom.currentStyle){
			for(var prop in style){
				returns[prop] = style[prop];
			};
			return returns;
		};
		return elem.css();
	},
	fullscreen : function(){
		this.fs = !this.fs;
		if (!this.fs){
			console.log('not fullscreen');
			$('#total').css(this.totalStyle).css('margin','auto');
			$('#divprinc').css(this.divPrincStyle);
			$('#content').css(this.contentStyle).css('width','980px').css('height','485px').css('position','absolute');
			$('#background').css(this.backgroundStyle);
			$('#controles').css('position','relative').css('width','auto').css('margin-top',0);
			$('#cuePlayer').css('width','300px').css('height','167px');
			$('#cuePlayer').css('position','relative');
			$('#text').css('position','relative');
			$('#subtitles').css('height','450px');
			$('#subtitles').css('width','600px');
		}
		else {
			console.log('fullscreen');
			$('#content').css('position','relative');
			$('#background').css('width','99%').css('height','99%');
			$('#total').css('height',$('#background').css('height')).css('width', $('#background').css('width'));
			$('#divprinc').css('height',$('#background').css('height')).css('width', $('#background').css('width'));
			$('.content').css('left','0');
			$('#content').css('width','100%').css('height','80%').css('padding-top','180px');
			$('#controles').css('margin-top','-30px');
			$('#cuePlayer').css('width','400px').css('height','222px');
			$('#cuePlayer').css('position','relative');
			$('#text').css('position','relative');
			$('#subtitles').css('height','480px');
			$('#subtitles').css('width','640px');
		}	
	}
}
/**
 * 
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
M.mod_richmedia = {};

var http; //XMLHttpRequest object

M.mod_richmedia.onBeforeUnload = function(Y,userid,richmediaid){
	window.onbeforeunload = function(evt){
		M.mod_richmedia.close(userid,richmediaid);
	}
}

M.mod_richmedia.close = function(userid,richmediaid){
	var data= "userid="+userid+"&richmediaid="+richmediaid;
	http = M.mod_richmedia.createRequestObject();
	http.open('POST', 'close.php', false);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", data.length);
	http.setRequestHeader("Connection", "close");

	http.onreadystatechange = M.mod_richmedia.readystatechange;
	http.send(data);
}

M.mod_richmedia.createRequestObject = function(){
	var http;
	if(window.XMLHttpRequest){ // Mozilla, Safari, ...
		http = new XMLHttpRequest();
	}
	else if(window.ActiveXObject){ // Internet Explorer
		http = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return http;
}

M.mod_richmedia.readystatechange = function(){
	if(http.readyState == 4){
		if(http.status == 200){
			//IF YOU WANT TO DO SOMETHING
		}
	}
}

M.mod_richmedia.init = function(Y,availableSlides,tabstep,fileurl,arrayTrad,movie,title,presentername,presenterbio,presentertitle,contextid,update,fontcolor,fontvalue,urlSlides,defaultview,autoplay,urlSubmit,urlLocation,urlView){
	console.log('init');
	Ext.onReady(function(){
		Ext.QuickTips.init();
		XmlEditor.init(availableSlides,tabstep,fileurl,arrayTrad,movie,title,presentername,presenterbio,presentertitle,contextid,update,fontcolor,fontvalue,urlSlides,defaultview,autoplay,urlSubmit,urlLocation,urlView);			
	});
}

var XmlEditor = {
	init : function(availableSlides,tabstep,fileurl,arrayTrad,movie,title,presentername,presenterbio,presentertitle,contextid,update,fontcolor,fontvalue,urlSlides,defaultview,autoplay,urlSubmit,urlLocation,urlView){
		this.availableSlides = availableSlides;
		this.tabstep = tabstep;
		this.fileurl = fileurl;
		this.arrayTrad = arrayTrad;
		this.movie = movie;
		this.title = title;
		this.presentername = presentername;
		this.presenterbio = presenterbio;
		this.presentertitle = presentertitle;
		this.contextid = contextid;
		this.update = update;
		this.fontcolor = fontcolor;
		this.fontvalue = fontvalue;
		this.urlSlides = urlSlides;
		this.defaultview = defaultview;
		this.autoplay = autoplay;
		this.urlSubmit = urlSubmit;
		this.urlLocation = urlLocation;
		this.urlView = urlView;
		var that = this;
		this.storesteps = new Ext.data.JsonStore({
			fields : [
				{name: 'id', type:'int'},{name: 'label', type:'string'},{name:'comment',type:'string'},{name:'framein',type:'string'},{name:'slide',type:'string'},{name:'url',type:'string'},{name:'view',type:'string'}
			],
			data : that.tabstep
		});
				
		this.titlecmp = new Ext.form.Hidden({
			value : title,
			name : 'title'
		});
				
		this.moviecmp = new Ext.form.Hidden({
			value : that.movie,
			name : 'movie'
		});	
		
		this.presenternamecmp = new Ext.form.Hidden({
			value : that.presentername,
			name : 'presentername'
		});	

		this.presentertitlecmp = new Ext.form.Hidden({
			value : that.presentertitle,
			name : 'presentertitle'
		});

		this.presenterbiocmp = new Ext.form.Hidden({
			value : that.presenterbio,
			name : 'presenterbio'
		});
		
		this.color = new Ext.form.Hidden({
			value : that.fontcolor,
			name : 'fontcolor'
		});
			
		this.defaultviewcmp = new Ext.form.Hidden({
			value : that.defaultview,
			name : 'defaultview'
		});	

		this.autoplaycmp = new Ext.form.Hidden({
			value : that.autoplay,
			name : 'autoplay'
		});
			
		this.fontcmp = new Ext.form.Hidden({
			value : that.fontvalue,
			name : 'font'
		});
			
		this.submitbtn = new Ext.Button({
			text : that.arrayTrad['saveandreturn'],
			handler : function (){
				that.form.getForm().submit({
					url		:	'xmleditor_save.php',
					waitTitle : that.arrayTrad['strwait'],
					waitMsg	:	that.arrayTrad['strcurrentsave'],
					params : {
						steps :  Ext.encode(that.gridsteps.getValue()),
						contextid : that.contextid,
						update    : that.update
					},
					success : function(result, request){
						document.location.href = that.urlLocation;
					},
					failure: function(result,request){
						console.log(result,request);
					}
				});
			}
		});
		
		this.cancelbtn = new Ext.Button({
			text : that.arrayTrad['strcancel'],
			handler : function(){
				document.location.href = that.urlLocation;
			}
		});

		this.addbtn = new Ext.Button({
			iconCls : 'add',
			text : that.arrayTrad['straddline'],
			handler : function(){
				that.getTime(that.storesteps.data.length);
			}
		});	

		this.playbtn = new Ext.Button({
			iconCls : 'play',
			text : that.arrayTrad['test'],
			handler : function(){
				that.form.getForm().submit({
					url		:	'xmleditor_save.php',
					waitTitle : that.arrayTrad['strwait'],
					waitMsg	:	that.arrayTrad['strcurrentsave'],
					params : {
						steps :  Ext.encode(that.gridsteps.getValue()),
						contextid : that.contextid,
						update    : that.update
					},
					success : function(result, request){
						window.open(that.urlView);
					},
					failure: function(result,request){
						console.log(result,request);
					}
				});
			}
		});
		
		this.savebtn = new Ext.Button({
			iconCls : 'save',
			text : that.arrayTrad['strsave'],
			handler : function(){
				that.form.getForm().submit({
					url		:	'xmleditor_save.php',
					waitTitle : that.arrayTrad['strwait'],
					waitMsg	:	that.arrayTrad['strcurrentsave'],
					params : {
						steps :  Ext.encode(that.gridsteps.getValue()),
						contextid : that.contextid,
						update    : that.update
					},
					success : function(result, request){
						Ext.Msg.show({
							title : that.arrayTrad['information'],
							msg : that.arrayTrad['savedone'],
							buttons : Ext.Msg.OK
						});
					},
					failure: function(result,request){
						console.log(result,request);
					}
				});
			}
		});
		
		this.cmsteps = new Ext.grid.ColumnModel({
			defaults: {
				sortable: true        
			},
			columns: [
				{
					header : 'Id',
					dataIndex : 'id',
					sortable : true,
					width : 30
				},
				{
					header : that.arrayTrad['strslidetitle'],
					dataIndex : 'label',
					id : 'label',
					sortable : true,
					width : 430,
					editor: new Ext.form.TextField({
						allowBlank: false
					})
				},
				{
					header : that.arrayTrad['strslidecomment'],
					dataIndex : 'comment',
					sortable : true,
					width : 233,
					editor: new Ext.form.TextField({
						allowBlank: true
					})
				},{
					header : "MM:SS",
					dataIndex : 'framein',
					sortable : true,
					width : 45,
					editor: new Ext.form.TextField({
						allowBlank: false
					})
				},{
					header : that.arrayTrad['strslide'],
					dataIndex : 'slide',
					sortable : true,
					width : 133,
					editor: {
						xtype:'combo', 
						store: new Ext.data.ArrayStore({
							fields : [{name:'slide'}],
							data   : that.availableSlides,
							expandData : true
						}),
						displayField : 'slide',
						valueField : 'slide',
						mode : 'local',
						typeAhead : false,
						triggerAction : 'all',
						lazyRender : true,
						emptyText : 'Select a slide'
					}
				},{
					header :  that.arrayTrad['strview'],
					width : 100,
					dataIndex : 'view',
					sortable : true,
					renderer : that.renderView,
					editor: {
						xtype:'combo', 
						store: new Ext.data.ArrayStore({
							fields : ['view','display'],
							data   : [["1",that.arrayTrad['strdefaultview']],["2",that.arrayTrad['strpresentation']],["3",that.arrayTrad['strvideo']]]
						}),
						displayField: 'display',
						valueField: 'view',
						mode: 'local',
						typeAhead: false,
						triggerAction: 'all',
						editable : false,
						lazyRender: true
					}
				},{
					header : that.arrayTrad['stractions'],
					sortable : false,
					width : 74,
					renderer : that.renderDel
				}
			]	
		});
		
		this.gridsteps = new Ext.grid.EditorGridPanel({
			store		: that.storesteps,
			height		: 500,
			width		: 932,
			loadMask	: true,
			border		: true,
			clicksToEdit: 1,
			cm 			: that.cmsteps,
			region 		: 'south',
			ddGroup 	: 'mygrid-dd',   
			enableDragDrop : true,
			autoExpandColumn : 'label',
			sm : new Ext.grid.RowSelectionModel({  
				singleSelect:true,  
				listeners: {  
					beforerowselect: function(sm,i,ke,row){
						that.gridsteps.ddText = row.data.label; 
					}  
				}  
			}),					
			tbar : [that.addbtn,that.savebtn,that.playbtn]
		});
		
		this.gridsteps.on('cellclick',function(grid,row,column){
			var slide = that.storesteps.getAt(row).data;
			if (that.isAvailable(slide.slide)){
				Ext.get('imageDisplay').update('<div style="width : 338px;height : 218px;"><img src="'+ that.urlSlides + slide.slide + '" height="100%"/></div>');
			}
			else {
				Ext.get('imageDisplay').update('<div style="width : 338px;height : 218px;">'+ that.arrayTrad['strfilenotavailable'] +'</div>');
			}
		});
		
		this.gridsteps.on('keydown',function(event){
			var last = this.getSelectionModel().last;
			if (event.button == 37){
				last--;
			}
			else if (event.button == 39){
				last++;
			}
			if (last >= 0 && last < that.storesteps.data.length){
				var slide = that.storesteps.getAt(last).data;
				if (that.isAvailable(slide.slide)){
					Ext.get('imageDisplay').update('<div style="width : 338px;height : 218px;"><img src="'+ that.urlSlides + slide.slide + '" height="100%"/></div>');
				}
				else {
					Ext.get('imageDisplay').update('<div style="width : 338px;height : 218px;">'+ that.arrayTrad['strfilenotavailable'] +'</div>');
				}
			}				
		});
		
		this.gridsteps.getValue = function() {
			var ret = [];
			var storestepslength = this.getStore().data.length;
			for (var i = 0; i < storestepslength; i++) {
				ret[i] = new Array(this.getStore().data.items[i].data.id,this.getStore().data.items[i].data.label,this.getStore().data.items[i].data.comment,this.getStore().data.items[i].data.framein,this.getStore().data.items[i].data.slide,this.getStore().data.items[i].data.view);
			}
			return ret;
		}
		
		this.gridsteps.on('sortchange',function(){
			for(var i = 0; i < this.getStore().data.length; i++) {
				this.getStore().getAt(i).set('id',i);
			}
		});
				
		this.form = new Ext.form.FormPanel({
			id : "form",
			height : 250,
			bodyStyle : 'padding : 10px;',
			width : 932,
			border : false,
			items : [that.titlecmp,that.moviecmp,that.presenternamecmp,that.presentertitlecmp,that.presenterbiocmp,that.fontcmp,that.color,that.defaultviewcmp,that.autoplaycmp]
		});
		
		this.panelprincipal = new Ext.Panel({
			layout : "border",
			style  : "margin : auto;",
			height : 815,
			width  : 932,
			renderTo : 'tab',
			buttonAlign : 'center',
			items : [
				that.form,
				{
					html : '<object id="myFlash" type="application/x-shockwave-flash" data="playerflash/player.swf" width="338" height="246"> <param name="wmode" value="transparent"><param name="movie" value="playerflash/player.swf" /><param name="FlashVars" value="flv='+ that.fileurl +'&amp;showtime=1&amp;showplayer=always&amp;autoload=1" /></object>',
					xtype : "panel",
					region : 'center',
					width : 340,
					height : 246,
					border : false
				},{
					id : 'imageDisplay',
					html : '',
					xtype : 'panel',
					region : 'east',
					width : 338,
					height : 218,
					style : 'margin-top : 14px;margin-left:-65px;',
					border : false
				},
				that.gridsteps
			],
			buttons : [that.submitbtn,that.cancelbtn]
		});
		
		this.ddrow = new Ext.dd.DropTarget(that.gridsteps.getView().mainBody, {  
			ddGroup : 'mygrid-dd',  
			notifyDrop : function(dd, e, data){ 
				var sm = that.gridsteps.getSelectionModel();  
				var rows = sm.getSelections();  
				var cindex = dd.getDragData(e).rowIndex;  
				if (sm.hasSelection()) {  
					for (i = 0; i < rows.length; i++) {  
						that.storesteps.remove(that.storesteps.getById(rows[i].id));  
						that.storesteps.insert(cindex,rows[i]);  
					}  
					sm.selectRecords(rows);  
				} 
				for(var i = 0;i < that.storesteps.data.length;i++) {
					that.storesteps.getAt(i).set('id',i);
				}
			}  
		});
	},
	is_int : function(input){
		return typeof(input)=='number'&&parseInt(input)==input;
	},
	is_string : function(input){
		return typeof(input)=='string';
	},
	convertTime : function(nbsecondes){
		if (this.is_string(nbsecondes)){
			return nbsecondes;
		}
		nbsecondes = Math.floor(nbsecondes);
		temp = nbsecondes % 3600;
		var time = new Array();
		time[0] = ( nbsecondes - temp ) / 3600 ;
		time[2] = temp % 60 ;
		time[1] = ( temp - time[2] ) / 60;

		if (time[1] == 0 || (this.is_int(time[1]) &&  time[1] < 10)){
			time[1] = '0' + time[1];
		}
		if (this.is_int(time[2]) &&  time[2] < 10){
			time[2] = '0' + time[2];
		}
		return time[1] + ':' + time[2];
	},
	isAvailable : function(slide){
		for (key in this.availableSlides){
			if (this.availableSlides[key] == slide){
				return true;
			}
		}
		return false;
	},
	upLine : function(rowIndex){
		var row = this.storesteps.getAt(rowIndex);
		this.storesteps.remove(row);
		this.storesteps.insert(rowIndex -1,row);
		for(var i =0; i < this.storesteps.data.length;i++) {
			this.storesteps.getAt(i).set('id',i);
		}
	},	
	downLine : function(rowIndex){
		var row = this.storesteps.getAt(rowIndex);
		this.storesteps.remove(row);
		this.storesteps.insert(rowIndex + 1,row);
		for(var i = 0;i < this.storesteps.data.length;i++) {
			this.storesteps.getAt(i).set('id',i);
		}
	},
	deleteRow : function(id){
		var that = this;
		Ext.Msg.show({
			title  : that.arrayTrad['strwarning'],
			msg    : that.arrayTrad['strconfirm'] + id + ' ?',
			buttons: Ext.Msg.YESNO,
			icon   : Ext.MessageBox.WARNING,
			fn : function(res){
				if (res == 'yes'){
					var storestepslength = that.storesteps.data.length;
					for(var i = 0; i < storestepslength;i++) {
						if (that.storesteps.getAt(i).data.id == id){
							that.storesteps.remove(that.storesteps.getAt(i));
							for(var j = 0;j < that.storesteps.data.length;j++) {
								that.storesteps.getAt(j).set('id',j);
							}
							break;
						}
					}
					that.gridsteps.getView().refresh();
				}
			}
		});
	},
	addLine : function(time){
		time = this.convertTime(time);
		this.storesteps.insert(this.storesteps.data.length,new Ext.data.Record({'id': this.storesteps.data.length,'label': this.arrayTrad['strnewline'],'comment':'','framein': time,'slide':'','url': this.urlSlides}));
		this.storesteps.singleSort('framein','DESC');
		this.storesteps.singleSort('framein','ASC');
		this.gridsteps.getView().refresh();
	},
	getTime : function(id){
		document.myFlash.getCurrentTime("displayCurrentTime", id);
	},
	displayCurrentTime : function(id, time){
		time = this.convertTime(time);
		if (id != this.storesteps.data.length){
			record = this.storesteps.getAt(id);
			record.set('framein',time);
		}
		else {
			this.addLine(time);
		}
	},
	renderDel : function(value, metaData, record, rowIndex, colIndex, store){
		ret = '<img src = "pix/application_edit.png" title="'+ XmlEditor.arrayTrad['strgettime'] +'" width=16px height=16px alt="edit" onclick="XmlEditor.getTime('+ record.data.id +');" style="cursor:pointer;" />';
		ret += '<img src = "pix/cross.png" title="'+ XmlEditor.arrayTrad['strdelete'] +'" alt="suppr" onclick="XmlEditor.deleteRow('+ record.data.id +');" style="cursor:pointer;" />';
		if (rowIndex != 0){
			ret += '<img src ="pix/up.png" title="'+ XmlEditor.arrayTrad['strup'] +'" alt="suppr" onclick="XmlEditor.upLine('+ rowIndex +');" style="cursor:pointer;" />';
		}
		if (rowIndex != (store.data.length -1)){
			ret += '<img src = "pix/down.png" title="'+ XmlEditor.arrayTrad['strdown'] +'" alt="suppr" onclick="XmlEditor.downLine('+ rowIndex +');" style="cursor:pointer;" />';
		}	

		return ret;
	},
	renderView : function(value){
		if (value == 1){
			return XmlEditor.arrayTrad['strdefaultview'];
		}
		else if (value == 2){
			return XmlEditor.arrayTrad['strpresentation'];
		}
		else if (value == 3){
			return XmlEditor.arrayTrad['strvideo'];
		}
	}
}

//Called by Flash after getTime()
displayCurrentTime = function(ln, time){
	XmlEditor.displayCurrentTime(ln,time);
}
//Called by Flash
addLine = function(time){
	XmlEditor.addLine(time);
}
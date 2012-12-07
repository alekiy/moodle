<?php
/**
 *
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
 
require_once("../../config.php");
$course  = required_param('course', PARAM_INT);
$course  = $DB->get_record('course', array('id'=>$course), '*', MUST_EXIST);

$context = get_context_instance(CONTEXT_COURSE, $course->id);
//$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$url = new moodle_url('/mod/richmedia/edit_theme.php');
$PAGE->set_url($url);
require_login();

$strapropos = get_string('themeedition','richmedia');
//barre de navigation
$PAGE->navbar->add($strapropos);
$PAGE->set_title(format_string($strapropos));
$PAGE->set_heading($course->fullname);

//headers utf8
echo $OUTPUT->header();
//titre
echo $OUTPUT->heading($strapropos);

require_capability('moodle/course:manageactivities', $context);

?>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/resources/css/ext-all.css'?>"/>
<script type="text/javascript" src="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/adapter/ext/ext-base.js'?>"></script>
<script type="text/javascript" src="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/ext-all.js'?>"></script>
<script type="text/javascript">
	var storetheme;
	var cancelbtn;
	var addbtn;
	var cmsteps;
	var gridtheme;
	var panelprincipal;
	
	function deleteRow(id){
		var the = storetheme.getById(id);
		Ext.Msg.show({
			title : "<?php echo get_string('warning','richmedia') ?>",
			msg : "<?php echo get_string('removetheme','richmedia') ?> "+ the.data.nom + ' ?',
			buttons : Ext.Msg.YESNO,
			fn		: function(btn){
				if(btn == 'yes'){
					Ext.Ajax.request({
						url         :    'save_theme.php?delete=1'
						,method     :    'POST'
						,params     :    {							
							nom	:  the.data.nom
						}
						,success    : function( result, request) {
							if (result.responseText == 1){
								Ext.Msg.show({
									title : "<?php echo get_string('information','richmedia') ?>",
									msg : "<?php echo get_string('deletedtheme','richmedia') ?>",
									buttons : Ext.Msg.OK
								});
								delete storetheme.lastParams;
								storetheme.reload();
							}
						}
					});
				}
			}	
		});
	}
	function editRow(id){
		var the = storetheme.getById(id);
		var panelEdit = new Ext.form.FormPanel({
			fileUpload: true,
			width: 450,
			height : 200,
			bodyStyle: 'padding: 10px 10px 10px 10px;',
			labelWidth: 50,
			defaults: {
				anchor: '95%',
				allowBlank: false,
				msgTarget: 'side'
			},
			items: [
				{
					xtype : 'hidden',
					name : 'anciennom',
					value : the.data.nom
				},{
					xtype :'textfield',
					name : 'nom',
					fieldLabel : "<?php echo get_string('name','richmedia') ?>",
					vtype : 'alphanum',
					value : the.data.nom
				},{
					xtype: 'panel',
					border : false,
					html : "<?php echo get_string('logo','richmedia') ?>" + ':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="logoupload" type="file" size="50" maxlength="100000">'
				},{
					xtype: 'panel',
					border : false,
					html :"<?php echo get_string('fond','richmedia') ?>" + ':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="backgroundupload" type="file" size="50" maxlength="100000">'
				}	
			]
		});
		
		fenetreEdit = new Ext.Window({
			title	: "<?php echo get_string('themeedition','richmedia') ?>",
			closeAction : 'close',
			layout : 'fit',
			resizable : false,
			height 	: 180,
			width 	: 500,
			items : [panelEdit],
			buttonAlign : 'center',
			buttons : [{
				text : 'OK',
				handler : function(){
					panelEdit.getForm().submit({
						url		:	"save_theme.php?edit=1",
						waitTitle : "<?php echo get_string('wait','richmedia') ?>",
						timeout	:	3500000,
						waitMsg	:	"<?php echo get_string('currentsave','richmedia') ?>",
						success	:	function(obj, action){
							Ext.Msg.show({
								title : "<?php echo get_string('success','richmedia') ?>",
								msg : "<?php echo get_string('importdone','richmedia') ?>",
								buttons : Ext.Msg.OK
							});
							delete storetheme.lastParams;
							storetheme.reload();
							fenetreEdit.close();
						},
						failure	:	function(form, action){
							Ext.Msg.show({
								title : "<?php echo get_string('error','richmedia') ?>",
								msg : action.result.msg.reason,
								buttons : Ext.Msg.OK
							});
						}
					});
				}
			},{
				text : "<?php echo get_string('cancel','richmedia') ?>",
				handler : function(){
					fenetreEdit.close();
				}
			}]
		});
		fenetreEdit.show();
	}
	
	Ext.onReady(function(){
	
		function renderDel(value, metaData, record, rowIndex, colIndex, store){
			ret = '<img src = "<?php echo $CFG->wwwroot . '/mod/richmedia/pix/cross.png' ?>" alt = "suppr" onclick="deleteRow('+ record.data.id +');"/><img src = "<?php echo $CFG->wwwroot . '/mod/richmedia/pix/image_add.png' ?>" alt = "edit" onclick="editRow('+ record.data.id +');"/>';
			return ret;
		}
		
		storetheme = new Ext.data.JsonStore({
			fields : [{name: 'nom', type:'string'},{name: 'logo', type:'string'},{name:'background',type:'string'},{name:'id',type:'int'}],
			url : 'save_theme.php?store=1',
		});
		storetheme.load();
		
		cancelbtn = new Ext.Button({
			text : "<?php echo get_string('return','richmedia') ?>"
		});
		cancelbtn.on('click',function(){
			history.go(-1);
		});

		addbtn = new Ext.Button({
			text : "<?php echo get_string('addtheme','richmedia') ?>",
		});
		addbtn.on('click',function(){
			var panelUpload = new Ext.form.FormPanel({
				fileUpload: true,
				width: 450,
				height : 180,
				bodyStyle: 'padding: 10px 10px 10px 10px;',
				labelWidth: 50,
				defaults: {
					anchor: '95%',
					allowBlank: false,
					msgTarget: 'side'
				},
				items: [
					{
						xtype :'textfield',
						name : 'nom',
						fieldLabel : "<?php echo get_string('name','richmedia') ?>",
						vtype : 'alphanum'
					},{
						xtype: 'panel',
						border : false,
						html : '<label for="logoupload"><?php echo get_string('logo','richmedia') ?> :</label><input id="logoupload" name="logoupload" type="file" size="50" maxlength="100000">'
					},{
						xtype: 'panel',
						border : false,
						html : '<label for="backgroundupload"><?php echo get_string('fond','richmedia') ?> :</label><input id="backgroundupload" name="backgroundupload" type="file" size="50" maxlength="100000">'
					}	
				]
			});
			
			fenetreImport = new Ext.Window({
				title	: "<?php echo get_string('themeimport','richmedia') ?>",
				closeAction : 'close',
				layout : 'fit',
				resizable : false,
				height 	: 180,
				width 	: 500,
				items : [panelUpload],
				buttonAlign : 'center',
				buttons : [{
					text : 'OK',
					handler : function(){
						panelUpload.getForm().submit({
							url		:	"save_theme.php?upload=1",
							waitTitle : "<?php echo get_string('wait','richmedia') ?>",
							timeout	:	3500000,
							waitMsg	:	"<?php echo get_string('currentsave','richmedia') ?>",
							success	:	function(obj, action){
								Ext.Msg.show({
									title : "<?php echo get_string('success','richmedia') ?>",
									msg : "<?php echo get_string('importdone','richmedia') ?>",
									buttons : Ext.Msg.OK
								});
								delete storetheme.lastParams;
								storetheme.reload();
								fenetreImport.close();
							},
							failure	:	function(form, action){
								Ext.Msg.show({
									title : "<?php echo get_string('error','richmedia') ?>",
									msg : action.result.msg.reason,
									buttons : Ext.Msg.OK
								});
							}
						});
					}
				},{
					text : "<?php echo get_string('cancel','richmedia') ?>",
					handler : function(){
						fenetreImport.close();
					}
				}]
			});
			fenetreImport.show();
		});
		
		cmsteps = new Ext.grid.ColumnModel({
			defaults: {
				sortable: true        
			},
			columns: [
				{
					header : "<?php echo get_string('name','richmedia') ?>",
					dataIndex : 'nom',
					sortable : true,
					width : 230
				},
				{
					header : "<?php echo get_string('logo','richmedia') ?>",
					dataIndex : 'logo',
					sortable : true,
					width : 130
				},
				{
					header : "<?php echo get_string('fond','richmedia') ?>",
					dataIndex : 'background',
					sortable : true,
					width : 200
				},{
					header : "<?php echo get_string('actions','richmedia') ?>",
					sortable : true,
					width : 60,
					renderer : renderDel
				}
			]	
		});
		
		gridtheme = new Ext.grid.EditorGridPanel({
			store		: 	storetheme,
			height		:	280,
			width		:	650,
			loadMask	: 	true,
			border		:	true,
			clicksToEdit:	2,
			cm 			: 	cmsteps
		});

		panelprincipal = new Ext.Panel({
			style : "margin : auto;margin-top : 50px;",
			layout : 'fit',
			autoHeight : true,
			width	:	650,
			renderTo : 'tab',
			buttonAlign : 'center',
			items : [gridtheme],
			buttons : [cancelbtn,addbtn]
		});
	});
</script>

<?php
echo '<div id="tab"></div>';
echo $OUTPUT->footer();
?>	


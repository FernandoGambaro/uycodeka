"undefined"!=typeof CKEDITOR&&(CKEDITOR.disableAutoInline=!0,CKEDITOR.editorConfig=function(e)
{e.skin="moonocolor",
e.contentsCss="/ckeditor/contents.css",
e.filebrowserBrowseUrl="/ckeditor/attachment_files",
e.filebrowserFlashBrowseUrl="/ckeditor/attachment_files",
e.filebrowserFlashUploadUrl="/ckeditor/attachment_files",
e.filebrowserImageBrowseLinkUrl="/ckeditor/pictures",
e.filebrowserImageBrowseUrl="/ckeditor/pictures",
e.filebrowserImageUploadUrl="/ckeditor/pictures",
e.filebrowserUploadUrl="/ckeditor/attachment_files",
e.filebrowserParams=function(){var e=jQuery("meta[name=csrf-token]").attr("content"),
t=jQuery("meta[name=csrf-param]").attr("content"),o=new Object;return void 0!==t&&void 0!==e&&(o[t]=e),o},
e.removeDialogTabs="link:upload;image:Upload",
e.extraPlugins="onchange,tokens,dragresize",
e.title=!1,e.defaultLanguage="en",e.language="en",e.toolbar="Easy",
e.fontSize_sizes="8/8px;9/9px;10/10px;11/11px;12/12px;13/13px;14/14px;15/15px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px",
e.toolbar_Easy=[["Source"],["Cut","Copy","Paste","PasteText","PasteFromWord"],
["Undo","Redo","-","RemoveFormat"],["Format","FontSize","TextColor"],["Image","Table"],"/",
["tokens"],["Bold","Italic","Underline","Strike"],["NumberedList","BulletedList","-","Outdent","Indent"],
["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],["Link","Unlink"]],e.toolbar_Area=[["Maximize"],"/",
["Source"],["Cut","Copy","Paste","PasteText","PasteFromWord"],["Undo","Redo","-","RemoveFormat"],["Format","FontSize","TextColor"],
["Image","Table"],"/",["tokens"],["Bold","Italic","Underline","Strike"],["NumberedList","BulletedList","-","Outdent","Indent"],
["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],["Link","Unlink"]],e.toolbar_Inline=[["Sourcedialog"],
["Cut","Copy","Paste","PasteText","PasteFromWord"],["Undo","Redo","-","RemoveFormat"],["Format","FontSize"],
["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],"/",["tokens"],["Image","Link","Link","Unlink","Table"],
["Bold","Italic","Underline","Strike"],["NumberedList","BulletedList","-","Outdent","Indent"],["TextColor"]],
e.scayt_autoStartup=!1,e.autoGrow_onStartup=!0,e.allowedContent=!0,
"undefined"!=typeof CKEDITOR_ADDITIONAL_CONFIG&&CKEDITOR_ADDITIONAL_CONFIG(e)},
CKEDITOR.on("dialogDefinition",
function(e){var t=e.data.name,o=e.data.definition;if("table"==t){var i=o.getContents("info");txtWidth=i.get("txtWidth"),
txtWidth["default"]="",txtCellPad=i.get("txtCellPad"),txtCellPad["default"]="",txtCellSpace=i.get("txtCellSpace"),txtCellSpace["default"]=""}}));
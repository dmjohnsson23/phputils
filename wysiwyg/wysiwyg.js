(function() {
  window.addEventListener("load", function() {
    if (document.execCommand != null) {
      document.execCommand('styleWithCSS', false, false);
      document.execCommand('insertBrOnReturn', false, false);
      document.execCommand('enableInlineTableEditing', false, true);
      return document.execCommand('enableObjectResizing', false, true);
    } else {
      return alert('Your browser does not support the features needed to use the rich-text editor. Use the "Edit as HTML" feature instead.');
    }
  }, false);

  this.wysiwygToggleHTML = function(id) {
    var controlbox, editor, textarea, toggle;
    textarea = document.getElementById(id + "_textarea");
    editor = document.getElementById(id + "_editor");
    controlbox = document.getElementById(id + "_controlbox");
    toggle = document.getElementById(id + "_toggle");
    if (getComputedStyle(textarea, null).display === 'none') {
      textarea.style.display = 'block';
      editor.style.display = 'none';
      controlbox.style.display = 'none';
      toggle.innerHTML = "Edit as Rich Text";
      contenteditableToTextarea(id);
      textareaAdaptHeight(id);
    } else {
      textarea.style.display = 'none';
      editor.style.display = 'block';
      controlbox.style.display = 'block';
      toggle.innerHTML = "Edit as HTML";
      textareaToContenteditable(id);
    }
    return false;
  };

  this.contenteditableToTextarea = function(id) {
    var editor, textarea;
    textarea = document.getElementById(id + "_textarea");
    editor = document.getElementById(id + "_editor");
    return textarea.value = editor.innerHTML;
  };

  this.textareaToContenteditable = function(id) {
    var editor, textarea;
    textarea = document.getElementById(id + "_textarea");
    editor = document.getElementById(id + "_editor");
    return editor.innerHTML = textarea.value;
  };

  this.wysiwygOnSubmit = function(id) {
    var textarea;
    textarea = document.getElementById(id + "_textarea");
    if (getComputedStyle(textarea, null).display === 'none') {
      contenteditableToTextarea(id);
    }
    return true;
  };

  this.textareaAdaptHeight = function(id) {
    var textarea;
    textarea = document.getElementById(id + "_textarea");
    textarea.style.height = '1px';
    return textarea.style.height = textarea.scrollHeight + "px";
  };

  this.undo = function() {
    return document.execCommand("undo", false, null);
  };

  this.redo = function() {
    return document.execCommand("redo", false, null);
  };

  this.unformat = function() {
    return document.execCommand("removeFormat", false, null);
  };

  this.cut = function() {
    return document.execCommand("cut", false, null);
  };

  this.copy = function() {
    return document.execCommand("copy", false, null);
  };

  this.paste = function() {
    return document.execCommand("paste", false, null);
  };

  this.underline = function() {
    return document.execCommand("underline", false, null);
  };

  this.bold = function() {
    return document.execCommand("bold", false, null);
  };

  this.italic = function() {
    return document.execCommand("italic", false, null);
  };

  this.strike = function() {
    return document.execCommand("strikeThrough", false, null);
  };

  this.sub = function() {
    return document.execCommand("subscript", false, null);
  };

  this.sup = function() {
    return document.execCommand("superscript", false, null);
  };

  this.alignleft = function() {
    return document.execCommand("justifyLeft", false, null);
  };

  this.alignright = function() {
    return document.execCommand("justifyRight", false, null);
  };

  this.alignjustify = function() {
    return document.execCommand("justifyFull", false, null);
  };

  this.aligncenter = function() {
    return document.execCommand("justifyCenter", false, null);
  };

  this.ol = function() {
    return document.execCommand("insertOrderedList", false, null);
  };

  this.ul = function() {
    return document.execCommand("insertUnorderedList", false, null);
  };

  this.indent = function() {
    return document.execCommand("indent", false, null);
  };

  this.unindent = function() {
    return document.execCommand("outdent", false, null);
  };

  this.highlight = function(color) {
    return document.execCommand("hiliteColor", false, color);
  };

  this.color = function(color) {
    return document.execCommand("foreColor", false, color);
  };

  this.formatBlock = function(tag) {
    return document.execCommand("formatBlock", false, tag);
  };

  this.font = function(font) {
    return document.execCommand("fontName", false, font);
  };

  this.fontSize = function(size) {
    return document.execCommand("fontSize", false, size);
  };

  this.img = function() {
    var url;
    url = prompt("Enter the URL to the image");
    return document.execCommand("insertImage", false, url);
  };

  this.link = function() {
    var url;
    url = prompt("Enter the URL");
    return document.execCommand("createLink", false, url);
  };

  this.unlink = function() {
    return document.execCommand("unlink", false, null);
  };

}).call(this);

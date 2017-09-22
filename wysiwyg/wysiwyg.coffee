# Add a compatiility warning
window.addEventListener(
  "load"
  ->
      # first check if execCommand and contentEditable attribute is supported or not.
      if document.execCommand?
        document.execCommand('styleWithCSS', false, false) # I suppose using css would be more proper, but this uses less characters
        document.execCommand('insertBrOnReturn', false, false) # Makes it created seperate <p>s instead of just adding a <br>
        document.execCommand('enableInlineTableEditing', false, true)
        document.execCommand('enableObjectResizing', false, true)
      else
        alert '
          Your browser does not support the features needed to use the rich-text editor.
          Use the "Edit as HTML" feature instead.'
  false)





# Utility functions ################################################################
@wysiwygToggleHTML = (id)->
  textarea = document.getElementById("#{id}_textarea")
  editor = document.getElementById("#{id}_editor")
  controlbox = document.getElementById("#{id}_controlbox")
  toggle = document.getElementById("#{id}_toggle")

  if getComputedStyle(textarea, null).display == 'none'
    # If textarea is hidden, i.e. we are in rich text editor mode. Swap to HTML.
    textarea.style.display = 'block'
    editor.style.display = 'none'
    controlbox.style.display = 'none'
    toggle.innerHTML = "Edit as Rich Text"
    contenteditableToTextarea(id)
    textareaAdaptHeight(id)

  else
    # We are editing HTML, swap to rich text editor
    textarea.style.display = 'none'
    editor.style.display = 'block'
    controlbox.style.display = 'block'
    toggle.innerHTML = "Edit as HTML"
    textareaToContenteditable(id)

  return false # Tell it not to actually follow the link that calls this function




@contenteditableToTextarea = (id)->
  textarea = document.getElementById("#{id}_textarea")
  editor = document.getElementById("#{id}_editor")
  textarea.value = editor.innerHTML





@textareaToContenteditable = (id)->
  textarea = document.getElementById("#{id}_textarea")
  editor = document.getElementById("#{id}_editor")
  editor.innerHTML = textarea.value





@wysiwygOnSubmit = (id)->
  textarea = document.getElementById("#{id}_textarea")
  if getComputedStyle(textarea, null).display == 'none'
    # If textarea is hidden, i.e. we are in rich text editor mode.
    # Put the data in the textarea
    contenteditableToTextarea(id)
  return true # Make sure it continues with the submission





@textareaAdaptHeight = (id)->
  textarea = document.getElementById("#{id}_textarea")
  textarea.style.height = '1px' # see http://stackoverflow.com/questions/10722058/height-of-textarea-increases-when-value-increased-but-does-not-reduce-when-value
  textarea.style.height = "#{textarea.scrollHeight}px"







# Formatting functions *********************************************************
@undo = -> document.execCommand("undo", false, null)

@redo = -> document.execCommand("redo", false, null)

@unformat = -> document.execCommand("removeFormat", false, null)

@cut = -> document.execCommand("cut", false, null)

@copy = -> document.execCommand("copy", false, null)

@paste = -> document.execCommand("paste", false, null)

@underline = -> document.execCommand("underline", false, null)

@bold = -> document.execCommand("bold", false, null)

@italic = -> document.execCommand("italic", false, null)

@strike = -> document.execCommand("strikeThrough", false, null)

@sub = -> document.execCommand("subscript", false, null)

@sup = -> document.execCommand("superscript", false, null)

@alignleft= -> document.execCommand("justifyLeft", false, null)

@alignright = -> document.execCommand("justifyRight", false, null)

@alignjustify = -> document.execCommand("justifyFull", false, null)

@aligncenter = -> document.execCommand("justifyCenter", false, null)

@ol = -> document.execCommand("insertOrderedList", false, null)

@ul = -> document.execCommand("insertUnorderedList", false, null)

@indent = -> document.execCommand("indent", false, null)

@unindent = -> document.execCommand("outdent", false, null)

@highlight = (color)-> document.execCommand("hiliteColor", false, color)

@color = (color)-> document.execCommand("foreColor", false, color)

@formatBlock = (tag)-> document.execCommand("formatBlock", false, tag)

@font = (font)-> document.execCommand("fontName", false, font)

@fontSize = (size)-> document.execCommand("fontSize", false, size)

@img= ->
  url = prompt("Enter the URL to the image")
  document.execCommand("insertImage", false, url)

@link= ->
  url = prompt("Enter the URL")
  document.execCommand("createLink", false, url)

@unlink= -> document.execCommand("unlink", false, null)

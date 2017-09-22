<?php

class Wysiwyg{
  protected $id;
  protected $formaction;
  protected $content;
  public static $url_dir;


  public function __construct($id='wysiwyg', $formaction='', $content='<p>Enter text here</p><p></p>'){
    $this->id = $id; // This id will allow us to have multiple editors on a page if they all have a unique id
    $this->formaction = $formaction;
    $this->content = $content;
  }


  public static function linkResources(){
    ?>
    <!-- Scripts and styles for the WYSIWYG editor -->
    <script type="text/javascript" src="<?=self::$url_dir?>/wysiwyg.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=self::$url_dir?>/wysiwyg.css"/>
    <?php
  }


  public function printAll(){
    // Print an entire editor as a single div
    echo "<div class='wysiwyg' id='$this->id'>";
    $this->printToggleButton();
    $this->printControlbox();
    $this->printEditor();
    $this->printSaveButton();
    echo '</div>'; // end wysiwyg
  }

  public function printEditor(){
    // Create the editor box and a textarea which will be used to edit it as raw HTML
    ?>
    <div class='wysiwyg_editor'
         contenteditable='true'
         spellcheck='true'
         id='<?=$this->id?>_editor'>

      <?=$this->content?>
    </div>

    <textarea name='content'
              form='<?=$this->id?>_form'
              id='<?=$this->id?>_textarea'
              oninput='textareaAdaptHeight("<?=$this->id?>")'
              class='wysiwyg_editor'></textarea>
    <?php
  }

  public function printSaveButton(){
    // Now the form that will allow us to submit it
    ?>
    <form action='<?=$this->formaction?>'
          method='post'
          id='<?=$this->id?>_form'
          onsubmit='wysiwygOnSubmit("<?=$this->id?>")'>
      <input type='submit' text='Save'/>
    </form>
    <?php
  }

  public function printToggleButton(){
      echo "<button onclick='wysiwygToggleHTML(\"$this->id\")' id='{$this->id}_toggle'>Edit as HTML</button>";
  }


  public function printControlbox(){
    // Create the contol box
    ?>
    <div class='wysiwyg_controlbox' id='<?=$this->id?>_controlbox'>
      <span class='toolbox'>
        <button onclick='undo()'><img alt='Undo' title='Undo'
          src='<?=self::$url_dir?>/icons/Edit-undo.svg' /></button>
        <button onclick='redo()'><img alt='Redo' title='Redo'
          src='<?=self::$url_dir?>/icons/Edit-redo.svg' /></button>
        <button onclick='unformat()'><img alt='Remove Formatting' title='Remove Formatting'
          src='<?=self::$url_dir?>/icons/Edit-clear.svg' /></button>
      </span>

      <span class='toolbox'>
        <button onclick='copy()'><img alt='Copy' title='Copy'
          src='<?=self::$url_dir?>/icons/Edit-copy.svg' /></button>
        <button onclick='cut()'><img alt='Cut' title='Cut'
          src='<?=self::$url_dir?>/icons/Edit-cut.svg' /></button>
        <button onclick='paste()'><img alt='Paste' title='Paste'
          src='<?=self::$url_dir?>/icons/Edit-paste.svg' /></button>
      </span>

      <span class='toolbox'>
        <select oninput='formatBlock(this.value)' title='Block Type'>
          <option value='<p>'>Paragraph</option>
          <option value='<h1>'>Heading 1</option>
          <option value='<h2>'>Heading 2</option>
          <option value='<h3>'>Heading 3</option>
          <option value='<h4>'>Heading 4</option>
          <option value='<h5>'>Heading 5</option>
          <option value='<h6>'>Heading 6</option>
          <option value='<blockquote>'>Block Quote</option>
          <option value='<pre>'>Preformatted Text</option>
        </select>

        <select oninput='fontSize(this.value)' title='Font Size'>
          <option value='1'>1</option>
          <option value='2'>2</option>
          <option value='3'>3</option>
          <option value='4'>4</option>
          <option value='5'>5</option>
          <option value='6'>6</option>
          <option value='7'>7</option>
        </select>

        <select oninput='font(this.value)' title='Font'>
          <option value='serif'>Serif</option>
          <option value='sans-serif'>Sans Serif</option>
          <option value='monospace'>Monospace</option>
          <option value='cursive'>Cursive</option>
          <option value='fantasy'>Fantasy</option>
        </select>
      </span>

      <span class='toolbox'>
        <button onclick='underline()'><img alt='Underline' title='Underline'
          src='<?=self::$url_dir?>/icons/Format-text-underline.svg' /></button>
        <button onclick='bold()'><img alt='Bold' title='Bold'
          src='<?=self::$url_dir?>/icons/Format-text-bold.svg' /></button>
        <button onclick='italic()'><img alt='Italic' title='Italic'
          src='<?=self::$url_dir?>/icons/Format-text-italic.svg' /></button>
        <button onclick='strike()'><img alt='Strikthrough' title='Strikthrough'
          src='<?=self::$url_dir?>/icons/Format-text-strikethrough.svg' /></button>
        <button onclick='sub()'><img alt='Subscript' title='Subscript'
          src='<?=self::$url_dir?>/icons/Format-text-subscript.svg' /></button>
        <button onclick='sup()'><img alt='Superscript' title='Superscript'
          src='<?=self::$url_dir?>/icons/Format-text-superscript.svg' /></button>
      </span>

      <span class='toolbox'>
        <button onclick='alignleft()'><img alt='Left' title='Left'
          src='<?=self::$url_dir?>/icons/Format-justify-left.svg' /></button>
          <button onclick='aligncenter()'><img alt='Center' title='Center'
            src='<?=self::$url_dir?>/icons/Format-justify-center.svg' /></button>
        <button onclick='alignright()'><img alt='Right' title='Right'
          src='<?=self::$url_dir?>/icons/Format-justify-right.svg' /></button>
        <button onclick='alignjustify()'><img alt='Justified' title='Justified'
          src='<?=self::$url_dir?>/icons/Format-justify-fill.svg' /></button>
      </span>

      <span class='toolbox'>
        <button onclick='ol()'><img alt='Numbered List' title='Numbered List'
          src='<?=self::$url_dir?>/icons/Format-list-ordered.svg' /></button>
        <button onclick='ul()'><img alt='Bullet List' title='Bullet List'
          src='<?=self::$url_dir?>/icons/Format-list-unordered.svg' /></button>
        <button onclick='indent()'><img alt='Indent' title='Indent'
          src='<?=self::$url_dir?>/icons/Format-indent-more.svg' /></button>
        <button onclick='unindent()'><img alt='Unindent' title='Unindent'
          src='<?=self::$url_dir?>/icons/Format-indent-less.svg' /></button>
      </span>

      <span class='toolbox'>
        <label for='hcolor'>Highlight: </label>
        <input name='hcolor' type='color' oninput='highlight(this.value)'/>
        <label for='tcolor'>Text Color: </label>
        <input name='tcolor' type='color' oninput='color(this.value)'>
      </span>

      <span class='toolbox'>
        <button onclick='img()'><img alt='Insert Image' title='Insert Image'
          src='<?=self::$url_dir?>/icons/Image-x-generic.svg' /></button>
        <button onclick='link()'><img alt='Link' title='Link'
          src='<?=self::$url_dir?>/icons/Link.svg' /></button>
        <button onclick='unlink()'><img alt='Break Link' title='Break Link'
          src='<?=self::$url_dir?>/icons/Link-break.svg' /></button>
      </span>
    </div>
    <?php // End control box
  }
}


Wysiwyg::$url_dir = str_replace( // Replace the \ with / to make windows compatible
  "\\",
  "/",
  substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))); // Get the url of this dir relitive to documnet root
?>

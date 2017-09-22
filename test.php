<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <style>
  .controlbox{
    text-align: justify;
    text-align-last: center;
  }

  .toolbox{
    display: inline-block;
    padding-left: 5px;
    padding-right: 5px;
    margin: 0;
    font-size: 16pt;
  }

  .editor{
    min-height: 100px;
    margin: 15px;
    padding: 15px;
    padding-top: 0;
    background-color: white;
    border: 1px solid blue;
    border-radius: 5px;
    box-shadow: inset 0px 0px 2px 2px gray;
  }

  textarea{
    display: none; // Javascript will change this value
    resize: none;
    overflow: auto;
    min-height: 100px;
    margin: 15px;
    padding: 15px;
    background-color: white;
    border: 1px solid blue;
    border-radius: 5px;
    box-shadow: inset 0px 0px 2px 2px gray;
  }

  .toggle{
    text-align: right;
    font-size: small;
  }

  button, select, input{
    font-size: inherit;
    margin: 1px;
    text-align: center;
    vertical-align: middle;
  }

  button img{
    height: 1em;
    width: 1em;
  }
  </style>
</head>

<body>
  <h1>WYSIWYG test page</h1>
  <?php
  require 'wysiwyg.php';

  printEditor();

  // Print the output from the previous submission
  if (isset($_POST['content'])){
    echo '<div>', $_POST['content'], '</div>';
  }
  ?>
</body>
</html>

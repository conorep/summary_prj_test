<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset='utf-8' />
  <script src='https://unpkg.com/babel-standalone@6/babel.min.js'></script>
  <link rel='stylesheet' href='styles/styling.css?v=<?php echo filemtime('styles/styling.css');?>'>
  <title>Testing Annotated PDF Queries</title>
</head>
<body>
  <div id='mainCont'>
	  <?php include 'get_assn.php'; ?>
  </div>
  <script type='text/babel' src='scripts/scripting.tsx?v=<?php echo filemtime('scripts/scripting.tsx');?>'></script>
</body>
</html>
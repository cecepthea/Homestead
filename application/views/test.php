<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <!--<link rel="stylesheet" href="css/styles.css?v=1.0">-->
</head>
<body>

<div id="example-2">
  <p v-if="greeting">Hello!</p>
</div>

  <script src="<?php echo base_url().'public/js/vue.js'; ?>"></script>

  <script>
var exampleVM2 = new Vue({
  el: '#example-2',
  data: {
    greeting: false
  }
})
  </script>

</body>
</html>
<?php
require_once '../../vendor/autoload.php';
Less_Autoloader::register();

$options = array('compress' => false);
$parser = new Less_Parser($options);
$parser->parseFile('styles.less', '');
$css = $parser->getCss();

$config['captcha']['enabled'] = true;
$msgs = [];
$base_url = '';
file_put_contents('../css/style.css', $css);
?>
<pre>
<?php
echo $css;
?>
</pre>

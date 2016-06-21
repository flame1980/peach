<?php
function smarty_block_clean($params, $content, $template, &$repeat)
{
    return preg_replace('/<!--[\s\S]*?-->/','',$content);
}
?>

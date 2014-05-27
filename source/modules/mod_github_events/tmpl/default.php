<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2013
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access'); 

$count = $params->get('count', 10);
?>

<?php if(!empty($items)) : ?>
<ul>
<?php $i = 0; ?>
<?php foreach( $items as $item ) : ?>

    <?php if(empty($item)) continue; ?>
    <?php if($i > $count) break; ?>

    <?php $type = JText::_($item['type']); ?>
    <?php $date = date($params->get('date_format', 'd/m/Y'), strtotime($item['created_at'])); ?>
    <?php $repoName = preg_replace('/^([^\/]+)\//', '', $item['repo_name']); ?>
    <?php $lineLength = strlen($type.$repoName); ?>
    <?php $maxLength = $params->get('string_length', 30); ?>
    <?php if($lineLength > $maxLength) $repoName = substr($repoName, 0, strlen($repoName) + $maxLength - $lineLength); ?>
    <?php $url = str_replace('https://api.github.com/repos/', 'https://github.com/', $item['repo_url']); ?>
    <?php $line = '['.$date.'] '.$type.' on <a href="'.$url.'">'.$repoName.'</a>'; ?>
    <li>
        <?php echo $line; ?>
        <?php if($params->get('show_actor', 1) == 1) : ?>
            <?php if(!empty($item['actor_url'])) : ?>
            <a href="<?php echo $item['actor_url']; ?>" title="<?php echo $item['actor_name']; ?>">
            <?php endif; ?>
            <?php if($params->get('show_actor_image', 1) == 1 && !empty($item['actor_image'])) : ?>
                <?php $size = explode('|', $params->get('actor_image_size', 30)); ?>
                <?php $width = $size[0]; ?>
                <?php $height = (isset($size[1])) ? $size[1] : $width; ?>
                <img src="<?php echo $item['actor_image']; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" title="<?php echo $item['actor_name']; ?>" alt="<?php echo $item['actor_name']; ?>" />
            <?php endif; ?>
            <span><?php echo $item['actor_name']; ?></span>
            <?php if(!empty($item['actor_url'])) : ?>
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </li>
    <?php $i++; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php
$title = get_field('title');
$icon = get_field('icon');
$content = get_field('content');

$fields = array(
    'title'     => $title,
    'icon'      => $icon,
    'content'   => $content
);

$class_name = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
$style = '';
$anchor = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : '';

printf(
    '<div class="%s" style="%s"%s>',
    $class_name,
    $style,
    $anchor
);
?>
    <div class="starlight-block card-block">
        <p class="block-title">Card</p>
        <?php
        foreach ($fields as $field => $data) {
            if (!empty($field)) printf(
                '<div class="%1$s" data-field="%1$s">%2$s</div>',
                $field,
                $data
            );
        }
        ?>
    </div>
</div>
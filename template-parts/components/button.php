<?php
$url = ! empty( $args['url'] ) ? $args['url'] : '#';
$text = ! empty( $args['text'] ) ? $args['text'] : 'Contact Us';
$style = ! empty( $args['style'] ) ? $args['style'] : 'blue';
$icon_name = ! empty( $args['icon'] ) ? $args['icon'] : false;
?>

<a href="<?php echo esc_url( $url ); ?>">
    <button class="btn btn-<?php echo esc_html( $style ); ?>">
        <?php esc_html_e( $text ); ?>
        <?php if ( $icon_name ) : ?>
        <span class="ml-2">
            <?php \Nyxit\Helper::icon( $icon_name ); ?>
        </span>
        <?php endif; ?>
    </button>
</a>
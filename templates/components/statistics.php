<?php
/**
 * @var array $data
 * @var string $icon
 * @var string $type
 */
$number_formatter = new NumberFormatter(get_user_locale(), NumberFormatter::CURRENCY);

?>

<section class="wsh-box" id="<?php printf('stats-%s', esc_attr($data['id'])) ?>">
    <header class="wsh-box__header">
        <?php echo sprintf('<h2 class="wsh-box__title">%s</h2>', esc_html($data['label'])); ?>
        <?php
        if (!empty($icon)) {
            echo $icon;
        }
        ?>
    </header>
    <div class="wsh-statistics__number">
        <?php
        if ($type === 'currency') {
            echo get_woocommerce_currency_symbol();
        }
        // Display the number in the format that fits the current user locale and without training zeroes.
        echo esc_html(preg_replace('/(\.00$)|(,00$)/', '', number_format_i18n($data['text'], 2)));
        ?>
    </div>
    <footer>
        <div class="wsh-statistics__mutation">
            <?php
            echo sprintf(
                '<span class="wsh-statistics__result wsh-statistics__result--%1$s">%2$s&percnt; %3$s</span>',
                $data['percentage'] >= 0 ? esc_attr('positive') : esc_attr('negative'),
                esc_html($data['percentage']),
                $data['percentage'] >= 0 ? esc_html('increase') : esc_html('decrease'),
            );
            ?>
        </div>
    </footer>
</section>
<?php

/**
 * Template helper to include inline icons
 */
function include_inline_icon($icon)
{
    $icon_path = get_template_directory() . '/dist/static/icons/' . esc_html($icon) . '.svg';
    include($icon_path);
}

function get_inline_icon($icon)
{
    ob_start();
    include_inline_icon($icon);
    return ob_get_clean();
}

// include_inline_icon('time');

// Figma (copy as svg) -> Svg optimizer (paste) -> avan editoris ja muudan fill=“#00000” ära fill=“currentColor”

// add svg with the function to a container with parent
// add the styles (hover as an example) to the container

// EXAMPLE:
// <div class="text-white hover:text-black">
//     include inline_icon('arrow');
// </div>

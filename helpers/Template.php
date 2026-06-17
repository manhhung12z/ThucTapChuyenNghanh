<?php class Template
{
    public static $blocks = [];
    public static $stack = [];
    public static function init()
    {
        ob_start([self::class, 'callback']);
    }
    public static function callback($html)
    {
        $blocks = array_values(self::$blocks);
        usort($blocks, function ($a, $b) { //sắp xếp theo vị trí từ nhỏ đến lơn
            return $a['pos'] - $b['pos'];
        });
        $result = '';
        $end = strlen($html);
        for ($i = count($blocks) - 1; $i >= 0; $i--) {
            $block = $blocks[$i];

            $result =
                $block['content'] .
                substr($html, $block['pos'], $end - $block['pos']) .
                $result;

            $end = $block['pos'];
        }
        $result = substr($html, 0, $end) . $result;

        return $result;
    }
}
function startblock($name, $mode = 0)
{
    if (!isset(Template::$blocks[$name])) {
        Template::$blocks[$name] = [
            'name' => $name,
            'content' => null,
            'pos' => ob_get_length()
        ];
    }

    Template::$stack[] = [
        'name' => $name,
        'mode' => $mode
    ];

    ob_start();
}
function endblock()
{
    $temp = array_pop(Template::$stack);

    $name = $temp['name'];
    $mode = $temp['mode'];

    $content = ob_get_clean();

    if (Template::$blocks[$name]['content'] === null || $mode == 0) {
        Template::$blocks[$name]['content'] = $content;
    } elseif ($mode == 1) {
        // append
        Template::$blocks[$name]['content'] .= $content;
    } else {
        // prepend
        Template::$blocks[$name]['content'] =
            $content . Template::$blocks[$name]['content'];
    }
}

// Placeholder (giữ vị trí)
function defineblock($name)
{
    if (!isset(Template::$blocks[$name])) {
        Template::$blocks[$name] = [
            'name' => $name,
            'content' => '',
            'pos' => ob_get_length()
        ];
    }
}
?>
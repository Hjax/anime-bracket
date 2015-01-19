<?php

namespace Controller\Admin {

    use Api;
    use Lib;

    class Edit extends \Controller\Me {

        public static function generate(array $params) {

            $bracket = self::_getBracket(array_shift($params));

            if ($bracket) {

                // Create the bracket on POST
                if ($_POST) {

                    $id = Lib\Url::Post('bracketId', true);
                    $name = Lib\Url::Post('bracketName');
                    $rules = Lib\Url::Post('rules');

                    if ($name && $rules) {
                        $bracket->name = trim($name);
                        $bracket->rules = $rules;

                        $advanceHour = Lib\Url::Post('advanceHour', true);
                        $advanceHour = null !== $advanceHour ? $advanceHour : -1;
                        $bracket->advanceHour = $advanceHour;

                        if ($bracket->sync()) {
                            header('Location: /me/?edited');
                            exit;
                        }

                    }

                }


                $bracket->times = self::_generateAdvanceTimes($bracket->advanceHour);
                Lib\Display::renderAndAddKey('content', 'admin/bracket', $bracket);

            }

        }

    }

}
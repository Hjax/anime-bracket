<?php

namespace Controller\Admin {

    use Api;
    use Lib;

    class Create extends \Controller\Me {

        public static function generate(array $params) {
            // Create the bracket on POST
            if ($_POST) {

                $name = Lib\Url::Post('bracketName');
                $rules = Lib\Url::Post('rules');

                if ($name && $rules) {
                    $bracket = new Api\Bracket();
                    $bracket->name = trim($name);
                    $bracket->rules = $rules;
                    $bracket->state = 0;
                    $bracket->start = time();
                    $bracket->generatePerma();

                    $advanceHour = Lib\Url::Post('advanceHour', true);
                    if ($advanceHour !== null) {
                        $utcOffset = Lib\Url::Post('utcOffset', true);
                        $advanceHour += $utcOffset !== null ? $utcOffset : 0;
                    } else {
                        $advanceHour = -1;
                    }
                    $bracket->advanceHour = $advanceHour;

                    if ($bracket->sync()) {
                        $bracket->addUser(self::$_user);
                        header('Location: /me/?created');
                        exit;
                    }

                }

            }

            // Or display the form
            $_POST['times'] = self::_generateAdvanceTimes();
            Lib\Display::renderAndAddKey('content', 'admin/bracket', $_POST);
        }

    }

}
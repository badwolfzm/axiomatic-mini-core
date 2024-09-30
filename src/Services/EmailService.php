<?php

namespace Services;

class EmailService {
    public function send($input) {
        $to = $input['user_email'];
        $message = $input['formatted_message'];
        if (mail($to, "Notification", $message)) {
            return ['status' => 'success'];
        }
        return ['status' => 'failure'];
    }
}

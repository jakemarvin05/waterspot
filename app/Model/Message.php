<?php

Class Message extends AppModel
{
    public $name = "Message";

    public function getAllListById($member_id)
    {
        $all_messages = $this->query("SELECT * FROM messages as m1 WHERE m1.member_id = $member_id AND m1.sent_at = (SELECT m2.sent_at FROM messages as m2 WHERE vendor_id = m1.vendor_id ORDER BY sent_at DESC LIMIT 1)");
        $messages = [];
        foreach ($all_messages as $message) {
            $messages[] = $message['m1'];
        }

        return $messages;
	}

    public function getAllConversation($member_id, $vendor_id, $page = 0)
    {
        $all_conversation = $this->find('all', [
                'conditions' => [
                    'vendor_id' => $vendor_id,
                    'member_id' => $member_id
                ],
                'order' => 'sent_at DESC',
                'limit' => 5,
                'offset' => $page
            ]
        );
        // var_dump($all_conversation);die;
        $conversations = [];
        foreach ($all_conversation as $conversation) {
            $conversations[] = $conversation['Message'];
        }

        return $conversations;
    }

    public function vendorGetAllListById($vendor_id)
    {
        $all_messages = $this->query("SELECT * FROM messages as m1 WHERE m1.vendor_id = $vendor_id AND m1.sent_at = (SELECT m2.sent_at FROM messages as m2 WHERE member_id = m1.member_id ORDER BY sent_at DESC LIMIT 1)");
        $messages = [];
        foreach ($all_messages as $message) {
            $messages[] = $message['m1'];
        }

        return $messages;
    }
}


?>

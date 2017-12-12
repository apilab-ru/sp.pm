<?php


namespace app\controller;

class messages extends base
{
    public function user($param)
    {
        $user = (new \app\model\users())->getUser($param['id']);
        echo $this->render('messages/main',[
            'navigation' => 'user',
            'user'       => $user,
            'content'    => $this->render('messages/dialog',[
                'user' => $user['id']
            ])
        ]);
        return ['struct' => 'messages'];
    }
    
    public function dialogs($param)
    {
        echo $this->render('messages/main',[
            'navigation' => 'dialogs',
        ]);
        return ['struct' => 'messages'];
    }
    
    public function notices($param)
    {
        echo $this->render('messages/main',[
            'navigation' => 'notices',
        ]);
        return ['struct' => 'messages'];
    }
}

<?php

namespace app\controller;
use \app\model\events;

class messages extends base
{
    public function user($param, $args)
    {
        ob_start();
        $this->renderMessages($param['id'], $page);
        $content = ob_get_clean();
        
        echo $this->render('messages/main',[
            'navigation' => 'user',
            'content'    => $this->render('messages/dialog',[
                'opponent' => (new \app\model\users())->getUser($param['id']),
                'content'  => $content
            ])
        ]);
        return ['struct' => 'messages'];
    }
    
    public function renderMessages($opponent, $page)
    {
        $users    = new \app\model\users();
        $opponent = $users->getUser($opponent);
        $user     = $users->getUser($_SESSION['user']['id']); 
        
        $messages = new \app\model\messages();
        echo $this->render('messages/dialogMessages',[
            'list'     => $messages->getMessages($user['id'], $opponent['id'], $page),
            'user'     => $user,
            'opponent' => $opponent
        ]);
    }
    
    public function dialogs($param, $args)
    {
        $messages = new \app\model\messages();
        
        $filter = array(
            'user' => $_SESSION['user']['id'],
            'page' => $args['page']
        );
        
        $data = $messages->getDialogs($filter);
        
        echo $this->render('messages/main',[
            'navigation' => 'dialogs',
            'content'    => $this->render('messages/dialogs',$data)
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
    
    public function sendToUser($args, $param)
    {
       $messages = new \app\model\messages();
       $messages->sendMessage($_SESSION['user']['id'], $param['user'], $param['message']);
       return [
           'stat' => 1
        ];
    }
    
    public function server($args, $param)
    {
        set_time_limit(30);
        session_write_close();
        if(!$_SESSION['user']){
            throw new \Exception("Вы не авторизованны");
        }
        $time = $param['time'];
        
        for($i=0; $i<28; $i++){
            if($event = events::read($_SESSION['user']['id'], $time)){
                if($event['type'] == 'message'){
                    return [
                        'stat'    => 1,
                        'message' => $event
                    ];
                }
            }
            sleep(1);
        }
        
        return [
            'stat' => 1
        ];
    }
    
    /*public function server()
    {
        //set_time_limit(0);
        $link = "websocket://" . $_SERVER['SERVER_ADDR'] . ":889" ;
        
        // Create a Websocket server
        $ws_worker = new \Workerman\Worker($link);

        // 4 processes
        $ws_worker->count = 4;

        // Emitted when new connection come
        $ws_worker->onConnect = function($connection) {
            echo "New connection\n";
        };

        // Emitted when data received
        $ws_worker->onMessage = function($connection, $data) {
            // Send hello $data
            $connection->send('hello ' . $data);
        };

        // Emitted when connection closed
        $ws_worker->onClose = function($connection) {
            echo "Connection closed\n";
        };

        // Run worker
        \Workerman\Worker::runAll();

        //if(extension_loaded('sockets')) echo "WebSockets OK";
        //else echo "WebSockets UNAVAILABLE";
    }*/
}

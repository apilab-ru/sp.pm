<?php

namespace app\controller;

class users extends base
{
    public function cabinet()
    {
        if(!$_SESSION['user']){
            (new \app\controller\page())->authPage();
        }else{
            $user = (new \app\model\users())->getUser();
            echo (new \app\controller\page())->main(
                $this->render('users/cabinet',[
                    'user' => $user
                ]),
                ["struct" => "cabinet"]
            );
        }
    }
    
    public function usersList()
    {
        return [
            'list' => (new \app\model\users())->getList()
        ];
    }
    
    public function organizator($args, $param)
    {
        echo (new page())->main(
                "organizator " . $args['id'],
                ["struct"=>"organizator"]
            );
    }
}

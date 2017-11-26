<?php

namespace app\controller;

class users extends base
{
    public $types = [
        'simple'       => "Пользователь",
        'organizator'  => "Организатор",
        'admin'        => "Админ"
    ];
    
    
    public function cabinet()
    {
        if(!$_SESSION['user']){
            return (new \app\controller\page())->authBox();
        }else{
            $user = (new \app\model\users())->getUser();
            echo $this->render('users/cabinet',[
                'user' => $user
            ]);
            return  ["struct" => "cabinet"];
        }
    }
    
    /*public function usersList()
    {
        return [
            'list' => (new \app\model\users())->getList()
        ];
    }*/
    
    public function employees($param)
    {
        $widget = new widget();
        $users  = new \app\model\users();
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $users->getList($param),
                [
                    'title'  => 'Пользователи',
                    'add'    => '/admin/users/add',
                    'edit'   => "/admin/users/edit",
                    'delete' => "/admin/users/delete",
                    "labels" => [
                        "id"         => "id",
                        "image"      => [
                            "type" => "image",
                            "name" => "Фото"
                        ],
                        "name"       => "Имя",
                        "surname"    => "Фамилия",
                        "secondname" => "Отчество",
                        "email"      => "Email",
                        "type"       => [
                            "type" => "select",
                            "data" => $this->types,
                            "name" => "Тип"
                        ],
                        "birthday"  => [
                            "type" => "date",
                            "name" => "День рождения"
                        ],
                        "date_reg"  => [
                            "type" => "datetime",
                            "name" => "Дата регистрации"
                        ]
                ]]), 
            ""
        );
    }
    
    public function edit($param)
    {
        $users = new \app\model\users();
        return $this->editUser(
            $users->getUser($param['send']['id'])
        );
    }
    
    public function add($param)
    {
        return $this->editUser();
    }
    
    public function editUser($user=null)
    {
        return $this->render("users/editUser",[
            'object' => $user,
            'types'  => $this->types
        ]);
    }
    
    public function save($param)
    {
        $users = new \app\model\users();
        $user = $users->saveUser($param['form']);
        if($_FILES['photo']){
            (new images())->savePhotoUser($_FILES['photo'], $user);
        }
        return [
            'stat' => 1,
            'user' => $user
        ];
    }
    
    public function organizator($args, $param)
    {
        echo  "organizator " . $args['id'];
        
        return [
            "struct" => "organizator"
        ];
    }
    
    public function delete($param)
    {
        (new \app\model\users())->deleteUser($param['send']['id']);
        return ['stat'=>1];
    }
}

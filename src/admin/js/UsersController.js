'use strict';

var UsersController = function($scope, $http, NgTableParams){
    
    var self = this;
    
    var userTypes = [
        {
            "id" : "admin",
            "title" : "Администратор"
        },
        {
            "id" : "simple",
            "title" : "Пользователь"
        },
        {
            "id" : "organozator",
            "title" : "Организатор"
        }
    ];
    
    $http.get('/ajax/users/usersList/').then(function(re){
        $scope.users = re.data.list;
        $scope.tableParams = new NgTableParams({ count: 5}, { 
            counts: [5, 10, 25], 
            //dataset: angular.copy($scope.users)
            getData: function(params) {
                params.total($scope.users.length);
                console.log('get data',params);
                return $scope.users;
            }
        });
    });
    
    $scope.cols = [
      { valueExpr: "item.id", title: "Id", filter: { id : "number" }, show: true },
      { valueExpr: "item.email", title: "Email", filter: { email: "text" }, show: true },
      { valueExpr: "item.surname", title: "Фамилия", filter: { surname: "text" }, show: true },
      { valueExpr: "item.name", title: "Имя", filter: { name : "text" }, show: true },
      { valueExpr: "item.secondname", title: "Отчество", filter: { secondname: "text" }, show: true },
      { valueExpr: "item.type", title: "Тип", filter: { 'type': "select" }, filterData: userTypes, show: true },
      { valueExpr: "item.birthday", title: "День рождения", filter: { birthday: "text" }, show: true },
      {
        field: "action",
        title: "",
        dataType: "command"
      }
    ];
    
    self.cancel = cancel;
    self.del = del;
    self.save = save;

    //////////

    function cancel(row, rowForm) {
      var originalRow = resetRow(row, rowForm);
      angular.extend(row, originalRow);
    }

    function del(row) {
      _.remove(self.tableParams.settings().dataset, function(item) {
        return row === item;
      });
      self.tableParams.reload().then(function(data) {
        if (data.length === 0 && self.tableParams.total() > 0) {
          self.tableParams.page(self.tableParams.page() - 1);
          self.tableParams.reload();
        }
      });
    }
    
    function resetRow(row, rowForm){
      row.isEditing = false;
      rowForm.$setPristine();
      self.tableTracker.untrack(row);
      return _.findWhere(originalData, function(r){
        return r.id === row.id;
      });
    }

    function save(row, rowForm) {
      var originalRow = resetRow(row, rowForm);
      angular.extend(originalRow, row);
    }
    
    $scope.isShowForm = false;
    $scope.addForm = function(){
        $scope.isShowForm = true;
        $scope.object = {};
    }
    
    admin.scope = $scope;
    
    $scope.tempUser = null;
    
    $scope.saveUser = function(){
        
        $scope.users.push( $scope.object );
        
        console.log('object add', $scope.object, $scope.users, $scope.tempUser);
        
        $scope.tempUser = null;
        
        //$scope.object = {};
        
        //$scope.tableParams.reload();
        $scope.isShowForm = false;
        
    }
    
    $scope.editUser = function(user){
        $scope.isShowForm = true;
        $scope.object     = user;
    }
    
};

UsersController.$inject = ['$scope', '$http', 'NgTableParams'];
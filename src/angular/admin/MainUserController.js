var MainUserController = function ($scope) {
    $scope.user = admin.storage.user;
    
    $scope.isOpen = false;
    
    $scope.toglerOpen = function(){
        $scope.isOpen = ($scope.isOpen) ? false : true;
    }
}
app.controller("MainUserController", MainUserController);
MainUserController.$inject = ['$scope'];




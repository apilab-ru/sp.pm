var app = angular.module('adminContent', ['ngRoute', 'ngTable']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider
                        .when('/users/', {
                            controller: UsersController,
                            templateUrl: '/view/admin/users.html'
                        })
                        .otherwise({
                            redirectTo: '/users/'
                        });
            }]);

var menu = [
    {
        "link" : "#!/users/",
        "name" : "Пользователи"
    }
];

var NavigationControoler = function ($scope) {
    $scope.current = [
        {"name": "Пользователи"}
    ];

    $scope.menu = menu;
}
app.controller("NavigationControoler", NavigationControoler);
NavigationControoler.$inject = ['$scope'];

        
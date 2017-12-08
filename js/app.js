var app = angular.module("mainApp", ['ngRoute'])
app.config(function($routeProvider){
    $routeProvider
        .when('/',{
            templateUrl: 'views/maintenance.html'
        })
        .when('/tabledisplay',{
            templateUrl: 'views/tabledisplay.html'
        })
        .when('/searchbooks',{
            templateUrl: 'views/searchbooks.html'
        })
        .otherwise({
            templateUrl: 'views/maintenance.html'
        });
});

app.controller('maintenanceCtrl', function ($scope, $http, $route) {
    $scope.selectTables = [{id:1,name:"Book"},{id:2,name:"Publisher"},{id:3,name:"Author"}, {id:4,name:"Copy"}];
    $scope.selectOps = [{id:1,name:"Insert"},{id:2,name:"Update"},{id:3,name:"Delete"}];

    $scope.querycolumn = function(tableName, operation) {
        $scope.columns = [];

        if (operation.name == "Insert") {
            $http.post("api/getcolumnname.php", {1: tableName, 2: operation}).success(function (response) {
                $scope.columns = response;
            });
        }
        else {
            $http.post("api/selectall.php", tableName).success(function(response) {
                $scope.result = response;
            });
        }
    };

    $scope.key = [];
    $scope.toPrep = function (data) {
        $scope.key = [];
        $scope.key = data;
    }

    $scope.toDelete = function (str, val) {
        $scope.key = [];
        $scope.key.push(str);
        $scope.key.push(val);
    }

    $scope.submitForm = function (selectTable, selectOp) {
        if (selectOp.name == "Insert") {
            $http.post("api/maintenance.php", {"selectTable":selectTable, "data":$scope.columns}).success(function(response) {
                $route.reload();
            });
        }
        else if (selectOp.name == "Update") {
            $http.put("api/maintenance.php", {"selectTable":selectTable, "data":$scope.key}).success(function (response) {
                $route.reload();
            });
        }
        else { // selectOp.name == "Delete"
            // console.log("selectTable"+"="+selectTable.name+"&"+$scope.key[0]+"="+$scope.key[1]);
            $http.delete("api/maintenance.php/?selectTable="+selectTable.name+"&field="+$scope.key[0]+"&val="+$scope.key[1]).success(function (response) {
                $route.reload();
            });
        }
    }

    $scope.clearColumn = function() {
        $scope.columns = [];
    }
});

app.controller('calltableCtrl', function ($scope, $http) {
    $scope.selectTables = [{id:1,name:"Book"},{id:2,name:"Publisher"},{id:3,name:"Author"}, {id:4,name:"Copy"}];
    $scope.querytable = function(tableName) {
        $scope.result = [];
        $http.post("api/selectall.php", tableName).success(function(response) {
            $scope.result = response;
            // console.log($scope.result);
        }, function (response) {
            // this function handles error
        });
    };
});

app.controller('searchbookCtrl', function ($scope, $http) {
    $scope.querytitle = function(booktitle) {
        $scope.result = [];
        $http.post("api/titlesearch.php", {"booktitle": booktitle}).success(function(response) {
            if (response == "") {
                $scope.result = "No Match Found";
                // console.log($scope.result);
            }
            else {
                $scope.result = response;
                // console.log($scope.result);
            }
        }, function (response) {
            // this function handles error
        });
    };
});